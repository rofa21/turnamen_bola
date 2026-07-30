<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index(Request $request)
    {
        $categories = AgeCategory::all();

        $query = Team::with(['operator', 'ageCategory', 'players.verification', 'players.documents']);

        if ($catId = $request->input('category_id')) {
            $query->where('age_category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $teams = $query->paginate(10)->withQueryString();

        return view('admin.teams.index', compact('teams', 'categories'));
    }

    public function show(Team $team)
    {
        $team->load(['operator', 'ageCategory', 'players.verification', 'players.documents']);

        return response()->json([
            'team' => $team,
            'players' => $team->players->map(function ($player) {
                return [
                    'id' => $player->id,
                    'name' => $player->name,
                    'nik' => $player->nik,
                    'birth_date' => $player->birth_date->format('d/m/Y'),
                    'birth_year' => $player->birth_year,
                    'jersey_number' => $player->jersey_number,
                    'position' => $player->position,
                    'registration_number' => $player->registration_number,
                    'verification' => $player->verification,
                    'documents' => $player->documents,
                ];
            }),
        ]);
    }
}
