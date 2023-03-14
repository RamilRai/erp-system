<div class="modal-header">
    <h5 class="modal-title">View Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="card-inner">
        <div class="nk-block">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Body</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td>{{$result->first_name}} {{$result->profile->middle_name}} {{$result->profile->last_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Username</th>
                        <td>{{$result->username}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Profile Image</th>
                        <td><img src="{{asset('storage/users-profile/'.$result->profile->profile)}}" height="100px" alt="profile image"></td>
                    </tr>
                    <tr>
                        <th scope="row">Permanent Address</th>
                        <td>{{$result->profile->permanent_address}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Temporary Address</th>
                        <td>{{$result->profile->temporary_address}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td>{{$result->phone_number}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email Address</th>
                        <td>{{$result->email}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td>{{$result->profile->gender}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Date of Birth (BS)</th>
                        <td>{{$result->profile->dob_bs}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Date of Birth (AD)</th>
                        <td>{{$result->profile->dob_ad}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Blood Group</th>
                        <td>{{$result->profile->blood_group}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>