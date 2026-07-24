@extends('layouts.app')

@section('content')

<style>
    *, *::before, *::after { box-sizing: border-box; }

    .kp-hero { background: linear-gradient(135deg, #1E2A4A 0%, #2D3F6B 100%); padding: 2rem 2rem 3.5rem; }
    .kp-hero-inner { max-width: 960px; margin: 0 auto; }
    .kp-hero-label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #8AAEFB; text-transform: uppercase; margin-bottom: 0.5rem; }
    .kp-hero-title { font-size: 26px; font-weight: 700; color: #fff; margin-bottom: 1.5rem; line-height: 1.35; }
    .kp-stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .kp-stat { background: rgba(255,255,255,.07); border: 0.5px solid rgba(255,255,255,.12); border-radius: 12px; padding: 14px 16px; cursor: pointer; transition: background .2s; text-align: left; }
    .kp-stat:hover { background: rgba(255,255,255,.12); }
    .kp-stat.active { background: #4F7EF8; border-color: #4F7EF8; }
    .kp-stat-num { font-size: 28px; font-weight: 800; color: #fff; line-height: 1; }
    .kp-stat-label { font-size: 10px; color: rgba(255,255,255,.5); letter-spacing: 1px; text-transform: uppercase; margin-top: 6px; font-weight: 600; }
    .kp-stat.active .kp-stat-label { color: rgba(255,255,255,.85); }

    .kp-main { max-width: 960px; margin: -1.5rem auto 4rem; padding: 0 2rem; position: relative; z-index: 1; }

    .kp-filter-bar { background: #fff; border-radius: 14px; padding: 10px; display: flex; align-items: center; gap: 8px; margin-bottom: 1.5rem; border: 0.5px solid rgba(0,0,0,.08); box-shadow: 0 2px 12px rgba(30,42,74,.06); flex-wrap: wrap; }
    .kp-search { flex: 1; min-width: 180px; display: flex; align-items: center; gap: 8px; background: #F4F6FB; border-radius: 8px; padding: 8px 12px; }
    .kp-search svg { color: #8A9BBF; width: 14px; height: 14px; flex-shrink: 0; }
    .kp-search input { border: none; background: transparent; font-size: 12px; color: #1E2A4A; outline: none; width: 100%; }
    .kp-search input::placeholder { color: #94A3B8; }
    .kp-chips { display: flex; gap: 6px; flex-wrap: wrap; }
    .kp-chip { font-size: 11px; font-weight: 600; padding: 6px 14px; border-radius: 20px; cursor: pointer; border: 0.5px solid #E2E8F0; color: #64748B; background: #fff; transition: all .15s; text-transform: uppercase; letter-spacing: 0.5px; }
    .kp-chip:hover { background: #F4F6FB; border-color: #CBD5E1; }
    .kp-chip.active { background: #EEF3FE; border-color: #4F7EF8; color: #2B5BD4; }

    .kp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    @media (max-width: 700px)  { .kp-grid { grid-template-columns: 1fr; } }
    @media (max-width: 960px) and (min-width: 701px) { .kp-grid { grid-template-columns: repeat(2, 1fr); } }

    .kp-card { background: #fff; border-radius: 16px; padding: 18px; border: 0.5px solid rgba(0,0,0,.07); box-shadow: 0 2px 10px rgba(30,42,74,.05); transition: transform .2s, box-shadow .2s; position: relative; overflow: hidden; cursor: pointer; display: flex; flex-direction: column; }
    .kp-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(30,42,74,.1); }
    .kp-card:active { transform: scale(0.99); }
    .kp-card-accent { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 16px 16px 0 0; }
    .accent-green { background: #10B981; }
    .accent-amber { background: #F59E0B; }
    .accent-red    { background: #EF4444; }
    .accent-blue  { background: #4F7EF8; }

    .kp-card-top { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .kp-avatar { width: 46px; height: 46px; border-radius: 12px; overflow: hidden; background: #E8EEF8; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 17px; font-weight: 700; color: #4F7EF8; }
    .kp-avatar img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(30%); }
    .kp-card-name { font-size: 14px; font-weight: 700; color: #1E2A4A; line-height: 1.3; }
    .kp-card-role { font-size: 10px; color: #94A3B8; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; }

    .kp-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 20px; margin-bottom: 8px; text-transform: uppercase; }
    .kp-badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .badge-green { background: #ECFDF5; color: #059669; }
    .badge-green .kp-badge-dot { background: #10B981; }
    .badge-amber { background: #FFFBEB; color: #B45309; }
    .badge-amber .kp-badge-dot { background: #F59E0B; }
    .badge-red    { background: #FEF2F2; color: #B91C1C; }
    .badge-red .kp-badge-dot    { background: #EF4444; }
    .badge-blue  { background: #EEF3FE; color: #2B5BD4; }
    .badge-blue .kp-badge-dot  { background: #4F7EF8; }

    .kp-requested-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px; background: #F0FDF4; color: #15803D; border: 0.5px solid #BBF7D0; margin-bottom: 8px; margin-left: 6px; }
    .kp-requested-badge svg { width: 10px; height: 10px; }

    .kp-card-info { display: flex; flex-direction: column; gap: 5px; margin-bottom: auto; }
    .kp-card-row  { display: flex; align-items: center; gap: 6px; font-size: 11px; color: #64748B; font-weight: 600; }
    .kp-card-row svg { width: 12px; height: 12px; color: #94A3B8; flex-shrink: 0; }
    .kp-card-note { font-size: 11px; color: #94A3B8; font-style: italic; background: #F8FAFC; border-radius: 6px; padding: 5px 8px; margin-top: 4px; border-left: 2px solid #E2E8F0; }

    /* Button Styles di Card */
    .btn-action-group { display: flex; gap: 8px; margin-top: 14px; }
    .kp-btn { flex: 1.5; padding: 9px 0; border-radius: 10px; font-size: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; border: 0.5px solid #E2E8F0; background: #F4F6FB; color: #4F7EF8; transition: all .15s; text-transform: uppercase; letter-spacing: 0.5px; font-family: inherit; }
    .kp-btn:hover { background: #EEF3FE; border-color: #4F7EF8; }
    .kp-btn.requested { background: #F0FDF4; border-color: #BBF7D0; color: #15803D; }
    .kp-btn.requested:hover { background: #DCFCE7; }
    .kp-btn svg { width: 13px; height: 13px; }
    
    .btn-jadwal { flex: 1; padding: 9px 0; border-radius: 10px; font-size: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; border: 0.5px solid #CBD5E1; background: #fff; color: #475569; transition: all .15s; text-transform: uppercase; letter-spacing: 0.5px; font-family: inherit; }
    .btn-jadwal:hover { background: #F8FAFC; border-color: #94A3B8; color: #1E2A4A; }

    .kp-empty { grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; color: #94A3B8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

    /* Modal Form Request Bimbingan */
    .kp-modal-overlay { position: fixed; inset: 0; z-index: 60; background: rgba(0,0,0,.55); display: flex; align-items: center; justify-content: center; }
    .kp-modal { background: #fff; width: 100%; max-width: 460px; margin: 1rem; padding: 2rem; position: relative; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
    .kp-modal-close { position: absolute; top: 16px; right: 16px; width: 28px; height: 28px; border-radius: 50%; border: none; background: #F4F6FB; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; color: #64748B; }
    .kp-modal-close:hover { background: #E2E8F0; color: #1E2A4A; }
    .kp-modal-close svg { width: 14px; height: 14px; }
    .kp-modal-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #8AAEFB; margin-bottom: 4px; }
    .kp-modal-title { font-size: 22px; font-weight: 800; color: #1E2A4A; margin-bottom: 4px; line-height: 1.2; }
    .kp-modal-sub    { font-size: 12px; color: #64748B; margin-bottom: 1.5rem; }
    .kp-modal-sub span { font-weight: 700; color: #1E2A4A; }
    .kp-field-row { display: flex; gap: 12px; margin-bottom: 12px; }
    .kp-field { flex: 1; }
    .kp-label { display: block; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 6px; }
    .kp-input { width: 100%; height: 40px; border: 0.5px solid #E2E8F0; border-radius: 10px; padding: 0 12px; font-size: 13px; font-family: inherit; outline: none; color: #1E2A4A; transition: border-color .15s; background: #fff; }
    .kp-input:focus { border-color: #4F7EF8; }
    .kp-textarea { width: 100%; border: 0.5px solid #E2E8F0; border-radius: 10px; padding: 10px 12px; font-size: 13px; font-family: inherit; outline: none; color: #1E2A4A; resize: none; transition: border-color .15s; background: #fff; }
    .kp-textarea:focus { border-color: #4F7EF8; }
    .kp-error { color: #EF4444; font-size: 11px; margin-bottom: 10px; font-weight: 600; }
    .kp-submit { width: 100%; height: 44px; background: #1E2A4A; color: #fff; border: none; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; font-family: inherit; transition: background .15s; }
    .kp-submit:hover { background: #4F7EF8; }

    /* Modal Jadwal Dosen */
    .modal-jadwal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
    .modal-jadwal-content { width: 100%; max-width: 500px; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.2); display: flex; flex-direction: column; max-height: 80vh; }
    .modal-jadwal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; background: #1E2A4A; color: white; }
    .modal-jadwal-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
    .modal-jadwal-close { cursor: pointer; font-size: 24px; line-height: 1; color: rgba(255,255,255,.7); transition: color .15s; }
    .modal-jadwal-close:hover { color: #fff; }
    .modal-jadwal-body { padding: 0; overflow-y: auto; background: #F8FAFC; }
    
    .schedule-item { padding: 16px 24px; border-bottom: 1px solid #E2E8F0; background: #fff; display: flex; flex-direction: column; gap: 4px; }
    .schedule-item:last-child { border-bottom: none; }
    .sched-day-time { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .sched-day { font-size: 14px; font-weight: 700; color: #1E2A4A; }
    .sched-time { font-size: 11px; font-weight: 700; color: #4F7EF8; background: #EEF3FE; padding: 4px 10px; border-radius: 20px; }
    .sched-act { font-size: 13px; font-weight: 600; color: #475569; }
    .sched-mk { font-size: 12px; color: #94A3B8; }

    /* Toast & Chatbot CSS biarkan sama... */
    .kp-toast { position: fixed; top: 20px; right: 20px; z-index: 70; background: #1E2A4A; color: #fff; padding: 12px 20px; border-radius: 12px; font-size: 12px; font-weight: 700; box-shadow: 0 8px 24px rgba(30,42,74,.25); border-left: 3px solid #10B981; display: flex; align-items: center; gap: 8px; }
    .kp-toast svg { width: 14px; height: 14px; color: #10B981; }
    
    .chat-fab { position: fixed; bottom: 28px; right: 28px; z-index: 50; width: 48px; height: 48px; background: #1E2A4A; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(30,42,74,.3); transition: transform .2s, background .2s; }
    .chat-fab:hover { background: #4F7EF8; transform: scale(1.08); }
    .chat-fab svg { width: 20px; height: 20px; }
    .chat-window { position: fixed; bottom: 88px; right: 28px; z-index: 50; width: 340px; height: 480px; background: #fff; border: 0.5px solid rgba(0,0,0,.1); border-radius: 20px; box-shadow: 0 16px 48px rgba(30,42,74,.15); display: flex; flex-direction: column; overflow: hidden; }
    .chat-header { background: #1E2A4A; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
    .chat-header-left { display: flex; align-items: center; gap: 10px; }
    .chat-icon { width: 30px; height: 30px; background: #4F7EF8; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
    .chat-title    { font-size: 13px; font-weight: 700; color: #fff; }
    .chat-subtitle { font-size: 10px; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: 0.8px; }
    .chat-close    { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.5); transition: color .15s; }
    .chat-close:hover { color: #fff; }
    .chat-close svg { width: 16px; height: 16px; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; font-size: 12px; }
    .chat-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; text-align: center; color: #94A3B8; }
    .chat-empty-icon { width: 44px; height: 44px; background: #EEF3FE; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 10px; }
    .chat-empty-title { font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 4px; }
    .chat-empty-sub   { font-size: 11px; color: #94A3B8; line-height: 1.5; }
    .chat-input-row { border-top: 0.5px solid #E2E8F0; padding: 10px; display: flex; gap: 8px; flex-shrink: 0; }
    .chat-input { flex: 1; font-size: 12px; border: 0.5px solid #E2E8F0; border-radius: 10px; padding: 8px 12px; outline: none; font-family: inherit; color: #1E2A4A; transition: border-color .15s; }
    .chat-input:focus { border-color: #4F7EF8; }
    .chat-send { width: 36px; height: 36px; background: #1E2A4A; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background .15s; }
    .chat-send:hover { background: #4F7EF8; }
    .chat-send svg { width: 14px; height: 14px; }
    .msg-user { margin-left: auto; background: #1E2A4A; color: #fff; padding: 8px 12px; border-radius: 14px 14px 2px 14px; max-width: 85%; line-height: 1.5; }
    .msg-bot  { background: #F4F6FB; color: #1E2A4A; padding: 8px 12px; border-radius: 14px 14px 14px 2px; max-width: 85%; line-height: 1.5; }
    .msg-typing { opacity: 0.6; animation: pulse 1.2s ease-in-out infinite; }
    @keyframes pulse { 0%,100%{opacity:.6} 50%{opacity:1} }
    .msg-typing-dots { display: flex; gap: 4px; align-items: center; padding: 2px 0; }
    .msg-typing-dots span { width: 6px; height: 6px; border-radius: 50%; background: #94A3B8; animation: typingBounce 1.2s infinite ease-in-out; }
    .msg-typing-dots span:nth-child(2) { animation-delay: 0.15s; }
    .msg-typing-dots span:nth-child(3) { animation-delay: 0.3s; }
    .schedule-item {
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 10px;
    }
    .sched-day { font-weight: bold; color: #4a5568; }
    .sched-time { color: #718096; font-size: 0.9em; }
    @keyframes typingBounce { 0%,60%,100% { transform: translateY(0); opacity: 0.5; } 30% { transform: translateY(-4px); opacity: 1; } }
</style>

{{-- HERO --}}
<div class="kp-hero">
    <div class="kp-hero-inner">
        <div class="kp-hero-label">Direktori Dosen</div>
        <div class="kp-hero-title">Siapa yang ada di kampus<br>hari ini?</div>
        <div class="kp-stats-row">
            <button onclick="setStatFilter(this,'di_ruangan')" class="kp-stat active" id="statBtn-di_ruangan">
                <div class="kp-stat-num" id="statDiRuangan">0</div>
                <div class="kp-stat-label">Di Ruangan</div>
            </button>
            <button onclick="setStatFilter(this,'tidak_ada')" class="kp-stat" id="statBtn-tidak_ada">
                <div class="kp-stat-num" id="statTidakAda">0</div>
                <div class="kp-stat-label">Tidak Ada</div>
            </button>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="kp-main">
    {{-- CARD DETAIL DOSEN PEMBIMBING MAHASISWA --}}
    <div class="card-status-dosen" style="background: #fff; border-radius: 16px; padding: 20px; margin-bottom: 1.5rem; border: 0.5px solid rgba(0,0,0,.07); box-shadow: 0 2px 12px rgba(30,42,74,.05);">
        <h4 style="font-size: 14px; font-weight: 700; color: #1E2A4A; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-thumbtack"></i> Status Dosen Pembimbing Kamu
        </h4>
        @if($dosenPembimbing)
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #64748B;">
                <p><strong>Nama Dosen:</strong> <span style="color: #1E2A4A; font-weight: 600;">{{ $dosenPembimbing->nama }}</span></p>
                <p style="display: flex; align-items: center; gap: 4px;"><strong>Posisi Saat Ini:</strong>
                    <span class="kp-badge {{ $dosenPembimbing->status === 'di_ruangan' ? 'badge-green' : ($dosenPembimbing->status === 'mengajar' ? 'badge-amber' : ($dosenPembimbing->status === 'bimbingan' ? 'badge-blue' : 'badge-red')) }}" style="margin-bottom: 0; margin-left: 4px;">
                        <div class="kp-badge-dot"></div>
                        {{ ucwords(str_replace('_', ' ', $dosenPembimbing->status)) }}
                    </span>
                </p>
                <p><strong>Ruangan:</strong> <span style="color: #1E2A4A; font-weight: 500;">{{ $dosenPembimbing->ruangan ?? 'Belum diatur' }}</span></p>
                <div style="margin-top: 4px;">
                    <strong style="display: block; margin-bottom: 4px;">Catatan Dosen:</strong>
                    <div style="font-style: italic; background: #F8FAFC; border-radius: 8px; padding: 10px 12px; border-left: 3px solid #4F7EF8; color: #475569; font-size: 12px; line-height: 1.5;">
                        "{{ $dosenPembimbing->catatan ?? 'Tidak ada catatan terbaru dari dosen.' }}"
                    </div>
                </div>
            </div>
        @else
            <p style="font-size: 12px; color: #94A3B8; font-style: italic;">Kamu belum memiliki atau belum dikaitkan dengan Dosen Pembimbing.</p>
        @endif
    </div>

    {{-- FILTER DIRECTORY BAR --}}
    <div class="kp-filter-bar">
        <div class="kp-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama atau ruangan...">
        </div>
        <div class="kp-chips">
            <button onclick="setFilter(this,'semua')"       class="kp-chip active filter-btn">Semua</button>
            <button onclick="setFilter(this,'di_ruangan')"  class="kp-chip filter-btn">Di Ruangan</button>
            <button onclick="setFilter(this,'tidak_ada')"   class="kp-chip filter-btn">Tidak Ada</button>
        </div>
    </div>
    
    <div class="kp-grid" id="dosenGrid"></div>
</div>

{{-- MODAL REQUEST BIMBINGAN --}}
<div id="modalOverlay" class="kp-modal-overlay" style="display:none;">
    <div class="kp-modal">
        <button onclick="closeModal()" class="kp-modal-close">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <div class="kp-modal-label">Request Bimbingan</div>
        <div class="kp-modal-title">Ajukan jadwal bimbingan</div>
        <div class="kp-modal-sub">dengan <span id="modalDosenName"></span></div>
        
        <div id="modalAlreadyRequested" style="display:none; background:#FFF7ED; border:0.5px solid #FED7AA; border-radius:10px; padding:10px 12px; margin-bottom:14px; font-size:12px; color:#C2410C; font-weight:600;">
            <svg style="display:inline;width:13px;height:13px;margin-right:4px;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Kamu sudah pernah request ke dosen ini dan masih pending. Yakin mau request lagi?
        </div>
        
        <div class="kp-field-row">
            <div class="kp-field">
                <label class="kp-label">Tanggal</label>
                <input type="date" id="inputTanggal" class="kp-input">
            </div>
            <div class="kp-field">
                <label class="kp-label">Jam</label>
                <input type="time" id="inputJam" class="kp-input">
            </div>
        </div>
        <div style="margin-bottom:12px;">
            <label class="kp-label">Topik</label>
            <input type="text" id="inputTopik" placeholder="mis. Bab 3 skripsi" class="kp-input">
        </div>
        <div style="margin-bottom:20px;">
            <label class="kp-label">Catatan</label>
            <textarea id="inputCatatan" rows="3" class="kp-textarea"></textarea>
        </div>
        
        <div id="modalError" class="kp-error" style="display:none;"></div>
        <button onclick="submitRequest()" class="kp-submit">Kirim Request</button>
    </div>
</div>

{{-- MODAL LIHAT JADWAL --}}
<div id="scheduleModal" class="modal-jadwal-overlay" onclick="if(event.target===this) closeSchedule()">
    <div class="modal-jadwal-content">
        <div class="modal-jadwal-header">
            <h3 id="modalTitleJadwal">Jadwal Dosen</h3>
            <span class="modal-jadwal-close" onclick="closeSchedule()">&times;</span>
        </div>
        <div id="scheduleBody" class="modal-jadwal-body">
            <div style="padding:40px; text-align:center; color:#94A3B8; font-size:13px; font-weight:600;">
                Loading...
            </div>
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="toast" class="kp-toast" style="display:none;">
    <svg fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    <span id="toastMsg"></span>
</div>

{{-- CHATBOT FAB & WINDOW --}}
<button id="chatFab" onclick="toggleChat()" class="chat-fab">
    <svg fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
</button>

<div id="chatWindow" class="chat-window" style="display:none;">
    <div class="chat-header">
        <div class="chat-header-left">
            <div class="chat-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
            <div>
                <div class="chat-title">Asisten Kampus</div>
                <div class="chat-subtitle">AI · Online</div>
            </div>
        </div>
        <button onclick="toggleChat()" class="chat-close">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div id="chatMessages" class="chat-messages">
        <div id="chatEmptyState" class="chat-empty">
            <div class="chat-empty-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
            <div class="chat-empty-title">Asisten Kampus</div>
            <div class="chat-empty-sub">Tanyakan tentang dosen,<br>jadwal, atau bimbingan</div>
        </div>
    </div>
    <div class="chat-input-row">
        <input id="chatInput" type="text" placeholder="Tanya apa saja..." class="chat-input" onkeydown="if(event.key==='Enter') sendChat()">
        <button onclick="sendChat()" class="chat-send">
            <svg fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </div>
</div>

<script>
    // ── DATA DOSEN dari Laravel ──
    const rawData = @json($dosens ?? []);
    console.log(rawData);
    let dosenData = rawData.map(d => ({
        id:      d.id,
        user_id: d.user_id,
        nama:    d.nama,
        status:  d.status,
        ruangan: d.ruangan,
        no_hp:   d.no_hp,
        foto:    d.foto,
        catatan: d.catatan,
    }));

    const requestedDosenIds = @json(
        \App\Models\Bimbingan::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->pluck('dosen_id')
    );
    
    let currentDosen  = null;
    let currentFilter = 'semua';
    
    const statusConfig = {
        'di_ruangan': { badge:'badge-green', accent:'accent-green', label:'Di Ruangan'       },
        'mengajar':   { badge:'badge-amber', accent:'accent-amber', label:'Sedang Mengajar'  },
        'bimbingan':  { badge:'badge-blue',  accent:'accent-blue',  label:'Sedang Bimbingan' },
        'tidak_ada':  { badge:'badge-red',   accent:'accent-red',   label:'Tidak Ada'        },
    };

    function renderCards(list) {
        const grid = document.getElementById('dosenGrid');
        if (!list.length) {
            grid.innerHTML = '<div class="kp-empty">Tidak ada dosen ditemukan</div>';
            return;
        }
        
        grid.innerHTML = list.map(d => {
            const s          = statusConfig[d.status] || statusConfig['tidak_ada'];
            const initials   = d.nama.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
            const avatarHtml = d.foto
                ? '<img src="/storage/' + d.foto + '" alt="' + d.nama + '">'
                : '<span>' + initials + '</span>';
            const telpHtml   = d.no_hp
                ? '<div class="kp-card-row"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.18 2 2 0 012.22 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.66-.66a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>' + d.no_hp + '</div>'
                : '';
            const ruanganHtml = d.ruangan
                ? '<div class="kp-card-row"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>' + d.ruangan + '</div>'
                : '';
            const catatanHtml = d.catatan
                ? '<div class="kp-card-note">"' + d.catatan + '"</div>'
                : '';
                
            const isRequested = requestedDosenIds.includes(d.id);
            const requestedBadge = isRequested
                ? '<span class="kp-requested-badge"><svg fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Sudah direquest</span>'
                : '';
            const btnClass = isRequested ? 'kp-btn requested' : 'kp-btn';
            const btnIcon  = isRequested
                ? '<svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>'
                : '<svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
            const btnLabel = isRequested ? 'Request Lagi' : 'Bimbingan';

            return '<div class="kp-card" data-status="' + d.status + '" onclick="openModal(' + d.id + ')">'
                + '<div class="kp-card-accent ' + s.accent + '"></div>'
                + '<div class="kp-card-top"><div class="kp-avatar">' + avatarHtml + '</div>'
                + '<div><div class="kp-card-role">Dosen</div><div class="kp-card-name">' + d.nama + '</div></div></div>'
                + '<div style="display:flex;align-items:center;flex-wrap:wrap;gap:4px;margin-bottom:8px;">'
                + '<div class="kp-badge ' + s.badge + '"><div class="kp-badge-dot"></div>' + s.label + '</div>'
                + requestedBadge
                + '</div>'
                + '<div class="kp-card-info">' + ruanganHtml + telpHtml + catatanHtml + '</div>'
                
                // --- BAGIAN BUTTON YANG DIUPDATE ---
                + '<div class="btn-action-group">'
                    + '<button type="button" class="btn-jadwal" onclick="event.stopPropagation(); openSchedule(' + d.user_id + ', \'' + d.nama + '\')"><i class="fa-regular fa-calendar"></i> Jadwal</button>'
                    + '<button type="button" onclick="event.stopPropagation(); openModal(' + d.id + ')" class="' + btnClass + '">' + btnIcon + btnLabel + '</button>'
                + '</div>'
                
                + '</div>';
        }).join('');
    }

    // ── FUNGSI MODAL JADWAL ──
function openSchedule(id, nama) {

    document.getElementById("scheduleModal").style.display = "flex";
    document.body.style.overflow = "hidden";

    document.getElementById("modalTitleJadwal").innerHTML = "Jadwal " + nama;

    document.getElementById("scheduleBody").innerHTML = `
        <div style="padding:40px;text-align:center;color:#94A3B8;font-size:13px;font-weight:600;">
            <i class="fa-solid fa-hourglass"></i> Memuat jadwal...
        </div>
    `;

    fetch(`/mahasiswa/jadwal/${id}`)
        .then(response => response.json())
        .then(data => {

            let container = document.getElementById("scheduleBody");
            container.innerHTML = "";

            if (data.length === 0) {
                container.innerHTML = `
                    <div style="padding:40px;text-align:center;color:#94A3B8;font-size:13px;font-weight:600;">
                        Belum ada jadwal yang ditambahkan.
                    </div>
                `;
                return;
            }

            data.forEach(item => {

                let mulai = item.mulai ? item.mulai.substring(0,5) : "-";
                let selesai = item.selesai ? item.selesai.substring(0,5) : "-";

                container.insertAdjacentHTML("beforeend", `
                    <div class="schedule-item" style="
                        border:1px solid #e2e8f0;
                        padding:14px;
                        border-radius:10px;
                        margin-bottom:10px;
                        background:#fff;
                    ">

                        <div style="
                            font-weight:700;
                            color:#1e293b;
                            margin-bottom:4px;
                        ">
                            ${item.aktivitas}
                        </div>

                        <div style="
                            font-size:13px;
                            color:#64748b;
                            margin-bottom:4px;
                        ">
                            <i class="fa-regular fa-calendar"></i> ${item.hari}
                        </div>

                        <div style="
                            font-size:13px;
                            color:#64748b;
                            margin-bottom:6px;
                        ">
                            <i class="fa-regular fa-clock"></i> ${mulai} - ${selesai} WIB
                        </div>

                        ${
                            item.matakuliah
                            ? `<div style="font-size:13px;font-weight:600;color:#2563eb;">
                                   <i class="fa-regular fa-clock"></i> ${item.matakuliah}
                               </div>`
                            : ""
                        }

                    </div>
                `);

            });

        })
        .catch(error => {

            console.error(error);

            document.getElementById("scheduleBody").innerHTML = `
                <div style="padding:40px;text-align:center;color:#EF4444;font-size:13px;font-weight:600;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Gagal mengambil data jadwal.
                </div>
            `;

        });

}

function closeSchedule() {
    document.getElementById("scheduleModal").style.display = "none";
    document.body.style.overflow = "";
}

    // ── FUNGSI FILTER & MODAL REQUEST ──
    function applyFilter() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const filtered = dosenData.filter(d => {
            const matchStatus = currentFilter === 'semua' || d.status === currentFilter;
            const matchSearch = !q || d.nama.toLowerCase().includes(q) || (d.ruangan && d.ruangan.toLowerCase().includes(q));
            return matchStatus && matchSearch;
        });
        renderCards(filtered);
        updateStats();
    }
    
    function updateStats() {
        document.getElementById('statDiRuangan').textContent = dosenData.filter(d => d.status === 'di_ruangan').length;
        document.getElementById('statTidakAda').textContent  = dosenData.filter(d => d.status === 'tidak_ada').length;
    }
    
    function setStatFilter(btn, status) {
        document.querySelectorAll('.kp-stat').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        currentFilter = status;
        applyFilter();
    }
    
    function setFilter(btn, status) {
        currentFilter = status;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.kp-stat').forEach(b => b.classList.remove('active'));
        if (status !== 'semua') {
            const statBtn = document.getElementById('statBtn-' + status);
            if (statBtn) statBtn.classList.add('active');
        }
        applyFilter();
    }
    
    document.getElementById('searchInput').addEventListener('input', applyFilter);
    
    function openModal(dosenId) {
        currentDosen = dosenData.find(d => d.id == dosenId);
        if (!currentDosen) return;
        document.getElementById('modalDosenName').textContent = currentDosen.nama;
        ['inputTanggal','inputJam','inputTopik','inputCatatan'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('modalError').style.display = 'none';
        const alreadyEl = document.getElementById('modalAlreadyRequested');
        alreadyEl.style.display = requestedDosenIds.includes(currentDosen.id) ? 'block' : 'none';
        document.getElementById('modalOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.body.style.overflow = '';
        currentDosen = null;
    }
    
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    
    function submitRequest() {
        const tanggal = document.getElementById('inputTanggal').value;
        const jam     = document.getElementById('inputJam').value;
        const topik   = document.getElementById('inputTopik').value.trim();
        const catatan = document.getElementById('inputCatatan').value.trim();
        const errEl   = document.getElementById('modalError');
        
        if (!tanggal || !jam || !topik) {
            errEl.textContent = 'Tanggal, jam, dan topik wajib diisi.';
            errEl.style.display = 'block';
            return;
        }
        
        errEl.style.display = 'none';
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("mahasiswa.bimbingan.store") }}';
        
        const fields = {
            '_token':   '{{ csrf_token() }}',
            'dosen_id': currentDosen.id,
            'tanggal':  tanggal,
            'jam':      jam,
            'topik':    topik,
            'catatan':  catatan,
        };
        
        Object.entries(fields).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = name;
            input.value = value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }

    // ── Inisialisasi awal ──
    applyFilter();

    // ── CHATBOT ──
let chatHistory = [];

function toggleChat() {
    const win = document.getElementById('chatWindow');
    win.style.display = (win.style.display === 'none' || win.style.display === '') ? 'flex' : 'none';
}

async function sendChat() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    if (!message) return;

    const messagesBox = document.getElementById('chatMessages');
    const emptyState = document.getElementById('chatEmptyState');
    if (emptyState) emptyState.remove();

    // Tampilkan pesan user
    const userBubble = document.createElement('div');
    userBubble.className = 'msg-user';
    userBubble.textContent = message;
    messagesBox.appendChild(userBubble);

    input.value = '';
    messagesBox.scrollTop = messagesBox.scrollHeight;

    // Tampilkan indikator "mengetik..."
    const typingBubble = document.createElement('div');
    typingBubble.className = 'msg-bot msg-typing';
    typingBubble.textContent = 'Mengetik...';
    messagesBox.appendChild(typingBubble);
    messagesBox.scrollTop = messagesBox.scrollHeight;

    try {
        const response = await axios.post('/chat', {
            message: message,
            history: chatHistory
        });

        typingBubble.remove();

        const botBubble = document.createElement('div');
        botBubble.className = 'msg-bot';
        botBubble.textContent = response.data.reply;
        messagesBox.appendChild(botBubble);

        // Simpan ke history
        chatHistory.push({ role: 'user', text: message });
        chatHistory.push({ role: 'assistant', text: response.data.reply });

    } catch (error) {
        typingBubble.remove();
        const errBubble = document.createElement('div');
        errBubble.className = 'msg-bot';
        errBubble.textContent = 'Maaf, terjadi kesalahan. Coba lagi.';
        messagesBox.appendChild(errBubble);
        console.error('Chat error:', error);
    }

    messagesBox.scrollTop = messagesBox.scrollHeight;
}

// Tombol cepat (Cek status bimbingan, dll) — kalau ada elemen dengan onclick sendQuickChat('...')
function sendQuickChat(text) {
    document.getElementById('chatInput').value = text;
    sendChat();
}

</script>
@endsection