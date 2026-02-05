<?php


use Illuminate\Support\Facades\Route;
use Workbench\ContactForm\Http\Controllers\ContactFormController;

Route::middleware(['guest', 'web'])->group(function () {

    Route::get('/contact',[ContactFormController::class, 'create'])->name('contact.create');
    Route::post('submit/message', [ContactFormController::class, 'store'])->name('submit.message');
});

