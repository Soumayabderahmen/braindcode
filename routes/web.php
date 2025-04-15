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
// Route pour le tableau de bord, sans authentification
Route::get('/', function () {
    return Inertia::render('Home');
})->name('dashboard');


//  Route pour les COACHS
Route::get('/dashboard/coach', function () {
    return Inertia::render('Users/DashboardCoach');
})->middleware(['auth', CheckRole::class.':coach'])->name('dashboard.coach');

//  Route pour les STARTUPS
Route::get('/dashboard/startup', function () {
    return Inertia::render('Users/DashboardStartup');
})->middleware(['auth', CheckRole::class.':startup'])->name('dashboard.startup');

//  Route pour les INVESTISSEURS
Route::get('/dashboard/investisseur', function () {
    return Inertia::render('Users/DashboardInvestisseur');
})->middleware(['auth', CheckRole::class.':investisseur'])->name('dashboard.investisseur');

// Page ContactUs (visible par tous)
Route::get('/contact', [SupportMessageController::class, 'create'])->name('contactus');
Route::post('/contact/store', [SupportMessageController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route API pour envoyer un message au chatbot
Route::post('/api/chatbot', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::middleware('auth')->get('/api/chatbot/history', [ChatbotController::class, 'getHistory']);
Route::middleware('auth')->post('/api/chatbot/history/save', [ChatbotController::class, 'saveHistory']);

// Route Faq
Route::get('/faqs', [FaqController::class, 'index'])->name('faq');
Route::get('/faqs/list', [FaqController::class, 'list']);

// Routes Admin (Support Messages)
Route::prefix('admin')->middleware(['auth', 'verified', CheckAdmin::class])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/support-messages', [SupportMessageAdminController::class, 'index'])->name('support.messages');
    Route::get('/support-messages/{id}', [SupportMessageAdminController::class, 'show'])->name('support.message.view');
    Route::delete('/support-messages/{id}', [SupportMessageAdminController::class, 'destroy'])->name('support.messages.delete');
    Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [AdminFaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');
    Route::get('/chatbot', [ChatbotAdminController::class, 'index'])->name('chatbot.index');
    Route::get('/chatbot/messages', [ChatbotAdminController::class, 'messages']);
});


require __DIR__.'/auth.php';

