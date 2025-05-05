<?php

use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\AgentIA\AgentController;
use App\Http\Controllers\Coach\CoachProfileController;
use App\Http\Controllers\Investisseurs\ListInvestisseurController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Startups\ListStartupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Coach\AvailabilityController;
use App\Http\Controllers\Startups\PaiementController;
use App\Http\Controllers\Startups\ReservationController;

Route::get('/', function () {
    // Si l'utilisateur est authentifié, le rediriger vers le tableau de bord ou une autre page
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Assure-toi de créer la route 'dashboard' si nécessaire
    }

    // Si l'utilisateur n'est pas authentifié, rediriger vers la page de login
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('coach.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/activation-message', function () {
    return Inertia::render('Coach/ActivationMessage');
})->name('activation.message');
// Route::middleware('auth')->get('/notifications', function () {
//     return Auth::user()->notifications()->latest()->get();
// });

Route::middleware('auth')->patch('/notifications/{id}', function ($id) {
    Auth::user()->notifications()->findOrFail($id)->markAsRead();
    return response()->noContent();
});
Route::middleware('auth')->get('/notifications', function () {
    $user = Auth::user();

    // Récupérer toutes les notifications de type ReservationRequestNotification
    return response()->json($user->notifications()->where('type', 'App\\Notifications\\ReservationRequestNotification')->get());
});
Route::post('/reservations/{reservation}/response', [ReservationController::class, 'respond']);

Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {  
Route::get('/activate-coach/{id}', [CoachController::class, 'activateCoach'])->name('activate_coach');
Route::get('/activate-startup/{id}', [CoachController::class, 'activateStartup'])->name('activate_startup');
Route::get('/activate-investisseur/{id}', [CoachController::class, 'activateInvestisseur'])->name('activate_investisseur');
Route::get('/coaches', [CoachController::class, 'index'])->name('coaches');
Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');
Route::get('/startups', [CoachController::class, 'startup'])->name('startups');
Route::get('/investisseurs', [CoachController::class, 'investisseur'])->name('investisseurs');
Route::get('/reservations', [ReservationController::class, 'indexAdmin'])->name('reservations');

});
Route::prefix('startup')->middleware(['auth', 'verified'])->name('startup.')->group(function () {  
    Route::get('/dashboard', [ListStartupController::class, 'ListMembres'])->name('list');
    Route::get('/calendar', [ListStartupController::class, 'FullCalandryStartup'])->name('calendar');
    Route::get('/res/create', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation/add', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation-message', function () {return Inertia::render('Startups/ReservationMessage');})->name('reservation.message');
    Route::get('/reservations', [ReservationController::class, 'indexStartup'])->name('reservations');
    Route::get('/reservation/{coach}/{availability}/{date}', [ReservationController::class, 'showBookingForm'])->name('reservation.form');

});
Route::prefix('coach')->middleware(['auth', 'verified'])->name('coach.')->group(function () {  
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{id}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
    Route::put('/availabilities/{id}', [AvailabilityController::class, 'updateTimes'])->name('availability.updateTimes');
    Route::put('/availabilityStatut/{id}', [AvailabilityController::class, 'updateStatus'])->name('availability.updateStatus');  
    Route::get('/reservations', [ReservationController::class, 'indexCoach'])->name('reservations');

});

Route::prefix('agentia')->group(function () {
    Route::get('/', [AgentController::class, 'agentia'])->name('coach.agentia');
    Route::get('/details', [AgentController::class, 'detailsAgentia'])->name('coach.agentia.details');
    Route::get('/add', [AgentController::class, 'addAgentia'])->name('coach.agentia.add');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/ListStartups', [ListStartupController::class, 'List'])->name('list.startup');
    Route::get('/profile/Coach/{id}', [CoachProfileController::class, 'profile'])->name('profile.coach');
    Route::get('/ListInvestisseurs', [ListInvestisseurController::class, 'List'])->name('list.investisseurs');
    Route::get('/ListCoachs', [CoachProfileController::class, 'List'])->name('list.coach');
    Route::get('/profile/Investisseur/{id}', [ListInvestisseurController::class, 'profile'])->name('profile.investisseur');
    Route::get('/profile/startup/{id}', [ListStartupController::class, 'profile'])->name('profile.startup');
   
    // Page de paiement
    Route::get('/paiement/{reservation}', [PaiementController::class, 'show'])->name('paiement.show');
    
    // API de création du paiement Stripe
    Route::post('/paiement/create-intent', [PaiementController::class, 'createStripeIntent'])->name('paiement.intent');
    
    // Route::put('/coach/availabilityStatut/{id}', [AvailabilityController::class, 'updateStatus'])->name('coach.availability.updateStatus');
    Route::get('/calendar', [AvailabilityController::class, 'FullCalandry'])->name('coach.calendar');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.complete'); // Sauvegarder les modifications

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
