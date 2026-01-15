<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'instructors' => User::where('role', 'instructor')->count(),
            'students' => User::where('role', 'student')->count(),
            'news' => News::count(),
        ];

        $latestPublishedNews = News::query()
            ->where('status', 'publish')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestPublishedNews'));
    }
}