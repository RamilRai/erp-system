<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{asset('backend/images/favicon.png')}}">
    <!-- Page Title  -->
    <title>Login | ERP System</title>
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
                                    <a href="{{route('admin.login')}}" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" srcset="{{asset('backend/images/logo2x.png')}} 2x" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" srcset="{{asset('backend/images/logo-dark2x.png')}} 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title signh1">Sign-In</h5>
                                    </div>
                                </div><!-- .nk-block-head -->

                                @include('layouts.include.alertMessage')

                                <div class="errorLogin"></div>
                                <form action="{{route('admin.check-login')}}" method="POST" enctype="multipart/form-data" class="form-validate is-alter" id="loginForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Email or Username</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="text" name="email_userName" class="form-control form-control-lg" id="email-address" placeholder="Enter your email address or username">
                                        </div>
                                        @error('email_userName')
                                            <p class="text-danger errorMessage">Please input email or username.</p>
                                        @enderror
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password</label>
                                            <a class="link link-primary link-sm forgotCode" href="#">Forgot Code?</a>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Enter your passcode">
                                        </div>
                                        @error('password')
                                            <p class="text-danger errorMessage">Please input password.</p>
                                        @enderror
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                                    </div>
                                </form><!-- form -->

                                {{-- forgot password email form start --}}
                                <div class="forgotClass" style="display:none;">
                                    <div class="nk-block-head-content" >
                                        <h4 class="nk-block-title mt-3 mb-3">Forgot Password</h4>
                                    </div>
                                    <form id="forgotPassword" enctype="multipart/form-data" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="default-01">Email</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control form-control-lg forgotEmail" name="email" id="forgotEmail" placeholder="Enter your email address"                                        </div>
                                            </div>
                                            <p class="text-danger validationError"></p>
                                        </div>
                                        <div class="form-group mt-3 mb-3">
                                            <button type="button" class="btn btn-lg btn-primary btn-block sendOtp" id="sendOtp">Send Otp</button>
                                        </div>
                                    </form>
                                </div>
                                {{-- forgot password email form end --}}

                                {{-- verify otp and reset password form start --}}
                                <div class="resetclass"  style="display:none;" >
                                    <div class="nk-block-head-content" >
                                        <h4 class="nk-block-title mt-3 mb-3">Reset Password</h4>
                                    </div>
                                    <form id="resetPassword" enctype="multipart/form-data" method="post" >
                                        @csrf
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="otp">OTP</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="hidden" id="useridHidden" name="userid">
                                                <input type="text" class="form-control form-control-lg otp" name="otp" id="otp" placeholder="Enter your otp">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="newPassword">New Password</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="password" class="form-control form-control-lg newPassword" disabled name="newPassword" id="newPassword" placeholder="Enter your new password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="confirmPassword">Confirm Password</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="password" class="form-control form-control-lg confirmPassword" disabled name="confirmPassword" id="confirmPassword" placeholder="Re-enter your new password">
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mb-3">
                                            <button type="button" class="btn btn-lg btn-primary btn-block resetPasswordBtn" disabled id="sendOtp">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                {{-- verify otp and reset password form end --}}

                            </div>
                            <div class="nk-block nk-auth-footer">
                                <div class="mt-3">
                                    <p>&copy; <?php echo date('Y'); ?> ERP System</p>
                                </div>
                            </div><!-- .nk-block -->
                        </div><!-- .nk-block -->
                    </div><!-- .nk-split-content -->
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

    {{-- sweet alert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            //================== fadeout error messages start ==================
            $('.sessionMessage').fadeOut(7000);
            $('.errorMessage').fadeOut(7000);
            //================== fadeout error messages end ==================

            //================== password visibility toggle and preventing page for being refresh start ==================
            $('.passcode-switch').on('click', function(e){
                e.preventDefault();
            });

            $('.icon-show').on('click', function(e){
                e.preventDefault();
                $('#password').attr('type', 'text');
                $('.icon-show').hide();
                $('.icon-hide').show();
            });

            $('.icon-hide').on('click', function(e){
                e.preventDefault();
                $('#password').attr('type', 'password');
                $('.icon-hide').hide();
                $('.icon-show').show();
            });
            //================== password visibility toggle and preventing page for being refresh end ==================

            //================== forgot password start ==================
            $('.forgotCode').on('click', function(){
                $('#loginForm').css("display",'none');
                $('.forgotClass').css("display",'block');
                $('.signh1').css("display",'none');
            });
            //================== forgot password end ==================

            //================== send otp start ==================
            $('.sendOtp').on('click', function(e){
                e.preventDefault();
                var email = $('.forgotEmail').val();
                var url = "{{ route('admin.sendOtp') }}";
                $.ajax({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: 'POST',
                    data:{
                        email:email
                    },
                    beforeSend: function() {
                        $('.sendOtp').html(
                            "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Sending..."
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.sendOtp').html("Send OTP").removeAttr('disabled');
                    },
                    success:function(response){
                        var result=JSON.parse(response);
                        if(result.type=='success'){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                text: result.message
                            });
                            $('.resetclass').css("display",'block');
                            $('.forgotClass').css("display",'none');
                            $('#useridHidden').val(result.userId);
                        }else{
                            $('.validationError').html(result.message).delay(5000).fadeOut();
                        }
                    }
                })
            });
            //================== send otp end ==================

            //================== otp check start ==================
            $(document).on('keyup','.otp',function(){
                var otp = $(this).val();
                var url='{{route('admin.verifyOtp')}}';
                var id=$('#useridHidden').val();
                $.ajax({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:url,
                    data:{
                        userId:id,
                        otp:otp
                    },
                    success:function(response){
                        var result=JSON.parse(response);
                        if(result.type=='success'){
                            $('.newPassword').removeAttr('disabled');
                        }else{
                            $('.errorLogin').html("<div class='alert alert-danger' role='alert'>" + result.message + "</div>").delay(5000).fadeOut();
                        }
                    }

                })
            });
            //================== otp check end ==================

            //================== new password field start ==================
            $('.newPassword').on('keyup', function () {
                $('#oldPasswordMessage').html("");
                var newPassword = $(this).val();
                var regularExpression = /^(?=.*\d)(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,}$/;
                var result = regularExpression.test(newPassword);
                if (result == true) {
                    $(".errorLogin").html("<p class='text-success'>Ok ! now you can retype new password for confirmation.</p>").show();
                    $('.confirmPassword').removeAttr('disabled');
                } else {
                    $('.errorLogin').html("<p class='text-danger'>Password must be atleast 6 character long including 1 Uppercase, symbol and number.</p>");
                }
                $('.errorLogin').delay(10000).fadeOut('slow');
            });
            //================== new password field end ==================

            //================== confirm password field start ==================
            $('.confirmPassword').on('keyup', function () {
                var confirmPassword = $(this).val();
                var newPassword = $('.newPassword').val();
                if (confirmPassword == newPassword) {
                    $(".errorLogin").html("<p class='text-success'>Yes ! password confirmed. now you can update.</p>").show();
                    $('.resetPasswordBtn').removeAttr('disabled');
                } else {
                    $('.resetPasswordBtn').attr('disabled', 'disabled');
                    $(".errorLogin").html("<p class='text-danger'>New password does not matches with confirm password.</p>").show();
                }
                $('.errorLogin').delay(10000).fadeOut('slow');
            });
            //================== confirm password field end ==================

            //================== resetPassword start ==================
            $('.resetPasswordBtn').on('click',function(e){
                e.preventDefault();
                var newPassword = $('.newPassword').val();
                var confirmPassword = $('.confirmPassword').val();
                var email = $('.forgotEmail').val();

                if(newPassword == confirmPassword){
                    $.ajax({
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url:'{{route('admin.updatePassword')}}',
                        type:'POST',
                        data:{
                            email:email,
                            new_password:confirmPassword
                        },
                        beforeSend: function() {
                            $('.resetPasswordBtn').html(
                                "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Sending..."
                            ).attr('disabled', 'disabled');
                        },
                        complete: function() {
                            $('.resetPasswordBtn').html("Send OTP").removeAttr('disabled');
                        },
                        success:function(response){
                            var result=JSON.parse(response);
                            if(result.type=='success'){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    text: result.message,
                                    showConfimButton: false,
                                    timer: 30000
                                });
                                $('#loginForm').css("display",'block');
                                $('.resetclass').css("display",'none');
                                $('.forgotClass').css("display",'none');

                            }else{
                                $('.errorLogin').html("<div class='alert alert-danger' role='alert'>" + result.message + "</div>").delay(5000).fadeOut();
                            }
                        }
                    })
                }

            });
            //================== resetPassword end ==================

        });
    </script>
</body>
</html>
