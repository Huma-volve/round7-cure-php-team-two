<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)"><i
                class="icon-base ri ri-menu-line icon-md"></i></a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        {{-- Search --}}
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="icon-base ri ri-search-line icon-lg lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                    aria-label="Search..." />
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <li class="nav-item lh-1 me-4">
                <a class="github-button"
                    href="https://github.com/themeselection/materio-bootstrap-html-admin-template-free"
                    data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star">Star</a>
            </li>

            {{-- User dropdown --}}
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('storage/images/users/' . auth()->user()->profile_photo) }}" alt="avatar"
                            class="rounded-circle" />

                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('storage/images/users/' . auth()->user()->profile_photo) }}"
                                            alt="avatar" class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->user()->name ?? 'John Doe' }}</h6>
                                    <small class="text-body-secondary">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    @role('doctor')
                        <li><a class="dropdown-item" href="{{ route('doctor.profile') }}"><i
                                    class="icon-base ri ri-user-line icon-md me-3"></i><span>My Profile</span></a></li>

                        <li>
                            <div class="dropdown-divider my-1"></div>
                        <li></li>
                    @endrole
                    @role('admin')
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i
                                    class="icon-base ri ri-settings-4-line icon-md me-3"></i><span>Settings</span></a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        <li></li>
                    @endrole

                    <div class="d-grid px-4 pt-2 pb-1">
                        <a class="btn btn-danger d-flex" href="javascript:void(0);"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <small class="align-middle">Logout</small>
                            <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                        </a>
                    </div>
            </li>
        </ul>
        </li>
        </ul>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
