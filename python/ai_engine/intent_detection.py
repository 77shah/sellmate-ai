# python/ai_engine/intent_detection.py

import json
import os
from groq import Groq

GROQ_API_KEY = os.getenv('GROQ_API_KEY')
GROQ_MODEL = os.getenv('GROQ_MODEL', 'llama3-70b-8192')

class IntentDetector:
    def __init__(self):
        self.client = Groq(api_key=GROQ_API_KEY)
        
    def detect(self, message: str):
        try:
            # 🔥 Groq se intent detect karein
            prompt = f"""Classify the customer message into one of these intents:
1. product_inquiry - Asking about products
2. order_placement - Wanting to place order
3. order_status - Asking about order status
4. complaint - Having an issue/complaint
5. payment - Payment related query
6. general_chat - General conversation

Message: {message}

Return ONLY JSON: {{"intent": "intent_name", "confidence": 0.95}}"""

            completion = self.client.chat.completions.create(
                model=GROQ_MODEL,
                messages=[{"role": "user", "content": prompt}],
                temperature=0.3,
                max_tokens=100
            )
            
            result = json.loads(completion.choices[0].message.content)
            
            return {
                'intent': result.get('intent', 'general_chat'),
                'confidence': result.get('confidence', 0.5),
                'entities': {}
            }
            
        except Exception as e:
            print(f"Intent detection error: {e}")
            return {'intent': 'general_chat', 'confidence': 0.5, 'entities': {}}