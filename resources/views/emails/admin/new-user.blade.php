@component('mail::message')
# Welcome
<p>{{$user->first_name}},</p>

<h1 style="text-decoration: underline;">Following are your login details:</h1>
<p>Email: {{$user->email}}</p>
<p>Username: "{{$user->username}}"</p>
<p>Password: "password", Please change it for security reason.</p>
<p>OTP: "{{$user->otp}}"</p>
<a href="{{route('admin.login')}}">Click here for login</a>

<i>"Please, don't share this information with anyone else."</i>
<br>
Thanks,<br>
CLTech Team
@endcomponent