# python/ai_engine/rag_engine.py

import uuid
import logging
from typing import List, Dict, Optional
from qdrant_client import QdrantClient
from qdrant_client.http import models
from sentence_transformers import SentenceTransformer
from .config import config

logger = logging.getLogger(__name__)


class RAGEngine:
    def __init__(self):
        print("=" * 60)
        print("✅ RAG Engine initializing...")
        print("=" * 60)
        
        # 🔥 Qdrant Client
        try:
            if config.QDRANT_API_KEY:
                self.qdrant = QdrantClient(
                    url=config.QDRANT_URL,
                    api_key=config.QDRANT_API_KEY
                )
                print(f"✅ Qdrant connected (with API key): {config.QDRANT_URL}")
            else:
                self.qdrant = QdrantClient(url=config.QDRANT_URL)
                print(f"✅ Qdrant connected: {config.QDRANT_URL}")
        except Exception as e:
            print(f"❌ Qdrant connection failed: {e}")
            raise
        
        # 🔥 FREE Local Embedding Model
        print(f"🔄 Loading embedding model: {config.EMBEDDING_MODEL}...")
        try:
            self.embedder = SentenceTransformer(config.EMBEDDING_MODEL)
            print(f"✅ Embedding model loaded (Free, offline)")
            print(f"   Vector size: {config.VECTOR_SIZE}")
        except Exception as e:
            print(f"❌ Embedding model failed: {e}")
            raise
        
        self.collection = config.COLLECTION_NAME
        self._ensure_collection()
        
        # 🔥 Startup pe collections list dikhao
        self._print_collections()
        
        print("✅ RAG Engine ready!")
        print("=" * 60)
    
    # ============================================================
    # COLLECTION MANAGEMENT
    # ============================================================
    def _ensure_collection(self):
        """Collection create karo agar nahi hai"""
        try:
            collections = self.qdrant.get_collections().collections
            names = [c.name for c in collections]
            
            if self.collection not in names:
                self.qdrant.create_collection(
                    collection_name=self.collection,
                    vectors_config=models.VectorParams(
                        size=config.VECTOR_SIZE,
                        distance=models.Distance.COSINE
                    )
                )
                print(f"✅ Collection created: {self.collection}")
            else:
                print(f"✅ Collection exists: {self.collection}")
                
                # Count check karo
                try:
                    info = self.qdrant.get_collection(self.collection)
                    print(f"   📊 Points in collection: {info.points_count}")
                except Exception as e:
                    print(f"   ⚠️ Could not get count: {e}")
        except Exception as e:
            print(f"❌ Collection check error: {e}")
            raise
    
    def _print_collections(self):
        """Startup pe saari collections print karo — debug ke liye"""
        try:
            collections = self.qdrant.get_collections().collections
            print(f"📦 Available Qdrant collections:")
            for c in collections:
                try:
                    info = self.qdrant.get_collection(c.name)
                    print(f"   • {c.name} — {info.points_count} points")
                except:
                    print(f"   • {c.name}")
        except Exception as e:
            print(f"⚠️ Could not list collections: {e}")
    
    # ============================================================
    # EMBEDDING
    # ============================================================
    def _get_embedding(self, text: str) -> List[float]:
        """FREE local embedding"""
        try:
            if not text or not text.strip():
                print("⚠️ Empty text for embedding")
                return [0.0] * config.VECTOR_SIZE
            
            embedding = self.embedder.encode(
                text,
                convert_to_numpy=True,
                show_progress_bar=False
            )
            return embedding.tolist()
        except Exception as e:
            print(f"❌ Embedding error: {e}")
            import traceback
            traceback.print_exc()
            return [0.0] * config.VECTOR_SIZE
    
    # ============================================================
    # ADD DOCUMENT
    # ============================================================
    def add_document(self, tenant_id: str, text: str, metadata: dict = None):
        """Document chunks ko Qdrant me save karo — PERMANENT"""
        try:
            print(f"\n{'='*60}")
            print(f"📄 Adding document")
            print(f"   Tenant ID: {tenant_id}")
            print(f"   Text length: {len(text)} chars")
            print(f"   Metadata: {metadata}")
            print(f"{'='*60}")
            
            if not text or not text.strip():
                print("❌ Empty text — cannot add document")
                return 0
            
            # 🔥 Chunks banao
            chunks = []
            chunk_size = config.CHUNK_SIZE
            overlap = getattr(config, 'CHUNK_OVERLAP', 50)
            
            for i in range(0, len(text), chunk_size - overlap):
                chunk = text[i:i + chunk_size]
                if chunk.strip():
                    chunks.append(chunk)
            
            print(f"📝 Created {len(chunks)} chunks (size: {chunk_size}, overlap: {overlap})")
            
            # 🔥 Points banao
            points = []
            for i, chunk in enumerate(chunks):
                embedding = self._get_embedding(chunk)
                point_id = str(uuid.uuid4())
                
                points.append(
                    models.PointStruct(
                        id=point_id,
                        vector=embedding,
                        payload={
                            'tenant_id': tenant_id,
                            'text': chunk,
                            'chunk_index': i,
                            'metadata': metadata or {}
                        }
                    )
                )
            
            # 🔥 Qdrant me upsert
            self.qdrant.upsert(
                collection_name=self.collection,
                points=points
            )
            
            print(f"✅ Added {len(points)} chunks to Qdrant for tenant: {tenant_id}")
            
            # Verify karo
            try:
                info = self.qdrant.get_collection(self.collection)
                print(f"📊 Total points in collection now: {info.points_count}")
            except:
                pass
            
            return len(points)
        except Exception as e:
            print(f"❌ Add document error: {e}")
            import traceback
            traceback.print_exc()
            return 0
    
    # ============================================================
    # SEARCH
    # ============================================================
    def search(self, tenant_id: str, query: str, limit: int = 5):
        """Qdrant se relevant chunks dhundho"""
        try:
            print(f"🔍 Searching for tenant: {tenant_id}")
            print(f"   Query: {query}")
            
            query_embedding = self._get_embedding(query)
            
            results = self.qdrant.search(
                collection_name=self.collection,
                query_vector=query_embedding,
                query_filter=models.Filter(
                    must=[
                        models.FieldCondition(
                            key='tenant_id',
                            match=models.MatchValue(value=tenant_id)
                        )
                    ]
                ),
                limit=limit
            )
            
            formatted = []
            for result in results:
                formatted.append({
                    'text': result.payload.get('text', ''),
                    'score': result.score,
                    'metadata': result.payload.get('metadata', {})
                })
            
            print(f"✅ Found {len(formatted)} results")
            return formatted
        except Exception as e:
            print(f"❌ Search error: {e}")
            import traceback
            traceback.print_exc()
            return []
    
    # ============================================================
    # GET FULL CONTEXT — MAIN FUNCTION
    # ============================================================
    def get_full_context(self, tenant_id: str) -> str:
        """Poore document ka context nikaalo — WhatsApp AI ke liye"""
        try:
            print(f"\n{'='*60}")
            print(f"📚 Getting full context")
            print(f"   Tenant ID: {tenant_id}")
            print(f"   Collection: {self.collection}")
            print(f"{'='*60}")
            
            # 🔥 Pehle check karo collection me total points
            try:
                info = self.qdrant.get_collection(self.collection)
                print(f"📊 Total points in collection: {info.points_count}")
            except Exception as e:
                print(f"⚠️ Could not get collection info: {e}")
            
            # 🔥 Scroll karo tenant-specific points
            results, _ = self.qdrant.scroll(
                collection_name=self.collection,
                scroll_filter=models.Filter(
                    must=[
                        models.FieldCondition(
                            key='tenant_id',
                            match=models.MatchValue(value=tenant_id)
                        )
                    ]
                ),
                limit=1000,
                with_payload=True,
                with_vectors=False
            )
            
            print(f"📦 Found {len(results)} points for tenant: {tenant_id}")
            
            if not results:
                print(f"⚠️⚠️⚠️ NO DATA FOUND for tenant: {tenant_id}")
                print(f"⚠️ Ye tenant Qdrant me nahi hai!")
                print(f"⚠️ Check karo:")
                print(f"   1. PDF upload hui thi is tenant ke liye?")
                print(f"   2. Tenant ID match kar rahi hai?")
                
                # 🔥 Debug — kaunse tenant_ids Qdrant me hain
                self._debug_tenant_ids()
                
                return ""
            
            # 🔥 Sort by chunk_index
            sorted_results = sorted(
                results,
                key=lambda x: x.payload.get('chunk_index', 0)
            )
            
            # 🔥 Combine all text
            full_text = " ".join([
                r.payload.get('text', '') for r in sorted_results
                if r.payload.get('text', '').strip()
            ])
            
            print(f"✅ Context built: {len(full_text)} chars")
            print(f"📚 Preview: {full_text[:200]}...")
            print(f"{'='*60}\n")
            
            return full_text
            
        except Exception as e:
            print(f"❌ Get context error: {e}")
            import traceback
            traceback.print_exc()
            return ""
    
    # ============================================================
    # DEBUG HELPER
    # ============================================================
    def _debug_tenant_ids(self):
        """Debug — Qdrant me kaunse tenant_ids hain"""
        try:
            print(f"\n🔍 DEBUG: Fetching all tenant_ids from Qdrant...")
            
            results, _ = self.qdrant.scroll(
                collection_name=self.collection,
                limit=1000,
                with_payload=True,
                with_vectors=False
            )
            
            tenant_ids = set()
            for r in results:
                tid = r.payload.get('tenant_id')
                if tid:
                    tenant_ids.add(tid)
            
            if tenant_ids:
                print(f"📋 Available tenant_ids in Qdrant:")
                for tid in tenant_ids:
                    count = sum(1 for r in results if r.payload.get('tenant_id') == tid)
                    print(f"   • {tid} ({count} points)")
            else:
                print(f"⚠️ NO tenant_ids found — Qdrant is EMPTY!")
            
            print()
        except Exception as e:
            print(f"❌ Debug error: {e}")
    
    # ============================================================
    # DELETE
    # ============================================================
    def delete_document(self, tenant_id: str):
        """Tenant ke saare documents delete karo"""
        try:
            print(f"🗑️ Deleting all documents for tenant: {tenant_id}")
            
            self.qdrant.delete(
                collection_name=self.collection,
                points_selector=models.FilterSelector(
                    filter=models.Filter(
                        must=[
                            models.FieldCondition(
                                key='tenant_id',
                                match=models.MatchValue(value=tenant_id)
                            )
                        ]
                    )
                )
            )
            
            print(f"✅ Deleted all documents for {tenant_id}")
            return True
        except Exception as e:
            print(f"❌ Delete error: {e}")
            import traceback
            traceback.print_exc()
            return False
    
    # ============================================================
    # HEALTH CHECK
    # ============================================================
    def health_check(self) -> dict:
        """RAG engine ka health check"""
        try:
            info = self.qdrant.get_collection(self.collection)
            return {
                "status": "healthy",
                "collection": self.collection,
                "total_points": info.points_count,
                "qdrant_url": config.QDRANT_URL,
                "embedding_model": config.EMBEDDING_MODEL,
                "vector_size": config.VECTOR_SIZE,
            }
        except Exception as e:
            return {
                "status": "unhealthy",
                "error": str(e)
            }
    
    # ============================================================
    # LIST TENANTS (Debug)
    # ============================================================
    def list_tenants(self) -> dict:
        """Qdrant me kaunse tenants hain aur kitne points"""
        try:
            results, _ = self.qdrant.scroll(
                collection_name=self.collection,
                limit=10000,
                with_payload=True,
                with_vectors=False
            )
            
            tenant_map = {}
            for r in results:
                tid = r.payload.get('tenant_id', 'unknown')
                tenant_map[tid] = tenant_map.get(tid, 0) + 1
            
            return {
                "total_points": len(results),
                "tenants": tenant_map
            }
        except Exception as e:
            return {"error": str(e)}