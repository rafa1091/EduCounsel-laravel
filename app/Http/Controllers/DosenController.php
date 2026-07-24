<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    private function getDosen()
    {
        return Dosen::where('user_id', Auth::id())->first();
    }

    // ================= DASHBOARD =================
    public function dashboard()
    {
        $dosen = $this->getDosen();

        $statistik = [
            'pending'  => \App\Models\Bimbingan::where('dosen_id', $dosen->id)->where('status', 'pending')->count(),
            'diterima' => \App\Models\Bimbingan::where('dosen_id', $dosen->id)->where('status', 'approved')->count(),
            'total'    => \App\Models\Bimbingan::where('dosen_id', $dosen->id)->count(),
        ];

        $html = <<<'HTML'
        @extends('layouts.app')

        @section('title', 'Dashboard Dosen - KAMPUS/presence')

        @section('content')

        <style>
            *, *::before, *::after { box-sizing: border-box; }

            .ds-hero {
                background: linear-gradient(135deg, #1E2A4A 0%, #2D3F6B 100%);
                padding: 2rem 2rem 3.5rem;
            }
            .ds-hero-inner { max-width: 960px; margin: 0 auto; }
            .ds-hero-label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #8AAEFB; text-transform: uppercase; margin-bottom: 0.4rem; }
            .ds-hero-name  { font-size: 26px; font-weight: 700; color: #fff; line-height: 1.3; margin-bottom: 4px; }
            .ds-hero-sub    { font-size: 13px; color: rgba(255,255,255,.45); margin-bottom: 1.5rem; }

            .ds-status-pill {
                display: inline-flex; align-items: center; gap: 6px;
                padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700;
                text-transform: uppercase; letter-spacing: 0.8px;
                background: rgba(255,255,255,.1); border: 0.5px solid rgba(255,255,255,.2); color: #fff;
            }
            .ds-status-dot { width: 7px; height: 7px; border-radius: 50%; }

            .ds-main { max-width: 960px; margin: -1.5rem auto 4rem; padding: 0 2rem; position: relative; z-index: 1; }
            .ds-grid { display: grid; grid-template-columns: 1fr 280px; gap: 16px; }
            @media (max-width: 700px) { .ds-grid { grid-template-columns: 1fr; } }

            .ds-card {
                background: #fff; border-radius: 16px; padding: 24px;
                border: 0.5px solid rgba(0,0,0,.07);
                box-shadow: 0 2px 10px rgba(30,42,74,.05);
            }
            .ds-card-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 16px; }

            .ds-status-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }

            .ds-status-card {
                border-radius: 12px; padding: 14px 10px; text-align: center;
                cursor: pointer; transition: all .2s;
                border: 0.5px solid #E2E8F0; background: #F8FAFC; color: #64748B;
            }
            .ds-status-card:hover { border-color: #CBD5E1; background: #F1F5F9; }
            .ds-status-card.active { background: #1E2A4A; border-color: #1E2A4A; color: #fff; }
            .ds-status-card .dot { width: 8px; height: 8px; border-radius: 50%; margin: 0 auto 8px; }
            .ds-status-card .label { font-size: 12px; font-weight: 600; }

            .ds-textarea {
                width: 100%; border: 0.5px solid #E2E8F0; border-radius: 10px;
                padding: 10px 14px; font-size: 13px; font-family: inherit;
                color: #1E2A4A; resize: none; outline: none;
                transition: border-color .15s; background: #fff;
            }
            .ds-textarea:focus { border-color: #4F7EF8; }
            .ds-textarea::placeholder { color: #94A3B8; }

            .ds-input-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 6px; display: block; }
            .ds-input {
                width: 100%; height: 40px; border: 0.5px solid #E2E8F0; border-radius: 10px;
                padding: 0 12px; font-size: 13px; font-family: inherit;
                color: #1E2A4A; outline: none; transition: border-color .15s; background: #fff;
            }
            .ds-input:focus { border-color: #4F7EF8; }

            .ds-btn {
                width: 100%; height: 42px; background: #1E2A4A; color: #fff;
                border: none; border-radius: 10px; font-size: 10px; font-weight: 700;
                text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer;
                font-family: inherit; transition: background .15s; margin-top: 16px;
            }
            .ds-btn:hover { background: #4F7EF8; }
            .ds-btn-outline {
                width: 100%; height: 42px; background: #F4F6FB; color: #1E2A4A;
                border: 0.5px solid #E2E8F0; border-radius: 10px; font-size: 10px; font-weight: 700;
                text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer;
                font-family: inherit; transition: all .15s; margin-top: 10px;
            }
            .ds-btn-outline:hover { background: #EEF3FE; border-color: #4F7EF8; color: #4F7EF8; }

            .ds-divider { border: none; border-top: 0.5px solid #F1F5F9; margin: 18px 0; }

            .ds-stats { display: flex; text-align: center; }
            .ds-stat-item { flex: 1; }
            .ds-stat-item + .ds-stat-item { border-left: 0.5px solid #F1F5F9; }
            .ds-stat-num   { font-size: 24px; font-weight: 800; color: #1E2A4A; }
            .ds-stat-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; margin-top: 2px; }

            .ds-alert { padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
            .ds-alert-success { background: #ECFDF5; color: #059669; border: 0.5px solid #A7F3D0; }
            .ds-alert-error   { background: #FEF2F2; color: #B91C1C; border: 0.5px solid #FECACA; }

            .ds-foto-preview { width: 100%; height: 80px; object-fit: cover; border-radius: 10px; border: 0.5px solid #E2E8F0; margin-bottom: 8px; }
            .ds-file-input { width: 100%; font-size: 12px; color: #64748B; }
        </style>

        {{-- HERO --}}
        <div class="ds-hero">
            <div class="ds-hero-inner">
                <div class="ds-hero-label">Dashboard Dosen</div>
                <div class="ds-hero-name">Halo, {{ $dosen->nama ?? Auth::user()->name }}</div>
                <div class="ds-hero-sub">Perbarui status agar mahasiswa tahu keberadaan Anda.</div>

                @php
                    $statusPill = [
                        'di_ruangan' => ['dot' => '#10B981', 'label' => 'Di Ruangan'],
                        'tidak_ada'  => ['dot' => '#EF4444', 'label' => 'Tidak Ada'],
                    ];

                    $currentStatus = $dosen->status ?? 'tidak_ada';
                    if (!array_key_exists($currentStatus, $statusPill)) {
                        $currentStatus = 'tidak_ada';
                    }

                    $pill = $statusPill[$currentStatus];
                @endphp
                <div class="ds-status-pill">
                    <span class="ds-status-dot" style="background: {{ $pill['dot'] }}"></span>
                    {{ $pill['label'] }}
                </div>
            </div>
        </div>

        {{-- MAIN --}}
        <div class="ds-main">

            @if(session('success'))
                <div class="ds-alert ds-alert-success">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="ds-alert ds-alert-error">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="ds-grid">

                {{-- KOLOM KIRI: STATUS --}}
                <div class="ds-card">
                    <div class="ds-card-label">Status Saya</div>

                    <form action="{{ route('dosen.status.update') }}" method="POST" id="formStatus">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" id="inputStatus" value="{{ in_array($dosen->status ?? '', ['di_ruangan', 'tidak_ada']) ? $dosen->status : 'tidak_ada' }}">

                        <div class="ds-status-grid">
                            <div id="card-di_ruangan" onclick="pilihStatus('di_ruangan', this)"
                                 class="ds-status-card {{ ($dosen->status ?? '') === 'di_ruangan' ? 'active' : '' }}">
                                <div class="dot" style="background:#10B981;"></div>
                                <div class="label">Di Ruangan</div>
                            </div>
                            <div id="card-tidak_ada" onclick="pilihStatus('tidak_ada', this)"
                                 class="ds-status-card {{ (($dosen->status ?? '') === 'tidak_ada' || !in_array($dosen->status ?? '', ['di_ruangan', 'tidak_ada'])) ? 'active' : '' }}">
                                <div class="dot" style="background:#EF4444;"></div>
                                <div class="label">Tidak Ada</div>
                            </div>
                        </div>

                        <div style="margin-bottom:16px;">
                            <label class="ds-input-label">Catatan Status (Opsional)</label>
                            <textarea name="catatan" rows="4" placeholder="Masukkan catatan..." class="ds-textarea">{{ $dosen->catatan ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="ds-btn">Simpan Status</button>
                    </form>
                </div>

                {{-- KOLOM KANAN: PROFIL & STATISTIK --}}
                <div>
                    <div class="ds-card" style="margin-bottom:16px;">
                        <div class="ds-card-label">Profil Saya</div>

                        <form action="{{ route('dosen.profil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div style="margin-bottom:12px;">
                                <label class="ds-input-label">Ruangan</label>
                                <input type="text" name="ruangan" value="{{ $dosen->ruangan ?? '' }}" class="ds-input" placeholder="mis. Ruang 301">
                                @error('ruangan')<small style="color:#EF4444;font-size:11px;margin-top:4px;display:block;">{{ $message }}</small>@enderror
                            </div>

                            <div style="margin-bottom:12px;">
                                <label class="ds-input-label">No. HP</label>
                                <input type="text" name="no_hp" value="{{ $dosen->no_hp ?? '' }}" class="ds-input" placeholder="08xxxxxxxxxx">
                                @error('no_hp')<small style="color:#EF4444;font-size:11px;margin-top:4px;display:block;">{{ $message }}</small>@enderror
                            </div>

                            <div style="margin-bottom:4px;">
                                <label class="ds-input-label">Foto Profil</label>
                                @if($dosen->foto ?? null)
                                    <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto" class="ds-foto-preview">
                                @endif
                                <input type="file" name="foto_profil" accept="image/*" class="ds-file-input">
                                @error('foto_profil')<small style="color:#EF4444;font-size:11px;margin-top:4px;display:block;">{{ $message }}</small>@enderror
                            </div>

                            <button type="submit" class="ds-btn">Simpan Profil</button>
                        </form>
                    </div>

                    {{-- STATISTIK --}}
                    <div class="ds-card">
                        <div class="ds-card-label">Statistik Bimbingan</div>
                        <div class="ds-stats">
                            <div class="ds-stat-item">
                                <div class="ds-stat-num">{{ $statistik['pending'] ?? 0 }}</div>
                                <div class="ds-stat-label">Pending</div>
                            </div>
                            <div class="ds-stat-item">
                                <div class="ds-stat-num">{{ $statistik['diterima'] ?? 0 }}</div>
                                <div class="ds-stat-label">Diterima</div>
                            </div>
                            <div class="ds-stat-item">
                                <div class="ds-stat-num">{{ $statistik['total'] ?? 0 }}</div>
                                <div class="ds-stat-label">Total</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
        function pilihStatus(status, el) {
            const statuses = ['di_ruangan', 'tidak_ada'];
            statuses.forEach(s => {
                const card = document.getElementById('card-' + s);
                if(card) card.classList.remove('active');
            });
            el.classList.add('active');
            document.getElementById('inputStatus').value = status;
            document.getElementById('formStatus').submit();
        }
        </script>
        @endsection
        HTML;

        return response(\Illuminate\Support\Facades\Blade::render($html, compact('dosen', 'statistik')));
    }

    // ================= JADWAL =================
    public function jadwal()
    {
        $dosen = $this->getDosen();

        if (!$dosen) {
            return redirect()->back()
                ->with('error','Data dosen tidak ditemukan.');
        }

        // ✅ dosen_id di tabel jadwals adalah FK ke users.id, jadi pakai Auth::id()
        $jadwal = Jadwal::where('dosen_id', Auth::id())
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat')")
            ->orderBy('mulai')
            ->get()
            ->toArray();

        $hariIni = Bimbingan::with('user')
            ->where('dosen_id',$dosen->id)
            ->where('status','approved')
            ->whereDate('tanggal',today())
            ->orderBy('jam')
            ->get();

        $pending = Bimbingan::with('user')
            ->where('dosen_id',$dosen->id)
            ->where('status','pending')
            ->orderBy('tanggal')
            ->get();

        $totalApproved = Bimbingan::where('dosen_id',$dosen->id)
            ->where('status','approved')
            ->count();

        $totalPending = Bimbingan::where('dosen_id',$dosen->id)
            ->where('status','pending')
            ->count();

        $totalRejected = Bimbingan::where('dosen_id',$dosen->id)
            ->where('status','rejected')
            ->count();

        $dosenStatus = $dosen->status;

        $html = <<<'HTML'
        @extends('layouts.app')
        @section('title', 'Aktivitas Saya')
        @section('content')
        <style>
            *, *::before, *::after { box-shadow: none; box-sizing: border-box; }
            .akt-hero { background: linear-gradient(135deg, #1E2A4A 0%, #2D3F6B 100%); padding: 2rem 2rem 3.5rem; }
            .akt-hero-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
            .akt-hero-label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #8AAEFB; text-transform: uppercase; margin-bottom: 0.4rem; }
            .akt-hero-title { font-size: 26px; font-weight: 700; color: #fff; line-height: 1.3; }
            .akt-status-wrap { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
            .akt-status-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; color: #8AAEFB; text-transform: uppercase; }
            .akt-status-btns { display: flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end; }
            .akt-status-btn { font-size: 10px; font-weight: 700; padding: 6px 14px; border-radius: 20px; border: 1.5px solid rgba(255,255,255,.2); background: rgba(255,255,255,.08); color: rgba(255,255,255,.6); cursor: pointer; font-family: inherit; transition: all .15s; text-transform: uppercase; letter-spacing: 0.5px; }
            .akt-status-btn.active-di_ruangan { background: #10B981; border-color: #10B981; color: #fff; }
            .akt-status-btn.active-mengajar { background: #F59E0B; border-color: #F59E0B; color: #fff; }
            .akt-status-btn.active-bimbingan { background: #4F7EF8; border-color: #4F7EF8; color: #fff; }
            .akt-status-btn.active-tidak_ada { background: #64748B; border-color: #64748B; color: #fff; }
            .akt-main { max-width: 1200px; margin: -1.5rem auto 4rem; padding: 0 2rem; position: relative; z-index: 1; }
            .jdw-form-card { background: #fff; border-radius: 16px; padding: 24px; border: 0.5px solid rgba(0,0,0,.07); box-shadow: 0 4px 20px rgba(30,42,74,.05); margin-bottom: 24px; }
            .jdw-form-label-section { font-size: 13px; font-weight: 700; color: #1E2A4A; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px; }
            .jdw-form-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 20px; }
            .jdw-btn-add { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; height: 42px; background: #1E2A4A; color: #fff; border: none; border-radius: 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; }
            .jdw-field-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 6px; display: block; }
            .jdw-input, .jdw-select { width: 100%; height: 40px; border: 0.5px solid #E2E8F0; border-radius: 10px; padding: 0 12px; font-size: 13px; color: #1E2A4A; background: #fff; }
            .jdw-week-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px; width: 100%; }
            .jdw-day-col { display: flex; flex-direction: column; gap: 8px; }
            .jdw-day-header { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #1E2A4A; border-bottom: 2px solid #E2E8F0; padding-bottom: 6px; }
            .jdw-slot { background: #fff; border-radius: 12px; padding: 12px; border: 0.5px solid rgba(0,0,0,.07); position: relative; }
            .jdw-empty { background: #fff; border-radius: 12px; padding: 24px; text-align: center; border: 0.5px dashed #CBD5E1; font-size: 12px; color: #94A3B8; }
            .akt-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 32px; }
            .akt-stat { background: #fff; border-radius: 16px; padding: 20px; border: 0.5px solid rgba(0,0,0,.07); }
            .akt-stat-num { font-size: 32px; font-weight: 800; color: #1E2A4A; }
            .akt-stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94A3B8; }
        </style>

        <div class="akt-hero">
            <div class="akt-hero-inner">
                <div>
                    <div class="akt-hero-label">Dosen</div>
                    <div class="akt-hero-title">Aktivitas Saya</div>
                </div>
            </div>
        </div>

        <div class="akt-main">
            @if(session('success'))
                <div style="padding: 10px 14px; background: #ECFDF5; color: #059669; border: 0.5px solid #A7F3D0; border-radius: 10px; font-size: 12px; font-weight: 600; margin-bottom: 16px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="jdw-form-card">
                <div class="jdw-form-label-section">Tambah Jadwal Baru</div>
                <form action="{{ route('dosen.storeJadwal') }}" method="POST">
                    @csrf
                    <div class="jdw-form-grid">
                        <div>
                            <label class="jdw-field-label">Hari</label>
                            <select name="hari" class="jdw-select" required>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)<option value="{{$h}}">{{$h}}</option>@endforeach
                            </select>
                        </div>
                        <div><label class="jdw-field-label">Mulai</label><input type="time" name="mulai" class="jdw-input" required></div>
                        <div><label class="jdw-field-label">Selesai</label><input type="time" name="selesai" class="jdw-input" required></div>
                        <div>
                            <label class="jdw-field-label">Aktifitas</label>
                            <select name="aktifitas" class="jdw-select" required>
                                <option value="mengajar">Mengajar</option><option value="bimbingan">Bimbingan</option>
                            </select>
                        </div>
                        <div><label class="jdw-field-label">Matakuliah</label><input type="text" name="matakuliah" class="jdw-input"></div>
                    </div>
                    <button type="submit" class="jdw-btn-add">Tambah Jadwal</button>
                </form>
            </div>

            <div class="jdw-week-grid">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                <div class="jdw-day-col">
                    <div class="jdw-day-header">{{ $hari }}</div>

                    @php
                        $jadwalHariIni = array_filter($jadwal, function($j) use ($hari) {
                            return $j['hari'] === $hari;
                        });
                    @endphp

                    @if(count($jadwalHariIni) > 0)
                        @foreach($jadwalHariIni as $item)
                            <div class="jdw-slot" style="margin-bottom: 8px; border-left: 4px solid #1E2A4A;">
                                <div style="font-size: 13px; font-weight: 700; color: #1E2A4A;">
                                    {{ $item['matakuliah'] ?? 'Aktivitas' }}
                                </div>
                                <div style="font-size: 11px; color: #64748B; text-transform: capitalize; margin: 2px 0;">
                                    📌 {{ $item['aktivitas'] }}
                                </div>
                                <div style="font-size: 11px; font-weight: 600; color: #4F7EF8;">
                                    🕒 {{ substr($item['mulai'], 0, 5) }} - {{ substr($item['selesai'], 0, 5) }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="jdw-empty">Kosong</div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="akt-stats">
                <div class="akt-stat"><div class="akt-stat-num">{{ $totalApproved }}</div><div class="akt-stat-label">Disetujui</div></div>
                <div class="akt-stat"><div class="akt-stat-num">{{ $totalPending }}</div><div class="akt-stat-label">Menunggu</div></div>
                <div class="akt-stat"><div class="akt-stat-num">{{ $totalRejected }}</div><div class="akt-stat-label">Ditolak</div></div>
            </div>
        </div>

        <script>

        function refreshStatus() {

            fetch('/api/dosen/status')
                .then(res => res.json())
                .then(data => {

                    const dosen = data.find(d => d.ruangan == '704');

                    if (!dosen) return;

                    const heroBadge = document.querySelector('.ds-status-pill');

                    if (dosen.status === 'di_ruangan') {

                        heroBadge.innerHTML =
                            '<span class="ds-status-dot" style="background:#10B981"></span>Di Ruangan';

                        document
                            .getElementById('card-di_ruangan')
                            .classList.add('active');

                        document
                            .getElementById('card-tidak_ada')
                            .classList.remove('active');

                    } else {

                        heroBadge.innerHTML =
                            '<span class="ds-status-dot" style="background:#EF4444"></span>Tidak Ada';

                        document
                            .getElementById('card-di_ruangan')
                            .classList.remove('active');

                        document
                            .getElementById('card-tidak_ada')
                            .classList.add('active');

                    }

                });

        }

        setInterval(refreshStatus, 3000);

        </script>

        @endsection
        HTML;

        return response(\Illuminate\Support\Facades\Blade::render($html, compact(
            'jadwal', 'hariIni', 'pending', 'totalApproved', 'totalPending', 'totalRejected', 'dosenStatus'
        )));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'hari'=>'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'mulai'=>'required',
            'selesai'=>'required',
            'aktifitas'=>'required',
            'matakuliah'=>'nullable|string'
        ]);

        $dosen = $this->getDosen();

        if(!$dosen){
            return redirect()->back()->with('error','Data dosen tidak ditemukan.');
        }

        // ✅ dosen_id FK ke users.id
        Jadwal::create([
            'dosen_id'    => Auth::id(),
            'hari'        => $request->hari,
            'mulai'       => $request->mulai,
            'selesai'     => $request->selesai,
            'aktivitas'   => $request->aktifitas,
            'matakuliah'  => $request->matakuliah
        ]);

        return redirect()->route('dosen.jadwal')->with('success','Jadwal berhasil ditambahkan.');
    }

    // ✅ DITAMBAHKAN KEMBALI — method ini hilang di file yang kamu kirim
    public function destroyJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->dosen_id != Auth::id()) {
            abort(403);
        }

        $jadwal->delete();

        return redirect()
            ->route('dosen.jadwal')
            ->with('success','Jadwal berhasil dihapus.');
    }

    // ================= BIMBINGAN =================
    public function bimbingan(Request $request)
    {
        $dosen = $this->getDosen();

        if (!$dosen) {
            return redirect()->back()
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        $status = strtoupper($request->get('status', 'SEMUA'));

        $query = Bimbingan::with('user')
            ->where('dosen_id', $dosen->id)
            ->latest();

        if ($status !== 'SEMUA') {
            $query->where('status', strtolower($status));
        }

        $bimbingans = $query->get();

        return view('layouts.dosen.bimbingan', compact(
            'bimbingans',
            'status'
        ));
    }

    // ── APPROVE BIMBINGAN ──
    public function approve(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'nullable|string'
        ]);

        $dosen = $this->getDosen();

        Bimbingan::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail()
            ->update([
                'status' => 'approved',
                'catatan_dosen' => $request->balasan
            ]);

        return redirect()
            ->route('dosen.bimbingan')
            ->with('success','Bimbingan berhasil disetujui.');
    }

    // ── REJECT BIMBINGAN ──
    public function reject(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string'
        ]);

        $dosen = $this->getDosen();

        Bimbingan::where('id',$id)
            ->where('dosen_id',$dosen->id)
            ->firstOrFail()
            ->update([
                'status'=>'rejected',
                'catatan_dosen'=>$request->balasan
            ]);

        return redirect()
            ->route('dosen.bimbingan')
            ->with('success','Bimbingan berhasil ditolak.');
    }

    // ================= STATUS =================
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:di_ruangan,tidak_ada',
            'catatan' => 'nullable|string'
        ]);

        $dosen = $this->getDosen();

        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Data dosen tidak ditemukan.');
        }

        $dosen->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Status keberadaan berhasil diperbarui.');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'ruangan'      => 'nullable|string|max:50',
            'no_hp'        => 'nullable|string|max:20',
            'foto_profil'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $dosen = $this->getDosen();

        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $dosen->ruangan = $request->ruangan;
        $dosen->no_hp   = $request->no_hp;

        if ($request->hasFile('foto_profil')) {

            if ($dosen->foto && \Storage::disk('public')->exists($dosen->foto)) {
                \Storage::disk('public')->delete($dosen->foto);
            }

            $path = $request->file('foto_profil')->store('foto-dosen', 'public');
            $dosen->foto = $path;
        }

        $dosen->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function statusApi()
    {
        $dosen = Dosen::select(
            'id',
            'nama',
            'status',
            'ruangan',
            'no_hp',
            'foto',
            'catatan'
        )->get();

        return response()->json($dosen);
    }
}