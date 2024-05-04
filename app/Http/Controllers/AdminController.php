<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivation;
use App\Models\Admin;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\isNull;

class AdminController extends Controller
{
    public function LoginPage() {
        return view("admin.auth.login");
    }   

    public function Login(Request $request) {
        try {
            $admin = Admin::where("username", $request->username)->first();
            if (!$admin) {
                throw new Error("Admin not found");
            }

            $credIsValid = Auth::guard("admin")->attempt(["username" => $request->username, "password" => $request->password]);
            $adminsIsValid = $admin->is_valid;
            $canLogin = $credIsValid && $adminsIsValid;


            if (!$credIsValid) {
                throw new Error("Wrong username / password");
            }

            if (!$canLogin) {
                Auth::guard("admin")->logout();
                throw new Error("Admin account is not validated yet by the super admin");
            }

            Auth::guard("admin")->login(Admin::where("username", $request->username)->first());

            return redirect(route('admin.dashboard'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());

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
                "confirm-password" => ["required", "same:password"],
                "email" => ["required"]
            ]);

            $existing = Admin::where("username", $request->username)->first();
            if($existing) {
                throw new Error("Username already taken");
            }
            
            $existing = Admin::where("email", $request->email)->first();
            if($existing) {
                throw new Error("Email already taken");
            }
            
            $admin = Admin::create([
                "username" => $request->username,
                "name" => $request->name,
                "password" => Hash::make($request->password),
                "email" => $request->email,
            ]);

            Mail::to($admin->email)->send(new AccountActivation($admin->id));

            Alert::success("Success", "Register is successful, please check your email to validate your account");

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

    public function Activation($id) {
        $admin = Admin::where("id", $id)
        ->first();

        if (!$admin) {
            Alert::error("Failed", "Admin not found");
            return redirect(route("admin.loginPage"));
        }

        if ($admin->is_valid) {
            Alert::error("Failed", "Admin account is already validated");
            return redirect(route("admin.loginPage"));
        }

        $admin->ValidateAdmin();

        Alert::success("Success", "Admin account is validated");

        return redirect(route("admin.loginPage"));
    }
}   
