<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
            <h6 class="fw-bold text-dark mb-0">
                <i class="bi bi-people-fill text-success me-2"></i>Daftar Pemain Terkumpul
                @if(isset($categoryLabel))
                    <span class="badge ms-2" style="background:#1a5c2a;">{{ $categoryLabel }}</span>
                @endif
                <span class="text-muted fw-normal ms-1">({{ $playerGroup->count() }} Pemain)</span>
            </h6>
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Klik badge hijau pada kolom dokumen untuk membuka/periksa berkas.</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="4%">#</th>
                        <th width="7%">Foto</th>
                        <th width="12%">No. Reg</th>
                        <th width="18%">Nama Pemain</th>
                        <th width="14%">NIK & Lahir</th>
                        <th class="text-center" width="6%">No. Punggung</th>
                        <th width="9%">Posisi</th>
                        <th width="20%">Dokumen Terkumpul</th>
                        <th width="10%">Status Verif</th>
                        <th class="text-center" width="9%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($playerGroup as $index => $p)
                    @php
                        $docs = $p->documents->keyBy('type');
                        $fotoUrl = $docs->has('foto') ? asset('storage/'.$docs['foto']->file_path) : null;
                        $status = $p->verification?->status ?? 'pending';
                    @endphp
                    <tr>
                        <td class="text-center small">{{ $index + 1 }}</td>
                        <td>
                            @if($fotoUrl)
                                <a href="{{ $fotoUrl }}" target="_blank" title="Klik untuk lihat foto utuh">
                                    <img src="{{ $fotoUrl }}" alt="Foto" class="rounded-circle shadow-sm" style="width:38px;height:38px;object-fit:cover;border:2px solid #2e7d32;">
                                </a>
                            @else
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center border" style="width:38px;height:38px;">
                                    <i class="bi bi-person-fill text-secondary small"></i>
                                </div>
                            @endif
                        </td>
                        <td><code class="small fw-bold text-dark">{{ $p->registration_number ?? '-' }}</code></td>
                        <td>
                            <span class="fw-bold text-dark small d-block">{{ $p->name }}</span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size:.65rem;">{{ $p->ageCategory?->name }}</span>
                        </td>
                        <td>
                            <small class="d-block text-muted">{{ $p->nik ?? '-' }}</small>
                            <small class="d-block text-dark">{{ $p->birth_date?->format('d/m/Y') ?? '-' }}</small>
                        </td>
                        <td class="fw-bold text-primary text-center">
                            @if($p->jersey_number)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6">#{{ $p->jersey_number }}</span>
                            @else
                                <span class="text-muted fst-italic small">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border small">{{ $p->position ?? '-' }}</span></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(['foto'=>'Foto','akta'=>'Akta','kk'=>'KK','kia'=>'KIA','ijazah'=>'Ijazah','nisn'=>'NISN','raport'=>'Rpt'] as $type => $label)
                                    @if($docs->has($type))
                                        <a href="{{ asset('storage/'.$docs[$type]->file_path) }}" target="_blank"
                                           class="badge bg-success text-decoration-none shadow-sm"
                                           title="Klik untuk membuka/periksa berkas {{ $label }}">
                                            ✓ {{ $label }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.55rem;"></i>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-50 text-white" style="font-size:.65rem;" title="Belum diunggah">
                                            {{ $label }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if(in_array($status,['approved','auto_approved']))
                                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Lolos</span>
                            @elseif($status==='rejected')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle small fw-bold" title="{{ $p->verification?->notes }}"><i class="bi bi-x-circle-fill me-1"></i>Revisi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle small fw-bold"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-warning me-1" title="Edit Pemain & Dokumen"
                                data-bs-toggle="modal" data-bs-target="#modalEditPemain{{ $p->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('operator.datapemain.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pemain ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada data pemain yang dikumpulkan. Klik "Tambah Pemain Baru" untuk mengumpulkan data squad.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
