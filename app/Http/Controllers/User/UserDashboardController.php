<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
    public function index()
    {
        try {
            $role = Auth::user()->getRoleNames()->first() ?? 'User';
            return view('user.dashboard.index', compact('role'));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat dashboard.');
        }
    }
}
