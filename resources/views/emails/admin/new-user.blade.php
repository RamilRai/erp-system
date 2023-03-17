<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Mail</title>
</head>
<body>
    <h1># Welcome</h1>
    <p>{{$user->first_name}},</p>
    
    <h3 style="text-decoration: underline;">Following are your login details:</h3>
    <p>Email: {{$user->email}}</p>
    <p>Username: "{{$user->username}}"</p>
    <p>Password: "password", Please change it for security reason.</p>
    <a href="{{route('admin.login')}}">Click here for login</a>
    
    <i>"Please, don't share this information with anyone else."</i>
    <br>
    <p>Thanks,</p><br>
    <p>CLTech Team</p>
</body>
</html>