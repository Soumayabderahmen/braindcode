<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;  
use App\Http\Controllers\Admin\SupportMessageAdminController;
use App\Http\Controllers\ContactUs\SupportMessageController;
use Inertia\Inertia;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Chatbot\ChatbotController;
use App\Http\Controllers\Faq\FaqController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\ChatbotAdminController;
use App\Http\Controllers\API\IntentionController;
use App\Http\Controllers\Admin\IntentionAdminController;
use App\Http\Controllers\Chatbot\ChatbotReactionController;
use App\Http\Controllers\Admin\ChatbotReactionAdminController;
use App\Http\Controllers\Admin\ChatbotSettingsController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('Home.home_front');
});
///links Home Page 
Route::get('/startup', [HomeController::class, 'startup'])->name('startup');
Route::get('/coach', [HomeController::class, 'coach'])->name('coach');
Route::get('/investisseur', [HomeController::class, 'investisseur'])->name('investisseur');
Route::get('/forum', [HomeController::class, 'forum'])->name('forum');
Route::get('/equipe', [HomeController::class, 'equipe'])->name('equipe');
Route::get('/startinc', [HomeController::class, 'startinc'])->name('startinc');
Route::get('/formation', [HomeController::class, 'formation'])->name('formation');
Route::get('/resources', [HomeController::class, 'resources'])->name('resources');
Route::get('/agentia', [HomeController::class, 'agentia'])->name('agentia');
Route::get('/agentia2', [HomeController::class, 'agentia2'])->name('agentia2');



//  Route pour les COACHS
Route::get('/dashboard/coach', function () {
    return view('Coach.dashboard');
})->middleware(['auth', CheckRole::class.':coach'])->name('dashboard.coach');

//  Route pour les STARTUPS
Route::get('/dashboard/startup', function () {
    return view('Startup.dashboard');
})->middleware(['auth', CheckRole::class.':startup'])->name('dashboard.startup');

//  Route pour les INVESTISSEURS
Route::get('/dashboard/investisseur', function () {
    return view('Investisseur.dashboard');
})->middleware(['auth', CheckRole::class.':investisseur'])->name('dashboard.investisseur');


Route::get('/intentions', [IntentionController::class, 'index']);

// Page ContactUs (visible par tous)
Route::get('/contact', [SupportMessageController::class, 'create'])->name('contactus');
Route::post('/contact/store', [SupportMessageController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Ajout : enregistre la réaction à un message du bot
});
// 💡 Accessible à tous (connecté ou non)
Route::get('/api/chatbot/history', [ChatbotController::class, 'getHistory']);
Route::post('/api/chatbot/history/save', [ChatbotController::class, 'saveHistory']);

// Route API pour envoyer un message au chatbot
Route::post('/api/chatbot', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
// Route::middleware('auth')->get('/api/chatbot/history', [ChatbotController::class, 'getHistory']);
// Route::middleware('auth')->post('/api/chatbot/history/save', [ChatbotController::class, 'saveHistory']);
Route::middleware('auth')->post('/chatbot/reaction', [ChatbotReactionController::class, 'store']);

// Route Faq
Route::get('/faqs', [FaqController::class, 'index'])->name('faq');
Route::get('/faqs/list', [FaqController::class, 'list']);

Route::get('/api/public/chatbot/settings', function () {
    return \App\Models\ChatbotSetting::first();
});

Route::get('/chatbot/history/anonymous', [ChatbotController::class, 'getAnonymousHistory']);

// Routes Admin 
Route::prefix('admin')->middleware(['auth', 'verified', CheckAdmin::class])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::put('/support-messages/{id}/status', [SupportMessageAdminController::class, 'updateStatus'])->name('support.message.status');
    Route::get('/support-messages', [SupportMessageAdminController::class, 'index'])->name('support.messages');
    Route::get('/support-messages/download/{id}', [SupportMessageAdminController::class, 'download'])->name('support.download');
    Route::get('/support-messages/{id}', [SupportMessageAdminController::class, 'show'])->name('support.message.view');
    Route::delete('/support-messages/{supportMessage}', [SupportMessageAdminController::class, 'destroy'])->name('support.messages.delete');
    Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [AdminFaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');
    Route::get('/chatbot', [ChatbotAdminController::class, 'index'])->name('chatbot.index');
    Route::get('/chatbot/messages', [ChatbotAdminController::class, 'messages']);
    Route::get('/chatbot/management', [ChatbotAdminController::class, 'management'])->name('chatbot.management');
    Route::get('/chatbot/stats', [ChatbotAdminController::class, 'stats'])->name('chatbot.stats');

    Route::get('/chatbot/reactions', [ChatbotReactionAdminController::class, 'index'])->name('chatbot.reactions');
    Route::get('/chatbot/settings', [ChatbotSettingsController::class, 'getSettings']);
    Route::post('/chatbot/settings', [ChatbotSettingsController::class, 'saveSettings']);

    // Route::get('/intentions', [IntentionAdminController::class, 'index'])->name('intentions');
    // Route::get('/intentions-list', [IntentionAdminController::class, 'list']);
    // Route::post('/intentions', [IntentionAdminController::class, 'store']);
    // Route::put('/intentions/{intention}', [IntentionAdminController::class, 'update']);
    // Route::delete('/intentions/{intention}', [IntentionAdminController::class, 'destroy']);
    

});


require __DIR__.'/auth.php';

