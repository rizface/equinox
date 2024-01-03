<?php

namespace App\Http\Controllers;

use App\Models\Coder;
use App\Traits\UtilsTrait;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class CoderController extends Controller
{
    use UtilsTrait;
    
    public function registerPage() {
        return view("coder.auth.register");
    }

    public function register(Request $request) {
        try {
            if ($this->NullOrEmpty($request->username)) {
                throw new Error("Username is required");
            }

            if ($this->NullOrEmpty($request->password)) {
                throw new Error("Password is required");
            }

            if ($this->NullOrEmpty($request["confirm-password"])) {
                throw new Error("Password Confirmation is required");
            }

            if ($this->NullOrEmpty($request->nim)) {
                throw new Error("NIM is required");
            }

            if ($this->NullOrEmpty($request->name)) {
                throw new Error("Name is required");
            }

            if($request->password != $request["confirm-password"]) {
                throw new Error("Password doesn't match");
            }

            $existing = Coder::where("username", $request->username)->first();
            if ($existing) {
                throw new Error("$request->username already taken");
            }

            Coder::create([
                "username" => $request->username,
                "password" => Hash::make($request->password),
                "name" => $request->name,
                "nim" => $request->nim
            ]);

            Alert::success("Success", "Success create coder account");
            return redirect()->route("coder.loginPage");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function loginPage() {
        return view("coder.auth.login");
    }

    public function login(Request $request) {
        try {
            if ($this->NullOrEmpty($request->username)) {
                throw new Error("Username is required");
            }

            if ($this->NullOrEmpty($request->password)) {
                throw new Error("Password is required");
            }

            $coder = Coder::where("username", $request->username)->first();
            
            if(!$coder) {
                throw new Error("Username $request->username not found");
            }

            if (Auth::guard("coder")->attempt(["username" => $request->username, "password" => $request->password])) {
                Auth::guard("coder")->login($coder);
                dd(Auth::guard("coder")->user());
            }
            
            throw new Error("Login failed check your username/password");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->route("coder.loginPage");
        }    
    }
}
