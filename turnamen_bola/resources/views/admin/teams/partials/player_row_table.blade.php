<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="text-center" width="4%">No</th>
                <th width="7%">Foto</th>
                <th width="20%">Nama Pemain & No. Punggung</th>
                <th width="20%">NIK & Tanggal Lahir</th>
                <th width="12%">No. Registrasi</th>
                <th width="22%">Dokumen Terkumpul (Klik Untuk Cek)</th>
                <th width="10%">Status</th>
                <th class="text-center" width="9%">Aksi Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($players as $pIndex => $p)
                @php
                    $ageValid = $p->checkAgeValidity();
                    $pdocs = $p->documents->keyBy('type');
                    $fotoDoc = $pdocs->get('foto');
                    $fotoUrl = $fotoDoc ? asset('storage/'.$fotoDoc->file_path) : null;
                    $status = $p->verification?->status ?? 'pending';
                @endphp
                <tr>
                    <td class="text-center small">{{ $pIndex + 1 }}</td>
                    <td>
                        @if($fotoUrl)
                            <a href="{{ $fotoUrl }}" target="_blank" title="Buka foto pemain">
                                <img src="{{ $fotoUrl }}" alt="Foto" class="rounded-circle shadow-sm" style="width:38px;height:38px;object-fit:cover;border:2px solid #0d6efd;">
                            </a>
                        @else
                            <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center border" style="width:38px;height:38px;">
                                <i class="bi bi-person-fill text-secondary" style="font-size:.8rem;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold small d-block text-dark">{{ $p->name }}</span>
                        <small class="text-muted">
                            @if($p->jersey_number)
                                No. {{ $p->jersey_number }} •
                            @else
                                <span class="fst-italic">No. Punggung: -</span> •
                            @endif
                            {{ $p->position ?? 'Penyerang' }}
                        </small>
                    </td>
                    <td>
                        <small class="d-block text-muted">{{ $p->nik ?? '-' }}</small>
                        <small class="d-block text-dark">{{ $p->birth_date?->format('d/m/Y') ?? '-' }} ({{ $p->birth_year }})</small>
                        @if($ageValid)
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.6rem;">✓ Usia Sesuai</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.6rem;">✗ Usia Melebihi</span>
                        @endif
                    </td>
                    <td><code class="small fw-bold text-dark">{{ $p->registration_number ?? '-' }}</code></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(['foto'=>'Foto','akta'=>'Akta','kk'=>'KK','kia'=>'KIA','ijazah'=>'Ijazah','nisn'=>'NISN','raport'=>'Rpt'] as $t => $l)
                                @if($pdocs->has($t))
                                    <a href="{{ asset('storage/'.$pdocs[$t]->file_path) }}" target="_blank"
                                       class="badge bg-success text-decoration-none shadow-sm"
                                       style="font-size:.65rem;"
                                       title="Klik untuk membuka/periksa berkas {{ $l }}">
                                        ✓ {{ $l }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.5rem;"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary bg-opacity-50 text-white" style="font-size:.65rem;" title="Belum diunggah oleh operator">
                                        {{ $l }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @if(in_array($status, ['approved', 'auto_approved']))
                            <span class="badge bg-success fw-bold"><i class="bi bi-check-circle me-1"></i>Lolos</span>
                        @elseif($status === 'rejected')
                            <span class="badge bg-danger fw-bold" title="{{ $p->verification?->notes }}"><i class="bi bi-x-circle me-1"></i>Revisi</span>
                        @else
                            <span class="badge bg-warning text-dark fw-bold"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <form action="{{ route('admin.verify.player', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-success py-1 px-2" title="Setujui (Lolos Verifikasi)"><i class="bi bi-check-lg"></i> Lolos</button>
                            </form>
                            <form action="{{ route('admin.verify.player', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="notes" value="Dokumen permohonan perlu revisi.">
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Tolak / Minta Revisi"><i class="bi bi-x-lg"></i> Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data pemain pada kategori ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
