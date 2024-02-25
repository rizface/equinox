<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SuperAdminController extends Controller
{
    public function LoginPage() {
        return view("superadmin.auth.login");
    }

    public function Login(Request $request) {
        try {
            if(Auth::guard("superadmin")->attempt(["username" => $request->username, "password" => $request->password])) {
                Auth::login(SuperAdmin::where("username", $request->username)->first());

                return redirect(route('superadmin.dashboard'));
            } else {
                throw new Error("wrong username or password");
            }
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());

            return redirect(route('superadmin.loginPage'));
        }
    }

    public function Logout() {
        dd("Logout");
    }

    public function Index() {
        return view("superadmin.dashboard.index");
    }
}
