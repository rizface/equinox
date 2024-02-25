<?php

namespace App\View\Components;

use App\Models\Notification;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class AdminNavbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $adminId = Auth::guard("admin")->user()->id;

        $numberOfUnseenNotif = Notification::where("to_id", $adminId)
        ->where("seen", false)
        ->count();

        $notif = Notification::where("to_id", $adminId)
        ->orderBy("created_at", "desc")
        ->limit(10)
        ->get();

        return view('components.admin-navbar', compact('numberOfUnseenNotif', "notif"));
    }
}
