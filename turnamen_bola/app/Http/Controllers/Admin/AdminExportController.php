<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function index()
    {
        $categories = AgeCategory::all();
        $teams = Team::all();

        return view('admin.export.index', compact('categories', 'teams'));
    }

    public function exportExcel(Request $request)
    {
        $catId = $request->input('category_id');
        $teamId = $request->input('team_id');
        $format = $request->input('format', 'xls'); // default to xls (Native Excel Table)

        $query = Player::with(['team', 'ageCategory', 'verification', 'documents']);

        if ($catId) {
            $query->where('age_category_id', $catId);
        }
        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $players = $query->get();

        if ($format === 'csv') {
            return $this->exportCsv($players);
        }

        // Default: Export Excel HTML Spreadsheet (.xls)
        // Guaranteed to open in Microsoft Excel with formatted columns, colors, text-formatted NIK, and zero CSV delimiter issues.
        $filename = 'Data_Pemain_Turnamen_Disdikpora_'.date('Ymd_His').'.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($players) {
            $output = fopen('php://output', 'w');

            // Write HTML Excel Spreadsheet structure
            $htmlHeader = 'html_header';
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data Pemain</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
            echo '<style>';
            echo 'th { background-color: #1a5c2a; color: #ffffff; font-weight: bold; border: 1px solid #0f3b1a; text-align: center; vertical-align: middle; height: 35px; }';
            echo 'td { border: 1px solid #d3d3d3; vertical-align: middle; font-size: 11pt; font-family: Arial, sans-serif; }';
            echo '.text { mso-number-format:"\@"; }'; // Force Excel plain text cell format for NIK & Phone
            echo '.center { text-align: center; }';
            echo '.bold { font-weight: bold; }';
            echo '.approved { background-color: #d4edda; color: #155724; font-weight: bold; text-align: center; }';
            echo '.rejected { background-color: #f8d7da; color: #721c24; font-weight: bold; text-align: center; }';
            echo '.pending { background-color: #fff3cd; color: #856404; font-weight: bold; text-align: center; }';
            echo '</style></head><body>';

            echo '<h2 style="color:#1a5c2a;font-family:Arial;">DATA REKAPITULASI PEMAIN TURNAMEN DISDIKPORA GRASSROOT KEBUMEN</h2>';
            echo '<p style="font-family:Arial;font-size:10pt;">Tanggal Ekspor: ' . date('d/m/Y H:i:s') . ' WIB | Total Record: ' . count($players) . ' Pemain</p>';

            echo '<table border="1" cellpadding="6" cellspacing="0">';
            echo '<thead><tr>';
            $columns = [
                'No', 'No. Registrasi', 'Nama Pemain', 'NIK', 'Tempat Lahir',
                'Tanggal Lahir', 'Tahun Lahir', 'Kategori Usia', 'SSB / Tim',
                'Kecamatan SSB', 'Manajer SSB', 'Kontak Manajer', 'No. Punggung',
                'Posisi', 'Dokumen Akta', 'Dokumen KK', 'Dokumen Foto',
                'Dokumen KIA', 'Dokumen Ijazah', 'Dokumen NISN', 'Dokumen Raport',
                'Status Verifikasi', 'Catatan Verifikasi'
            ];
            foreach ($columns as $col) {
                echo '<th>' . htmlspecialchars($col) . '</th>';
            }
            echo '</tr></thead><tbody>';

            foreach ($players as $index => $player) {
                $statusClass = match ($player->verification?->status) {
                    'approved', 'auto_approved' => 'approved',
                    'rejected'                  => 'rejected',
                    default                     => 'pending',
                };
                $statusLabel = match ($player->verification?->status) {
                    'approved', 'auto_approved' => 'Lolos Verifikasi',
                    'rejected'                  => 'Perlu Revisi / Ditolak',
                    default                     => 'Menunggu Verifikasi',
                };

                $docs = $player->documents->keyBy('type');

                echo '<tr>';
                echo '<td class="center">' . ($index + 1) . '</td>';
                echo '<td class="text center"><b>' . htmlspecialchars($player->registration_number ?? '-') . '</b></td>';
                echo '<td><b>' . htmlspecialchars($player->name) . '</b></td>';
                echo '<td class="text">' . htmlspecialchars($player->nik ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($player->birth_place ?? '-') . '</td>';
                echo '<td class="center">' . ($player->birth_date ? $player->birth_date->format('d/m/Y') : '-') . '</td>';
                echo '<td class="center">' . ($player->birth_year ?? '-') . '</td>';
                echo '<td class="center"><b>' . htmlspecialchars($player->ageCategory?->name ?? '-') . '</b></td>';
                echo '<td>' . htmlspecialchars($player->team?->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($player->team?->district ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($player->team?->manager_name ?? '-') . '</td>';
                echo '<td class="text">' . htmlspecialchars($player->team?->manager_phone ?? '-') . '</td>';
                echo '<td class="center bold">' . ($player->jersey_number ? '#' . $player->jersey_number : '-') . '</td>';
                echo '<td class="center">' . htmlspecialchars($player->position ?? '-') . '</td>';

                foreach (['akta', 'kk', 'foto', 'kia', 'ijazah', 'nisn', 'raport'] as $docType) {
                    $hasDoc = $docs->has($docType);
                    $cellClass = $hasDoc ? 'style="background-color:#e8f5e9;color:#2e7d32;text-align:center;"' : 'style="background-color:#f5f5f5;color:#9e9e9e;text-align:center;"';
                    echo '<td ' . $cellClass . '>' . ($hasDoc ? 'Ada' : 'Belum') . '</td>';
                }

                echo '<td class="' . $statusClass . '">' . htmlspecialchars($statusLabel) . '</td>';
                echo '<td>' . htmlspecialchars($player->verification?->notes ?? '-') . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
            fclose($output);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function exportCsv($players)
    {
        $filename = 'Data_Pemain_Turnamen_Disdikpora_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'No', 'No. Registrasi', 'Nama Pemain', 'NIK', 'Tempat Lahir',
            'Tanggal Lahir', 'Tahun Lahir', 'Kategori Usia', 'SSB / Tim',
            'Kecamatan SSB', 'Manajer SSB', 'Kontak Manajer', 'No. Punggung',
            'Posisi', 'Dokumen Akta', 'Dokumen KK', 'Dokumen Foto',
            'Dokumen KIA', 'Dokumen Ijazah', 'Dokumen NISN', 'Dokumen Raport',
            'Status Verifikasi', 'Catatan Verifikasi'
        ];

        $callback = function () use ($players, $columns) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens it correctly
            fwrite($file, "\xEF\xBB\xBF");

            // Use Semicolon (;) delimiter for Indonesian Excel default
            fputcsv($file, $columns, ';');

            foreach ($players as $index => $player) {
                $statusLabel = match ($player->verification?->status) {
                    'approved', 'auto_approved' => 'Lolos Verifikasi',
                    'rejected'                  => 'Perlu Revisi / Ditolak',
                    default                     => 'Menunggu Verifikasi',
                };

                $docs = $player->documents->keyBy('type');

                fputcsv($file, [
                    $index + 1,
                    $player->registration_number ?? '-',
                    $player->name,
                    "'".$player->nik,
                    $player->birth_place ?? '-',
                    $player->birth_date ? $player->birth_date->format('d/m/Y') : '-',
                    $player->birth_year ?? '-',
                    $player->ageCategory?->name ?? '-',
                    $player->team?->name ?? '-',
                    $player->team?->district ?? '-',
                    $player->team?->manager_name ?? '-',
                    "'".($player->team?->manager_phone ?? '-'),
                    $player->jersey_number ?? '-',
                    $player->position ?? '-',
                    $docs->has('akta') ? 'Ada' : 'Belum Ada',
                    $docs->has('kk') ? 'Ada' : 'Belum Ada',
                    $docs->has('foto') ? 'Ada' : 'Belum Ada',
                    $docs->has('kia') ? 'Ada' : 'Belum Ada',
                    $docs->has('ijazah') ? 'Ada' : 'Belum Ada',
                    $docs->has('nisn') ? 'Ada' : 'Belum Ada',
                    $docs->has('raport') ? 'Ada' : 'Belum Ada',
                    $statusLabel,
                    $player->verification?->notes ?? '-'
                ], ';');
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
