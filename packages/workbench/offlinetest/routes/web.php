	
    <?php


use Illuminate\Support\Facades\Route;
use Workbench\OfflineTest\Http\Controllers\OfflineTestController;
use Workbench\OfflineTest\Http\Controllers\OfflineTestMarksController;

 Route::get('/',[OfflineTestController::class,'home'])->name('home');
Route::middleware(['guest', 'web'])->group(function () {
    // Route::get('/',[OfflineTestController::class,'home'])->name('home');
    Route::get('/offline-test',[OfflineTestController::class,'index'])->name('user.offline-test');
	Route::get('/offline-test/views/{uuid}',[OfflineTestController::class,'views'])->name('user.offline-test.views');
	Route::get('/offline-test/section/{uuid}',[OfflineTestController::class,'section'])->name('user.offline-test.section');
	Route::get('/offline-test/bulk-marks',[OfflineTestController::class,'marks'])->name('user.offline-test.marks');
    Route::post('/offline-test/store',[OfflineTestController::class,'store'])->name('user.offline-test.store');
    Route::post('/offline-test/edit',[OfflineTestController::class,'edit'])->name('user.offline-test.edit');
	Route::post('/offline-test/delete',[OfflineTestController::class,'destroy'])->name('user.offline-test.delete');
	Route::post('/offline-test/restore',[OfflineTestController::class,'restore'])->name('user.offline-test.restore');
   //-----------------------------------------Bulk Update Marks--------------------------------------------//
   
   Route::post('/offline-test/bulk-marks',[OfflineTestMarksController::class,'store'])->name('user.offline-test.marks.store');
});     




 