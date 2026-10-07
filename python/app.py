# python/app.py

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import Optional, List
import uvicorn
import os
import re
from dotenv import load_dotenv

load_dotenv()

from ai_engine.rag_engine import RAGEngine as QdrantRAGEngine
from ai_engine.llm_handler import LLMHandler

app = FastAPI(title="SellMate AI Engine")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

rag_engine = QdrantRAGEngine()
llm_handler = LLMHandler()


class KnowledgeRequest(BaseModel):
    tenant_id: str
    text: str
    metadata: Optional[dict] = None

class SearchRequest(BaseModel):
    tenant_id: str
    query: str
    limit: int = 5

class ConversationMessage(BaseModel):
    role: str
    content: str

class MessageRequest(BaseModel):
    tenant_id: str
    message: str
    customer_name: Optional[str] = None
    conversation_id: Optional[str] = None
    conversation_history: Optional[List[ConversationMessage]] = None

class AIResponse(BaseModel):
    reply: str
    intent: str = "general"
    confidence: float = 0.85
    sentiment: str = "neutral"
    tool_calls: list = []
    should_escalate: bool = False


@app.get("/")
async def root():
    return {"message": "SellMate AI Engine", "status": "running"}

@app.get("/health")
async def health():
    return {"status": "healthy"}


def is_greeting(message: str) -> bool:
    """🔥 Check karo message greeting hai ya nahi"""
    msg = message.lower().strip()
    
    greetings = [
        'hi', 'hii', 'hiii', 'hello', 'helo', 'hey', 'heyy',
        'namaste', 'namaskar', 'salam', 'salaam', 'assalam',
        'good morning', 'good evening', 'good afternoon',
        'gm', 'ge', 'hlo', 'hlw'
    ]
    
    if msg in greetings:
        return True
    
    if len(msg) < 15:
        for g in greetings:
            if g in msg:
                return True
    
    return False


def get_greeting_reply(message: str) -> str:
    """🔥 Language detect karke generic greeting reply do"""
    # Hindi (Devanagari)
    if re.search(r'[\u0900-\u097F]', message):
        return "नमस्ते! 🙏 कैसे मदद कर सकती हूँ? 😊"
    
    # Urdu (Arabic script)
    if re.search(r'[\u0600-\u06FF]', message):
        return "السلام علیکم! 🙏 کیسے مدد کر سکتی ہوں؟ 😊"
    
    # English
    if re.match(r'^[a-zA-Z\s,\.!?]+$', message):
        return "Hello! 🙏 How can I help you today? 😊"
    
    # Hinglish (default)
    return "Namaste! 🙏 Kaise madad kar sakti hun? 😊"


@app.post("/api/ai/chat")
async def chat(request: MessageRequest):
    """Main chat endpoint — Smart search + conversation history"""
    try:
        print(f"\n{'='*60}")
        print(f"💬 Chat Request")
        print(f"   Tenant ID: {request.tenant_id}")
        print(f"   Message: {request.message}")
        print(f"   History: {len(request.conversation_history or [])} messages")
        print(f"{'='*60}")
        
        # 🔥 STEP 0: GREETING CHECK
        if is_greeting(request.message):
            reply = get_greeting_reply(request.message)
            print(f"👋 Greeting detected — instant reply")
            return AIResponse(
                reply=reply,
                intent="greeting",
                confidence=1.0,
                should_escalate=False
            )
        
        # 🔥 STEP 1: SMART SEARCH
        print(f"🔍 Searching relevant chunks...")
        search_results = rag_engine.search(
            tenant_id=request.tenant_id,
            query=request.message,
            limit=20
        )
        
        if search_results:
            context_parts = [r['text'] for r in search_results]
            context = "\n\n".join(context_parts)
            print(f"✅ Found {len(search_results)} chunks")
            print(f"📚 Context: {len(context)} chars")
        else:
            print(f"⚠️ No search results — full context")
            context = rag_engine.get_full_context(request.tenant_id)
            if len(context) > 12000:
                context = context[:12000]
        
        # 🔥 STEP 2: Context empty check
        if not context:
            print("⚠️ NO CONTEXT — using fallback")
            return AIResponse(
                reply="Namaste! 🙏 Kaise madad kar sakti hun? 😊",
                should_escalate=True
            )
        
        # 🔥 STEP 3: LLM Call
        print("📤 Sending to LLM...")
        
        history = None
        if request.conversation_history:
            history = [
                {"role": msg.role, "content": msg.content}
                for msg in request.conversation_history
            ]
        
        result = await llm_handler.generate_reply(
            user_message=request.message,
            knowledge_context=context,
            customer_name=request.customer_name,
            conversation_history=history,
        )
        
        print(f"🤖 Reply: {result['reply'][:200]}")
        print(f"📤 Answered: {result['is_answered']}")
        
        should_escalate = not result['is_answered']
        
        reply_lower = result['reply'].lower()
        escalation_phrases = [
            'forward', 'team ko', 'team se', 'jaldi contact',
            'team को', 'team से', 'request team',
            'contact karenge', 'संपर्क', 'رابطہ', 'shortly',
        ]
        
        if any(phrase in reply_lower for phrase in escalation_phrases):
            should_escalate = True
        
        print(f"📤 Should escalate: {should_escalate}")
        print(f"{'='*60}\n")
        
        return AIResponse(
            reply=result['reply'],
            intent=result['intent'],
            confidence=0.9,
            sentiment="neutral",
            should_escalate=should_escalate
        )
        
    except Exception as e:
        print(f"❌ Error: {e}")
        import traceback
        traceback.print_exc()
        
        return AIResponse(
            reply="Namaste! 🙏 Thodi technical issue hai. Aap thodi der me dobara message karein. 😊",
            should_escalate=True
        )


@app.post("/api/ai/knowledge")
async def add_knowledge(request: KnowledgeRequest):
    try:
        print(f"📥 Adding knowledge: {request.tenant_id}")
        count = rag_engine.add_document(request.tenant_id, request.text, request.metadata)
        print(f"✅ Added {count} chunks")
        return {"status": "success", "chunks_added": count}
    except Exception as e:
        print(f"❌ Knowledge error: {e}")
        raise HTTPException(status_code=500, detail=str(e))


@app.post("/api/ai/search")
async def search_knowledge(request: SearchRequest):
    try:
        results = rag_engine.search(request.tenant_id, request.query, request.limit)
        return {"results": results}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


if __name__ == "__main__":
    port = int(os.getenv("PORT", 8001))
    uvicorn.run(app, host="0.0.0.0", port=port)