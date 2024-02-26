<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\isNull;

class AdminController extends Controller
{
    public function LoginPage() {
        return view("admin.auth.login");
    }   

    public function Login(Request $request) {
        try {
            if(Auth::guard("admin")->attempt(["username" => $request->username, "password" => $request->password])) {
                Auth::guard("login")->login(Admin::where("username", $request->username)->first());

                return redirect(route('admin.dashboard'));
            } else {
                throw new Error("username / password salah");
            }
        } catch (\Throwable $th) {
            Alert::error("Gagal", $th->getMessage());

            return redirect(route('admin.loginPage'));
        }
    }

    public function RegisterPage() {
        return view("admin.auth.register");
    }

    public function Register(Request $request) {
        try {
            $this->validate($request, [
                "username" => ["required"],
                "name" => ["required"],
                "password" => ["required"],
                "confirm-password" => ["required", "same:password"]
            ]);

            $existing = Admin::where("username", $request->username)->first();
            if(!isNull($existing)) {
                throw new Error("Username telah digunakan");
            }
            
            Admin::create([
                "username" => $request->username,
                "name" => $request->name,
                "password" => Hash::make($request->password),
            ]);

            Alert::success("Berhasil", "Berhasil Terdaftar Sebagai Admin Kontes");

            return redirect(route('admin.loginPage'));
        } catch (\Throwable $th) {
            Alert::error('Gagal', $th->getMessage());

            return redirect(route("admin.registerPage"));
        }
    }

    public function logout(Request $request) {
        Auth::guard("admin")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("admin.loginPage");
    }
}   
