<?php

use App\Http\Controllers\Api\ClickController;
use App\Http\Controllers\Api\DtcVisitorController;
use App\Http\Controllers\Api\SparkController;
use App\Http\Controllers\Click\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dtc\CenterInventoryController;
use App\Http\Controllers\Dtc\ServiceController;
use App\Http\Controllers\Dtc\VisitorController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SdnPdiController;
use App\Http\Controllers\Settings\DatabaseController;
use App\Http\Controllers\Spark\TraineeController;
use App\Http\Controllers\Spark\TrainingController;
use App\Http\Controllers\Tmd\CourseController;
use App\Http\Controllers\Tmd\ParticipantController;
use App\Http\Controllers\Tmd\TmdPenetrationController;
use App\Http\Controllers\Tmd\TrainerController;
use App\Http\Controllers\Tmd\TrainingBatchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// TMD Module
Route::middleware(['auth', 'verified'])->prefix('tmd')->name('tmd.')->group(function () {
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::post('/participants', [ParticipantController::class, 'store'])->name('participants.store');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::put('/participants/{participant}', [ParticipantController::class, 'update'])->name('participants.update');
    Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
    Route::post('/participants/batch-delete', [ParticipantController::class, 'batchDelete'])->name('participants.batchDelete');
    Route::post('/participants/{participant}/certificate', [ParticipantController::class, 'uploadCertificate'])->name('participants.certificate');
    Route::delete('/participants/{participant}/certificate', [ParticipantController::class, 'deleteCertificate'])->name('participants.certificate.delete');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/batch-delete', [CourseController::class, 'batchDelete'])->name('courses.batchDelete');
    Route::get('/batches', fn () => redirect()->route('tmd.participants.index'))->name('batches.index');
    Route::post('/batches', [TrainingBatchController::class, 'store'])->name('batches.store');
    Route::put('/batches/{batch}', [TrainingBatchController::class, 'update'])->name('batches.update');
    Route::post('/batches/batch-delete', [TrainingBatchController::class, 'batchDelete'])->name('batches.batchDelete');
    Route::get('/courses', fn () => redirect()->route('tmd.participants.index'))->name('courses.index');
    Route::get('/penetration', fn () => redirect()->route('tmd.participants.index'))->name('penetration');
    Route::post('/penetration', [TmdPenetrationController::class, 'store'])->name('penetration.store');
    Route::put('/penetration/{penetration}', [TmdPenetrationController::class, 'update'])->name('penetration.update');
    Route::post('/penetration/preview', [TmdPenetrationController::class, 'previewDelete'])->name('penetration.previewDelete');
    Route::post('/penetration/batch-delete', [TmdPenetrationController::class, 'batchDelete'])->name('penetration.batchDelete');
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
    Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
    Route::put('/trainers/{trainer}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::post('/trainers/{trainer}/photo', [TrainerController::class, 'uploadPhoto'])->name('trainers.photo');
    Route::delete('/trainers/{trainer}/photo', [TrainerController::class, 'deletePhoto'])->name('trainers.photo.delete');
    Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
    Route::post('/trainers/batch-delete', [TrainerController::class, 'batchDelete'])->name('trainers.batchDelete');
});

// DTC Module
Route::middleware(['auth', 'verified'])->prefix('dtc')->name('dtc.')->group(function () {
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::post('/visitors', [VisitorController::class, 'store'])->name('visitors.store');
    Route::post('/visitors/import', [VisitorController::class, 'import'])->name('visitors.import');
    Route::put('/visitors/{visitor}', [VisitorController::class, 'update'])->name('visitors.update');
    Route::delete('/visitors/{visitor}', [VisitorController::class, 'destroy'])->name('visitors.destroy');
    Route::post('/visitors/batch-delete', [VisitorController::class, 'batchDelete'])->name('visitors.batchDelete');
    Route::get('/analytics', fn () => redirect()->route('dtc.visitors.index'))->name('analytics');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/centers', [CenterInventoryController::class, 'index'])->name('centers.index');
    Route::post('/centers', [CenterInventoryController::class, 'store'])->name('centers.store');
    Route::post('/centers/import', [CenterInventoryController::class, 'import'])->name('centers.import');
    Route::post('/centers/batch-delete', [CenterInventoryController::class, 'batchDelete'])->name('centers.batchDelete');
    Route::put('/centers/{center}', [CenterInventoryController::class, 'update'])->name('centers.update');
    Route::delete('/centers/{center}', [CenterInventoryController::class, 'destroy'])->name('centers.destroy');
});

// SDN & PDI Tech4ED Module
Route::middleware(['auth', 'verified'])->get('/sdn-pdi', [SdnPdiController::class, 'index'])->name('sdn-pdi.index');

// SPARK Module
Route::middleware(['auth', 'verified'])->prefix('spark')->name('spark.')->group(function () {
    Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::post('/trainings', [TrainingController::class, 'store'])->name('trainings.store');
    Route::put('/trainings/{training}', [TrainingController::class, 'update'])->name('trainings.update');
    Route::delete('/trainings/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');
    Route::post('/trainings/batch-delete', [TrainingController::class, 'batchDelete'])->name('trainings.batchDelete');
    Route::get('/trainees', [TraineeController::class, 'index'])->name('trainees.index');
    Route::post('/trainees', [TraineeController::class, 'store'])->name('trainees.store');
    Route::put('/trainees/{trainee}', [TraineeController::class, 'update'])->name('trainees.update');
    Route::delete('/trainees/{trainee}', [TraineeController::class, 'destroy'])->name('trainees.destroy');
});

// CLICK Module
Route::middleware(['auth', 'verified'])->prefix('click')->name('click.')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::put('/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::post('/devices/batch-delete', [DeviceController::class, 'batchDelete'])->name('devices.batchDelete');
});

