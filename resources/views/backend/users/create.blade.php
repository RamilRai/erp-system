<style>
    #documents {
        display: flex;
        flex-wrap: wrap;
    }

    #imgPreview img {
        width: 30px;
        height: 30px;
        margin: 5px;
        border: 1px solid gray;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">User Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="userForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="userId" value="{{isset($user)?$user->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="first_name">First Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{isset($user)?$user->first_name:''}}">
                </div>
                <p class="form-text text-danger first_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="middle_name">Middle Name </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="middle_name" value="{{isset($user)?$user->profile->middle_name:''}}">
                </div>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="last_name">Last Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{isset($user)?$user->profile->last_name:''}}">
                </div>
                <p class="form-text text-danger last_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="permanent_address">Permanent Address <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="permanent_address" name="permanent_address" value="{{isset($user)?$user->profile->permanent_address:''}}">
                </div>
                <p class="form-text text-danger permanent_address"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="temporary_address">Temporary Address <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="temporary_address" name="temporary_address" value="{{isset($user)?$user->profile->temporary_address:''}}">
                </div>
                <p class="form-text text-danger temporary_address"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="email">Email <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="email" name="email" value="{{isset($user)?$user->email:''}}">
                </div>
                <p class="form-text text-danger email"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="phone_number">Mobile Number <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{isset($user)?$user->phone_number:''}}">
                </div>
                <p class="form-text text-danger phone_number"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="username">Username <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="username" id="username" value="{{isset($user)?$user->username:''}}">
                    <a href="#" id="generateUsername">Generate username</a>
                </div>
                <p class="form-text text-danger username"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="profile">Profile Image <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control" id="profile" value="{{isset($user)?$user->profile:''}}" name="profile">
                    <div class="profilePreview mt-2">
                        @isset($user->profile)
                            <img src="{{asset('storage/users-profile/'.$user->profile->profile) }}" alt="profile" id="image" loading="lazy" style="height: 100px;">
                        @endisset
                    </div>
                </div>
                <p class="form-text text-danger profile"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="gender">Gender <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="gender" id="gender" class="form-control">
                        <option value="" hidden>-- Select Gender --</option>
                        <option value="Male" @if (@$user->profile->gender == 'Male') selected @endif>Male</option>
                        <option value="Female" @if (@$user->profile->gender == 'Female') selected @endif>Female</option>
                        <option value="Other" @if (@$user->profile->gender == 'Other') selected @endif>Other</option>
                    </select>
                </div>
                <p class="form-text text-danger gender"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="dob_bs">Date of Birth (BS) <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="dob_bs" name="dob_bs" value="{{isset($user)?$user->profile->dob_bs:''}}">
                </div>
                <p class="form-text text-danger dob_bs"></p>
            </div>
            <input type="hidden" id="dob_ad" name="dob_ad" value="{{isset($user)?$user->profile->dob_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="blood_group">Blood Group </label>
                <div class="form-control-wrap">
                    <select name="blood_group" class="form-control">
                        <option value="" hidden>-- Select Blood Group --</option>
                        <option value="A+" @if (@$user->profile->blood_group == 'A+') selected @endif>A+</option>
                        <option value="A-" @if (@$user->profile->blood_group == 'A-') selected @endif>A-</option>
                        <option value="B+" @if (@$user->profile->blood_group == 'B+') selected @endif>B+</option>
                        <option value="B-" @if (@$user->profile->blood_group == 'B-') selected @endif>B-</option>
                        <option value="O+" @if (@$user->profile->blood_group == 'O+') selected @endif>O+</option>
                        <option value="O-" @if (@$user->profile->blood_group == 'O-') selected @endif>O-</option>
                        <option value="AB+" @if (@$user->profile->blood_group == 'AB+') selected @endif>AB+</option>
                        <option value="AB-" @if (@$user->profile->blood_group == 'AB-') selected @endif>AB-</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="recruited_date_bs">Recruited Date (BS) <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="recruited_date_bs" name="recruited_date_bs" value="{{isset($user)?$user->profile->recruited_date_bs:''}}">
                </div>
                <p class="form-text text-danger recruited_date_bs"></p>
            </div>
            <input type="hidden" class="form-control" id="recruited_date_ad" name="recruited_date_ad" value="{{isset($user)?$user->profile->recruited_date_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="department_id">Department <code>*</code> <a href="#" id="addDepartment" class="text-primary" data-bs-toggle="modal" data-bs-target="#modalForm">Add Department</a></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="department_id" id="department_id">
                        <option value="" hidden>-- Select Department --</option>
                        @foreach ($department as $d_item)
                            <option class="projectName" value="{{$d_item->id}}" {{$d_item->id == @$user->profile->department_id ? 'selected':''}}>{{$d_item->department_name}}</option>
                        @endforeach
                    </select>
                </div>
                <p class="form-text text-danger department_id"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="documents">Documents <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control" id="documents" name="documents[]" multiple>
                </div>
                <p class="form-text text-danger documents"></p>
                <div id="imgPreview"></div>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveUserInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== nepali date picker start ==================
    $("#dob_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#dob_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#dob_ad").val(dateinAD);
            $("#dob_bs").trigger('change');
        }
    });

    $("#recruited_date_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#recruited_date_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#recruited_date_ad").val(dateinAD);
            $("#recruited_date_bs").trigger('change');
        }
    });
    //================== nepali date picker end ==================

    //================== generate username start ==================
    $("#generateUsername").on('click', function(e){
        e.preventDefault();
        var firstName = $("#first_name").val();
        var lastName = $("#last_name").val();
        var name = firstName + lastName;
        var url = '{{route('generate.username')}}';
        var token = "{{csrf_token()}}";
        var data = {name:name, _token:token};
        $.post(url, data, function(response){
            var result = JSON.parse(response);
            if (result.type == "success") {
                $('#username').val(result.response);
                $('#username').trigger('change');
            }
        });
    });
    //================== generate username end ==================

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

    //================== uploaded image preview start ==================
    $('#documents').on('change', function() {
        var files = $(this).prop('files');

        $('#imgPreview').empty();
        // Loop through each file and check if it is an image or pdf or word
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileReader = new FileReader();

            if (file.type.match('image.*')) {
                fileReader.onload = function(e) {
                    var imagePreview = $('<img>').attr('src', e.target.result);
                    $('#imgPreview').append(imagePreview);
                };
                fileReader.readAsDataURL(file);
            } else if(file.type === "application/pdf") {
                var fileName = $('<img>').attr('src', "{{asset('default-images/pdf.png')}}");
                $('#imgPreview').append(fileName);
            } else if(file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                var fileName = $('<img>').attr('src', "{{asset('default-images/word.png')}}");
                $('#imgPreview').append(fileName);
            }
        }
    });
    //================== uploaded image preview end ==================

    //================== store data start ==================
    $("#saveUserInfo").on('click', function(e){
        e.preventDefault();
        $('#userForm').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50
                },
                middle_name: {
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    maxlength: 50
                },
                permanent_address: {
                    required: true,
                    maxlength: 100
                },
                temporary_address: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    maxlength: 50,
                    email: true
                },
                phone_number: {
                    required: true,
                    minlength: 10
                },
                username: {
                    required: true,
                    maxlength: 50
                },
                profile: {
                    required: function(e){
                        return $('#userId').val() == "";
                    }
                },
                gender: 'required',
                dob_bs: 'required',
                recruited_date_bs: 'required',
                department_id: 'required',
                "documents[]": {
                    required: function(e){
                        return $('#userId').val() == "";
                    }
                }
            },
            messages: {
                first_name: {
                    required: "Please input first name.",
                    maxlength: "Please input not more than 50 characters."
                },
                middle_name: {
                    maxlength: "Please input not more than 50 characters."
                },
                last_name: {
                    required: "Please input last name.",
                    maxlength: "Please input not more than 50 characters."
                },
                permanent_address: {
                    required: "Please input permanent address.",
                    maxlength: "Please input not more than 100 characters."
                },
                temporary_address: {
                    required: "Please input temporary address.",
                    maxlength: "Please input not more than 100 characters."
                },
                email: {
                    required: "Please input email.",
                    maxlength: "Please input not more than 50 characters.",
                    email: "Please input valid email"
                },
                phone_number: {
                    required: "Please input phone number.",
                    minlength: "Please input not less than 10 numbers."
                },
                username: {
                    required: "Please input username.",
                    maxlength: "Please input not more than 50 characters."
                },
                profile: {
                    required: "Please upload profile image."
                },
                gender: {
                    required: "Please select gender."
                },
                dob_bs: {
                    required: "Please select date of birth."
                },
                recruited_date_bs: {
                    required: "Please select recruited date."
                },
                department_id: {
                    required: "Please select department."
                },
                "documents[]": {
                    required: "Please upload atleast one file."
                }
            }
        });
        if ($('#userForm').valid()) {
            var updateProfile = 'N';
            var token = "{{csrf_token()}}";
            $('#userForm').ajaxSubmit({
                url: '{{route('user.submit')}}',
                type: 'POST',
                data: {'_token':token, updateProfile},
                beforeSend: function() {
                    $('#saveUserInfo').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveUserInfo').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#userModalShow').hide();
                        $('#userForm')[0].reset();
                        userTable.fnDraw();
                    } else {
                        Swal.fire({
                            title: 'Error!',
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
    //================== store data end ==================

    //================== load options start ==================
    function loadDepartment(){
        var url = '{{route('department.load')}}';
        $.get(url, function (response) {
            var result = JSON.parse(response);
            if(result.type == 'success'){
                $('#department_id').html('');
                $.each(result.response,function(key,items){
                    $('#department_id').append("<option value='"+items.id+"'>"+items.department_name+"</option>")
                });
            }
        });
    } 
    //================== load options end ==================

</script>
