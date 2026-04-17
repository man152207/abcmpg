<?php


use App\Http\Controllers\AdAccountController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardCreditController;
use App\Http\Controllers\CardDebitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OtherExpController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPrivilegeController;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DutyScheduleController;
use App\Http\Controllers\OtherIncomeController;
use App\Http\Controllers\UsdIncomeController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\CampaignLinkController;
use App\Http\Controllers\AdAccountManagementController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\DailyCardSpendController;
use App\Http\Controllers\FacebookAdInsightController;
use App\Http\Controllers\InvoicelistsController ;
use App\Http\Controllers\Admin\InternalChatController;
use App\Http\Controllers\Admin\TwoFAController;
use App\Http\Controllers\Admin\MultimediaController;
use App\Http\Controllers\InvoiceBillsController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\Reception\RecpDashboardController;
use App\Http\Controllers\Reception\RecpStudentController;
use App\Http\Controllers\Reception\RecpEnrollmentController;
use App\Http\Controllers\Reception\RecpPaymentController;
use App\Http\Controllers\Reception\RecpDocumentController;
use App\Http\Controllers\BoostingTaskController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\Reception\RecpReportController;
use App\Http\Controllers\FacebookWebhookController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\Admin\BonusSeasonController;
use App\Http\Controllers\Admin\BonusClaimController;
use App\Http\Controllers\Admin\Smmx\SmmxOnboardingController;
use App\Http\Controllers\Admin\Smmx\SmmxDeliverableController;
use App\Http\Controllers\Admin\Smmx\SmmxReportController;
use App\Http\Controllers\Admin\Smmx\SmmxCustomerPanelController;
use App\Http\Controllers\Admin\Smmx\SmmxCalendarPlannerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//user_login_register routes
// Route::get('/register', [UserController::class, 'register_form']);
// Route::get('/login', [UserController::class, 'login_form'])->name('login_form');
// Route::post('/login', [UserController::class, 'login'])->name('login');
// Route::post('/register', [UserController::class, 'register'])->name('register');

