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
                        <td>{{$result->projects->project_name}}</td>
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
                        <td>{{$result->task_start_date_bs}} B.S  ({{$result->task_start_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">Task End Date</th>
                        <td>{{$result->task_end_date_bs}} B.S  ({{$result->task_end_date_ad}} A.D)</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Priority</th>
                        <td>{{$result->priority}}</td>
                    </tr>
                    @if (!empty($result->assigned_by))
                        <tr>
                            <th scope="row">Task Assigned By</th>
                            <td>{{$result->assignedBy->first_name}} {{$result->assignedBy->middle_name}} {{$result->assignedBy->last_name}}</td>
                        </tr>
                    @endif
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
                    @if ($result->task_started_date_and_time_bs)
                        <tr>
                            <th scope="row">Task Started Date</th>
                            <td>{{$result->task_started_date_and_time_bs}} B.S ({{$result->task_started_date_and_time_ad}} A.D)</td>
                        </tr>
                    @endif
                    @if ($result->task_completed_date_and_time_ad)
                        <tr>
                            <th scope="row">Task Started Date</th>
                            <td>@if($result->task_completed_date_and_time_bs)({{$result->task_completed_date_and_time_bs}} B.S) @endif {{$result->task_completed_date_and_time_ad}} A.D</td>
                        </tr>
                        @php
                            $documentsArray = json_decode($result->documents);
                        @endphp
                        <tr>
                            <th scope="row">Documents</th>
                            <td>
                                @foreach ($documentsArray as $item)
                                @php
                                    $fileExtention = explode('.', $item);
                                @endphp
                                @if ($fileExtention[1] == "pdf")
                                    <a href="{{asset('storage/task-documents/'.$item)}}" target="_blank"><img src="{{asset('default-images/pdf.png')}}" height="50px" width="50px" alt="Documents"></a>
                                @elseif($fileExtention[1] == "docx")
                                    <a href="{{asset('storage/task-documents/'.$item)}}" target="_blank"><img src="{{asset('default-images/word.png')}}" height="50px" width="50px" alt="Documents"></a>
                                @else
                                    <a href="{{asset('storage/task-documents/'.$item)}}" target="_blank"><img src="{{asset('storage/task-documents/'.$item)}}" height="50px" width="50px" alt="Documents"></a>
                                @endif
                            @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Completed Status</th>
                            <td>{{$result->completed_status}}</td>
                        </tr>
                    @endif
                    @if ($result->feedback)
                        <tr>
                            <th scope="row">Feedback/Remarks</th>
                            <td>{{$result->feedback}}</td>
                        </tr>
                    @endif
                    @if ($result->verified_date_ad)
                        <tr>
                            <th scope="row">Verified By</th>
                            <td>{{$result->projects->profiles->first_name}} {{$result->projects->profiles->middle_name}} {{$result->projects->profiles->last_name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Verified Date</th>
                            <td>@if ($result->verified_date_bs)({{$result->verified_date_bs}} B.S) @endif {{$result->verified_date_ad}} A.D</td>
                        </tr>
                        <tr>
                            <th scope="row">Achieved Points</th>
                            <td>{{$result->achieved_point}}</td>
                        </tr>
                        @if ($result->response_from_supervisor)
                            <tr>
                                <th scope="row">Response From Supervisor</th>
                                <td>{{$result->response_from_supervisor}}</td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
