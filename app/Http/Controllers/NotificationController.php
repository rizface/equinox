<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class NotificationController extends Controller
{
    public function GetList() {
        try {
            $notifications = Notification::where("to_id", Auth::guard("admin")->user()->id)->get();
            $unseenIds = [];
  
            foreach ($notifications as $n) {
                if (!$n->seen) {
                    array_push($unseenIds, $n->id);
                }
            }

            Notification::whereIn("id", $unseenIds)->update(["seen" => true]);

            return view("admin.dashboard.notification-detail", compact('notifications'));
        } catch (\Throwable $th) {
            Alert::error("Error", $th->getMessage());

            return redirect()->back();
        }
    }
}
