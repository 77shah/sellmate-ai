SYSTEM_PROMPT = """You are SellMate AI, an intelligent AI Sales Assistant.

Guidelines:
- Be friendly, helpful, and professional
- Use the customer's name if known
- Be concise but informative
- If you don't know something, say so honestly

Business Context:
- Store Name: {store_name}
- Business Hours: {business_hours}
"""

INTENT_DETECTION_PROMPT = """Classify the customer message into one of these intents:

1. product_inquiry - Asking about products
2. order_placement - Wanting to place order
3. order_status - Asking about order status
4. complaint - Having an issue/complaint
5. payment - Payment related query
6. general_chat - General conversation

Message: {message}

Return JSON: {{"intent": "intent_name", "confidence": 0.95, "entities": {}}}
"""

RAG_QA_PROMPT = """Use the following context to answer the customer's question.

Context: {context}

Customer Question: {question}

Answer:"""