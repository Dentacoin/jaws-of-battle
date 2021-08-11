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
            var_dump(trim(Input::get('token')));
            var_dump($token);
            die();
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

        $token = DB::connection('mysql2')->table('users')->select('users.*')->where(array('users.requestedPasswordChangeToken' => trim($request->input('token'))))->get()->first();
        if (!empty($token)) {
            $salt = bin2hex(random_bytes(16));
            DB::connection('mysql2')->table('users')->where(array('users.requestedPasswordChangeToken' => trim($request->input('token'))))->limit(1)->update(array('requestedPasswordChangeToken' => NULL, 'salt' => $salt, 'password' => hash_pbkdf2('sha256', trim($request->input('password')), $salt, 10000, 32)));

            return redirect()->route('home')->with(['success' => 'Your Dentacare: Jaws of Battle password have been updated successfully!']);
        } else {
            return abort(404);
        }
    }
}