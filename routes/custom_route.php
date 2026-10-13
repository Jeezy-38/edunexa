<?php

use App\Http\Controllers\frontend\CourseController;
use App\Http\Controllers\frontend\MycourseController;
use App\Http\Controllers\frontend\SubscribedController;
use Illuminate\Support\Facades\Route;

Route::controller(CourseController::class)->group(function () {
    Route::get('compare', 'compare')->name('compare');
});

Route::controller(MycourseController::class)->middleware('auth')->group(function () {
    Route::get('my-course/invoice/{id}', 'invoice')->name('my_course.invoice');
});