//end here
Route::prefix('admin')->group(function () {
    Route::get('/link-store-room/{customer_id?}', [CampaignLinkController::class, 'linkStoreRoom'])->name('admin.link.store.room');
    Route::post('/link/store', [CampaignLinkController::class, 'store'])->name('admin.link.store');
    Route::put('/link/{id}', [CampaignLinkController::class, 'update'])->name('admin.link.update');
    Route::delete('/link/bulk-delete', [CampaignLinkController::class, 'bulkDelete'])->name('admin.link.bulkDelete');
    Route::delete('/link/{id}', [CampaignLinkController::class, 'destroy'])->name('admin.link.destroy');
    Route::get('/customer/{customer_id}/links', [CampaignLinkController::class, 'fetchLinks'])->name('admin.customer.links');
    Route::get('/customer/getRate', [CustomerController::class, 'getCustomerRate']);
    Route::get('/dashboard/ads_list', [AdController::class, 'showAds'])->name('admin.ads.list');

    // Internal Chat Routes
    Route::get('/internal-chat', [InternalChatController::class, 'index'])->name('admin.internal_chat');
    Route::post('/internal-chat', [InternalChatController::class, 'store'])->name('admin.internal_chat.store');
    Route::get('/internal-chat/{id}/edit', [InternalChatController::class, 'edit'])->name('admin.internal_chat.edit');
    Route::put('/internal-chat/{id}', [InternalChatController::class, 'update'])->name('admin.internal_chat.update');
    Route::delete('/internal-chat/{id}', [InternalChatController::class, 'destroy'])->name('admin.internal_chat.delete');
    Route::post('/internal-chat/reaction', [InternalChatController::class, 'addReaction'])->name('admin.internal_chat.addReaction');
    Route::get('/internal-chat/search', [InternalChatController::class, 'search'])->name('admin.internal_chat.search');
    Route::get('/internal-chat/filter', [InternalChatController::class, 'filter'])->name('admin.internal_chat.filter');
    Route::get('/internal-chat/load-more', [InternalChatController::class, 'loadMore'])->name('admin.internal_chat.loadMore');
    Route::get('/customers/search', [InternalChatController::class, 'searchCustomers'])->name('admin.customers.search');
    Route::get('/admin/chat/internal', [InternalChatController::class, 'index'])->name('admin.chat.internal');
    Route::get('/internal-chat/{id}', [InternalChatController::class, 'show'])->name('admin.internal_chat.show');

    //Prompts Routes
    Route::get('/prompts', [PromptController::class, 'index'])->name('admin.prompts.index');

    // JSON APIs (AJAX)
    Route::get('/prompts/list', [PromptController::class, 'list'])->name('admin.prompts.list');
    Route::post('/prompts', [PromptController::class, 'store'])->name('admin.prompts.store');
    Route::put('/prompts/{id}', [PromptController::class, 'update'])->name('admin.prompts.update');
    Route::delete('/prompts/{id}', [PromptController::class, 'destroy'])->name('admin.prompts.destroy');

    Route::post('/prompts/{id}/toggle-fav', [PromptController::class, 'toggleFav'])->name('admin.prompts.toggleFav');
    Route::post('/prompts/{id}/duplicate', [PromptController::class, 'duplicate'])->name('admin.prompts.duplicate');
    Route::get('/customers/quick-search', [\App\Http\Controllers\CustomerController::class, 'quickSearch'])
    ->name('admin.customers.quickSearch'); // (match this to your JS route call)
    //ends here
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/packages/json', function () {
        return \App\Models\Package::select('id','name','code','price','is_popular','active','synced_at')
            ->orderBy('is_popular','DESC')
            ->orderBy('price','ASC')
            ->get();
    })->name('admin.packages.json');

    // Customer ↔ Package (list / assign / update / remove)
    Route::get   ('/admin/customers/{customer}/packages',                     [CustomerPackageController::class, 'list'])->name('admin.customers.packages.list');
    Route::post  ('/admin/customers/{customer}/assign-package',               [CustomerPackageController::class, 'assign'])->name('admin.customers.packages.assign');
    Route::put   ('/admin/customers/{customer}/packages/{package}',           [CustomerPackageController::class, 'update'])->name('admin.customers.packages.update');
    Route::delete('/admin/customers/{customer}/packages/{package}',           [CustomerPackageController::class, 'destroy'])->name('admin.customers.packages.remove');
});

    
     // Portal Authentication Routes
    Route::get('portal/login', [CustomerAuthController::class, 'showLoginForm'])->name('portal.login');
    Route::post('portal/login', [CustomerAuthController::class, 'login'])->name('portal.login.post');
    Route::post('portal/logout', [CustomerAuthController::class, 'logout'])->name('portal.logout');
    
    Route::middleware(['impersonate'])->group(function () {
        Route::get('portal/dashboard', [CustomerDashboardController::class, 'index'])->name('portal.dashboard');
        Route::get('/customer/dashboard/{offset?}', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
        Route::get('/customer/dashboard-data/{offset}', [CustomerDashboardController::class, 'fetchTableData'])->name('customer.dashboard.data');
        Route::get('/portal/invoicelists', [InvoicelistsController::class, 'index'])->name('portal.invoices');
        Route::get('/portal/ads-insights', [\App\Http\Controllers\AdsInsightsController::class, 'index'])->name('portal.adsinsights');
        Route::post('/portal/insights/refetch/{id}', [FacebookAdInsightController::class, 'refetchInsight'])->name('portal.insights.refetch');
        Route::post('/portal/insights/fetch/{customer}', [\App\Http\Controllers\AdsInsightsController::class, 'fetchInsights'])->name('portal.insights.fetchFromApi');
        Route::delete('/portal/insights/delete/{id}', [\App\Http\Controllers\AdsInsightsController::class, 'deletePortalInsight'])->name('portal.insights.delete');

    });
    
    Route::prefix('admin/boosting')->middleware('auth:admin')->group(function () {
    Route::get('/', [BoostingTaskController::class, 'index'])->name('boosting.index');
    Route::post('/store', [BoostingTaskController::class, 'store'])->name('boosting.store');
    Route::post('/assign/{id}', [BoostingTaskController::class, 'assign'])->name('boosting.assign');
    Route::post('/complete/{id}', [BoostingTaskController::class, 'complete'])->name('boosting.complete');
    Route::delete('/delete/{id}', [BoostingTaskController::class, 'destroy'])->name('boosting.destroy');
    Route::get('/search-user', [UserController::class, 'search'])->name('search_user');
});

    Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('multimedia', [MultimediaController::class, 'index'])->name('multimedia.index');   // List + Page
    Route::get('multimedia/{id}', [MultimediaController::class, 'show'])->name('multimedia.show'); // Load for edit (AJAX)
    Route::post('multimedia/save', [MultimediaController::class, 'save'])->name('multimedia.save'); // Create + Update
    Route::delete('multimedia/{id}', [MultimediaController::class, 'destroy'])->name('multimedia.destroy'); // Delete
    Route::get('/customers/minimal', [CustomerController::class, 'minimal'])->name('customers.minimal');
    Route::get('/customers/lookup-by-phone', [CustomerController::class, 'lookupByPhone'])->name('customers.lookupByPhone');

    Route::resource('daily-logs', \App\Http\Controllers\Admin\DailyLogController::class)
         ->only(['index','create','store','edit','update','destroy','show']); // show enabled
});

    Route::match(['GET', 'POST'], '/facebook/webhook', [FacebookWebhookController::class, 'handle']);
    Route::post('/admin/multimedia/get-customer', [App\Http\Controllers\Admin\MultimediaController::class, 'getCustomer'])->name('admin.multimedia.get-customer');
    
    Route::middleware('auth:customer')->group(function () {
    Route::get('/portal/profile-settings', [CustomerDashboardController::class, 'showProfileSettings'])->name('customer.profileSettings');
    Route::put('/portal/profile-settings', [CustomerDashboardController::class, 'updateProfile'])->name('customer.updateProfile');
});

    Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/customer/requirements/{requirementId}', [CustomerController::class, 'showRequirement'])
        ->name('customer.requirements.show');
    Route::get('/admin/customer/impersonate/{id}', [CustomerDashboardController::class, 'impersonate'])
        ->name('admin.customer.impersonate');
    Route::get('/admin/impersonation/stop', [CustomerDashboardController::class, 'stopImpersonation'])
        ->name('admin.impersonation.stop');
    Route::patch('/admin/customers/{id}/requires-bill', [CustomerController::class, 'toggleRequiresBill'])
        ->name('admin.customers.requires_bill');
    Route::get('/admin/invoices/pending-bills', [InvoiceBillsController::class, 'index'])
        ->name('invoice.pendingBills');
    Route::patch('/admin/invoice/ads/{ad}/billing-status',
    [InvoiceBillsController::class, 'updateBillingStatus']
    )->name('invoice.updateBillingStatus');
    Route::get('/admin/packages', [\App\Http\Controllers\Admin\PackageController::class, 'index'])->name('admin.packages.index');
    Route::post('/admin/packages/sync-now', function () { \Artisan::call('mpg:sync-packages'); return back()->with('status', 'Packages synced!'); })
    ->middleware(['auth:admin']);

    Route::post('/admin/customer/{id}/toggle-requires-bill', [CustomerController::class, 'toggleRequiresBill'])
        ->name('admin.customer.toggleRequiresBill');
    Route::post('/activity/ping', [ActivityController::class, 'ping'])->name('activity.ping');
    Route::get('/admin/dashboard/user/check-status/{id}', [ActivityController::class, 'checkStatus'])->name('admin.user.checkstatus');
    Route::post('/admin/dashboard/user/update-location', [ActivityController::class, 'updateLocation'])->name('admin.user.updatelocation');
    Route::get('/admin/dashboard/user/activity/{id}', [ActivityController::class, 'getUserActivity'])
        ->name('admin.user.activity');


    Route::get('/customer/{id}/requirements', [CustomerController::class, 'getRequirements'])->name('customer.requirements');
    Route::post('/customer/{id}/requirements', [CustomerController::class, 'storeRequirement'])->name('customer.requirements.store');
    Route::get('/customer/requirement-detail/{id}', [CustomerController::class, 'showRequirementDetail'])->name('customer.requirement.detail');
    Route::delete('/customer/requirements/{requirementId}', [CustomerController::class, 'deleteRequirement'])->name('customer.requirements.delete');

});

    Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/{customer}/requirements', [CustomerController::class, 'getRequirements'])->name('customer.portal.requirements');
    Route::put('/customer/requirements/{requirementId}', [CustomerController::class, 'updateRequirement'])->name('customer.requirements.update');
});
    
    Route::get('/quotation/generate', [QuotationController::class, 'create'])->name('quotation.generate');
    Route::post('/quotation/store', [QuotationController::class, 'store'])->name('quotation.store');
    Route::get('/quotation/{id}/edit', [QuotationController::class, 'edit'])->name('quotation.edit');
    Route::put('/quotation/{id}', [QuotationController::class, 'update'])->name('quotation.update');
    Route::get('/quotation/{id}/pdf', [QuotationController::class, 'download'])->name('quotation.pdf');
    Route::get('/quotation/{id}/view', [QuotationController::class, 'view'])->name('quotation.view');
    Route::delete('/quotation/{id}', [QuotationController::class, 'destroy'])->name('quotation.destroy');
    Route::post('/calculate-estimated-results', [QuotationController::class, 'calculateEstimatedResults'])->name('quotation.calculate');

