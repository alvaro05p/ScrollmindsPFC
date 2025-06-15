<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Top 3 videos más liked
        $topVideos = Video::withCount('likes as likes_count')
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        // Top 3 usuarios con mayor nivel (si 'nivel' es un campo o método)
        $usuarios = User::all()->sortByDesc(fn($user) => $user->nivel ?? 0)->take(3);

       
        return view('home', compact('topVideos', 'usuarios'));
    }
}
