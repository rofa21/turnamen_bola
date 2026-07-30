<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\MatchSchedule;
use App\Models\Operator;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class OperatorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $operator = $request->attributes->get('operator');
        if (! $operator) {
            $opId = $request->session()->get('operator_id');
            $operator = $opId ? Operator::find($opId) : null;
        }

        if (! $operator) {
            return redirect()->route('operator.login')->with('error', 'Sesi login telah habis. Silakan login kembali.');
        }

        $team = Team::where('operator_id', $operator->id)->first();

        $playersCount = $team ? Player::where('team_id', $team->id)->count() : 0;

        $approvedCount = 0;
        $pendingCount = 0;
        $rejectedCount = 0;
        $upcomingMatches = collect();

        if ($team) {
            $players = Player::where('team_id', $team->id)->with('verification')->get();
            $approvedCount = $players->filter(fn ($p) => in_array($p->verification?->status, ['approved', 'auto_approved']))->count();
            $pendingCount = $players->filter(fn ($p) => $p->verification?->status === 'pending' || ! $p->verification)->count();
            $rejectedCount = $players->filter(fn ($p) => $p->verification?->status === 'rejected')->count();

            $upcomingMatches = MatchSchedule::with(['homeTeam', 'awayTeam', 'ageCategory'])
                ->where(function ($q) use ($team) {
                    $q->where('home_team_id', $team->id)
                        ->orWhere('away_team_id', $team->id);
                })
                ->orderBy('match_date')
                ->get();
        }

        return view('operator.dashboard', compact('operator', 'team', 'playersCount', 'approvedCount', 'pendingCount', 'rejectedCount', 'upcomingMatches'));
    }
}
