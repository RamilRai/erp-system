<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Verified</title>
</head>
<body>
    <div style="float: right;">
        <p><b>Date & Time:</b> {{date('Y-m-d', strtotime($taskManagement->verified_date_ad))}} {{date('g:i a', strtotime($taskManagement->verified_date_ad))}}, {{date('l', strtotime($taskManagement->verified_date_ad))}}</p>
    </div>
    
    <div style="clear:both">
        <p>Dear {{$members->first_name}} {{$members->middle_name}} {{$members->last_name}},</p>
    </div>
    <div>
        <p>I wanted to take a moment to thank you for your hard work in completing the task #{{$taskManagement->ticket_number}} {{$taskManagement->task_title}} assigned to you. I appreciate your attention to detail and the effort you put into ensuring that the task was completed accurately and efficiently.</p>
        <p>I have reviewed the task that you have completed and can confirm that it has been verified. I am impressed with the quality of work that you have produced, and it is a testament to your professionalism and dedication.</p>
        <p>Your contribution to our team and the company is highly valued, and I want to thank you for your commitment to excellence. Your work has not gone unnoticed, and I am grateful for your efforts.</p>
        <p>Please do not hesitate to reach out to me if you have any questions or concerns. I am always here to support you and provide guidance whenever you need it.</p>
        <p>Once again, thank you for your hard work and dedication to our team. I look forward to seeing more great work from you in the future.</p>
    </div>
    
    <div>
        <p> With Best Regards, </p>
        <p> {{$taskManagement->projects->profiles->first_name}} {{$taskManagement->projects->profiles->middle_name}} {{$taskManagement->projects->profiles->last_name}} </p>
    </div>
</body>
</html>