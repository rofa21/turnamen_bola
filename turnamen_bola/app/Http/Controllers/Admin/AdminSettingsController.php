<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class AdminSettingsController extends Controller
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

        return view('admin.settings.index', compact('event', 'categories'));
    }

    public function updateEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $event = Event::active() ?? new Event;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        $event->fill($validated);
        $event->is_active = true;
        $event->save();

        return back()->with('success', 'Identitas dan logo turnamen berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'max_birth_year' => ['required', 'integer', 'min:1990', 'max:2050'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        AgeCategory::create($validated);

        return back()->with('success', 'Kategori kelompok usia berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, AgeCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'max_birth_year' => ['required', 'integer', 'min:1990', 'max:2050'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        $category->update($validated);

        return back()->with('success', 'Kategori kelompok usia berhasil diperbarui.');
    }

    public function destroyCategory(AgeCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori kelompok usia berhasil dihapus.');
    }

    public function downloadBackup(): BinaryFileResponse
    {
        $dbPath = database_path('database.sqlite');
        if (! File::exists($dbPath)) {
            File::put($dbPath, '');
        }

        $zipFileName = 'backup_lengkap_turnamen_'.date('Ymd_His').'.zip';
        $zipPath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // 1. Add SQLite Database file
            $zip->addFile($dbPath, 'database.sqlite');

            // 2. Add uploaded files from storage/app/public (documents, logos, etc.)
            $publicStoragePath = storage_path('app/public');
            if (File::exists($publicStoragePath)) {
                $files = File::allFiles($publicStoragePath);
                foreach ($files as $file) {
                    $relativePath = 'storage/' . $file->getRelativePathname();
                    // Avoid including backup zip files recursively
                    if (! str_starts_with($file->getFilename(), 'backup_lengkap_')) {
                        $zip->addFile($file->getRealPath(), $relativePath);
                    }
                }
            }

            $zip->close();
        }

        return response()->download($zipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'max:102400'],
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $dbPath = database_path('database.sqlite');
        $publicStoragePath = storage_path('app/public');

        if ($extension === 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($file->getRealPath()) === true) {
                // Extract database.sqlite
                if ($zip->locateName('database.sqlite') !== false) {
                    $zip->extractTo(storage_path('app/temp_backup'), 'database.sqlite');
                    File::copy(storage_path('app/temp_backup/database.sqlite'), $dbPath);
                    File::deleteDirectory(storage_path('app/temp_backup'));
                }

                // Extract storage files
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entryName = $zip->getNameIndex($i);
                    if (str_starts_with($entryName, 'storage/')) {
                        $relativeSubPath = substr($entryName, strlen('storage/'));
                        if (! empty($relativeSubPath)) {
                            $targetPath = $publicStoragePath . '/' . $relativeSubPath;
                            if (str_ends_with($entryName, '/')) {
                                File::makeDirectory($targetPath, 0755, true, true);
                            } else {
                                File::makeDirectory(dirname($targetPath), 0755, true, true);
                                copy("zip://{$file->getRealPath()}#{$entryName}", $targetPath);
                            }
                        }
                    }
                }
                $zip->close();

                return back()->with('success', 'Backup LENGKAP (Database SQLite + Berkas Dokumen/Foto) berhasil dipulihkan.');
            } else {
                return back()->withErrors(['backup_file' => 'Gagal membuka file cadangan ZIP.']);
            }
        } elseif (in_array($extension, ['sql', 'txt'])) {
            try {
                $sqlContent = File::get($file->getRealPath());
                DB::connection()->getPdo()->exec('PRAGMA foreign_keys = OFF;');
                DB::unprepared($sqlContent);
                DB::connection()->getPdo()->exec('PRAGMA foreign_keys = ON;');
            } catch (\Throwable $e) {
                return back()->withErrors(['backup_file' => 'Gagal memproses file SQL dump: '.$e->getMessage()]);
            }
        } else {
            File::copy($file->getRealPath(), $dbPath);
        }

        return back()->with('success', 'Basis data (database) berhasil dipulihkan dari file cadangan.');
    }
}
