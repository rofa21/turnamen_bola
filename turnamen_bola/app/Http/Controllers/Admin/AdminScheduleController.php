<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\MatchSchedule;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $categories = AgeCategory::all();
        $teams = Team::with('ageCategory')->get();

        $query = MatchSchedule::with(['ageCategory', 'homeTeam', 'awayTeam']);

        if ($catId = $request->input('category_id')) {
            $query->where('age_category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('homeTeam', fn ($t) => $t->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('awayTeam', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query->orderBy('match_date')->orderBy('match_time')->paginate(15)->withQueryString();

        return view('admin.schedule.index', compact('schedules', 'categories', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'home_team_id'    => ['required', 'exists:teams,id'],
            'away_team_id'    => [
                'required',
                'exists:teams,id',
                // Cek beda tim dengan custom rule (menghindari bug validasi 'different')
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->input('home_team_id')) {
                        $fail('Tim Home dan Tim Away tidak boleh sama.');
                    }
                },
            ],
            'round'       => ['required', 'in:penyisihan,8besar,semifinal,final,perebutan_juara3'],
            'group_name'  => ['nullable', 'string', 'max:50'],
            'match_date'  => ['required', 'date'],
            'match_time'  => ['required'],
            'location'    => ['required', 'string', 'max:255'],
        ]);

        try {
            MatchSchedule::create($validated);
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')
                ->with('error', 'Gagal menyimpan jadwal: '.$e->getMessage());
        }
    }

    public function update(Request $request, MatchSchedule $schedule)
    {
        $validated = $request->validate([
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'round'           => ['required', 'in:penyisihan,8besar,semifinal,final,perebutan_juara3'],
            'group_name'      => ['nullable', 'string', 'max:50'],
            'match_date'      => ['required', 'date'],
            'match_time'      => ['required'],
            'location'        => ['required', 'string', 'max:255'],
            'home_score'      => ['nullable', 'integer', 'min:0'],
            'away_score'      => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:scheduled,ongoing,finished,cancelled'],
        ]);

        try {
            $schedule->update($validated);
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')
                ->with('error', 'Gagal memperbarui jadwal: '.$e->getMessage());
        }
    }

    public function destroy(MatchSchedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')
                ->with('error', 'Gagal menghapus jadwal: '.$e->getMessage());
        }
    }
}
