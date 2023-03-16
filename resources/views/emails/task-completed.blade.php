<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Completed</title>
</head>
<body>
    <div style="float: right;"><p><b>Date & Time:</b> {{date('Y-m-d', strtotime($taskManagement->task_completed_date_and_time_ad))}} {{date('g:i a', strtotime($taskManagement->task_completed_date_and_time_ad))}}, {{date('l', strtotime($taskManagement->task_completed_date_and_time_ad))}}</p>
    </div>
    
    <div style="clear:both">
        <p>Dear {{$taskManagement->projects->profiles->first_name}},</p>
    </div>
    <div>
        <p>I am emailing today to express my gratitude for completing provided task #{{$taskManagement->ticket_number}} {{$taskManagement->task_title}}. <br>
            Please review the attached information containing about task. Thank you for your time in reviewing this email.
        </p>
    </div>
    
    <div>
        <p> Sincerely, </p>
        <p>
            @php
                $users = json_decode($taskManagement->assigned_to);
                $assignedTeamMembers = App\Models\Profile::select('first_name', 'middle_name', 'last_name')->whereIn('user_id', $users)->get();
            @endphp
            @foreach ($assignedTeamMembers as $item)
            {{$item->first_name}} {{$item->middle_name}} {{$item->last_name}} <br>
            @endforeach
        </p>
    </div>
    
    
    <u>Project Information:</u>
    <p style="font-size: 14px">
        - Project Name: {{$taskManagement->projects->project_name}} <br>
        - Task Ticket & Title: #{{$taskManagement->ticket_number}} {{$taskManagement->task_title}} <br>
        - Task Type & Priority: {{$taskManagement->task_type}} & {{$taskManagement->priority}} <br>
        - Task Assigned Time Duration: {{$taskManagement->task_start_date_ad}} to {{$taskManagement->task_end_date_ad}} <br>
        - Task Completed Time Duration: {{$taskManagement->task_started_date_and_time_ad}} to {{$taskManagement->task_completed_date_and_time_ad}} <br>
        - Completed Status: {{$taskManagement->completed_status}}
    </p>
    
    <div>
        <p style="text-align:center;font-size:12px">
            This is an automated email sent by code logic; do not reply to or forward this email.
        </p>
        <p style="text-align:center;font-size:12px">
            {{ date('Y') }} Code Logic, Jana Ekta Marg, Buddhanagar, Kathmandu 44600, Nepal.
        </p>
    </div>
</body>
</html>