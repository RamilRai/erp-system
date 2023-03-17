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
                        <th scope="row">Project Name</th>
                        <td>{{$result->project_name}}</td>
                    </tr>
                    @if ($result->pdf)
                        <tr>
                            <th scope="row">Project PDF File</th>
                            <td><a href="{{asset('storage/projects-pdf/'.$result->pdf)}}" target="_black">View PDF</a></td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Project Type</th>
                        <td>{{$result->project_type}}</td>
                    </tr>
                    @if ($result->project_type == 'Client')
                        <tr>
                            <th scope="row">Client Company Name</th>
                            <td>{{$result->client_company_name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client's Contact Person</th>
                            <td>{{$result->contact_person}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Clinet's Phone Number</th>
                            <td>{{$result->phone_number}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client's Email Address</th>
                            <td>{{$result->email}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Project Time Duration</th>
                        <td>{{$result->project_time_duration}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Start Date</th>
                        <td>{{$result->start_date_bs}} B.S ({{$result->start_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">End Date</th>
                        <td>{{$result->end_date_bs}} B.S ({{$result->end_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">Project Lead By</th>
                        <td> <img src="{{asset('storage/users-profile/'.$result->profiles->profile)}}" alt="image" style="height:2.5rem; width:2.5rem; border-radius:50%;"> {{$result->profiles->first_name}} {{$result->profiles->middle_name}} {{$result->profiles->last_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Assigned Team Members</th>
                        <td>
                            @foreach ($assignedMembers as $item)
                                <div> <img src="{{asset('storage/users-profile/'.$item->profile)}}" alt="image" style="height:2.5rem; width:2.5rem; border-radius:50%;"> {{$item->first_name}} {{$item->middle_name}} {{$item->last_name}} @if (!$loop->last) , @endif</div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Project Status</th>
                        <td>{{$result->project_status}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>