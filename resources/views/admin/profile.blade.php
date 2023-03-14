@extends('layouts.adminpanel.design')

@section('title') Update Profile @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="card card-bordered" style="margin: -5% -5%">
                        <div class="card-aside-wrap">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head nk-block-head-lg">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Personal Information</h4>
                                        </div>
                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="nk-data data-list">
                                        <div class="data-head">
                                            <h6 class="overline-title">Manage Profile Informations</h6>
                                        </div>
                                        <div class="data-item" data-bs-toggle="modal">
                                            <div class="data-col">
                                                <span class="data-label">First Name</span>
                                                <span class="data-value p_first_name"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item" data-bs-toggle="modal">
                                            <div class="data-col">
                                                <span class="data-label">Middle Name</span>
                                                <span class="data-value p_middle_name"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Last Name</span>
                                                <span class="data-value p_last_name"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Username</span>
                                                <span class="data-value p_username"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Email</span>
                                                <span class="data-value p_email"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Phone Number</span>
                                                <span class="data-value p_phone_number"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Date of Birth (BS)</span>
                                                <span class="data-value profileDOB"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Permanent Address</span>
                                                <span class="data-value p_permanent_address"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Temporary Address</span>
                                                <span class="data-value p_temporary_address"></span>
                                            </div>
                                            <div class="data-col data-col-end"></div>
                                        </div><!-- data-item -->
                                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#profile-edit">Update Profile</button>
                                    </div><!-- data-list -->
                                </div><!-- .nk-block -->
                            </div>
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<!-- @@ Profile Edit Modal @e -->
<div class="modal fade" role="dialog" id="profile-edit">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">Update Profile</h5>
                <ul class="nk-nav nav nav-tabs"></ul><!-- .nav-tabs -->
                <div class="tab-content">
                    <div class="tab-pane active" id="profileDetails">
                        <form action="{{route('user.submit')}}" method="POST" enctype="multipart/form-data" id="userProfile">
                            <div class="row">
                                <input type="hidden" value="{{$user->id}}" name="id" id="userId">
                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        <label class="form-label" for="first_name">First Name <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="first_name" name="first_name">
                                    </div>
                                    <p class="form-text text-danger first_name"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="middle_name">Middle Name <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="middle_name" name="middle_name">
                                    </div>
                                    <p class="form-text text-danger middle_name"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="last_name">Last Name <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="last_name" name="last_name">
                                    </div>
                                    <p class="form-text text-danger last_name"></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="form-label" for="username">Username <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="username" name="username">
                                    </div>
                                    <p class="form-text text-danger username"></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email Address <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="email" name="email">
                                    </div>
                                    <p class="form-text text-danger email"></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="form-label" for="phone_number">Phone Number <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="phone_number" name="phone_number">
                                    </div>
                                    <p class="form-text text-danger phone_number"></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="permanent_address">Permanent Address <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="permanent_address" name="permanent_address">
                                    </div>
                                    <p class="form-text text-danger permanent_address"></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="form-label" for="temporary_address">Temporary Address <code>*</code></label>
                                        <input type="text" class="form-control form-control-lg" id="temporary_address" name="temporary_address" >
                                    </div>
                                    <p class="form-text text-danger temporary_address"></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="profile">Profile Image <code>*</code></label>
                                        <input type="file" id="profile" class="form-control form-control-lg" name="profile">
                                        <div class="profilePreview mt-2">
                                            @if (Auth::user()->profile->profile)
                                                <img class="userProfile" src='' height="100px"/>
                                            @else
                                                <img src="{{asset('default-images/avatar.png')}}" height="100px" alt="avatar"/>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="form-text text-danger profile"></p>
                                </div>
                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <a href="#" class="btn btn-lg btn-primary" id="saveUserProfile">Update Profile</a>
                                        </li>
                                        <li>
                                            <a href="#" data-bs-dismiss="modal" class="link link-light">Cancel</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div><!-- .tab-pane -->
                </div><!-- .tab-content -->
            </div><!-- .modal-body -->
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->

@endsection

@section('main-scripts')
<script type="text/javascript">
    $(document).ready(function () {

        //================== preview image while uploading start ==================
        $('#profile').on('change', function(){
            var filereader = new FileReader();
            filereader.onload = (e) => {
                $('.profilePreview').html('<img src="#" id="img" alt="profile"  style="height:100px;">');
                $('#img').attr('src', e.target.result);
            }
            filereader.readAsDataURL(this.files[0]);
        });
        //================== preview image while uploading end ==================

        //================== update profile start ==================
        $("#saveUserProfile").on('click', function(e){
            e.preventDefault();
            $('#userProfile').validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    username: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    phone_number: "required",
                    permanent_address: "required",
                    temporary_address: "required"
                },
                messages: {
                    first_name: {
                        required: "Please input your first name."
                    },
                    last_name: {
                        required: "Please input your last name."
                    },
                    username: {
                        required: "Please input your username."
                    },
                    email: {
                        required: "Please input email.",
                        email: "Please input valid email."
                    },
                    phone_number: {
                        required: "Please input your phone number."
                    },
                    permanent_address: {
                        required: "Please input your permanent address."
                    },
                    temporary_address: {
                        required: "Please input your temporary address."
                    }
                }
            });
            if($('#userProfile').valid()){
                var updateProfile = 'Y';
                var token = "{{csrf_token()}}";
                $('#userProfile').ajaxSubmit({
                    data:{'_token':token, updateProfile},
                    beforeSend: function() {
                        $('#saveUserProfile').html(
                            "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Updating..."
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('#saveUserProfile').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Update").removeAttr('disabled');
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if(result.type=='success'){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                text: result.message,
                            });
                            getUserData();
                            $('#profile-edit').modal('toggle');
                        }else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                text: result.message
                            });
                        }
                    },
                    error: function(response) {
                        var errors =  response.responseJSON.errors;
                        $.each(errors, function (key, val) {
                            $('.'+key).html('');
                            $('.'+key).append(val);
                            $('#'+key).addClass('border-danger');
                            $('#'+key).change(function(){
                                $('.'+key).html('');
                                $('#'+key).removeClass('border-danger');
                            });
                        });
                    }
                });
            }
        });
        //================== update profile end ==================

    });
</script>
@endsection
