<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UsersPageController extends Controller
{
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = $this->userModel->all();

        return Inertia::render('users/page', [
            'users' => $users,
        ]);
    }
}
