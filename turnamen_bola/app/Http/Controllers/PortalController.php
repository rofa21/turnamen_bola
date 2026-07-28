<?php

namespace App\Http\Controllers;

use App\Models\AgeCategory;
use App\Models\Event;
use App\Models\MatchSchedule;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        $event = Event::active() ?? new Event([
            'name' => 'Piala Disdikpora Grassroot Regional Kebumen 2026',
            'organizer' => 'Dinas Pendidikan, Kepemudaan, dan Olahraga Kab. Kebumen',
            'location' => 'Stadion Chandradimuka Kebumen',
            'season' => '2026/2027',
        ]);

        $categories = AgeCategory::all();
        $totalTeams = Team::count();
        $totalPlayers = Player::count();
        $approvedPlayers = Player::whereHas('verification', function($q) {
            $q->whereIn('status', ['approved', 'auto_approved']);
        })->count();

        $schedules = MatchSchedule::with(['homeTeam', 'awayTeam', 'ageCategory'])
            ->orderBy('match_date', 'asc')
            ->orderBy('match_time', 'asc')
            ->take(6)
            ->get();

        return view('portal', compact('event', 'categories', 'totalTeams', 'totalPlayers', 'approvedPlayers', 'schedules'));
    }
}
