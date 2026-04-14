<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\DokterDashboardController;
use App\Http\Controllers\Dashboard\ApotekerDashboardController;
use App\Http\Controllers\Dashboard\PasienDashboardController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\ClinicQuotaController;
use App\Http\Controllers\Admin\PatientCheckinController;
use App\Http\Controllers\Admin\QueueControlController;
use App\Http\Controllers\Admin\ResetQueueController;
use App\Http\Controllers\Admin\VisitReportController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\QueueDisplayController;

use App\Http\Controllers\Admin\HealthTipController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Redirect /dashboard to role-based dashboard
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return match($role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'dokter'   => redirect()->route('dokter.dashboard'),
            'apoteker' => redirect()->route('apoteker.dashboard'),
            default    => redirect()->route('pasien.dashboard'),
        };
    })->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('dashboard/admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('clinic-quotas', ClinicQuotaController::class);
        Route::post('/clinic-quotas/{id}/reset', [ClinicQuotaController::class, 'reset'])->name('clinic-quotas.reset');
        Route::resource('patient-checkins', PatientCheckinController::class);
        Route::get('/queue-control', [QueueControlController::class, 'index'])->name('queue-control.index');
        Route::put('/queue-control/{poli_id}/action', [QueueControlController::class, 'handleAction'])->name('queue-control.update');

        // Display Antrian Routes
        Route::get('/display', [QueueDisplayController::class, 'index'])->name('display');
        Route::get('/display-pemeriksaan', [QueueDisplayController::class, 'displayPemeriksaan'])->name('display.pemeriksaan');
        Route::get('/api/display-status', [QueueDisplayController::class, 'getStatus'])->name('display.api');

        Route::resource('reset-queues', ResetQueueController::class);
        Route::resource('visit-reports', VisitReportController::class);
        Route::resource('doctors', DoctorController::class);
        Route::post('/doctors/{id}/schedules', [DoctorController::class, 'addSchedule'])->name('doctors.add-schedule');
        Route::delete('/doctors/{id}/schedules/{scheduleId}', [DoctorController::class, 'deleteSchedule'])->name('doctors.delete-schedule');
        Route::resource('clinics', ClinicController::class);

        // Extra clinic quota routes
        Route::get('/clinics/{id}/manage-quota', [ClinicController::class, 'manageQuota'])->name('clinics.quota');
        Route::put('/clinics/{id}/update-quota', [ClinicController::class, 'updateQuota'])->name('clinics.update-quota');
        Route::post('/clinics/{id}/reset-quota', [ClinicController::class, 'resetQuota'])->name('clinics.reset-quota');
        Route::post('/clinics/{id}/reset-quota-by-date', [ClinicController::class, 'resetQuotaByDate'])->name('clinics.reset-quota-by-date');
        Route::post('/clinics/reset-all-quota', [ClinicController::class, 'resetAllQuota'])->name('clinics.reset-all');
        Route::get('/clinics/{id}/manage-doctors', [ClinicController::class, 'manageDoctors'])->name('clinics.doctors');
        Route::post('/clinics/{id}/assign-doctor', [ClinicController::class, 'assignDoctor'])->name('clinics.assign-doctor');
        Route::delete('/clinics/{id}/remove-doctor/{doctorId}', [ClinicController::class, 'removeDoctor'])->name('clinics.remove-doctor');
        
        // Health Tips CRUD
        Route::resource('health-tips', HealthTipController::class);
        Route::post('health-tips/{healthTip}/toggle', [HealthTipController::class, 'toggleStatus'])->name('health-tips.toggle');
    });

    /*
    |----------------------------------------------------------------------
    | Dokter Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:dokter')->prefix('dashboard/dokter')->name('dokter.')->group(function () {
        Route::get('/', [DokterDashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-patients', [DokterDashboardController::class, 'myPatients'])->name('my-patients');
        Route::get('/history', [DokterDashboardController::class, 'history'])->name('history');
        
        // Queue Control Routes
        Route::post('/call-next', [DokterDashboardController::class, 'callNext'])->name('call-next');
        Route::post('/call-prev', [DokterDashboardController::class, 'callPrev'])->name('call-prev');
        Route::post('/recall',    [DokterDashboardController::class, 'recall'])->name('recall');
        Route::post('/call-specific', [DokterDashboardController::class, 'callSpecific'])->name('call-specific');
        Route::post('/finish-current', [DokterDashboardController::class, 'finishCurrent'])->name('finish-current');
        Route::post('/skip-patient', [DokterDashboardController::class, 'skipPatient'])->name('skip-patient');
        Route::post('/re-examine', [DokterDashboardController::class, 'reExamine'])->name('re-examine');
        Route::get('/export-report', [DokterDashboardController::class, 'exportReport'])->name('export-report');
        Route::get('/settings', [DokterDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [DokterDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('/visit/{id}/pdf', [DokterDashboardController::class, 'downloadVisitPdf'])->name('visit.pdf');
    });

    /*
    |----------------------------------------------------------------------
    | Apoteker Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:apoteker')->prefix('dashboard/apoteker')->name('apoteker.')->group(function () {
        // Pages
        Route::get('/',              [ApotekerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/incoming',      [ApotekerDashboardController::class, 'index'])->name('incoming');
        Route::get('/in-process',    [ApotekerDashboardController::class, 'inProcess'])->name('in-process');
        Route::get('/completed',     [ApotekerDashboardController::class, 'completed'])->name('completed');
        Route::get('/reports',       [ApotekerDashboardController::class, 'reports'])->name('reports');

        // Actions
        Route::post('/resep/{id}/start',          [ApotekerDashboardController::class, 'startProcess'])->name('resep.start');
        Route::post('/resep/{id}/hold',           [ApotekerDashboardController::class, 'holdResep'])->name('resep.hold');
        Route::post('/resep/{id}/finish',         [ApotekerDashboardController::class, 'finishProcess'])->name('resep.finish');
        Route::post('/resep/{id}/pickup',         [ApotekerDashboardController::class, 'confirmPickup'])->name('resep.pickup');
        Route::post('/resep/{id}/catatan',        [ApotekerDashboardController::class, 'updateCatatan'])->name('resep.catatan');
        Route::post('/resep/{resepId}/item/{itemId}/toggle', [ApotekerDashboardController::class, 'toggleItem'])->name('resep.toggle-item');
        Route::get('/resep/{id}/etiket',          [ApotekerDashboardController::class, 'cetakEtiket'])->name('resep.etiket');
    });

    /*
    |----------------------------------------------------------------------
    | Pasien Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:pasien')->prefix('dashboard/pasien')->name('pasien.')->group(function () {
        Route::get('/', [PasienDashboardController::class, 'index'])->name('dashboard');
        Route::post('/ambil-tiket', [PasienDashboardController::class, 'ambilTiket'])->name('ambil-tiket');
        Route::get('/live-queue', [PasienDashboardController::class, 'liveQueue'])->name('live-queue');
        Route::get('/book-appointment', [PasienDashboardController::class, 'bookAppointment'])->name('book-appointment');
        Route::get('/checkin', [PasienDashboardController::class, 'checkinPage'])->name('checkin.page');
        Route::post('/checkin', [PasienDashboardController::class, 'checkIn'])->name('checkin');
        Route::get('/medical-history', [PasienDashboardController::class, 'medicalHistory'])->name('medical-history');
        Route::get('/medical-history/{id}', [PasienDashboardController::class, 'medicalHistoryDetail'])->name('medical-history.detail');
        Route::get('/settings', [PasienDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [PasienDashboardController::class, 'settingsUpdate'])->name('settings.update');
        Route::post('/settings/photo', [PasienDashboardController::class, 'updatePhoto'])->name('settings.photo');

        // Polling API for Live Queue
        Route::get('/api/live-status', [PasienDashboardController::class, 'getLiveStatus'])->name('api.live-status');
    });
});
