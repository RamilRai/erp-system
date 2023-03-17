<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{asset('backend/images/favicon.png')}}">
    <!-- Page Title  -->
    <title>Login | DashLite Admin Template</title>
    <!-- StyleSheets  -->
    {{-- <link rel="stylesheet" href="./assets/css/dashlite.css?ver=3.0.3"> --}}
    <link rel="stylesheet" href="{{asset('backend/assets/css/dashlite.css')}}">
    <link id="skin-default" rel="stylesheet" href="{{asset('backend/assets/css/theme.css?ver=3.0.3')}}">
</head>

<body class="nk-body bg-white npc-general pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-lg">
                        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                                <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                            </div>
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5">
                                    <a href="html/index.html" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" src="./images/logo.png" srcset="{{asset('backend/images/logo2x.png')}} 2x" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" src="./images/logo-dark.png" srcset="{{asset('backend/images/logo-dark2x.png')}} 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Enter OTP</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="errorMessage">@include('layouts.include.alertMessage')</div>

                                @if (Session::has('message'))   
                                    <div class="alert alert-danger alert-dismissible fade show text-center errorMessage" role="alert">
                                        <strong>{{Session::get('message')}}</strong>
                                    </div>
                                @endif
                                
                                <form action="{{route('admin.check-otp')}}" method="POST" enctype="multipart/form-data" class="form-validate is-alter" autocomplete="off">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" value="{{Session::get('username')}}" name="username">
                                        <div class="form-label-group">
                                            <label class="form-label" for="otp">OTP</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="text" name="otp" class="form-control form-control-lg" id="otp" placeholder="Enter otp">
                                        </div>
                                        @error('otp')
                                            <p class="text-danger errorMessage">Please input otp.</p>
                                        @enderror
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block">Submit</button>
                                    </div>
                                </form><!-- form -->
                            </div><!-- .nk-block -->
                            <div class="nk-block nk-auth-footer">
                                <div class="mt-3">
                                    <p>&copy; <?php echo date('Y'); ?> DashLite. All Rights Reserved.</p>
                                </div>
                            </div><!-- .nk-block -->
                        </div><!-- .nk-split-content -->
                    </div><!-- .nk-split -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->

    {{-- jquery localized cdn link --}}
    <script src="{{asset('backend/links/jquery-3.6.3.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            //================== fadeout error messages start ==================
            $('.errorMessage').fadeOut(7000);
            //================== fadeout error messages end ==================
        });
    </script>
</body>
</html>