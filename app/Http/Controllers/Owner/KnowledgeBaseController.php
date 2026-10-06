<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $documents = KnowledgeBase::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(15);
        
        return view('owner.knowledge.index', compact('documents'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $user = Auth::user();
        $plan = $user->currentPlan;
        
        // 🔥 PLAN LIMITS
        $maxChunks = $plan->knowledge_max_chunks ?? 15;
        $maxChars = $plan->knowledge_max_chars ?? 15000;
        $maxDocuments = $plan->max_documents ?? 2;
        
        // 🔥 CURRENT USAGE
        $documents = KnowledgeBase::where('tenant_id', $tenantId)
            ->whereIn('status', ['processed', 'processing'])
            ->get();
        
        $currentChunks = 0;
        $currentChars = 0;
        
        foreach ($documents as $doc) {
            $currentChunks += $doc->metadata['chunks_added'] ?? 0;
            $currentChars += $doc->metadata['text_length'] ?? 0;
        }
        
        $currentDocumentCount = $documents->count();
        
        $remainingChunks = max(0, $maxChunks - $currentChunks);
        $remainingChars = max(0, $maxChars - $currentChars);
        $remainingDocuments = max(0, $maxDocuments - $currentDocumentCount);
        
        $usage = [
            'plan_name' => $plan->name ?? 'Free',
            'max_chunks' => $maxChunks,
            'max_chars' => $maxChars,
            'max_documents' => $maxDocuments,
            'current_chunks' => $currentChunks,
            'current_chars' => $currentChars,
            'current_documents' => $currentDocumentCount,
            'remaining_chunks' => $remainingChunks,
            'remaining_chars' => $remainingChars,
            'remaining_documents' => $remainingDocuments,
            'can_upload' => $remainingChunks > 0 && $remainingDocuments > 0,
        ];
        
        return view('owner.knowledge.create', compact('usage'));
    }

    public function store(Request $request)
    {
        Log::info('🔥 KNOWLEDGE STORE CALLED');
        
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $user = Auth::user();
        $plan = $user->currentPlan;
        
        // 🔥 PLAN LIMITS
        $maxChunks = $plan->knowledge_max_chunks ?? 15;
        $maxChars = $plan->knowledge_max_chars ?? 15000;
        $maxDocuments = $plan->max_documents ?? 2;
        
        // 🔥 CURRENT USAGE
        $documents = KnowledgeBase::where('tenant_id', $tenantId)
            ->whereIn('status', ['processed', 'processing'])
            ->get();
        
        $currentChunks = 0;
        $currentChars = 0;
        
        foreach ($documents as $doc) {
            $currentChunks += $doc->metadata['chunks_added'] ?? 0;
            $currentChars += $doc->metadata['text_length'] ?? 0;
        }
        
        $currentDocumentCount = $documents->count();
        
        // 🔥 CHECK 1: Document count limit
        if ($currentDocumentCount >= $maxDocuments) {
            return back()->with('error', 
                "❌ Document limit reached! " .
                "Your {$plan->name} plan allows maximum {$maxDocuments} documents. " .
                "You have {$currentDocumentCount} documents. " .
                "Please delete old documents or upgrade your plan."
            );
        }
        
        // 🔥 VALIDATION
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,csv,txt|max:10240',
            'type' => 'required|in:pdf,excel,website',
            'name' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('knowledge/' . $tenantId, $filename, 'public');

        // 🔥 EXTRACT TEXT
        $text = $this->extractText($path, $request->type);
        $textLength = strlen($text);
        
        Log::info("Text extracted: {$textLength} chars");

        // 🔥 CHECK 2: Char limit per document
        if ($textLength > $maxChars) {
            Storage::disk('public')->delete($path);
            
            return back()->with('error', 
                "❌ Document too large! " .
                "Your document has " . number_format($textLength) . " characters. " .
                "Your {$plan->name} plan allows maximum " . number_format($maxChars) . " characters per document. " .
                "\n\nPlease: \n" .
                "1. Split your document into smaller parts, OR\n" .
                "2. Upgrade your plan for larger limits."
            );
        }

        // 🔥 CHECK 3: Total chars limit
        $newTotalChars = $currentChars + $textLength;
        if ($newTotalChars > $maxChars * $maxDocuments) {
            Storage::disk('public')->delete($path);
            
            return back()->with('error', 
                "❌ Plan limit would be exceeded! " .
                "Total chars after upload: " . number_format($newTotalChars) . ". " .
                "Your {$plan->name} plan allows maximum " . number_format($maxChars * $maxDocuments) . " characters total. " .
                "\n\nPlease delete old documents or upgrade your plan."
            );
        }

        // 🔥 ESTIMATE CHUNKS
        $estimatedChunks = ceil($textLength / 500);
        $newTotalChunks = $currentChunks + $estimatedChunks;
        
        // 🔥 CHECK 4: Chunks limit
        if ($newTotalChunks > $maxChunks) {
            Storage::disk('public')->delete($path);
            
            $remainingChunks = max(0, $maxChunks - $currentChunks);
            
            return back()->with('error', 
                "❌ Not enough chunks available! " .
                "This document needs ~{$estimatedChunks} chunks. " .
                "Your {$plan->name} plan has only {$remainingChunks} chunks remaining (out of {$maxChunks}). " .
                "\n\nOptions:\n" .
                "1. Delete old documents, OR\n" .
                "2. Split this document into smaller parts, OR\n" .
                "3. Upgrade your plan."
            );
        }

        // 🔥 CREATE RECORD
        $knowledge = KnowledgeBase::create([
            'tenant_id' => $tenantId,
            'name' => $request->name ?? $file->getClientOriginalName(),
            'type' => $request->type,
            'file_path' => $path,
            'status' => 'processing',
            'uploaded_by' => Auth::id(),
            'metadata' => [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'text_length' => $textLength,
                'estimated_chunks' => $estimatedChunks,
                'uploaded_at' => now()->toDateTimeString(),
            ],
        ]);

        Log::info('Knowledge record created: ' . $knowledge->id);

        // 🔥 PROCESS WITH AI
        $this->processWithAI($knowledge, $text);

        return redirect()->route('owner.knowledge')
            ->with('success', 
                "✅ Document uploaded successfully! " .
                "~{$estimatedChunks} chunks added. " .
                "Plan usage: " . ($currentChunks + $estimatedChunks) . "/{$maxChunks} chunks, " .
                ($currentDocumentCount + 1) . "/{$maxDocuments} documents."
            );
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $document = KnowledgeBase::where('tenant_id', $tenantId)->findOrFail($id);
        return view('owner.knowledge.show', compact('document'));
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $document = KnowledgeBase::where('tenant_id', $tenantId)->findOrFail($id);

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('owner.knowledge.index')
            ->with('success', 'Document deleted! You can upload a new one now.');
    }

    public function reprocess($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $document = KnowledgeBase::where('tenant_id', $tenantId)->findOrFail($id);

        try {
            $health = Http::timeout(5)->get('http://localhost:8001/health');
            if (!$health->successful()) {
                return back()->with('error', 'Python AI service is not responding.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Python AI service is not running!');
        }

        try {
            $qdrant = Http::timeout(5)->get('http://localhost:6333/healthz');
            if (!$qdrant->successful()) {
                return back()->with('error', 'Qdrant is not responding.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Qdrant is not running!');
        }

        $document->update(['status' => 'processing']);

        $text = $this->extractText($document->file_path, $document->type);
        $this->processWithAI($document, $text);

        return back()->with('success', 'Document is being reprocessed.');
    }

    private function extractText($path, $type)
    {
        $fullPath = Storage::disk('public')->path($path);

        if ($type === 'pdf') {
            return $this->extractPdfText($fullPath);
        } elseif ($type === 'excel') {
            return $this->extractExcelText($fullPath);
        }

        return file_get_contents($fullPath);
    }

    private function extractPdfText($path)
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
            
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            $text = preg_replace('/[^\x20-\x7E\x0A\x0D\x09]/u', ' ', $text);
            $text = preg_replace('/\s+/', ' ', trim($text));
            $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);
            
            return $text;
        } catch (\Exception $e) {
            Log::error('PDF Extract Error: ' . $e->getMessage());
            return "Error extracting PDF text: " . $e->getMessage();
        }
    }

    private function extractExcelText($path)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $text = '';
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $text .= $cell->getValue() . ' ';
                    }
                    $text .= "\n";
                }
            }
            return $text;
        } catch (\Exception $e) {
            Log::error('Excel Extract Error: ' . $e->getMessage());
            return "Error extracting Excel text: " . $e->getMessage();
        }
    }

    private function processWithAI($knowledge, $text)
    {
        try {
            Log::info('🔥 PROCESS WITH AI STARTED');
            Log::info('Document ID: ' . $knowledge->id);
            Log::info('Text length: ' . strlen($text));
            
            try {
                $health = Http::timeout(2)->get('http://localhost:8001/health');
                Log::info('✅ Python health: ' . $health->status());
            } catch (\Exception $e) {
                Log::error('❌ Python not running: ' . $e->getMessage());
                $knowledge->update([
                    'status' => 'failed',
                    'metadata' => array_merge($knowledge->metadata ?? [], [
                        'error' => 'Python not running: ' . $e->getMessage()
                    ])
                ]);
                return;
            }

            $response = Http::timeout(60)->post('http://localhost:8001/api/ai/knowledge', [
                'tenant_id' => $knowledge->tenant_id,
                'text' => $text,
                'metadata' => [
                    'document_id' => $knowledge->id,
                    'document_name' => $knowledge->name,
                    'type' => $knowledge->type,
                ]
            ]);

            Log::info('📥 Python response: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $chunksAdded = $data['chunks_added'] ?? 0;
                
                $knowledge->update([
                    'status' => 'processed',
                    'metadata' => array_merge($knowledge->metadata ?? [], [
                        'chunks_added' => $chunksAdded,
                        'processed_at' => now()->toDateTimeString(),
                        'ai_status' => 'success'
                    ])
                ]);
                
                Log::info('✅ Document processed! Chunks: ' . $chunksAdded);
            } else {
                Log::error('❌ AI failed: ' . $response->status());
                $knowledge->update([
                    'status' => 'failed',
                    'metadata' => array_merge($knowledge->metadata ?? [], [
                        'ai_status' => 'failed',
                        'error' => 'Status: ' . $response->status()
                    ])
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ AI Error: ' . $e->getMessage());
            $knowledge->update([
                'status' => 'failed',
                'metadata' => array_merge($knowledge->metadata ?? [], [
                    'ai_status' => 'failed',
                    'error' => $e->getMessage()
                ])
            ]);
        }
    }
}