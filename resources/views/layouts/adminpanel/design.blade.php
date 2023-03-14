<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>@yield('title') | ERP System</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/dashlite.css?ver=3.0.3')}}">
    <link id="skin-default" rel="stylesheet" href="{{asset('backend/assets/css/theme.css?ver=3.0.3')}}">

    {{-- nepali date picker style --}}
    <link href="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/css/nepali.datepicker.v4.0.min.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{asset('backend/links/dataTables.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- select 2 css --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    @yield('main-styles')
    <style>
        .fade:not(.show) {
            background: #000000b3;
        }
    </style>
</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            @include('layouts.adminpanel.sidebar')
            <!-- wrap @s -->
            <div class="nk-wrap ">
                @include('layouts.adminpanel.header')
                    <!-- content @s -->
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    @yield('main-content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content @e -->
                @include('layouts.adminpanel.footer')
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{asset('backend/assets/js/bundle.js?ver=3.0.3')}}"></script>
    <script src="{{asset('backend/assets/js/scripts.js?ver=3.0.3')}}"></script>
    <script src="{{asset('backend/assets/js/charts/gd-default.js?ver=3.0.3')}}"></script>

    {{-- jquery localized script --}}
    <script src="{{asset('backend/links/jquery-3.6.3.js')}}"></script>

    {{-- nepali date picker script --}}
    <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.min.js" type="text/javascript"></script>

    {{-- jquery validation script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- ajax submit script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- cdn link for ck edittor --}}
    <script src="{{asset('backend/links/ckeditor/ckeditor.js')}}"></script>

    {{-- datatable js link --}}
    <script src="{{asset('backend/links/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/dist/js/jquery.dataTables.columnFilter.js')}}"></script>

    {{-- select 2 js link --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        function getUserData(){
            var userid = {{Auth::user()->id}};
            var url = "{{route('admin.fetchProfile')}}";
            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url: url,
                type: "POST",
                data : {userid: userid},
                success:function(response)
                {
                    var res = JSON.parse(response);
                    var firstName = res.data.profile.first_name;
                    var middleName = res.data.profile.middle_name;
                    var lastName = res.data.profile.last_name;
                    $('.loggedInUserName').html(firstName + ' ' + middleName + ' ' + lastName);
                    $('.p_email').html(res.data.user.email);
                    $('.userProfile').attr("src","/storage/users-profile/"+res.data.profile.profile);
                    $('.p_first_name').html(firstName);
                    $('.p_middle_name').html(middleName);
                    $('.p_last_name').html(lastName);
                    $('.p_username').html(res.data.user.username);
                    $('.p_phone_number').html(res.data.user.phone_number);
                    $('.profileDOB').html(res.data.profile.dob_bs);
                    $('.p_permanent_address').html(res.data.profile.permanent_address);
                    $('.p_temporary_address').html(res.data.profile.temporary_address);
                    $('#first_name').val(firstName);
                    $('#middle_name').val(middleName);
                    $('#last_name').val(lastName);
                    $('#username').val(res.data.user.username);
                    $('#email').val(res.data.user.email);
                    $('#phone_number').val(res.data.user.phone_number);
                    $('#permanent_address').val(res.data.profile.permanent_address);
                    $('#temporary_address').val(res.data.profile.temporary_address);
                }
            });
        }
        getUserData();
    </script>
    <script>
        $('.sessionMessage').fadeOut(7000);
    </script>
    @yield('main-scripts')

</body>

</html>
