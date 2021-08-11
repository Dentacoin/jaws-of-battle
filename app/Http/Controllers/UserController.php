<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    protected function getChangePasswordView(){
        if (!empty(Input::get('token'))) {
            $token = DB::connection('mysql2')->table('users')->select('users.*')->where(array('users.requestedPasswordChangeToken' => trim(Input::get('token'))))->get()->first();
            if (!empty($token)) {
                return view('pages/change-password');
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    protected function submitChangePassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:6|max:30',
            'repeat-password' => 'required|min:6|max:30',
            'token' => 'required'
        ], [
            'password.required' => 'Password is required.',
            'password.max' => 'Password must include maximum 30 symbols.',
            'password.min' => 'Password must include minimum 6 symbols.',
            'repeat-password.required' => 'Repeat password is required.',
            'repeat-password.max' => 'Repeat password must include maximum 30 symbols.',
            'repeat-password.min' => 'Repeat password must include minimum 6 symbols.',
            'token.required' => 'Token is required.'
        ]);

        var_dump($request->input());
        die();
    }
}
