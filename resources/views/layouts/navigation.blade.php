<style>
    .navbar {
        background: #ffffff;
        border-bottom: 1px solid #EEEEF2;
        position: sticky;
        top: 0;
        z-index: 50;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .navbar-inner {
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 62px;
    }

    /* Logo area */
    .navbar-left {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .navbar-logo a {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .logo-mark {
        width: 34px;
        height: 34px;
        background: #0F3D91;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -0.5px;
        flex-shrink: 0;
    }

    .logo-text {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.3px;
    }

    /* Nav links */
    .navbar-links {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #6B7280;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }

    .nav-link:hover {
        background: #F3F4F6;
        color: #111827;
    }

    .nav-link.active {
        background: #EFF4FF;
        color: #1D4ED8;
        font-weight: 600;
    }

    /* Right side */
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* User dropdown trigger */
    .user-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 10px 5px 5px;
        border-radius: 100px;
        border: 1px solid #EEEEF2;
        background: white;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .user-btn:hover {
        border-color: #D1D5DB;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #0F3D91;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-chevron {
        color: #9CA3AF;
        flex-shrink: 0;
        transition: transform 0.2s;
    }

    /* Dropdown panel */
    .dropdown-panel {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 220px;
        background: white;
        border: 1px solid #EEEEF2;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        z-index: 100;
    }

    .dropdown-user-info {
        padding: 14px 16px;
        border-bottom: 1px solid #F3F4F6;
    }

    .dropdown-user-name {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1px;
    }

    .dropdown-user-email {
        font-size: 11px;
        color: #9CA3AF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.1s;
        width: 100%;
        background: none;
        border: none;
        text-align: left;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dropdown-item:hover { background: #F9FAFB; }

    .dropdown-item.danger { color: #DC2626; }
    .dropdown-item.danger:hover { background: #FEF2F2; }

    .dropdown-item-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .dropdown-item-icon.gray   { background: #F3F4F6; }
    .dropdown-item-icon.red    { background: #FEE2E2; }

    /* Hamburger (mobile) */
    .hamburger-btn {
        display: none;
        padding: 8px;
        border-radius: 8px;
        background: none;
        border: none;
        cursor: pointer;
        color: #6B7280;
        transition: background 0.15s;
    }

    .hamburger-btn:hover { background: #F3F4F6; }

    /* Mobile drawer */
    .mobile-drawer {
        display: none;
        border-top: 1px solid #EEEEF2;
        background: white;
    }

    .mobile-drawer.open { display: block; }

    .mobile-nav-links {
        padding: 8px 12px;
    }

    .mobile-nav-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: background 0.1s;
    }

    .mobile-nav-link:hover { background: #F3F4F6; }
    .mobile-nav-link.active { background: #EFF4FF; color: #1D4ED8; font-weight: 600; }

    .mobile-user-section {
        padding: 12px 16px 16px;
        border-top: 1px solid #F3F4F6;
        margin-top: 4px;
    }

    .mobile-user-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .mobile-user-name  { font-size: 14px; font-weight: 700; color: #111827; }
    .mobile-user-email { font-size: 12px; color: #9CA3AF; }

    .mobile-logout-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #DC2626;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background 0.1s;
    }

    .mobile-logout-btn:hover { background: #FEF2F2; }

    @media (max-width: 640px) {
        .navbar-links, .navbar-right { display: none; }
        .hamburger-btn { display: flex; }
        .user-name { display: none; }
    }

    @media (min-width: 641px) {
        .hamburger-btn { display: none; }
        .mobile-drawer { display: none !important; }
    }
</style>

<nav class="navbar" x-data="{ open: false, dropdownOpen: false }">
    <div class="navbar-inner">

        {{-- Kiri: Logo + Nav Links --}}
        <div class="navbar-left">
            <div class="navbar-logo">
                <a href="{{ auth()->user()->dashboardRoute() }}">
                    <div class="logo-mark">T</div>
                    <span class="logo-text">{{ config('app.name', 'TutorApp') }}</span>
                </a>
            </div>

            <div class="navbar-links">
                <a href="{{ auth()->user()->dashboardRoute() }}"
                   class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        {{-- Kanan: User Dropdown --}}
        <div class="navbar-right">
            <div style="position: relative;">
                <button class="user-btn"
                        @click="dropdownOpen = !dropdownOpen"
                        @click.away="dropdownOpen = false">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <svg class="user-chevron" :style="dropdownOpen ? 'transform:rotate(180deg)' : ''"
                         width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div class="dropdown-panel" x-show="dropdownOpen" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     style="transform-origin: top right;">

                    <div class="dropdown-user-info">
                        <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                        <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                    </div>

                    <div style="padding: 6px;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <span class="dropdown-item-icon red">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16 17 21 12 16 7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                </span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hamburger (mobile) --}}
            <button class="hamburger-btn" @click="open = !open">
                <svg x-show="!open" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
                <svg x-show="open" x-cloak width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div class="mobile-drawer" :class="{ 'open': open }" x-show="open" x-cloak>
        <div class="mobile-nav-links">
            <a href="{{ auth()->user()->dashboardRoute() }}"
               class="mobile-nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
        </div>

        <div class="mobile-user-section">
            <div class="mobile-user-row">
                <div class="user-avatar" style="width:38px;height:38px;font-size:13px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                    <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-logout-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
