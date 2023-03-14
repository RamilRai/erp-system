@extends('layouts.adminpanel.design')

@section('title') Password Change @endsection

@section('main-content')

<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between text-center">
        <div class="card col-8 mx-auto" id="card1">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Change password</h5>
                        <div class="nk-block-des">
                            <p>You can change your password here.</p>
                        </div>
                    </div>
                </div>
                <form method="POST" id="passwordChangeForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="default-01">Current Password</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="password" name="currentPassword" id="currentPassword" class="form-control form-control-lg">
                            <label class="message" id="current-pwd-error"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="default-01">New Password</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="password" name="newPassword" disabled class="form-control form-control-lg" id="newPassword">
                            <label class="message" id="new-pwd-error"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="default-01">Confirm New Password</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="password" name="confirmPassword" disabled class="form-control form-control-lg" id="confirmPassword">
                            <label class="message" id="confirm-pwd-error"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block" disabled id="sendOtpButton">Send OTP</button>
                        <button class="btn btn-lg btn-primary btn-block" id="updatePasswordButton" disabled="disabled" style="display: none">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card col-8 mx-auto" id="card2" style="display: none">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Verify Otp</h5>
                    </div>
                </div>
                <form id="verifyOtpForm" action="#">
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label text-center" for="default-01">Enter OTP</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" name="otp" id="otp" class="form-control form-control-lg">
                            <label class="message" id="current-pwd-error"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-lg btn-primary btn-block verifyOtpButton" id="verifyOtpButton">Verify OTP</button>
                    </div>
                    {{-- Only for commit --}}
                </form>
            </div>
        </div>
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->

@endsection

@section('main-styles')

@endsection

