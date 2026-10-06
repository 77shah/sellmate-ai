# python/ai_engine/models.py

from pydantic import BaseModel
from typing import Optional, List

class MessageRequest(BaseModel):
    tenant_id: str
    message: str
    customer_name: Optional[str] = None
    conversation_id: Optional[str] = None

class AIResponse(BaseModel):
    reply: str
    intent: str = "general"
    confidence: float = 0.8
    sentiment: str = "neutral"
    tool_calls: List = []
    should_escalate: bool = False