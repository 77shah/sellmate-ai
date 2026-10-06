class Tools:
    def __init__(self):
        pass
        
    async def check_inventory(self, product_id: str):
        return {"stock": 10}
        
    async def create_order(self, customer_id: str, products: list):
        return {"order_id": "ORD-001", "status": "created"}