// Funding Module
Route::middleware(['auth', 'verified'])->prefix('funding')->name('funding.')->group(function () {
    Route::get('/', [FundingController::class, 'index'])->name('index');
    Route::post('/', [FundingController::class, 'store'])->name('store');
    Route::put('/{funding}', [FundingController::class, 'update'])->name('update');
    Route::delete('/{funding}', [FundingController::class, 'destroy'])->name('destroy');
    Route::post('/batch-delete', [FundingController::class, 'batchDelete'])->name('batchDelete');
});

// CSV Exports
Route::middleware(['auth', 'verified'])->prefix('export')->name('export.')->group(function () {
    Route::get('/{module}/csv', [ExportController::class, 'csv'])->name('csv');
    Route::get('/{module}/xlsx', [ExportController::class, 'xlsx'])->name('xlsx');
    Route::get('/{module}/template', [ExportController::class, 'template'])->name('template');
});

// Settings > Database (Administrator only)
Route::middleware(['auth', 'verified', 'admin'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');
    Route::post('/database/export', [DatabaseController::class, 'export'])->name('database.export');
    Route::post('/database/import', [DatabaseController::class, 'import'])->name('database.import');
    Route::get('/database/download', [DatabaseController::class, 'download'])->name('database.download');
});

// API Routes (for charts/AJAX)
Route::middleware(['auth', 'verified'])->prefix('api')->name('api.')->group(function () {
    Route::get('/tmd/participants', [App\Http\Controllers\Api\ParticipantController::class, 'index'])->name('tmd.participants');
    Route::get('/dtc/visitors', [DtcVisitorController::class, 'index'])->name('dtc.visitors');
    Route::get('/dtc/traffic', [DtcVisitorController::class, 'traffic'])->name('dtc.traffic');
    Route::get('/dtc/services', [DtcVisitorController::class, 'services'])->name('dtc.services');
    Route::get('/spark/trainings', [SparkController::class, 'trainings'])->name('spark.trainings');
    Route::get('/spark/demographics', [SparkController::class, 'demographics'])->name('spark.demographics');
    Route::get('/spark/financials', [SparkController::class, 'financials'])->name('spark.financials');
    Route::get('/click/devices', [ClickController::class, 'index'])->name('click.devices');
    Route::get('/funding/summary', [App\Http\Controllers\Api\FundingController::class, 'summary'])->name('funding.summary');
    Route::get('/funding/categories', [App\Http\Controllers\Api\FundingController::class, 'categories'])->name('funding.categories');
    Route::get('/funding/historical', [App\Http\Controllers\Api\FundingController::class, 'historical'])->name('funding.historical');
});

require __DIR__.'/auth.php';
