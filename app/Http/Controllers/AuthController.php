<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController {
    public function index() {
        if( Auth::check() ) {
            return redirect()->route( 'admin.login' );
        }
        return view( "admin.contests" );
    }
    public function login() {
        // ...
    }
    public function logout() {
        auth('web')->logout();
        return redirect()->route('login');
    }
}
