<style>
    .kp-nav {
        background: #1E2A4A;
        padding: 0 2rem;
        display: flex; align-items: center; justify-content: space-between;
        height: 56px; position: sticky; top: 0; z-index: 100;
    }
    .kp-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .kp-logo-icon { width: 32px; height: 32px; background: #4F7EF8; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .kp-logo-icon svg { width: 16px; height: 16px; color: #fff; }
    .kp-logo-text { color: #fff; font-size: 15px; font-weight: 700; letter-spacing: 0.3px; }
    .kp-logo-text span { color: #8AAEFB; font-weight: 400; }
    .kp-nav-links { display: flex; }
    .kp-nav-link { color: #8A9BBF; font-size: 13px; padding: 0 16px; height: 56px; display: flex; align-items: center; gap: 6px; text-decoration: none; border-bottom: 2px solid transparent; transition: color .2s, border-color .2s; }
    .kp-nav-link:hover { color: #C8D4F0; }
    .kp-nav-link.active { color: #fff; border-bottom-color: #4F7EF8; }
    .kp-nav-link svg { width: 15px; height: 15px; }
    .kp-user { display: flex; align-items: center; gap: 10px; }
    .kp-user-info { text-align: right; }
    .kp-user-label { font-size: 10px; color: #5A6A8A; letter-spacing: 0.5px; text-transform: uppercase; }
    .kp-user-name  { font-size: 13px; color: #C8D4F0; font-weight: 600; }
    
    /* 🌟 PERBAIKAN STYLING AVATAR AGAR GAMBAR TIDAK GEPENG */
    .kp-avatar-sm { width: 32px; height: 32px; border-radius: 50%; background: #4F7EF8; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 11px; font-weight: 700; overflow: hidden; }
    .kp-avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
    
    .kp-logout { width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,.07); border: 0.5px solid rgba(255,255,255,.12); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .15s; }
    .kp-logout:hover { background: rgba(239,68,68,.2); border-color: rgba(239,68,68,.3); }
    .kp-logout svg { width: 15px; height: 15px; color: #8A9BBF; }
    .kp-logout:hover svg { color: #FCA5A5; }

    /* Dropdown User */
.kp-user-menu {
    position: relative;
}

.kp-user-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.kp-dropdown {
    position: absolute;
    top: 48px;
    right: 0;
    width: 200px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,.15);
    overflow: hidden;
    display: none;
    z-index: 999;
}

.kp-dropdown.show {
    display: block;
}

.kp-dropdown a,
.kp-dropdown button {
    width: 100%;
    border: none;
    background: none;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #334155;
    font-size: 14px;
    cursor: pointer;
}

.kp-dropdown a:hover,
.kp-dropdown button:hover {
    background: #F1F5F9;
}

.kp-dropdown hr {
    margin: 0;
    border: none;
    border-top: 1px solid #E2E8F0;
}

.profile-menu { position: relative; }
.profile-menu button#menuBtn {
    background: rgba(255,255,255,.07);
    border: 0.5px solid rgba(255,255,255,.12);
    width: 32px; height: 32px; border-radius: 8px;
    color: #8A9BBF; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.profile-menu button#menuBtn:hover { background: rgba(255,255,255,.15); color: #fff; }
.dropdown {
    display: none;
    position: absolute; top: 42px; right: 0;
    width: 180px; background: #fff; border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,.15);
    overflow: hidden; z-index: 999;
}
.dropdown.show { display: block; }
.dropdown a, .dropdown button {
    width: 100%; border: none; background: none;
    padding: 12px 16px; display: flex; align-items: center; gap: 10px;
    text-decoration: none; color: #334155; font-size: 14px; cursor: pointer;
}
.dropdown a:hover, .dropdown button:hover { background: #F1F5F9; }
</style>

<nav class="kp-nav">
{{-- Logo --}}
<a href="{{ Auth::user()->role === 'dosen' ? route('dosen.dashboard') : route('mahasiswa.dashboard') }}"
    class="kp-logo flex items-center gap-1">
 
     <svg class="logo-svg" width="40" height="40" viewBox="0 0 40 40" fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <circle cx="20" cy="20" r="18" fill="rgba(255,255,255,0.15)"
              stroke="rgba(255,255,255,0.3)" stroke-width="1" />
          <path d="M20 8 L32 12.5 L32 19.5 Q32 27 20 31 Q8 27 8 19.5 L8 12.5 Z"
              fill="none" stroke="white" stroke-width="1.6" />
          <circle cx="20" cy="17" r="4" fill="none" stroke="#e8c97a"
              stroke-width="1.6" />
          <path d="M12 28 Q12 23 20 23 Q28 23 28 28" fill="none"
              stroke="#e8c97a" stroke-width="1.6" stroke-linecap="round" />
     </svg>
 
     <span class="kp-logo-text">
         KAMPUS<span>/presence</span>
     </span>
 </a>
{{-- Nav Links --}}
    <div class="kp-nav-links">
        @if(Auth::user()->role === 'dosen')
            {{-- Dashboard Dosen --}}
            <a href="{{ Route::has('dosen.dashboard') ? route('dosen.dashboard') : '#' }}" class="kp-nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
            
            {{-- Bimbingan Dosen (Sudah dipindah ke sini dengan fix .index) --}}
            <a href="{{ Route::has('dosen.bimbingan.index') ? route('dosen.bimbingan.index') : (Route::has('dosen.bimbingan') ? route('dosen.bimbingan') : (Route::has('bimbingan.index') ? route('bimbingan.index') : '#')) }}" 
               class="kp-nav-link {{ request()->routeIs('dosen.bimbingan*') || request()->routeIs('bimbingan*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                Bimbingan
            </a>
            
            {{-- Jadwal Dosen --}}
            <a href="{{ Route::has('dosen.jadwal') ? route('dosen.jadwal') : (Route::has('jadwal') ? route('jadwal') : '#') }}" class="kp-nav-link {{ request()->routeIs('dosen.jadwal') || request()->routeIs('jadwal') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Jadwal Saya
            </a>
        @else
            {{-- Bagian Mahasiswa --}}
            <a href="{{ Route::has('mahasiswa.dashboard') ? route('mahasiswa.dashboard') : '#' }}" class="kp-nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ Route::has('mahasiswa.bimbingan') ? route('mahasiswa.bimbingan') : '#' }}" class="kp-nav-link {{ request()->routeIs('mahasiswa.bimbingan*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                Bimbingan
            </a>
        @endif
    </div>

    {{-- User + Logout --}}
    <div class="kp-user">
    <div class="kp-user-info">
        <div class="kp-user-label">{{ Auth::user()->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</div>
        <div class="kp-user-name">{{ Auth::user()->name ?? 'Guest' }}</div>
    </div>

    <div class="kp-avatar-sm">
        @php
            $dosenLogin = null;
            if(Auth::user()->role === 'dosen') {
                $dosenLogin = \App\Models\Dosen::where('user_id', Auth::id())->first();
            }
        @endphp

        @if($dosenLogin && $dosenLogin->foto)
            <img src="{{ asset('storage/' . $dosenLogin->foto) }}" alt="PP">
        @else
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
        @endif
    </div>

    {{-- 🌟 DROPDOWN PROFILE MENU --}}
    <div class="profile-menu">
        <button id="menuBtn">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div id="dropdownMenu" class="dropdown">
            @if(Auth::user()->role === 'dosen')
                <a href="{{ route('password.edit') }}">
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('menuBtn')?.addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('dropdownMenu').classList.toggle('show');
    });
    document.addEventListener('click', function() {
        document.getElementById('dropdownMenu')?.classList.remove('show');
    });
</script>
</nav>