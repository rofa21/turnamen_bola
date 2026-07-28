<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminPrintController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with('ageCategory')->get();
        $selectedTeamId = $request->input('team_id', $teams->first()?->id);
        $documentType = $request->input('type', 'buku-tim');

        $event = Event::active();

        $team = null;
        if ($selectedTeamId) {
            // ONLY LOAD APPROVED / AUTO_APPROVED PLAYERS FOR PRINTING
            $team = Team::with(['operator', 'ageCategory', 'players' => function ($query) {
                $query->whereHas('verification', function ($vq) {
                    $vq->whereIn('status', ['approved', 'auto_approved']);
                })->with(['verification', 'documents']);
            }])->find($selectedTeamId);
        }

        // Optional signature details for printing
        $signature = [
            'location'    => $request->input('sign_location', 'Kebumen'),
            'date'        => $request->input('sign_date', date('d F Y')),
            'name_left'   => $request->input('sign_name_left', $team?->manager_name ?? 'Manajer SSB'),
            'title_left'  => $request->input('sign_title_left', 'Manajer SSB / Pendamping'),
            'name_right'  => $request->input('sign_name_right', 'Drs. H. Slamet, M.Pd'),
            'title_right' => $request->input('sign_title_right', 'Ketua Panitia Pusat Disdikpora'),
        ];

        return view('admin.print.index', compact('teams', 'team', 'selectedTeamId', 'documentType', 'event', 'signature'));
    }
}
