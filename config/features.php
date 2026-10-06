<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    | Ye flags code me define hain, database me nahi.
    | Inhe enable/disable karna hai to bas config change karein.
    */
    
    'ai_chat' => [
        'enabled' => env('FEATURE_AI_CHAT', true),
        'name' => 'AI Chat',
        'description' => 'Enable AI chat for all customers',
        'category' => 'ai',
        'icon' => 'bx-chat',
    ],
    
    'whatsapp_integration' => [
        'enabled' => env('FEATURE_WHATSAPP', true),
        'name' => 'WhatsApp Integration',
        'description' => 'Enable WhatsApp messaging integration',
        'category' => 'integration',
        'icon' => 'bxl-whatsapp',
    ],
    
    'instagram_integration' => [
        'enabled' => env('FEATURE_INSTAGRAM', false),
        'name' => 'Instagram Integration',
        'description' => 'Enable Instagram messaging integration',
        'category' => 'integration',
        'icon' => 'bxl-instagram',
    ],
    
    'knowledge_base' => [
        'enabled' => env('FEATURE_KNOWLEDGE_BASE', true),
        'name' => 'Knowledge Base',
        'description' => 'Enable RAG knowledge base for AI',
        'category' => 'ai',
        'icon' => 'bx-book',
    ],
    
    'voice_ai' => [
        'enabled' => env('FEATURE_VOICE_AI', false),
        'name' => 'Voice AI',
        'description' => 'Enable voice AI calling agent (Beta)',
        'category' => 'ai',
        'icon' => 'bx-microphone',
    ],
    
    'multi_tenant' => [
        'enabled' => env('FEATURE_MULTI_TENANT', true),
        'name' => 'Multi-Tenant',
        'description' => 'Enable multi-tenant support',
        'category' => 'core',
        'icon' => 'bx-building',
    ],
    
    'analytics_dashboard' => [
        'enabled' => env('FEATURE_ANALYTICS', true),
        'name' => 'Analytics Dashboard',
        'description' => 'Enable advanced analytics dashboard',
        'category' => 'analytics',
        'icon' => 'bx-bar-chart',
    ],
    
    'automation_workflows' => [
        'enabled' => env('FEATURE_WORKFLOWS', false),
        'name' => 'Automation Workflows',
        'description' => 'Enable drag-drop automation workflows',
        'category' => 'automation',
        'icon' => 'bx-git-branch',
    ],
    
    'payment_gateway' => [
        'enabled' => env('FEATURE_PAYMENT', true),
        'name' => 'Payment Gateway',
        'description' => 'Enable payment processing',
        'category' => 'payment',
        'icon' => 'bx-credit-card',
    ],
    
    'bulk_import' => [
        'enabled' => env('FEATURE_BULK_IMPORT', true),
        'name' => 'Bulk Import/Export',
        'description' => 'Enable CSV/Excel bulk import and export',
        'category' => 'data',
        'icon' => 'bx-import',
    ],
];