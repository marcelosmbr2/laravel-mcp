<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersPageController;

Route::get('users', UsersPageController::class)->name("users");