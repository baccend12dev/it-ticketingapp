<div class="sidebar" id="sidebarMenu">
    <div class="brand">
        <i class="bi bi-shield-check me-2"></i> IT Support System
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tickets.index') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
                <i class="bi bi-ticket-detailed"></i> My Tickets
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}" href="{{ route('tickets.create') }}">
                <i class="bi bi-plus-circle"></i> Create Ticket
            </a>
        </li>
        
        @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isIT()))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
        @endif
        
        @if(auth()->user() && auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between {{ request()->is('departments*') || request()->is('locations*') || request()->is('categories*') || request()->is('sub-categories*') ? 'active' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#masterDataCollapse" 
                   role="button" 
                   aria-expanded="{{ request()->is('departments*') || request()->is('locations*') || request()->is('categories*') || request()->is('sub-categories*') ? 'true' : 'false' }}" 
                   aria-controls="masterDataCollapse">
                    <span>
                        <i class="bi bi-database-gear"></i> Master Data
                    </span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                </a>
                <div class="collapse {{ request()->is('departments*') || request()->is('locations*') || request()->is('categories*') || request()->is('sub-categories*') ? 'show' : '' }}" id="masterDataCollapse">
                    <ul class="nav-menu ps-3 py-1" style="background-color: rgba(0, 0, 0, 0.2); list-style: none; padding-left: 0; margin-bottom: 0;">
                        <li class="nav-item mb-1">
                            <a class="nav-link py-1 px-3 {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}" style="font-size: 13px;">
                                <i class="bi bi-building" style="font-size: 0.95rem; margin-right: 8px;"></i> Department
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link py-1 px-3 {{ request()->routeIs('locations.index') ? 'active' : '' }}" href="{{ route('locations.index') }}" style="font-size: 13px;">
                                <i class="bi bi-geo-alt" style="font-size: 0.95rem; margin-right: 8px;"></i> Location
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link py-1 px-3 {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}" style="font-size: 13px;">
                                <i class="bi bi-grid" style="font-size: 0.95rem; margin-right: 8px;"></i> Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-1 px-3 {{ request()->routeIs('sub-categories.index') ? 'active' : '' }}" href="{{ route('sub-categories.index') }}" style="font-size: 13px;">
                                <i class="bi bi-grid-3x3-gap" style="font-size: 0.95rem; margin-right: 8px;"></i> Sub Category
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
                <i class="bi bi-gear"></i> Settings
            </a>
        </li>
    </ul>
    
    <div class="p-3 border-top border-secondary text-center mt-auto">
        <span class="caption text-muted">Version 1.0.0</span>
    </div>
</div>
