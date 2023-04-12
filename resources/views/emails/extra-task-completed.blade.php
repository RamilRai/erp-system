<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extra Task Completed</title>
</head>
<body>
    <div style="float: right;"><p><b>Date & Time:</b> {{date('Y-m-d', strtotime($extraTask->task_completed_date_ad))}} {{date('g:i a', strtotime($extraTask->task_completed_date_ad))}}, {{date('l', strtotime($extraTask->task_completed_date_ad))}}</p>
    </div>

    <div style="clear:both">
        <p>Dear {{$extraTask->project->profiles->first_name}},</p>
    </div>
    <div>
        @php
            $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $extraTask->task_completed_date_ad);
            $formattedDate = $date->format('d F, Y h:i A');
        @endphp
        <p>I hope this email finds you well. I wanted to bring to your attention an additional task that I have completed extra task #{{$extraTask->ticket_no}}-{{$extraTask->task_title}} on the {{$extraTask->project->project_name}} on time {{$formattedDate}}.</p>
    </div>
    <div>
        <p> Sincerely, </p>
        <p> {{$extraTask->createdBy->first_name}} {{$extraTask->createdBy->middle_name}} {{$extraTask->createdBy->last_name}} </p>
    </div>
</body>
</html>