//User auth routes
    Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');
    });
    //end here


// Reception Routes
Route::prefix('admin/recp')
  ->middleware(['auth:admin','department:Reception'])
  ->name('recp.')
  ->group(function () {
    Route::get('/', [RecpDashboardController::class,'index'])->name('dashboard');

    // Students
    Route::get('students', [RecpStudentController::class,'list'])->name('students.list');
    Route::get('students/{student}', [RecpStudentController::class,'show'])->name('students.show');
    Route::get('students/create', [RecpStudentController::class,'create'])->name('students.create');
    Route::post('students', [RecpStudentController::class,'store'])->name('students.store');
    Route::get('students/{student}/edit', [RecpStudentController::class,'edit'])->name('students.edit');
    Route::put('students/{student}', [RecpStudentController::class,'update'])->name('students.update');
    Route::delete('students/{student}', [RecpStudentController::class,'destroy'])->name('students.destroy');

    // Enrollments
    Route::get('enrollments', [RecpEnrollmentController::class,'list'])->name('enroll.list');
    Route::get('students/{student}/enroll', [RecpEnrollmentController::class,'create'])->name('enroll.create');
    Route::post('students/{student}/enroll', [RecpEnrollmentController::class,'store'])->name('enroll.store');
    Route::get('enrollments/{enrollment}/edit', [RecpEnrollmentController::class,'edit'])->name('enroll.edit');
    Route::put('enrollments/{enrollment}', [RecpEnrollmentController::class,'update'])->name('enroll.update');
    Route::delete('enrollments/{enrollment}', [RecpEnrollmentController::class,'destroy'])->name('enroll.destroy');

    // Payments
    Route::get('payments', [RecpPaymentController::class,'list'])->name('payment.list');
    Route::get('enrollments/{enrollment}/pay', [RecpPaymentController::class,'create'])->name('payment.create');
    Route::post('enrollments/{enrollment}/pay', [RecpPaymentController::class,'store'])->name('payment.store');
    Route::get('payments/{payment}/edit', [RecpPaymentController::class,'edit'])->name('payment.edit');
    Route::put('payments/{payment}', [RecpPaymentController::class,'update'])->name('payment.update');
    Route::delete('payments/{payment}', [RecpPaymentController::class,'destroy'])->name('payment.destroy');
    Route::get('payments/{payment}/receipt', [RecpPaymentController::class,'receipt'])->name('payment.receipt');

    // Documents
    Route::get('documents', [RecpDocumentController::class,'list'])->name('doc.list');
    Route::get('students/{student}/documents/create', [RecpDocumentController::class,'create'])->name('doc.create');
    Route::post('students/{student}/documents', [RecpDocumentController::class,'store'])->name('doc.store');
    Route::get('documents/{document}/edit', [RecpDocumentController::class,'edit'])->name('doc.edit');
    Route::put('documents/{document}', [RecpDocumentController::class,'update'])->name('doc.update');
    Route::delete('documents/{document}', [RecpDocumentController::class,'destroy'])->name('doc.destroy');
    Route::get('documents/{document}/receipt', [RecpDocumentController::class,'receipt'])->name('doc.receipt');

    // Reports
    Route::get('reports/dues', [RecpReportController::class,'dues'])->name('report.dues');
    Route::get('reports/payments-summary', [RecpReportController::class,'paymentsSummary'])->name('report.payments.summary');
    Route::get('reports/export-dues', [RecpReportController::class,'exportDues'])->name('report.export.dues');
  });
    //end here

    //ad management routes
    Route::get('/happy-birthday', function () {
    return view('admin.HappyBirthday');
});

    Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/2fa', [TwoFAController::class, 'index'])->name('admin.2fa.index');
    Route::post('/2fa', [TwoFAController::class, 'store'])->name('admin.2fa.store');
    Route::post('/2fa/{id}/generate', [TwoFAController::class, 'generateCode'])->name('admin.2fa.generate');
    Route::post('/2fa/{id}/reset', [TwoFAController::class, 'resetCode'])->name('admin.2fa.reset');
    Route::put('/2fa/{id}', [TwoFAController::class, 'update'])->name('admin.2fa.update');
    Route::delete('/2fa/{id}', [TwoFAController::class, 'destroy'])->name('admin.2fa.destroy');
    Route::get('/2fa/{id}/logs', [TwoFAController::class, 'showLogs'])->name('admin.2fa.logs');

    });

   // ADMIN CRM FOLLOW-UPS
    Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {

    // 🔹 Bonus Season CRUD (Admin)
    Route::get('/bonus-season', [BonusSeasonController::class, 'show'])
        ->name('bonus-season.show');
    Route::post('/bonus-season', [BonusSeasonController::class, 'store'])
        ->name('bonus-season.store');
    Route::post('/bonus-season/deactivate', [BonusSeasonController::class, 'deactivate'])
        ->name('bonus-season.deactivate');

    // 🔹 Customer ko bonus claim (Admin panel बाट trigger हुने)
    Route::post('/customers/{customer}/bonus-claim', [BonusClaimController::class, 'claim'])
        ->name('customers.bonus.claim');

    // 🔹 Admin ले claim status अपडेट गर्ने (pending → approved/rejected ... आदि)
    Route::patch('/bonus-claims/{claim}/status', [BonusClaimController::class, 'updateStatus'])
        ->name('bonus-claims.status');

    // 🔹 Admin ले "Mark Completed" गर्ने
    Route::patch('/bonus-claims/{bonusClaim}/complete', [BonusClaimController::class, 'markCompleted'])
        ->name('bonus-claims.complete');

    // 🔹 Followups (CRM)
    Route::get('/followups', [\App\Http\Controllers\Admin\FollowupController::class, 'index'])
        ->name('followups.index');
    Route::get('/followups/data', [\App\Http\Controllers\Admin\FollowupController::class, 'data'])
        ->name('followups.data');
    Route::post('/followups/contact', [\App\Http\Controllers\Admin\FollowupController::class, 'storeContact'])
        ->name('followups.contact.store');
    Route::post('/followups/followup', [\App\Http\Controllers\Admin\FollowupController::class, 'storeFollowup'])
        ->name('followups.followup.store');
    Route::post('/followups/contact/inline', [\App\Http\Controllers\Admin\FollowupController::class, 'updateInline'])
        ->name('followups.contact.inline');
    Route::post('/followups/contact/snooze', [\App\Http\Controllers\Admin\FollowupController::class, 'snooze'])
        ->name('followups.contact.snooze');
});


    //dailycardsspend
    Route::get('/daily-card-spends', [DailyCardSpendController::class, 'index'])->name('daily-card-spends.index');
    Route::post('/daily-card-spends', [DailyCardSpendController::class, 'store'])->name('daily-card-spends.store');
    Route::resource('daily-card-spends', \App\Http\Controllers\DailyCardSpendController::class);
    Route::get('daily-card-spends/view/{cardName}', [DailyCardSpendController::class, 'viewCard'])->name('daily-card-spends.view');
    Route::get('/daily-card-spends/download/{cardName}', [DailyCardSpendController::class, 'downloadCardRecords'])->name('daily-card-spends.download');
    Route::get('/daily-card-spends/{cardName}', [DailyCardSpendController::class, 'showCardRecords']);
    Route::post('daily-card-spends/clear/{cardName}', [DailyCardSpendController::class, 'clearCardTotal']);
    Route::post('daily-card-spends/undo/{cardName}', [DailyCardSpendController::class, 'undoClear']);
    Route::post('daily-card-spends/redo/{cardName}', [DailyCardSpendController::class, 'redoClear']);

    //end here
    
    // Facebook Ad Insights System
    Route::post('/insights/fetch', [FacebookAdInsightController::class, 'fetchInsights'])->name('insights.fetch');
    Route::post('/insights/fetch-from-api', [FacebookAdInsightController::class, 'fetchInsightsFromApi'])->name('insights.fetchFromApi');
    Route::get('/insights/fetch/{customerId}', [FacebookAdInsightController::class, 'showInsights'])->name('insights.show');
    Route::delete('/insights/delete/{id}', [FacebookAdInsightController::class, 'deleteInsight'])->name('insights.delete');
    Route::post('/insights/refetch/{id}', [FacebookAdInsightController::class, 'refetchInsight'])->name('insights.refetch');
    Route::put('/insights/update/{id}', [FacebookAdInsightController::class, 'updateCampaignName'])->name('insights.updateCampaignName');
    Route::put('/insights/update/adset/{id}', [FacebookAdInsightController::class, 'updateAdSetName'])->name('insights.updateAdSetName');
    Route::put('/insights/update/ad/{id}', [FacebookAdInsightController::class, 'updateAdName'])->name('insights.updateAdName');
    //end here

    
    // Group routes under a prefix for better organization
    Route::prefix('ad-management')->group(function () {
    // Ad Account-related routes
    Route::get('/', [AdAccountManagementController::class, 'index'])->name('adAccount.index'); // View Ad Account list
    Route::post('/adaccount/store', [AdAccountManagementController::class, 'storeAdAccount'])->name('adAccount.store'); // Add a new Ad Account
    Route::put('/adaccount/{id}', [AdAccountManagementController::class, 'updateAdAccount'])->name('adAccount.update'); // Update Ad Account details
    Route::delete('/adaccount/{id}', [AdAccountManagementController::class, 'deleteAdAccount'])->name('adAccount.delete'); // Delete Ad Account
    Route::post('/adaccount/bulk-delete', [AdAccountManagementController::class, 'bulkDelete'])->name('adAccount.bulkDelete'); // Bulk delete Ad Accounts
    Route::put('/ad-management/adaccount/{id}', [AdAccountManagementController::class, 'updateAdAccount']);
    Route::get('/ad-management/adaccount', [AdAccountManagementController::class, 'index'])->name('adaccount.index');
    Route::post('/ad-management/adaccount/grouped/store', [AdAccountManagementController::class, 'storeGroupedAdAccount'])->name('adaccount.grouped.store');
    Route::delete('/ad-management/adaccount/grouped/{id}', [AdAccountManagementController::class, 'deleteGroupedAdAccount'])->name('adaccount.grouped.delete');
    Route::post('/adaccount/group/store', [AdAccountManagementController::class, 'storeGroup'])->name('adaccount.group.store');
    Route::post('/adaccount/grouped/store', [AdAccountManagementController::class, 'storeGroupedAdAccount'])->name('adaccount.grouped.store.alternate');

    });
    //end here


    //user_login_register routes
    Route::get('admin/register', [AdminController::class, 'register_form']);
    Route::get('/', [AdminController::class, 'login_form'])->name('admin.login_form');
    Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('admin/register', [AdminController::class, 'register'])->name('admin.register');

    // Admin password reset routes (guest/unauthenticated only)
    Route::get('admin/forgot-password', [\App\Http\Controllers\Auth\AdminPasswordResetController::class, 'showForgotForm'])->name('admin.password.request');
    Route::post('admin/forgot-password', [\App\Http\Controllers\Auth\AdminPasswordResetController::class, 'sendResetLink'])->name('admin.password.email');
    Route::get('admin/reset-password/{token}', [\App\Http\Controllers\Auth\AdminPasswordResetController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('admin/reset-password', [\App\Http\Controllers\Auth\AdminPasswordResetController::class, 'resetPassword'])->name('admin.password.update');
    Route::get('/api/weather', [WeatherController::class, 'show'])->name('api.weather');
    Route::get('/api/weather-forecast', [WeatherController::class, 'forecast'])->name('api.weather-forecast');

//end here
    //add-on routes
    Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/get-customer-addons/{whatsapp}', [AdController::class, 'getCustomerAddons'])
         ->name('admin.getCustomerAddons');
    Route::get('/admin/addons/by-customer', [AddonController::class,'byCustomer']);
    Route::post('/admin/addons/attach',     [AddonController::class,'attach']);

});
    //ends