@section('main-scripts')
<script type="text/javascript">
    $(document).ready(function () {

        var useremail = '{{Auth::user()->email}}';
        var userid = '{{Auth::user()->id}}';

        //============================== check current password and validate with database start ==============================
        $('#currentPassword').keyup(function(){
            var currentpassword = $("#currentPassword").val();
            if (currentpassword.length>5) {
                validate(currentpassword);
            }else{
                $("#current-pwd-error").html("<p class='text-danger'>Current password must be at least 6 character. Keep trying</p>").show();
            }
        });

        function validate(currentpassword) {
            var current_password = currentpassword;
            var user_id = userid;
            var url = "{{route('check-current-password')}}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : "POST",
                url : url,
                data : {current_password, user_id},
                dataType : 'json',
                async : false,
                success : function(response) {
                    var result = JSON.parse(JSON.stringify(response));
                    if(result.type=='success'){
                        $("#current-pwd-error").html("<p class='text-success'>The password matches successfully.</p>").show();
                        $("#newPassword").removeAttr('disabled');
                    } else {
                        $("#current-pwd-error").html("<p class='text-danger'>The password doesnot match. Keep trying</p>").show();
                    }
                    $('#current-pwd-error').delay(10000).fadeOut('slow')
                }
            });
        }
        //============================== check current password and validate with database end ==============================

        //============================== validate and check new password start ==============================
        function validatePassword(pwd) {
            var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
            var txt = pwd;
            if (regex.test(txt)) {
                return true;
            } else {
                return false;
            }
        }

        $("#newPassword").keyup(function(){
            var new_password = $("#newPassword").val();
            var status = validatePassword(new_password);
            if (status) {
                $("#new-pwd-error").html("<p class='text-success'>Ok ! now you can retype new password for confirmation.</p>").show();
                $("#confirmPassword").removeAttr('disabled');
            }else{
                $("#new-pwd-error").html("<p class='text-danger'>Your password must be at atleast 6 characters containing Alpha Numeric and Symbol. .</p>").show();
            }
            $('#new-pwd-error').delay(10000).fadeOut('slow');
        });
        //============================== validate and check new password start ==============================

        //============================== check new password and confirm password start ==============================
        $("#confirmPassword").keyup(function(){
            var new_password = $("#newPassword").val();
            var confirm_password = $("#confirmPassword").val();
            var status = validatePassword(confirm_password);
            if (status) {
                $("#confirm-pwd-error").html("<p class='text-success'>Yes ! password matches format.</p>").show();
                if (new_password==confirm_password) {
                    $("#confirm-pwd-error").html("<p class='text-success'>Yes ! password confirmed. now you can update.</p>").show();
                    $("#sendOtpButton").removeAttr('disabled');
                }else{
                    $("#confirm-pwd-error").hide();
                    $("#confirm-pwd-error").html("<p class='text-danger'>New password does not matches with confirm password.</p>").show();
                }
            }else{
                $("#confirm-pwd-error").html("<p class='text-danger'>Your password must be at atleast 6 characters containing Alpha Numeric and Symbol. .</p>").show();
            }
            $('#confirm-pwd-error').delay(10000).fadeOut('slow');
        });
        //============================== check new password and confirm password end ==============================

        //============================== generate and send otp to the user's mail start ==============================
        $('#sendOtpButton').on('click', function(e){
            e.preventDefault();
            var url = "{{ route('admin.sendOtp') }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'POST',
                dataType: 'json',
                data:{
                    userid:userid,
                    useremail:useremail
                },
                beforeSend: function() {
                    $('#sendOtpButton').html(
                        "<svg width='25' viewBox='-2 -2 55 55' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> &nbsp;&nbsp; Sending Otp..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#sendOtpButton').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send block mx-auto"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>&nbsp;Send Otp').removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(JSON.stringify(response));
                    if (result.type=='success') {
                        var email = response.email;
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            text: result.message,
                            showConfimButton: false,
                            timer: 30000
                        });
                        $('#sendOtpButton').toggle('slow');
                        $('#card1').hide();
                        $('#card2').show();
                    } else {
                        $.notify(result.message, "error");
                    }
                }
            });
        });
        //============================== generate and send otp to the user's mail end ==============================

        //============================== validate and verify otp start ==============================
        $("#verifyOtpButton").on('click', function(e){
            e.preventDefault();
            $("#verifyOtpForm").validate({
                rules: {
                    otp: {
                        required: true
                    }
                },
                messages: {
                    otp: {
                        required: "Please enter OTP to verify."
                    }
                }
            });
            if($('#verifyOtpForm').valid()){
                var otp = $('#otp').val();
                var email = useremail;
                var url = "{{route('admin.verifyOtp')}}";
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {userId:userid, otp:otp, email:email},
                    beforeSend: function() {
                        $('#verifyOtpButton').html(
                                "<svg width='25' viewBox='-2 -2 55 55' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> &nbsp;&nbsp; Verifying OTP..."
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('#verifyOtpButton').html("Verify Otp").removeAttr('disabled');
                    },
                    success: function(response) {
                        var result = JSON.parse(JSON.stringify(response));
                        if(result.type=='success'){
                            $('#card2').hide();
                            $('#card1').show();
                            $('#current-pwd-error').hide();
                            $('#new-pwd-error').hide();
                            $('#confirm-pwd-error').hide();
                            $('#updatePasswordButton').toggle('slow');
                            $('#updatePasswordButton').removeAttr('disabled');
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                text: result.message
                            });
                        }
                    },
                    error: function(response) {
                        var errors =  response.responseJSON.errors;
                        $('#current-pwd-error').append(errors);
                    }
                });
            }
        });
        //============================== validate and verify otp end ==============================

        //============================== update password start ==============================
        $("#updatePasswordButton").on('click', function(e){
            e.preventDefault();
            var new_password = $("#newPassword").val();
            var user_id = userid;
            var url = "{{route('admin.updatePassword')}}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : "POST",
                url : url,
                data : {new_password, user_id},
                beforeSend: function() {
                    $('#updatePasswordButton').html(
                        "<svg width='25' viewBox='-2 -2 55 55' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> &nbsp;&nbsp; Updating..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $("#updatePasswordButton").html("Update Password").removeAttr('disabled');
                },
                success : function(response) {
                    var result = JSON.parse(response);
                    if(result.type == 'success'){
                        $("#passwordChangeForm").trigger("reset");
                        $("#newPassword").attr('disabled', 'disabled');
                        $("#confirmPassword").attr('disabled', 'disabled');
                        $("#updatePasswordButton").hide();
                        $("#sendOtpButton").show();
                        $("#sendOtpButton").html("Send OTP");
                        $("#sendOtpButton").attr('disabled', 'disabled');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        } else{
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });
        });
        //============================== update password end ==============================

    });
</script>
@endsection
