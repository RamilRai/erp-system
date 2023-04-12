<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extra Task Verified</title>
</head>
<body>
    <div style="float: right;"><p><b>Date & Time:</b> {{date('Y-m-d', strtotime($extraTask->task_verified_date_ad))}} {{date('g:i a', strtotime($extraTask->task_verified_date_ad))}}, {{date('l', strtotime($extraTask->task_verified_date_ad))}}</p>
    </div>

    <div style="clear:both">
        <p>Dear {{$extraTask->createdBy->first_name}},</p>
    </div>
    <div>
        <p>I hope this email finds you well. I wanted to express my sincere appreciation for your outstanding efforts in completing the extra task of #{{$extraTask->ticket_no}}-{{$extraTask->task_title}} on the {{$extraTask->project->project_name}}. Your dedication and hard work are truly commendable.</p>
        <p>Thank you for your hard work and dedication.</p>
    </div>
    <div>
        <p> Best Regards, </p>
        <p> {{$extraTask->project->profiles->first_name}} {{$extraTask->project->profiles->middle_name}} {{$extraTask->project->profiles->last_name}} </p>
    </div>
</body>
</html>
