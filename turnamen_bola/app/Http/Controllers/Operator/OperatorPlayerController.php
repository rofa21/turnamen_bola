<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Player;
use App\Models\PlayerDocument;
use App\Models\PlayerVerification;
use App\Models\Team;
use Illuminate\Http\Request;

class OperatorPlayerController extends Controller
{
    /**
     * All document types supported
     */
    private array $docTypes = [
        'file_akta'   => 'akta',
        'file_kk'     => 'kk',
        'file_foto'   => 'foto',
        'file_kia'    => 'kia',
        'file_ijazah' => 'ijazah',
        'file_nisn'   => 'nisn',
        'file_raport' => 'raport',
    ];

    public function index(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();
        $categories = AgeCategory::all();

        $players = collect();
        if ($team) {
            $players = Player::where('team_id', $team->id)
                ->with(['ageCategory', 'verification', 'documents'])
                ->get();
        }

        return view('operator.datapemain', compact('operator', 'team', 'players', 'categories'));
    }

    public function store(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if (! $team) {
            return back()->with('error', 'Tim SSB belum terdaftar. Lengkapi Profil Tim SSB terlebih dahulu.');
        }

        // Check if at least ONE supporting document (KIA / Ijazah / NISN / Raport) is uploaded
        $hasSupportingDoc = $request->hasFile('file_kia') ||
                            $request->hasFile('file_ijazah') ||
                            $request->hasFile('file_nisn') ||
                            $request->hasFile('file_raport');

        if (! $hasSupportingDoc) {
            return back()->withInput()->with('error', 'Wajib mengunggah minimal salah satu dokumen pendukung (KIA / Ijazah / NISN / Raport).');
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'nik'            => ['required', 'string', 'max:20', 'unique:players,nik'],
            'birth_date'     => ['required', 'date'],
            'birth_place'    => ['nullable', 'string', 'max:100'],
            'jersey_number'  => ['nullable', 'integer', 'min:1', 'max:99'],
            'position'       => ['required', 'string', 'max:50'],
            'age_category_id'=> ['required', 'exists:age_categories,id'],
            'file_akta'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_kk'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_foto'      => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:3048'],
            'file_kia'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_ijazah'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_nisn'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_raport'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
        ]);

        $regNumber = Player::generateRegistrationNumber($validated['age_category_id'], date('Y'));

        $player = Player::create([
            'team_id'             => $team->id,
            'age_category_id'     => $validated['age_category_id'],
            'name'                => $validated['name'],
            'nik'                 => $validated['nik'],
            'birth_date'          => $validated['birth_date'],
            'birth_place'         => $validated['birth_place'] ?? 'Kebumen',
            'jersey_number'       => $validated['jersey_number'],
            'position'            => $validated['position'],
            'registration_number' => $regNumber,
        ]);

        // Upload all document types
        $this->uploadDocuments($request, $player->id, isNew: true);

        // Verification status MUST be PENDING (requires Admin review)
        $ageValid = $player->checkAgeValidity();
        PlayerVerification::create([
            'player_id' => $player->id,
            'status'    => 'pending',
            'age_valid' => $ageValid,
            'notes'     => 'Menunggu verifikasi dan pemeriksaan berkas oleh Admin Panitia Pusat.',
        ]);

        return redirect()->route('operator.datapemain')->with('success', "Pemain {$player->name} berhasil didaftarkan. Status: Menunggu Verifikasi Admin.");
    }

    public function update(Request $request, Player $player)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if (! $team || $player->team_id !== $team->id) {
            abort(403, 'Akses ditolak.');
        }

        // Check if player has or is uploading at least ONE supporting document
        $existingSupportingDocs = $player->documents()->whereIn('type', ['kia', 'ijazah', 'nisn', 'raport'])->count();
        $hasNewSupportingDoc = $request->hasFile('file_kia') ||
                               $request->hasFile('file_ijazah') ||
                               $request->hasFile('file_nisn') ||
                               $request->hasFile('file_raport');

        if ($existingSupportingDocs === 0 && ! $hasNewSupportingDoc) {
            return back()->withInput()->with('error', 'Wajib mengunggah minimal salah satu dokumen pendukung (KIA / Ijazah / NISN / Raport).');
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'nik'            => ['required', 'string', 'max:20', 'unique:players,nik,'.$player->id],
            'birth_date'     => ['required', 'date'],
            'birth_place'    => ['nullable', 'string', 'max:100'],
            'jersey_number'  => ['nullable', 'integer', 'min:1', 'max:99'],
            'position'       => ['required', 'string', 'max:50'],
            'age_category_id'=> ['required', 'exists:age_categories,id'],
            'file_akta'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_kk'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_foto'      => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:3048'],
            'file_kia'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_ijazah'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_nisn'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
            'file_raport'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3048'],
        ]);

        $player->update([
            'name'           => $validated['name'],
            'nik'            => $validated['nik'],
            'birth_date'     => $validated['birth_date'],
            'birth_place'    => $validated['birth_place'] ?? $player->birth_place,
            'jersey_number'  => $validated['jersey_number'],
            'position'       => $validated['position'],
            'age_category_id'=> $validated['age_category_id'],
        ]);

        // Update documents
        $this->uploadDocuments($request, $player->id, isNew: false);

        // Reset to pending so Admin can re-verify updated data
        $player->refresh()->load('ageCategory');
        $ageValid = $player->checkAgeValidity();

        PlayerVerification::updateOrCreate(
            ['player_id' => $player->id],
            [
                'age_valid' => $ageValid,
                'status'    => 'pending',
                'notes'     => 'Data/berkas diperbarui operator. Menunggu re-verifikasi Admin Panitia.',
            ]
        );

        return redirect()->route('operator.datapemain')->with('success', "Data pemain {$player->name} berhasil diperbarui. Status diset ke Menunggu Verifikasi Admin.");
    }

    public function destroy(Request $request, Player $player)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if (! $team || $player->team_id !== $team->id) {
            abort(403, 'Akses ditolak.');
        }

        $name = $player->name;
        $player->delete();

        return redirect()->route('operator.datapemain')->with('success', "Pemain {$name} berhasil dihapus.");
    }

    /**
     * Handle uploading of all document types
     */
    private function uploadDocuments(Request $request, int $playerId, bool $isNew): void
    {
        foreach ($this->docTypes as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $path = $file->store("documents/{$playerId}", 'public');

                if ($isNew) {
                    PlayerDocument::create([
                        'player_id'     => $playerId,
                        'type'          => $docType,
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                } else {
                    PlayerDocument::updateOrCreate(
                        ['player_id' => $playerId, 'type' => $docType],
                        [
                            'file_path'     => $path,
                            'original_name' => $file->getClientOriginalName(),
                        ]
                    );
                }
            }
        }
    }
}