//admin auth routes
Route::middleware('admin')->group(function () {
Route::get('/admin/dashboard/export_customers', [CustomerController::class, 'exportCustomers'])->name('export_customers');

    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [AdController::class, 'summarydashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/user/details/{id}', [AdminController::class, 'showUserDetails'])->name('admin.user.details');

    Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    
    // SMMX - Social Media Management Module
    Route::prefix('admin/smmx')->name('admin.smmx.')->middleware(['auth:admin'])->group(function () {
    Route::get('/customers', [SmmxCustomerPanelController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [SmmxCustomerPanelController::class, 'show'])->name('customers.show');
    Route::post('/customers/{customer}/worklog', [SmmxCustomerPanelController::class, 'storeWorkLog'])->name('customers.worklog.store');
    Route::delete('/customers/{customer}/worklog/{id}', [SmmxCustomerPanelController::class, 'deleteWorkLog'])->name('customers.worklog.delete');
    //Calender 
    
    Route::get('/calendar', [SmmxCalendarPlannerController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/create', [SmmxCalendarPlannerController::class, 'create'])->name('calendar.create');
    Route::post('/calendar', [SmmxCalendarPlannerController::class, 'store'])->name('calendar.store');
    Route::get('/calendar/{id}/edit', [SmmxCalendarPlannerController::class, 'edit'])->name('calendar.edit');
    Route::put('/calendar/{id}', [SmmxCalendarPlannerController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{id}', [SmmxCalendarPlannerController::class, 'destroy'])->name('calendar.destroy');

    Route::get('/calendar-generate', [SmmxCalendarPlannerController::class, 'generateForm'])->name('calendar.generate.form');
    Route::post('/calendar-generate', [SmmxCalendarPlannerController::class, 'generateDraft'])->name('calendar.generate');

    Route::post('/calendar-copy-previous', [SmmxCalendarPlannerController::class, 'copyPreviousMonth'])->name('calendar.copy.previous');

    // Onboarding
    Route::get('/onboarding', [SmmxOnboardingController::class, 'index'])->name('onboarding.index');
    Route::get('/onboarding/create', [SmmxOnboardingController::class, 'create'])->name('onboarding.create');
    Route::post('/onboarding', [SmmxOnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding/{id}', [SmmxOnboardingController::class, 'show'])->name('onboarding.show');
    Route::get('/onboarding/{id}/edit', [SmmxOnboardingController::class, 'edit'])->name('onboarding.edit');
    Route::put('/onboarding/{id}', [SmmxOnboardingController::class, 'update'])->name('onboarding.update');
    Route::delete('/onboarding/{id}', [SmmxOnboardingController::class, 'destroy'])->name('onboarding.destroy');

    // Deliverables
    Route::get('/deliverables', [SmmxDeliverableController::class, 'index'])->name('deliverables.index');
    Route::get('/deliverables/create', [SmmxDeliverableController::class, 'create'])->name('deliverables.create');
    Route::post('/deliverables', [SmmxDeliverableController::class, 'store'])->name('deliverables.store');
    Route::get('/deliverables/{id}', [SmmxDeliverableController::class, 'show'])->name('deliverables.show');
    Route::get('/deliverables/{id}/edit', [SmmxDeliverableController::class, 'edit'])->name('deliverables.edit');
    Route::put('/deliverables/{id}', [SmmxDeliverableController::class, 'update'])->name('deliverables.update');
    Route::delete('/deliverables/{id}', [SmmxDeliverableController::class, 'destroy'])->name('deliverables.destroy');

    // Reports
    Route::get('/reports', [SmmxReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [SmmxReportController::class, 'show'])->name('reports.show');
    });
    
    // Ad routes
    Route::post('/admin/dashboard/ads', [AdController::class, 'storeAd'])->name('storeAd');
    Route::get('/admin/dashboard/ads/add', [AdController::class, 'ad_form'])->name('ad_form');
    Route::get('/admin/dashboard/ads_list', [AdController::class, 'showAds'])->name('ads.showAllAds');
    Route::get('/ads/show', [AdController::class, 'showAds'])->name('ads.show');
    Route::get('/admin/dashboard/ads_complete_list', [AdController::class, 'showCompleteAds'])->name('ads_complete.show');
    Route::put('/admin/dashboard/ad/{id}', [AdController::class, 'update'])->name('ads.updatePut');
    Route::post('/admin/dashboard/ads/edit/{id}', [AdController::class, 'update'])->name('ads.update');
    Route::get('/admin/dashboard/ads/edit/{id}', [AdController::class, 'edit'])->name('ads.editOld');
    Route::get('/admin/dashboard/ads/delete/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::get('/admin/dashboard/ads/search', [AdController::class, 'search'])->name('search_ad');
    Route::get('/admin/dashboard/ads_complete/search', [AdController::class, 'search_ad_complete'])->name('search_complete_ad');
    Route::get('/admin/dashboard/ads/summary', [AdController::class, 'summary'])->name('ads.summary');
    Route::get('/admin/dashboard/ads/this_day', [AdController::class, 'thisDay'])->name('ads.this_day');
    Route::get('/admin/dashboard/ads/yesterday', [AdController::class, 'yesterday'])->name('ads.yesterday');
    Route::get('/admin/dashboard/ads/this_week', [AdController::class, 'thisWeek'])->name('ads.this_week');
    Route::get('/admin/dashboard/ads/this_month', [AdController::class, 'thisMonth'])->name('ads.this_month');
    Route::get('/admin/dashboard/ads/this_day_complete', [AdController::class, 'thisDay_complete'])->name('ads.this_day_complete');
    Route::get('/admin/dashboard/ads/yesterday_complete', [AdController::class, 'yesterday_complete'])->name('ads.yesterday_complete');
    Route::get('/admin/dashboard/ads/this_week_complete', [AdController::class, 'thisWeek_complete'])->name('ads.this_week_complete');
    Route::get('/admin/dashboard/ads/this_month_complete', [AdController::class, 'thisMonth_complete'])->name('ads.this_month_complete');
    Route::get('/admin/ads_summary/{monthYear}', [AdController::class, 'monthlyDetails'])->name('admin.ads_summary.details');
    Route::get('/admin/dashboard/filter/{status}', [AdController::class, 'filterByStatus'])->name('ads.filterByStatus');
    Route::get('/admin/dashboard/ad/{id}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::get('/admin/dashboard/ad/{id}/email', [AdController::class, 'email_to_send'])->name('ads.email_to_send');
    Route::get('/admin/dashboard/ad/monthly/{monthYear}', [AdController::class, 'monthlyDetails'])->name('ads.monthlyDetails');
    Route::get('/admin/ads-list', [AdController::class, 'adsList'])->name('ads.list');
    Route::get('/ads/filterByCalculatedStatus/{status}', [AdController::class, 'filterByCalculatedStatus'])->name('ads.filterByCalculatedStatus')->middleware('auth:admin');
    Route::get('/admin/dashboard/monitoring', [AdController::class, 'filterByMonitoringStatus'])->name('ads.filterByMonitoringStatus');
    Route::get('/dashboard/filter', [AdController::class, 'filterDashboardData'])->name('dashboard.filter');
    Route::get('/calculate-arab', [AdController::class, 'calculateARAB'])->name('calculate.arab');
    Route::get('/calculate-daily-spend', [AdController::class, 'calculateDailySpend'])->name('calculate.dailySpend');
    Route::get('/calculate-active-ads', [AdController::class, 'calculateActiveAds'])->name('calculate.activeAds');

    // web.php
    Route::get('/admin/dashboard/ads/volume/{range}', [AdController::class,'filterByVolume'])
          ->name('ads.filterByVolume');

    //end here

    // in routes/web.php (inside admin auth group where you already added):
    Route::get('/duty-schedule', [DutyScheduleController::class, 'index'])->name('duty_schedule.index');
    Route::post('/duty-schedule/save-month', [DutyScheduleController::class, 'saveMonth'])->name('duty_schedule.saveMonth');
    Route::post('/duty-schedule/generate-window', [DutyScheduleController::class, 'generateWindow'])->name('duty_schedule.generateWindow');
    Route::post('/duty-schedule/update-day', [DutyScheduleController::class, 'updateDay'])->name('duty_schedule.updateDay');
    
    // NEW: delete entire schedule
    Route::post('/duty-schedule/delete-all', [DutyScheduleController::class, 'deleteAll'])->name('duty_schedule.deleteAll');

    //end here
    
    //income here
    Route::resource('other_income', OtherIncomeController::class);
    Route::get('/other_income', [OtherIncomeController::class, 'index'])->name('other_income.index');
    Route::post('/other_income', [OtherIncomeController::class, 'store'])->name('other_income.store');
    Route::resource('usd_incomes', UsdIncomeController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/other_income/month/{year_month}', [OtherIncomeController::class, 'loadMonthData']);
    //end here
    
    //customer routes
    Route::get('/customer/details/{id}', [CustomerController::class, 'showDetails'])->name('customer.details');
    Route::get('/admin/dashboard/customer/add', [CustomerController::class, 'add_form'])->name('customer.add');
    Route::post('/admin/dashboard/customer/add', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/admin/dashboard/customer_list', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/admin/dashboard/customer/edit/{id}', [CustomerController::class, 'update_form'])->name('customer.edit');
    Route::post('/admin/dashboard/customer/edit/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/admin/dashboard/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.destroy');
    Route::get('/admin/dashboard/customer/search', [CustomerController::class, 'search'])->name('search_customer');
    Route::get('/admin/dashboard/customer/details/{id}', [CustomerController::class, 'showDetails'])->name('customer.details');
    Route::get('/admin/dashboard/customer/details/{id}', [CustomerController::class, 'showDetails']);
    Route::get('/admin/dashboard/customer/details/{id}/{startMonthOffset?}', [CustomerController::class, 'showDetails']);
    Route::post('/admin/dashboard/customer/filter', [CustomerController::class, 'filterByDateRange']);
    Route::post('/admin/dashboard/customer/edit/{id}', [CustomerController::class, 'update']);
    Route::post('/admin/dashboard/customer/financial-year', [CustomerController::class, 'getFinancialYearData']);
    Route::get('/admin/dashboard/customer/{id}/receipts/download', [CustomerController::class, 'downloadAllReceipts'])->name('customer.receipts.download');

    //ends here
    
    //Item routes
    Route::get('/admin/dashboard/item/add', [ItemController::class, 'add_form'])->name('item.add');
    Route::post('/admin/dashboard/item/add', [ItemController::class, 'store'])->name('item.store');
    Route::get('/admin/dashboard/item_list', [ItemController::class, 'show'])->name('item.show');
    Route::get('/admin/dashboard/item/edit/{id}', [ItemController::class, 'update_form'])->name('item.edit');
    Route::post('/admin/dashboard/item/edit/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::get('/admin/dashboard/item/delete/{id}', [ItemController::class, 'delete'])->name('item.destroy');
    Route::get('/admin/dashboard/item/search', [ItemController::class, 'search'])->name('search_item');
    //ends here


    //client routes
    Route::get('/admin/dashboard/client/add', [ClientController::class, 'add_form'])->name('client.add');
    Route::post('/admin/dashboard/client/add', [ClientController::class, 'store'])->name('client.store');
    Route::get('/admin/dashboard/client_list', [ClientController::class, 'show'])->name('client.show');
    Route::get('/admin/dashboard/client/edit/{id}', [ClientController::class, 'update_form'])->name('client.edit');
    Route::post('/admin/dashboard/client/update/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::get('/admin/dashboard/client/delete/{id}', [ClientController::class, 'delete'])->name('client.destroy');
    Route::get('/admin/dashboard/client/search', [ClientController::class, 'search'])->name('search_client');
    Route::get('/admin/dashboard/client/summary', [ClientController::class, 'summary'])->name('client_summary');
    Route::get('/admin/dashboard/clients/this_week', [ClientController::class, 'thisWeek'])->name('client.this_week');
    Route::get('/admin/dashboard/clients/this_month', [ClientController::class, 'thisMonth'])->name('client.this_month');
    Route::get('/admin/dashboard/clients/this_day', [ClientController::class, 'thisDay'])->name('client.this_day');
    Route::get('/admin/dashboard/clients/yesterday', [ClientController::class, 'yesterday'])->name('client.yesterday');
    Route::get('/admin/dashboard/client/details/{name}', [ClientController::class, 'showDetailsByName'])->name('client.detailsByName');
    //ends here

    //admin profile routes
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    //end here

    //CustomerExport routes
    Route::get('/export-customers', [CustomerController::class, 'exportToExcel']);
    Route::get('/total-customers', [CustomerController::class, 'getTotalCustomerCount']);
    Route::get('/admin/dashboard/customer_list/sort', [CustomerController::class, 'sortCustomers'])->name('sort_customers');


    //Ad account routes
    Route::get('/admin/dashboard/ad_account/add', [AdAccountController::class, 'add_form'])->name('ad_account.add');
    Route::post('/admin/dashboard/ad_account/add', [AdAccountController::class, 'store'])->name('ad_account.store');
    Route::get('/admin/dashboard/ad_accounts', [AdAccountController::class, 'show'])->name('ad_account.show');
    Route::get('/admin/dashboard/ad_account/edit/{id}', [AdAccountController::class, 'update_form'])->name('ad_account.edit');
    Route::post('/admin/dashboard/ad_account/edit/{id}', [AdAccountController::class, 'update'])->name('ad_account.update');
    Route::get('/admin/dashboard/ad_account/delete/{id}', [AdAccountController::class, 'delete'])->name('ad_account.destroy');
    Route::post('/admin/dashboard/ad_account/search', [AdAccountController::class, 'search'])->name('search_ad_account');
    //end here

    //invoice route here
    Route::get('/admin/dashboard/invoice/add_form', [InvoiceController::class, 'showForm'])->name('invoice.add');
    Route::post('/admin/dashboard/invoice/add', [InvoiceController::class, 'saveInvoice'])->name('invoice.store');
    Route::get('/admin/dashboard/invoice/list', [InvoiceController::class, 'list'])->name('invoice.list');
    Route::get('/admin/dashboard/invoice/update/{id}', [InvoiceController::class, 'update_form'])->name('invoice.edit');
    Route::post('/admin/dashboard/invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::post('/admin/dashboard/invoice/delete/{id}', [InvoiceController::class, 'delete'])->name('invoice.delete');
    //ends here

    //card routes
    Route::get('/admin/dashboard/card/add', [CardController::class, 'add_form'])->name('card.add');
    Route::post('/admin/dashboard/card/suspend/{id}', [CardController::class, 'suspend'])->name('card.suspend');
    Route::post('/admin/dashboard/card/reactivate/{id}', [CardController::class, 'reactivate'])->name('card.reactivate');
    Route::post('/admin/dashboard/card/add', [CardController::class, 'store'])->name('card.store');
    Route::get('/admin/dashboard/card_list', [CardController::class, 'show'])->name('card.show');
    Route::get('/admin/dashboard/card/edit/{id}', [CardController::class, 'update_form'])->name('card.edit');
    Route::post('/admin/dashboard/card/edit/{id}', [CardController::class, 'update'])->name('card.update');
    Route::get('/admin/dashboard/card/delete/{id}', [CardController::class, 'delete'])->name('card.destroy');
    Route::get('/admin/dashboard/card/search', [CardController::class, 'search'])->name('search_card');
    Route::get('/admin/dashboard/card/summary', [CardController::class, 'summary'])->name('card.summary');
    Route::get('/admin/dashboard/card_cre_deb/', [CardController::class, 'all_in_one'])->name('all_in_one');
    Route::get('/card/details/{id}', [CardController::class, 'details'])->name('card.details');
    //ends here

    //credit route
    Route::get('/admin/dashboard/credit/credit_form', [CardCreditController::class, 'credit_form'])->name('credit.add');
    Route::post('/admin/dashboard/credit/add', [CardCreditController::class, 'credit'])->name('credit.store');
    Route::get('/admin/dashboard/credit/list', [CardCreditController::class, 'show'])->name('credit.show');
    Route::get('/admin/dashboard/credit/summary', [CardCreditController::class, 'summary'])->name('credit.summary');
    Route::get('/admin/dashboard/credit/search', [CardCreditController::class, 'search'])->name('search_credit');
    Route::get('/admin/dashboard/credit/search_list', [CardCreditController::class, 'search_list'])->name('search_credit_list');
    //end here

    //debit route
    Route::get('/admin/dashboard/debit/debit_form', [CardDebitController::class, 'debit_form'])->name('debit.add');
    Route::post('/admin/dashboard/debit/add', [CardDebitController::class, 'debit'])->name('debit.store');
    Route::get('/admin/dashboard/debit/list', [CardDebitController::class, 'show'])->name('debit.show');
    Route::get('/admin/dashboard/debit/summary', [CardDebitController::class, 'summary'])->name('debit.summary');
    Route::get('/admin/dashboard/debit/search', [CardDebitController::class, 'search'])->name('search_debit');
    Route::get('/admin/dashboard/debit/search_list', [CardDebitController::class, 'search_list'])->name('search_debit_list');
    //end here


    //note route
    Route::post('/api/saveNote', [NoteController::class, 'saveNote']);
    Route::get('/api/getNote', [NoteController::class, 'getNotes']);
    Route::post('/api/save-link', [LinkController::class, 'saveLink']);
    //end here

    //user privillage route
    Route::get('/admin/dashboard/user/add', [UserPrivilegeController::class, 'register_form'])->name('admin.user.add');
    Route::post('/admin/dashboard/user/add', [UserPrivilegeController::class, 'register'])->name('admin.user.store');
    Route::get('/admin/dashboard/user/list', [UserPrivilegeController::class, 'show'])->name('admin.user.show');
    Route::get('/admin/dashboard/user/delete/{id}', [UserPrivilegeController::class, 'delete'])->name('admin.user.delete');
    Route::get('/admin/dashboard/user/search', [UserPrivilegeController::class, 'search'])->name('admin.user.search');
    Route::get('/admin/dashboard/user/edit/{id}', [UserPrivilegeController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/dashboard/user/update/{id}', [UserPrivilegeController::class, 'update'])->name('admin.user.update');
    Route::get('/admin/dashboard/user/privilege/{id}', [UserPrivilegeController::class, 'privilege'])->name('admin.user.privilege');
    Route::post('/admin/dashboard/user/privilege/{id}', [UserPrivilegeController::class, 'privilege_store'])->name('admin.user.privilege_store');
    Route::get('/admin/dashboard/user/{id}/details', [AdminController::class, 'showUserDetails']);
    
    Route::middleware(['auth:admin'])->prefix('admin/dashboard/user')->name('admin.user.')->group(function () {


    // Privileges + Departments UI & Save
    Route::get('/privilege/{id}', [UserPrivilegeController::class, 'privilege'])->name('privilege');
    Route::post('/privilege/{id}',[UserPrivilegeController::class, 'privilege_store'])->name('privilege_store');
});

    //end here

    //excel export
    Route::get('/admin/dashboard/export', [CardController::class, 'exportToExcel'])->name('excel_export');
    //end here

    //send email
    Route::get('/admin/dashboard/send_email_ajax/{adId}', [AdController::class, 'sendEmailAjax'])->name('send_email_ajax');
    Route::get('/admin/dashboard/send_email/{id}', [AdController::class, 'email_to_send'])->name('send_email');
    //end here

    // Other Expenses Routes
    Route::get('/admin/dashboard/exp/add', [OtherExpController::class, 'add_form'])->name('exp.add');
    Route::post('/admin/dashboard/exp/add', [OtherExpController::class, 'store'])->name('exp.store');
    Route::get('/admin/dashboard/exp_list', [OtherExpController::class, 'show'])->name('exp.show');
    Route::get('/admin/dashboard/exp/edit/{id}', [OtherExpController::class, 'update_form'])->name('exp.edit');
    Route::post('/admin/dashboard/exp/edit/{id}', [OtherExpController::class, 'update'])->name('exp.update');
    Route::get('/admin/dashboard/exp/delete/{id}', [OtherExpController::class, 'delete'])->name('exp.destroy');
    Route::get('/admin/dashboard/exp/search', [OtherExpController::class, 'search'])->name('search_exp');
    
    // New Route for AJAX field updates
    Route::post('/admin/dashboard/exp/update-field', [OtherExpController::class, 'updateField'])->name('exp.updateField');

    //end here

});
    Route::get('/khaja', function () { return view('khaja'); })->name('khaja');
// pdf route start here
Route::get('/receipt/show/{id}', [ReceiptController::class, 'show']);
Route::get('/receipt/pdf_gen/{id}', [ReceiptController::class, 'create_pdf']);
Route::get('/invoice/show_invoice/{id}', [ReceiptController::class, 'show_invoice'])->name('invoice.view');
Route::get('/invoice/pdf_gen_invoice/{id}', [ReceiptController::class, 'create_pdf_invoice'])->name('invoice.download');
//end here

Route::prefix('admin/us-calendar')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\UsCalendarController::class, 'index'])->name('admin.uscalendar.index');
});
Route::get('/us/timezones', [\App\Http\Controllers\Api\UsApiController::class, 'timezones']);
Route::get('/us/holidays', [\App\Http\Controllers\Api\UsApiController::class, 'holidays']);
Route::get('/us/banking', [\App\Http\Controllers\Api\UsApiController::class, 'bankStatus']);
Route::get('/us/emergency', [\App\Http\Controllers\Api\UsApiController::class, 'emergency']);

Route::get('/health', [\App\Http\Controllers\HealthController::class, 'check'])->name('health.check');


// Route::get('/admin/dashboard/customer_list_js', function () {
//     $customers = Customer::all();

//     return response()->json([
//         'data' => $customers
//     ]);
// });

