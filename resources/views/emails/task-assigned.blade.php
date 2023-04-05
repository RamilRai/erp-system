<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Assigned</title>
</head>
<body>
    <div>
        <p>Dear {{$member->first_name}} {{$member->middle_name}} {{$member->last_name}},</p>
    </div>
    <div>
        <p>I hope this email finds you well, you are assigned to new task #{{$taskManagement->ticket_number}} {{$taskManagement->task_title}}. The time duration for this task is {{$taskManagement->task_start_date_bs}} to {{$taskManagement->task_end_date_bs}}.</p>
        <p>If you face any difficulties or require additional resources to complete the task, please do not hesitate to reach out to me or your immediate supervisor for assistance.</p>
        <p>Thank you for your cooperation and dedication to the project. I look forward to your timely completion of the task.</p>
    </div>
    
    <div>
        <p> Best Regards, </p>
        <p> {{$taskManagement->assignedBy->first_name}} {{$taskManagement->assignedBy->middle_name}} {{$taskManagement->assignedBy->last_name}} </p>
    </div>

    @if (!empty($taskManagement->task_description))
        <div>
            <p><u>Task Description:</u></p>
            <div>{!! $taskManagement->task_description !!}</div>
        </div>
    @endif
</body>
</html>