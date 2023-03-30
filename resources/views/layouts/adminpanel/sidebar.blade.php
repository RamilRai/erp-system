<!-- sidebar @s -->
<div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            @php
                $userRole = App\Models\UserRole::where('user_id', Auth::user()->id)->first();
            @endphp
            <a href="@if($userRole->role_id == 1){{route('superadmin.dashboard')}}@elseif($userRole->role_id == 2){{route('projectmanager.dashboard')}}@else{{route('admin.dashboard')}}@endif" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" srcset="{{asset('backend/images/logo2x.png')}} 2x" alt="logo">
                <img class="logo-dark logo-img" srcset="{{asset('backend/images/logo-dark2x.png')}} 2x" alt="logo-dark">
            </a>
        </div>
    </div>
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    @if ($userRole->role_id == 1)
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">User Management</h6>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('user.index')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">User Manage</span>
                            </a>
                        </li>
                    @endif
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Use-Case Preview</h6>
                    </li>
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
                            <span class="nk-menu-text">CRM Panel</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('lead.index')}}" class="nk-menu-link"><span class="nk-menu-text">Leads</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{route('customer.index')}}" class="nk-menu-link"><span class="nk-menu-text">Customers</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('project-management.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="fa-solid fa-bars-progress fa-lg"></em></span>
                            <span class="nk-menu-text">Project Management</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('task-management.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="fa-solid fa-list-check fa-lg"></em></span>
                            <span class="nk-menu-text">Task Management</span>
                        </a>
                    </li>
                    @if($userRole->role_id == 1 || $userRole->role_id == 2)
                        <li class="nk-menu-item">
                            <a href="{{route('task-report.index')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="fa-solid fa-table fa-lg"></em></span>
                                <span class="nk-menu-text">Task Report</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- sidebar @e -->
