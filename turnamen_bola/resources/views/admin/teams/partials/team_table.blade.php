<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="4%">#</th>
                    <th width="6%">Logo</th>
                    <th width="28%">Nama SSB</th>
                    <th width="12%">Kategori</th>
                    <th width="12%">Total Pemain</th>
                    <th width="25%">Status Verifikasi Squad</th>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teamGroup as $index => $team)
                    @php
                        $summary = $team->verification_summary;
                        $logoUrl = $team->logo_url ?? null;
                    @endphp
                    <tr>
                        <td class="text-center small">{{ $index + 1 }}</td>
                        <td>
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo {{ $team->name }}" class="team-logo">
                            @else
                                <div class="team-logo-placeholder">
                                    <i class="bi bi-shield-shaded text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $team->name }}</span><br>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>Kab. {{ $team->district ?? '-' }}</small><br>
                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $team->operator?->name ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $team->ageCategory?->name ?? '-' }}</span></td>
                        <td>{{ $summary['total'] }} Pemain</td>
                        <td>
                            @if($summary['total'] > 0)
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-success">{{ $summary['approved'] }} Lolos</span>
                                    @if($summary['pending'] > 0)
                                        <span class="badge bg-warning text-dark">{{ $summary['pending'] }} Pending</span>
                                    @endif
                                    @if($summary['rejected'] > 0)
                                        <span class="badge bg-danger">{{ $summary['rejected'] }} Revisi</span>
                                    @endif
                                    <div class="progress flex-grow-1" style="height:6px;min-width:60px;">
                                        <div class="progress-bar bg-success"
                                            style="width:{{ $summary['total'] > 0 ? round($summary['approved']/$summary['total']*100) : 0 }}%"></div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted fst-italic small">Belum ada pemain</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetailTim{{ $team->id }}">
                                <i class="bi bi-check2-square me-1"></i> Squad
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-x fs-2 d-block mb-2"></i>
                            Belum ada data tim pada kategori ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
