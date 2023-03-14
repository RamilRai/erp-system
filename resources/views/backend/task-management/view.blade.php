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
                        <th scope="row">Ticket Number</th>
                        <td>{{$result->ticket_number}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Title</th>
                        <td>{{$result->task_title}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Project Name</th>
                        <td>{{$result->project_id}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Type</th>
                        <td>{{$result->task_type}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Estimated Hour</th>
                        <td>{{$result->estimated_hour}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Start Date</th>
                        <td>{{$result->task_start_date}} B.S  ({{$result->task_start_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">Task End Date</th>
                        <td>{{$result->task_end_date}} B.S  ({{$result->task_end_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Priority</th>
                        <td>{{$result->priority}}</td>
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
                        <th scope="row">Task Point</th>
                        <td>{{$result->task_point}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Status</th>
                        <td>{{$result->task_status}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Description</th>
                        <td>{!! $result->task_description !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>