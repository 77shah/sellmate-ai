<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TermsConditionController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\RefundPolicyController;
use App\Http\Controllers\SmsSettingController;
use App\Http\Controllers\SocialSettingController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\AppUpdateController;
use App\Http\Controllers\OtherDocumentController;
use App\Http\Controllers\ReferAndEarnController;
use App\Http\Controllers\ComplainTypeController;
use App\Http\Controllers\GatewaySettingController;
use App\Http\Controllers\AdsSettingController;
use App\Http\Controllers\FirebaseSettingController;
use App\Http\Controllers\GstRateController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\InnerCategoryController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\KnowledgeBaseController;
use App\Http\Controllers\Owner\CategoryController as OwnerCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\SuperAdmin\UserManagementController;

Route::get('/up', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'laravel',
        'version' => app()->version(),
        'environment' => app()->environment(),
    ]);
});

// ============================================
// 1. PUBLIC ROUTES
// ============================================
Route::get('/', function () { return view('auth.login'); });
Route::get('/privacy', function () { return view('privacy_public'); });
Route::get('/terms', function () { return view('terms_public'); });
Route::view('/data-deletion', 'data_deletion');

// ============================================
// 2. AI ROUTES
// ============================================
Route::post('/ai/chat', [AIController::class, 'chat'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/ai/health', [AIController::class, 'health']);

// ============================================
// 3. LOGIN ROUTES
// ============================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/super-admin/login', [AuthController::class, 'showSuperAdminLoginForm'])->name('super-admin.login');
Route::post('/super-admin/login', [AuthController::class, 'superAdminLogin'])->name('super-admin.login.submit');

Route::get('/staff/login', [AuthController::class, 'showStaffLoginForm'])->name('staff.login');
Route::post('/staff/login', [AuthController::class, 'staffLogin'])->name('staff.login.submit');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// 4. PROTECTED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {

    // ===== DASHBOARD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // ===== SUPER ADMIN =====
    Route::prefix('super-admin')->name('super-admin.')->middleware(['role:SuperAdmin'])->group(function () {

        // ==========================================
        // DASHBOARD
        // ==========================================
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])
            ->name('dashboard');

        // ==========================================
        // USER MANAGEMENT
        // ==========================================
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserManagementController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserManagementController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{id}', [UserManagementController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])
            ->name('users.destroy');

        Route::patch('/users/{id}/toggle', [UserManagementController::class, 'toggleStatus'])
            ->name('users.toggle');

        // ==========================================
        // BUSINESSES
        // ==========================================
        Route::get('/businesses', [SuperAdminController::class, 'businesses'])
            ->name('businesses');

        Route::get('/businesses/create', [SuperAdminController::class, 'createBusiness'])
            ->name('businesses.create');

        Route::post('/businesses', [SuperAdminController::class, 'storeBusiness'])
            ->name('businesses.store');

        Route::get('/businesses/{id}', [SuperAdminController::class, 'showBusiness'])
            ->name('businesses.show');

        Route::get('/businesses/{id}/edit', [SuperAdminController::class, 'editBusiness'])
            ->name('businesses.edit');

        Route::put('/businesses/{id}', [SuperAdminController::class, 'updateBusiness'])
            ->name('businesses.update');

        Route::delete('/businesses/{id}', [SuperAdminController::class, 'deleteBusiness'])
            ->name('businesses.delete');

        Route::post('/businesses/{id}/suspend', [SuperAdminController::class, 'suspendBusiness'])
            ->name('businesses.suspend');

        Route::post('/businesses/{id}/activate', [SuperAdminController::class, 'activateBusiness'])
            ->name('businesses.activate');

        Route::post('/businesses/{id}/impersonate', [SuperAdminController::class, 'impersonateBusiness'])
            ->name('businesses.impersonate');

        Route::post('/stop-impersonate', [SuperAdminController::class, 'stopImpersonate'])
            ->name('stop-impersonate');

        Route::get('/subscriptions', [SuperAdminController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/ai-usage', [SuperAdminController::class, 'aiUsage'])->name('ai-usage');
        Route::get('/cloud-resources', [SuperAdminController::class, 'cloudResources'])->name('cloud-resources');
        Route::get('/billing', [SuperAdminController::class, 'billing'])->name('billing');
        Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
        Route::post('/logs/clear', [SuperAdminController::class, 'clearLogs'])->name('logs.clear');
        Route::get('/api-gateway', [SuperAdminController::class, 'apiGateway'])->name('api-gateway');
        Route::get('/system-health', [SuperAdminController::class, 'systemHealth'])->name('system-health');
       
        // ===== DEPLOYMENTS =====
        Route::get('/deployments', [SuperAdminController::class, 'deployments'])->name('deployments');
        Route::get('/deployments/create', [SuperAdminController::class, 'createDeploymentForm'])->name('deployments.create');
        Route::post('/deployments', [SuperAdminController::class, 'storeDeployment'])->name('deployments.store');
        Route::post('/deployments/{id}/rollback', [SuperAdminController::class, 'rollbackDeployment'])->name('deployments.rollback');
        Route::get('/deployments/{id}', [SuperAdminController::class, 'deploymentDetails'])->name('deployments.show');
        Route::get('/feature-flags', [SuperAdminController::class, 'featureFlags'])->name('feature-flags');
        Route::get('/security', [SuperAdminController::class, 'security'])->name('security');
        Route::get('/whatsapp-settings', [SuperAdminController::class, 'whatsappSettings'])->name('whatsapp-settings');
    });

    // ===== OWNER =====
    Route::prefix('owner')->middleware(['auth', 'role:Owner'])->group(function () {
    
        // ===== SUBSCRIPTION PLANS (Owner) =====
        Route::get('/plans', [PaymentController::class, 'plans'])->name('owner.plans');
        Route::get('/subscription/status', [PaymentController::class, 'status'])->name('owner.subscription.status');
        Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('owner.payment.create.order');
        Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('owner.payment.verify');
        Route::post('/payment/cancel/{id}', [PaymentController::class, 'cancel'])->name('owner.payment.cancel');
        Route::post('/payment/renew/{id}', [PaymentController::class, 'renew'])->name('owner.payment.renew');
        Route::get('/subscribe/free/{plan}', [PaymentController::class, 'subscribeFree'])->name('owner.subscribe.free');
        Route::post('/switch-currency', [PaymentController::class, 'switchCurrency'])->name('switch.currency'); // 🔥 NEW

        // ===== KNOWLEDGE BASE =====
        Route::get('/knowledge', [KnowledgeBaseController::class, 'index'])->name('owner.knowledge');
        Route::get('/knowledge/create', [KnowledgeBaseController::class, 'create'])->name('owner.knowledge.create');
        Route::post('/knowledge', [KnowledgeBaseController::class, 'store'])->name('owner.knowledge.store');
        Route::get('/knowledge/{id}', [KnowledgeBaseController::class, 'show'])->name('owner.knowledge.show');
        Route::delete('/knowledge/{id}', [KnowledgeBaseController::class, 'destroy'])->name('owner.knowledge.destroy');
        Route::post('/knowledge/{id}/reprocess', [KnowledgeBaseController::class, 'reprocess'])->name('owner.knowledge.reprocess');
    
        // ===== DASHBOARD =====
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

        // ===== PRODUCTS =====
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('owner.products.index');
            Route::get('/create', [ProductController::class, 'create'])->name('owner.products.create');
            Route::post('/', [ProductController::class, 'store'])->name('owner.products.store');
            Route::get('/import', [ProductController::class, 'importForm'])->name('owner.products.import');
            Route::post('/import', [ProductController::class, 'import'])->name('owner.products.import.store');
            Route::get('/export', [ProductController::class, 'export'])->name('owner.products.export');
            Route::get('/{id}', [ProductController::class, 'show'])->name('owner.products.show');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('owner.products.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('owner.products.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('owner.products.destroy');
            Route::patch('/{id}/toggle', [ProductController::class, 'toggleStatus'])->name('owner.products.toggle');
        });

        // ===== CATEGORY =====
        Route::prefix('category')->group(function () {
            Route::get('/', [OwnerCategoryController::class, 'categoryIndex'])->name('owner.category.index');
            Route::post('/store', [OwnerCategoryController::class, 'categoryStore'])->name('owner.category.store');
            Route::put('/{id}', [OwnerCategoryController::class, 'categoryUpdate'])->name('owner.category.update');
            Route::patch('/{id}/toggle', [OwnerCategoryController::class, 'categoryToggle'])->name('owner.category.toggle');
            Route::delete('/{id}', [OwnerCategoryController::class, 'categoryDestroy'])->name('owner.category.destroy');
        });

        // ===== OTHER OWNER ROUTES =====
        Route::get('/inbox', [OwnerController::class, 'inbox'])->name('owner.inbox');
        Route::get('/inbox/{id}', [OwnerController::class, 'inboxShow'])->name('owner.inbox.show');
        Route::get('/crm', [OwnerController::class, 'crm'])->name('owner.crm');
        Route::get('/crm/create', [OwnerController::class, 'crmCreate'])->name('owner.crm.create');
        Route::post('/crm', [OwnerController::class, 'crmStore'])->name('owner.crm.store');
        Route::get('/crm/{id}', [OwnerController::class, 'crmShow'])->name('owner.crm.show');
        Route::get('/crm/{id}/edit', [OwnerController::class, 'crmEdit'])->name('owner.crm.edit');
        Route::put('/crm/{id}', [OwnerController::class, 'crmUpdate'])->name('owner.crm.update');
        Route::delete('/crm/{id}', [OwnerController::class, 'crmDelete'])->name('owner.crm.delete');
        Route::get('/orders', [OwnerController::class, 'orders'])->name('owner.orders');
        Route::get('/payments', [OwnerController::class, 'payments'])->name('owner.payments');

        // ===== UNANSWERED QUERIES =====
        Route::get('/unanswered-queries', [OwnerController::class, 'unansweredQueries'])->name('owner.unanswered-queries');
        Route::post('/unanswered-queries/{id}/reply', [OwnerController::class, 'replyToQuery'])->name('owner.unanswered-queries.reply');
        Route::post('/unanswered-queries/{id}/ignore', [OwnerController::class, 'ignoreQuery'])->name('owner.unanswered-queries.ignore');

        Route::get('/ai-settings', [OwnerController::class, 'aiSettings'])->name('owner.ai-settings');
        Route::post('/ai-settings', [OwnerController::class, 'updateAiSettings'])->name('owner.ai-settings.update');

        Route::get('/knowledge', [OwnerController::class, 'knowledge'])->name('owner.knowledge');
        Route::post('/knowledge/upload', [OwnerController::class, 'uploadKnowledge'])->name('owner.knowledge.upload');

        Route::get('/workflows', [OwnerController::class, 'workflows'])->name('owner.workflows');
        Route::post('/workflows', [OwnerController::class, 'storeWorkflow'])->name('owner.workflows.store');
        Route::get('/workflows/{id}/edit', [OwnerController::class, 'editWorkflow'])->name('owner.workflows.edit');
        Route::put('/workflows/{id}', [OwnerController::class, 'updateWorkflow'])->name('owner.workflows.update');
        Route::post('/workflows/{id}/toggle', [OwnerController::class, 'toggleWorkflow'])->name('owner.workflows.toggle');
        Route::delete('/workflows/{id}', [OwnerController::class, 'deleteWorkflow'])->name('owner.workflows.delete');

        Route::get('/analytics', [OwnerController::class, 'analytics'])->name('owner.analytics');

        // ===== REPORTS =====
        Route::get('/reports', [OwnerController::class, 'reports'])->name('owner.reports');
        Route::get('/reports/export/{type}', [OwnerController::class, 'exportReport'])->name('owner.reports.export');

        // ===== STAFF MANAGEMENT =====
        Route::get('/staff', [OwnerController::class, 'staff'])->name('owner.staff');
        Route::get('/staff/create', [OwnerController::class, 'staffCreate'])->name('owner.staff.create');
        Route::post('/staff', [OwnerController::class, 'staffStore'])->name('owner.staff.store');
        Route::get('/staff/{id}/edit', [OwnerController::class, 'staffEdit'])->name('owner.staff.edit');
        Route::put('/staff/{id}', [OwnerController::class, 'staffUpdate'])->name('owner.staff.update');
        Route::delete('/staff/{id}', [OwnerController::class, 'staffDelete'])->name('owner.staff.delete');
        Route::post('/staff/{id}/toggle', [OwnerController::class, 'staffToggle'])->name('owner.staff.toggle');

        // ===== WHATSAPP NUMBERS =====
        Route::get('/whatsapp-numbers', [OwnerController::class, 'whatsappNumbers'])->name('owner.whatsapp-numbers');
        Route::get('/whatsapp-numbers/create', [OwnerController::class, 'whatsappNumberCreate'])->name('owner.whatsapp-numbers.create');
        Route::post('/whatsapp-numbers', [OwnerController::class, 'whatsappNumberStore'])->name('owner.whatsapp-numbers.store');

        // 🔥 FIX: Edit route add karein
        Route::get('/whatsapp-numbers/{id}/edit', [OwnerController::class, 'whatsappNumberEdit'])->name('owner.whatsapp-numbers.edit');

        Route::put('/whatsapp-numbers/{id}', [OwnerController::class, 'whatsappNumberUpdate'])->name('owner.whatsapp-numbers.update');
        Route::delete('/whatsapp-numbers/{id}', [OwnerController::class, 'whatsappNumberDelete'])->name('owner.whatsapp-numbers.delete');
        Route::post('/whatsapp-numbers/{id}/set-default', [OwnerController::class, 'whatsappNumberSetDefault'])->name('owner.whatsapp-numbers.set-default');
        // 🔥 Embedded Signup route
Route::post('/whatsapp-numbers/embedded-signup', [OwnerController::class, 'whatsappNumberEmbeddedSignup'])
    ->name('owner.whatsapp-numbers.embedded-signup');
    });

    // ===== STAFF =====
    Route::prefix('staff')->middleware(['role:Staff,SalesAgent'])->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/inbox', [StaffController::class, 'inbox'])->name('staff.inbox');
        Route::get('/orders', [StaffController::class, 'orders'])->name('staff.orders');
        Route::get('/crm', [StaffController::class, 'crm'])->name('staff.crm');
    });

    // ===== ADMIN ROUTES =====
    Route::middleware(['role:Admin,SuperAdmin'])->group(function () {
        
        // Profile Routes
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile-update', [UserController::class, 'profileUpdate'])->name('profile.update');
        Route::get('/change-password', [UserController::class, 'changePassword'])->name('change.password');
        Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');

        // ===== PLANS MANAGEMENT (Super Admin Only) =====
        Route::prefix('plans')->group(function () {
            Route::get('/', [PlanController::class, 'index'])->name('admin.plans.index');
            Route::post('/', [PlanController::class, 'store'])->name('admin.plans.store');
            Route::put('/{plan}', [PlanController::class, 'update'])->name('admin.plans.update');
            Route::delete('/{plan}', [PlanController::class, 'destroy'])->name('admin.plans.destroy');
            Route::patch('/{plan}/toggle', [PlanController::class, 'toggleStatus'])->name('admin.plans.toggle');
        });

        // Category Routes
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::patch('/{id}/toggle', [CategoryController::class, 'toggleStatus'])->name('category.toggle');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
        });

        // Sub Category Routes
        Route::prefix('subcategory')->group(function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('subcategory.index');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('subcategory.store');
            Route::put('/{id}', [SubCategoryController::class, 'update'])->name('subcategory.update');
            Route::patch('/{id}/toggle', [SubCategoryController::class, 'toggleStatus'])->name('subcategory.toggle');
            Route::delete('/{id}', [SubCategoryController::class, 'destroy'])->name('subcategory.delete');
        });

        // Inner Category Routes
        Route::prefix('innercategory')->group(function () {
            Route::get('/', [InnerCategoryController::class, 'index'])->name('innercategory.index');
            Route::post('/store', [InnerCategoryController::class, 'store'])->name('innercategory.store');
            Route::put('/{id}', [InnerCategoryController::class, 'update'])->name('innercategory.update');
            Route::patch('/{id}/toggle', [InnerCategoryController::class, 'toggleStatus'])->name('innercategory.toggle');
            Route::delete('/{id}', [InnerCategoryController::class, 'destroy'])->name('innercategory.delete');
            Route::get('/get-subcategories/{categoryId}', [InnerCategoryController::class, 'getSubCategories']);
        });

        // Location Routes
        Route::prefix('location')->group(function () {
            Route::get('/country', [CountryController::class, 'index'])->name('location.country');
            Route::post('/country/store', [CountryController::class, 'store'])->name('location.country.store');
            Route::put('/country/{id}', [CountryController::class, 'update'])->name('location.country.update');
            Route::patch('/country/{id}/toggle', [CountryController::class, 'toggleStatus'])->name('location.country.toggle');
            Route::delete('/country/{id}', [CountryController::class, 'destroy'])->name('location.country.delete');
            Route::get('/state', [StateController::class, 'index'])->name('location.state');
            Route::post('/state/store', [StateController::class, 'store'])->name('location.state.store');
            Route::put('/state/{id}', [StateController::class, 'update'])->name('location.state.update');
            Route::patch('/state/{id}/toggle', [StateController::class, 'toggleStatus'])->name('location.state.toggle');
            Route::delete('/state/{id}', [StateController::class, 'destroy'])->name('location.state.delete');
            Route::get('/city', [CityController::class, 'index'])->name('location.city');
            Route::post('/city/store', [CityController::class, 'store'])->name('location.city.store');
            Route::put('/city/{id}', [CityController::class, 'update'])->name('location.city.update');
            Route::patch('/city/{id}/toggle', [CityController::class, 'toggleStatus'])->name('location.city.toggle');
            Route::delete('/city/{id}', [CityController::class, 'destroy'])->name('location.city.delete');
            Route::get('/get-states/{countryId}', [CityController::class, 'getStates']);
        });

        // Settings Routes
        Route::get('/terms-conditions', [TermsConditionController::class, 'index'])->name('terms.index');
        Route::post('/terms-conditions', [TermsConditionController::class, 'store'])->name('terms.store');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy.index');
        Route::post('/privacy-policy', [PrivacyPolicyController::class, 'store'])->name('privacy.store');
        Route::get('/about-us', [AboutUsController::class, 'index'])->name('about.index');
        Route::post('/about-us', [AboutUsController::class, 'store'])->name('about.store');
        Route::get('/refund-policy', [RefundPolicyController::class, 'index'])->name('refund.index');
        Route::post('/refund-policy', [RefundPolicyController::class, 'store'])->name('refund.store');
        Route::get('/sms-setting', [SmsSettingController::class, 'index'])->name('sms.index');
        Route::post('/sms-setting', [SmsSettingController::class, 'store'])->name('sms.store');
        Route::get('/social', [SocialSettingController::class, 'index'])->name('social.index');
        Route::post('/social', [SocialSettingController::class, 'store'])->name('social.store');
        Route::get('/social/edit/{id}', [SocialSettingController::class, 'edit'])->name('social.edit');
        Route::post('/social/{id}/update', [SocialSettingController::class, 'update'])->name('social.update');
        Route::post('/social/{id}/toggle', [SocialSettingController::class, 'toggleStatus'])->name('social.toggle');
        Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact.index');
        Route::post('/contact-us', [ContactUsController::class, 'update'])->name('contact.update');
        Route::get('/other-documents', [OtherDocumentController::class, 'index'])->name('document.index');
        Route::post('/other-documents', [OtherDocumentController::class, 'store'])->name('document.store');
        Route::get('/other-documents/{id}/edit', [OtherDocumentController::class, 'edit'])->name('document.edit');
        Route::post('/other-documents/{id}/update', [OtherDocumentController::class, 'update'])->name('document.update');
        Route::post('/other-documents/{id}/toggle', [OtherDocumentController::class, 'toggleStatus'])->name('document.toggle');
        Route::get('/site-setting', [SiteSettingController::class, 'index'])->name('site.setting');
        Route::post('/site-setting', [SiteSettingController::class, 'storeOrUpdate'])->name('site.setting.update');
        Route::get('/refer-and-earn', [ReferAndEarnController::class, 'index'])->name('refer.index');
        Route::post('/refer-and-earn', [ReferAndEarnController::class, 'store'])->name('refer.store');
        Route::get('/complain-types', [ComplainTypeController::class, 'index'])->name('complain-types.index');
        Route::post('/complain-types', [ComplainTypeController::class, 'store'])->name('complain-types.store');
        Route::get('/complain-types/{id}/edit', [ComplainTypeController::class, 'edit'])->name('complain-types.edit');
        Route::post('/complain-types/{id}/update', [ComplainTypeController::class, 'update'])->name('complain-types.update');
        Route::delete('/complain-types/{id}', [ComplainTypeController::class, 'destroy'])->name('complain-types.destroy');
        Route::post('/complain-types/{id}/toggle-status', [ComplainTypeController::class, 'toggleStatus'])->name('complain-types.toggle-status');
        Route::get('/application-update', [AppUpdateController::class, 'index'])->name('app.update');
        Route::post('/application-update', [AppUpdateController::class, 'store'])->name('app.update.store');
        Route::get('/settings/gateway', [GatewaySettingController::class, 'index'])->name('gateway.index');
        Route::post('/settings/gateway', [GatewaySettingController::class, 'store'])->name('gateway.store');
        Route::get('/settings/ads', [AdsSettingController::class, 'index'])->name('ads.index');
        Route::post('/settings/ads', [AdsSettingController::class, 'store'])->name('ads.store');
        Route::get('/settings/firebase', [FirebaseSettingController::class, 'index'])->name('firebase.index');
        Route::post('/settings/firebase', [FirebaseSettingController::class, 'store'])->name('firebase.store');
        Route::get('/api/firebase-config', [FirebaseSettingController::class, 'getConfig'])->name('firebase.config');
        Route::get('/settings/firebase/config', [FirebaseSettingController::class, 'getConfig'])->name('firebase.config');
        Route::get('/settings/firebase/download/{id}', [FirebaseSettingController::class, 'downloadJson'])->name('firebase.download');
        Route::prefix('gst')->group(function () {
            Route::get('/', [GstRateController::class, 'index'])->name('gst.index');
            Route::post('/store', [GstRateController::class, 'store'])->name('gst.store');
            Route::put('/{id}', [GstRateController::class, 'update'])->name('gst.update');
            Route::patch('/{id}/toggle-status', [GstRateController::class, 'toggleStatus'])->name('gst.toggle');
            Route::delete('/{id}', [GstRateController::class, 'destroy'])->name('gst.destroy');
        });
    });
});