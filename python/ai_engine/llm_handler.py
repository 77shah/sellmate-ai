# python/ai_engine/llm_handler.py

import os
import httpx
import json
import asyncio
import re
from typing import Optional, List
from dotenv import load_dotenv

load_dotenv()


class LLMHandler:
    def __init__(self):
        self.api_key = os.getenv('GROQ_API_KEY', '')
        self.api_url = "https://api.groq.com/openai/v1/chat/completions"
        self.model = os.getenv('GROQ_MODEL', 'openai/gpt-oss-20b')
        
        if not self.api_key:
            print("❌❌❌ GROQ_API_KEY NOT LOADED! Check python/.env ❌❌❌")
        else:
            print(f"✅ GROQ key loaded: {self.api_key[:15]}...")
        print(f"✅ LLM Handler initialized (Model: {self.model})")
    
    async def generate_reply(
        self,
        user_message: str,
        knowledge_context: str,
        customer_name: Optional[str] = None,
        conversation_history: List[dict] = None
    ) -> dict:
        
        system_prompt = self._build_system_prompt(knowledge_context)
        messages = [{"role": "system", "content": system_prompt}]
        
        # 🔥 CONVERSATION HISTORY — pichli baatein yaad rakho
        if conversation_history:
            for msg in conversation_history[-10:]:  # last 10 messages
                messages.append({
                    "role": msg.get('role', 'user'),
                    "content": msg.get('content', '')
                })
        
        messages.append({"role": "user", "content": user_message})
        
        # 🔥 MAX TOKENS — badhao (reply cut na ho)
        max_tokens = int(os.getenv('GROQ_MAX_TOKENS', 2500))
        
        # 🔥 RETRY LOGIC — 3 attempts (kam karo, fast reply ke liye)
        max_retries = 3
        last_error = None
        
        for attempt in range(max_retries):
            try:
                print(f"📤 Groq API call (attempt {attempt + 1}/{max_retries})...")
                
                async with httpx.AsyncClient(timeout=25.0) as client:
                    response = await client.post(
                        self.api_url,
                        headers={
                            "Authorization": f"Bearer {self.api_key}",
                            "Content-Type": "application/json"
                        },
                        json={
                            "model": self.model,
                            "messages": messages,
                            "temperature": 0.6,
                            "max_tokens": max_tokens,
                        }
                    )
                    
                    print(f"📥 Groq status: {response.status_code}")
                    
                    if response.status_code == 200:
                        data = response.json()
                        reply = data['choices'][0]['message']['content'].strip()
                        
                        finish_reason = data['choices'][0].get('finish_reason', 'stop')
                        if finish_reason == 'length':
                            print(f"⚠️ TRUNCATED — increase max_tokens")
                        
                        print(f"🤖 LLM Reply: {reply[:200]}...")
                        
                        reply = self._clean_hallucinations(reply, knowledge_context)
                        is_answered = self._is_answered(reply)
                        
                        return {
                            "reply": reply,
                            "is_answered": is_answered,
                            "intent": "general",
                        }
                    
                    elif response.status_code == 429:
                        retry_after = 5
                        try:
                            error_data = response.json()
                            msg = error_data.get('error', {}).get('message', '')
                            match = re.search(r'try again in ([\d.]+)s', msg)
                            if match:
                                retry_after = float(match.group(1)) + 1
                        except:
                            pass
                        
                        print(f"⏳ Rate limit — waiting {retry_after}s...")
                        await asyncio.sleep(retry_after)
                        last_error = "rate_limit"
                        continue
                    
                    else:
                        print(f"❌ Groq API Error: {response.status_code}")
                        last_error = f"http_{response.status_code}"
                        await asyncio.sleep(2)
                        continue
                        
            except Exception as e:
                print(f"❌ LLM Exception (attempt {attempt + 1}): {e}")
                last_error = str(e)
                await asyncio.sleep(1)
                continue
        
        print(f"❌ All {max_retries} attempts failed. Last error: {last_error}")
        
        fallback_reply = self._simple_fallback(user_message, knowledge_context)
        
        return {
            "reply": fallback_reply,
            "is_answered": False,
            "intent": "general",
        }
    
    def _clean_hallucinations(self, reply: str, context: str) -> str:
        """🔥 AI ne koi fake info di to usse saaf karo"""
        
        # 1. Fake phone numbers
        fake_phone_pattern = re.compile(r'\b(?:123|456|789|000|111)[\s\-]?[\d\s\-]{7,}\b')
        if fake_phone_pattern.search(reply):
            print(f"⚠️ Fake phone number detected")
            reply = re.sub(fake_phone_pattern, "hamare number", reply)
        
        # 2. Fake URLs
        if 'example.com' in reply.lower() or 'test.com' in reply.lower():
            print(f"⚠️ Fake URL detected")
            reply = reply.replace('example.com', 'hamari website')
            reply = reply.replace('test.com', 'hamari website')
        
        # 3. Context me nahi hai koi phone number
        phone_matches = re.findall(r'[\+]?[\d][\d\s\-\(\)]{8,}[\d]', reply)
        for phone in phone_matches:
            cleaned_phone = re.sub(r'[\s\-\(\)\+]', '', phone)
            if len(cleaned_phone) >= 10 and cleaned_phone not in re.sub(r'[\s\-\(\)\+]', '', context):
                print(f"⚠️ Phone '{phone}' not in context — removing")
                reply = reply.replace(phone, "hamare number")
        
        # 4. Bad phrases
        bad_phrases = [
            "we're working on that", "we are working on",
            "stay tuned", "coming soon", "will be available soon",
            "abhi kaam chal raha", "jald aa raha",
            "sorry, we don't have", "sorry, i don't have",
            "not available at the moment", "feel free to message us",
            "माफ़ कीजिये", "क्षमा करें",
        ]
        
        for phrase in bad_phrases:
            if phrase.lower() in reply.lower():
                print(f"⚠️ Bad phrase: {phrase}")
                reply = "Ye information mere paas abhi nahi hai. Main aapki query team ko forward kar rahi hun — wo aapko jaldi contact karenge. 😊"
                break
        
        # 🔥 5. Reply length limit — WhatsApp ke liye
        MAX_REPLY_LENGTH = 1500
        if len(reply) > MAX_REPLY_LENGTH:
            print(f"⚠️ Reply too long ({len(reply)} chars) — trimming")
            lines = reply.split('\n')
            trimmed_lines = []
            total_chars = 0
            
            for line in lines:
                if total_chars + len(line) > MAX_REPLY_LENGTH:
                    break
                trimmed_lines.append(line)
                total_chars += len(line) + 1
            
            reply = '\n'.join(trimmed_lines)
            
            if "aur bhi" not in reply.lower():
                reply += "\n\nAur bhi items hain! Koi specific dish ya category chahiye to bataiye 😊"
        
        return reply.strip()
    
    def _simple_fallback(self, message: str, context: str) -> str:
        """🔥 Agar AI fail ho jaye to context se simple reply"""
        message_lower = message.lower()
        
        # Language detect
        is_hindi_script = bool(re.search(r'[\u0900-\u097F]', message))
        is_urdu_script = bool(re.search(r'[\u0600-\u06FF]', message))
        is_roman_hindi = any(word in message_lower for word in [
            'kya', 'kaise', 'kahan', 'kab', 'kitna', 'batao', 'do', 'hai',
            'tum', 'aap', 'kr', 'kar', 'mai', 'me', 'bhai'
        ])
        
        if is_hindi_script:
            lang = 'hindi'
        elif is_urdu_script:
            lang = 'urdu'
        elif is_roman_hindi:
            lang = 'hinglish'
        else:
            lang = 'english'
        
        team_forward = {
            'hindi': "यह जानकारी मेरे पास अभी नहीं है। मैं आपकी request team को forward कर रही हूँ — वो आपको जल्दी contact करेंगे। 😊",
            'hinglish': "Ye information mere paas abhi nahi hai. Main aapki request team ko forward kar rahi hun — wo aapko jaldi contact karenge. 😊",
            'urdu': "یہ معلومات میرے پاس ابھی نہیں ہیں۔ میں آپ کی درخواست ٹیم کو بھیج رہی ہوں — وہ آپ سے جلد رابطہ کریں گے۔ 😊",
            'english': "I don't have this information right now. Let me forward your request to our team — they'll get back to you shortly. 😊",
        }
        
        # 🔥 Location / Address / Owner — context se dhundo
        if any(word in message_lower for word in ['location', 'address', 'kahan', 'where', 'पता', 'जगह', 'owner', 'malik', 'office', 'visit']):
            # Context me address dhundo
            if 'chishti nagar' in context.lower() or 'kanpur' in context.lower():
                return "📍 Hamara address:\nBismillah Restaurant\nChishti Nagar, Kanpur – 208015\nUttar Pradesh\n\nNear Chishti Nagar Market 📞"
            return team_forward[lang]
        
        # Menu
        if any(word in message_lower for word in ['menu', 'khana', 'food', 'dish', 'kya milega']):
            return "Haan ji! 🍽️ Hamare popular dishes:\n• Butter Chicken — ₹380\n• Chicken Biryani — ₹320\n• Mutton Biryani — ₹400\n• Tandoori Chicken — ₹550\n\nAur bhi items hain! Koi specific dish chahiye to bataiye 😊"
        
        # Timing
        elif any(word in message_lower for word in ['timing', 'time', 'khula', 'open', 'band']):
            return "🕐 Hamari timings:\n• Monday - Sunday: 12:00 PM - 11:30 PM\n\nAap kab aana chahenge? 😊"
        
        # Price
        elif any(word in message_lower for word in ['price', 'rate', 'kitna', 'cost']):
            return "💰 Prices menu me diye hain. Aap specific dish ka naam bataiye, main price bata deti hun. 😊"
        
        # Booking
        elif any(word in message_lower for word in ['book', 'reserve', 'table', 'booking']):
            return "✅ Table booking available hai! Aap batayein:\n• Date aur time\n• Kitne log\n\nMain book kar deti hun. 😊"
        
        # Greeting
        elif any(word in message_lower for word in ['hi', 'hello', 'hey', 'namaste', 'salam']):
            greeting = {
                'hindi': "नमस्ते! 🙏 हमारे restaurant में आपका स्वागत है। कैसे मदद कर सकती हूँ? 😊",
                'hinglish': "Namaste! 🙏 Hamare restaurant me aapka swagat hai. Kaise madad kar sakti hun? 😊",
                'urdu': "السلام علیکم! 🙏 ہمارے ریستوران میں خوش آمدید۔ کیسے مدد کر سکتی ہوں؟ 😊",
                'english': "Hi! 🙏 Welcome to our restaurant. How can I help you? 😊",
            }
            return greeting[lang]
        
        # QR / Payment
        elif any(word in message_lower for word in ['qr', 'payment', 'pay', 'credit card']):
            return team_forward[lang]
        
        return team_forward[lang]
    
    def _build_system_prompt(self, knowledge: str) -> str:
        return f"""You are a friendly, professional AI assistant for Bismillah Restaurant.
You are chatting with customers on WhatsApp.

BUSINESS KNOWLEDGE BASE:
═══════════════════════════════════════════════════════════════
{knowledge}
═══════════════════════════════════════════════════════════════

YOUR JOB:
- Reply to customer questions naturally like a human staff member
- Use ONLY the information from the knowledge base above
- Use conversation history to remember what customer said before
- Keep replies SHORT and friendly (3-6 lines max)
- Use emojis naturally

🌐 LANGUAGE MATCHING (CRITICAL):
- Customer writes HINGLISH → Reply HINGLISH
- Customer writes HINDI → Reply HINDI
- Customer writes ENGLISH → Reply ENGLISH
- Customer writes URDU → Reply URDU
- NEVER switch to English unnecessarily

🚨 CRITICAL RULES:

RULE 1: NEVER invent phone numbers, addresses, prices, URLs, QR codes
RULE 2: NEVER say "Sorry, we don't have..." or "Feel free to message us"
RULE 3: NEVER ask customer to "try again"
RULE 4: If info IS in knowledge base → GIVE IT DIRECTLY. Don't say "team will contact"
RULE 5: If info NOT in knowledge base → Say: "Ye information mere paas abhi nahi hai. Main aapki query team ko forward kar rahi hun — wo aapko jaldi contact karenge. 😊"

🔥 RULE 6: ADDRESS/LOCATION RULE (IMPORTANT):
- If customer asks "location", "address", "kahan hai":
  → Give FULL address from knowledge base: "Bismillah Restaurant, Chishti Nagar, Kanpur – 208015, Uttar Pradesh 📍"
- If address IS in knowledge base → give it directly, don't forward to team

🔥 RULE 7: MENU RULE (STRICT):
- If customer asks "menu kya hai" or "full menu":
  → Give ONLY 5-7 POPULAR items
  → End with: "Aur bhi items hain! Koi specific category chahiye to bataiye 😊"
  → NEVER list entire menu
- If customer asks specific category (e.g., "biryani") → list only that category

🔥 RULE 8: MEMORY RULE:
- Read the conversation history above
- If customer repeats something, acknowledge
- Don't ask again for info already given

FORMATTING:
- Line breaks for list items
- Bullet points (•)
- Emojis
- NO markdown (**, ##)
- NO prefixes like "Reply:"

EXAMPLES:

Q: "location btao"
A: "📍 Hamara address:\nBismillah Restaurant\nChishti Nagar, Kanpur – 208015\nUttar Pradesh\n\nNear Chishti Nagar Market. Aap easily aa sakte hain! 😊"

Q: "menu kya hai"
A: "Haan ji! 🍽️ Hamare popular dishes:\n• Butter Chicken — ₹380\n• Chicken Biryani — ₹320\n• Mutton Biryani — ₹400\n• Tandoori Chicken — ₹550\n• Paneer Tikka — ₹280\n\nAur bhi items hain! Koi specific category chahiye to bataiye 😊"

Q: "qr code do"
A: "Ye information mere paas abhi nahi hai. Main aapki query team ko forward kar rahi hun — wo aapko QR code bhej denge. 😊"

ALWAYS be helpful, polite, and NEVER invent information.
"""
    
    def _is_answered(self, reply: str) -> bool:
        fallback_keywords = [
            'forward', 'team ko', 'team se', 'jaldi contact',
            'team को', 'team से', 'request team',
            'contact karenge', 'संपर्क', 'رابطہ', 'shortly',
        ]
        reply_lower = reply.lower()
        return not any(kw in reply_lower for kw in fallback_keywords)