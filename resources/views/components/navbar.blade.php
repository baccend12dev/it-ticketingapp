<nav class="navbar-top">
    <div class="d-flex align-items-center">
        <!-- Sidebar toggle button for mobile/tablet -->
        <button class="btn btn-link text-dark p-0 me-3 d-lg-none" id="sidebarToggle" type="button" aria-label="Toggle Navigation">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="h2 mb-0 d-none d-md-inline-block text-secondary">
            @yield('page_title', 'IT Operations Portal')
        </span>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- User role pill badge -->
        @if(auth()->user())
            <span class="badge-pill-custom 
                @if(auth()->user()->role === 'admin') badge-critical 
                @elseif(auth()->user()->role === 'it') badge-active 
                @else badge-resolved @endif">
                {{ strtoupper(auth()->user()->role) }}
            </span>
            
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none dropdown-toggle text-dark p-0 d-flex align-items-center gap-2" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="body-sm fw-semibold">{{ auth()->user()->name }}</span>
                    <i class="bi bi-person-circle fs-5 text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" aria-labelledby="userMenuButton" style="border-radius: var(--rounded-default);">
                    <li>
                        <div class="dropdown-header">
                            <h6 class="mb-0 text-dark text-truncate" style="max-width: 180px;">{{ auth()->user()->name }}</h6>
                            <span class="caption text-muted d-block text-truncate" style="max-width: 180px;">{{ auth()->user()->email }}</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('settings') }}">
                            <i class="bi bi-gear text-muted"></i> Settings
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
