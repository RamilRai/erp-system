<!-- main header @s -->
<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="html/index.html" class="logo-link">
                    <img class="logo-light logo-img" src="./images/logo.png" srcset="{{asset('backend/images/logo2x.png')}} 2x" alt="logo">
                    <img class="logo-dark logo-img" src="./images/logo-dark.png" srcset="{{asset('images/logo-dark2x.png')}} 2x" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em>
                                        @if (Auth::user()->profile->profile)
                                            <img class="userProfile" src=''/>
                                        @else
                                            <img src="{{asset('default-images/avatar.png')}}" alt="avatar"/>
                                        @endif
                                    </em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <div class="user-name dropdown-indicator loggedInUserName"></div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>
                                            @if (Auth::user()->profile->profile)
                                                <img class="userProfile" src=''/>
                                            @else
                                                <img src="{{asset('default-images/avatar.png')}}" alt="avatar"/>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text loggedInUserName"></span>
                                        <span class="sub-text email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('admin.profile')}}"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                    <li><a href="{{route('change.password')}}"><em class="icon ni ni-lock-alt-fill"></em><span>Security Setting</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('admin.logout')}}"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
<!-- main header @e -->
