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
                        <td>{{$result->ticket_no}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Title</th>
                        <td>{{$result->task_title}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Created By</th>
                        <td>{{$result->createdBy->first_name}} {{$result->createdBy->middle_name}} {{$result->createdBy->last_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Project Name</th>
                        <td>{{$result->project->project_name}}</td>
                    </tr>
                    @if (!empty($result->updated_by))
                        <tr>
                            <th scope="row">Updated By</th>
                            <td>{{$result->updatedBy->first_name}} {{$result->updatedBy->middle_name}} {{$result->updatedBy->last_name}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Task Type</th>
                        <td>{{$result->task_type}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Status</th>
                        <td>{{$result->task_status}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Created Date & Time</th>
                        @php
                            $parsedDateTimeAD = Carbon\Carbon::parse($result->task_created_date_ad);
                        @endphp
                        <td>{{$parsedDateTimeAD->format('Y-m-d')}} A.D ({{Carbon\Carbon::parse($result->task_created_date_bs)->format('Y-m-d')}} B.S), {{$parsedDateTimeAD->format('h:i A')}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Task Description</th>
                        <td>{!! $result->task_description !!}</td>
                    </tr>
                    @if (!empty($result->task_completed_date_ad))
                        <tr>
                            <th scope="row">Task Completed Date & Time</th>
                            @php
                                $parsedCompletedDateTimeAD = Carbon\Carbon::parse($result->task_completed_date_ad);
                            @endphp
                            <td>{{$parsedCompletedDateTimeAD->format('Y-m-d')}} A.D ({{Carbon\Carbon::parse($result->task_completed_date_bs)->format('Y-m-d')}} B.S), {{$parsedCompletedDateTimeAD->format('h:i A')}}</td>
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
                                    <a href="{{asset('storage/extra-task-documents/'.$item)}}" target="_blank"><img src="{{asset('default-images/pdf.png')}}" height="50px" width="50px" alt="Documents"></a>
                                @elseif($fileExtention[1] == "docx")
                                    <a href="{{asset('storage/extra-task-documents/'.$item)}}" target="_blank"><img src="{{asset('default-images/word.png')}}" height="50px" width="50px" alt="Documents"></a>
                                @else
                                    <a href="{{asset('storage/extra-task-documents/'.$item)}}" target="_blank"><img src="{{asset('storage/extra-task-documents/'.$item)}}" height="50px" width="50px" alt="Documents"></a>
                                @endif
                            @endforeach
                            </td>
                        </tr>
                        @if (!empty($result->remarks))
                            <tr>
                                <th scope="row">Remarks/Feedback</th>
                                <td>{{$result->remarks }}</td>
                            </tr>
                        @endif
                    @endif
                    @if (!empty($result->task_verified_date_ad))
                        <tr>
                            <th scope="row">Task Verified Date & Time</th>
                            @php
                                $parsedVerifiedDateTimeAD = Carbon\Carbon::parse($result->task_verified_date_ad);
                            @endphp
                            <td>{{$parsedVerifiedDateTimeAD->format('Y-m-d')}} A.D ({{Carbon\Carbon::parse($result->task_verified_date_bs)->format('Y-m-d')}} B.S), {{$parsedVerifiedDateTimeAD->format('h:i A')}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Achieved Points</th>
                            <td>{{$result->achieved_point}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Verified By</th>
                            <td>{{$result->verifiedBy->first_name}} {{$result->verifiedBy->middle_name}} {{$result->verifiedBy->last_name}}</td>
                        </tr>
                        @if (!empty($result->supervisor_response))
                            <tr>
                                <th scope="row">Response From Supervisor</th>
                                <td>{{$result->supervisor_response }}</td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
