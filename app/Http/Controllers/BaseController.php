<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Ketpub;
use App\Models\Notification;
use App\Models\Penelitian;
use App\Models\Pkm;
use App\Models\Tugaspub;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BaseController extends Controller
{
    public function __construct()
    {
        // Share notifikasi ke semua views
        view()->composer('*', function ($view) {
            $notifications = [];
            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::id())
                    ->where('status', 'unread')
                    ->latest()
                    ->take(4)
                    ->get();
            }
            $view->with('notifications', $notifications);
        });
    }
}
