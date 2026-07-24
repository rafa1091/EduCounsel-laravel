@extends('layouts.app')

@section('title', 'Kelola Bimbingan Mahasiswa')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; }
    .bim-hero { background: linear-gradient(135deg, #1E2A4A 0%, #2D3F6B 100%); padding: 2rem; }
    .bim-hero-inner { max-width: 960px; margin: 0 auto; }
    .bim-hero-label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #8AAEFB; text-transform: uppercase; margin-bottom: 0.4rem; }
    .bim-hero-title { font-size: 26px; font-weight: 700; color: #fff; }

    .bim-main { max-width: 960px; margin: -1.5rem auto 4rem; padding: 0 2rem; position: relative; z-index: 1; }
    .bim-alert-success { padding: 12px 16px; background: #ECFDF5; color: #059669; border: 0.5px solid #A7F3D0; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 600; }

    .bim-card { background: #fff; border-radius: 16px; padding: 20px; border: 0.5px solid rgba(0,0,0,.07); box-shadow: 0 4px 20px rgba(30,42,74,.05); margin-bottom: 16px; display: flex; flex-direction: column; gap: 14px; }
    .bim-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; }
    .bim-mhs-name { font-size: 15px; font-weight: 700; color: #1E2A4A; }
    .bim-meta { font-size: 12px; color: #64748B; margin-top: 4px; display: flex; gap: 12px; }
    
    .bim-status-badge { font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
    .badge-pending { background: #FFFBEB; color: #B45309; }
    .badge-approved { background: #ECFDF5; color: #059669; }
    .badge-rejected { background: #FEF2F2; color: #EF4444; }

    .bim-body { font-size: 13px; color: #475569; }
    .bim-body strong { color: #1E2A4A; display: block; margin-bottom: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .bim-action-zone { background: #F8FAFC; border-radius: 12px; padding: 14px; border: 0.5px solid #E2E8F0; }
    .bim-input-balasan { width: 100%; min-height: 40px; height: 40px; border: 0.5px solid #CBD5E1; border-radius: 8px; padding: 8px 12px; font-size: 13px; font-family: inherit; outline: none; margin-bottom: 10px; resize: none; transition: border-color 0.2s; }
    .bim-input-balasan:focus { border-color: #4F7EF8; }
    
    .bim-btn-group { display: flex; gap: 8px; }
    .bim-btn { font-size: 11px; font-weight: 700; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-family: inherit; text-transform: uppercase; letter-spacing: 0.5px; transition: opacity 0.15s; display: inline-flex; align-items: center; gap: 6px; }
    .bim-btn:hover { opacity: 0.9; }
    .btn-approve { background: #10B981; color: #fff; }
    .btn-reject { background: #EF4444; color: #fff; }
    
    .bim-empty { background: #fff; border-radius: 16px; padding: 40px; text-align: center; border: 0.5px dashed #CBD5E1; color: #94A3B8; font-size: 14px; }
</style>

<div class="bim-hero">
    <div class="bim-hero-inner">
        <div class="bim-hero-label">Dosen Panel</div>
        <div class="bim-hero-title">Permintaan Bimbingan Mahasiswa</div>
    </div>
</div>

<div class="bim-main">
    @if(session('success'))
        <div class="bim-alert-success">{{ session('success') }}</div>
    @endif

    @forelse($bimbingans as $b)
        <div class="bim-card">
            <div class="bim-header">
                <div>
                    <div class="bim-mhs-name">{{ $b->user->name ?? 'Mahasiswa Kelompok / Personal' }}</div>
                    <div class="bim-meta">
                        <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</span>
                        <span><i class="fa-regular fa-clock"></i> {{ substr($b->jam, 0, 5) }} WIB</span>
                    </div>
                </div>
                <span class="bim-status-badge badge-{{ $b->status }}">
                    {{ $b->status }}
                </span>
            </div>

            <div class="bim-body">
                <strong>Topik / Bahasan:</strong>
                <p style="margin: 0 0 10px 0; font-weight: 600; color: #1E2A4A;">{{ $b->topik }}</p>
                
                @if($b->catatan)
                    <strong>Catatan Mahasiswa:</strong>
                    <p style="margin: 0; color: #64748B; background: #F8FAFC; padding: 8px 12px; border-radius: 6px;">{{ $b->catatan }}</p>
                @endif

                @if($b->status !== 'pending')
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed #E2E8F0;">
                        <strong>Respons Anda:</strong>
                       <p style="margin: 4px 0 0 0; font-style: italic; color: #475569;">"{{ $b->catatan_dosen ?? '-' }}"</p>
                    </div>
                @endif
            </div>

            {{-- Form Aksi Hanya Muncul Jika Status Maskih Pending --}}
            @if($b->status === 'pending')
                <div class="bim-action-zone">
                    <strong style="margin-bottom: 6px;">Berikan Catatan atau Ruangan (Opsional):</strong>
                    
                    {{-- Form ini akan diarahkan secara dinamis menggunakan JavaScript tombol --}}
                    <form id="form-bimbingan-{{ $b->id }}" method="POST" action="">
                        @csrf
                        <input type="text" name="balasan" placeholder="Contoh: Ke ruangan lab IoT lantai 2 ya..." class="bim-input-balasan">
                        
                        <div class="bim-btn-group">
                            <button type="button" class="bim-btn btn-approve" onclick="submitAksi({{ $b->id }}, 'approve')"><i class="fa-solid fa-check"></i> Setujui</button>
                            <button type="button" class="bim-btn btn-reject" onclick="submitAksi({{ $b->id }}, 'reject')"><i class="fa-solid fa-xmark"></i> Tolak</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <div class="bim-empty">
            <p>Belum ada masuk permintaan bimbingan baru untuk Anda.</p>
        </div>
    @endforelse
</div>

<script>
    // Fungsi JavaScript untuk mengubah action form secara dinamis saat klik Approve/Reject
    function submitAksi(id, tipe) {
        const form = document.getElementById('form-bimbingan-' + id);
        if (tipe === 'approve') {
            form.action = `/dosen/bimbingan/${id}/approve`;
        } else if (tipe === 'reject') {
            if(!confirm('Apakah Anda yakin ingin menolak request bimbingan ini?')) return;
            form.action = `/dosen/bimbingan/${id}/reject`;
        }
        form.submit();
    }
</script>
@endsection