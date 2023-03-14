@if (Session::has('success'))  
    <div class="alert alert-success text-center sessionMessage" role="alert">
        <strong>{{Session::get('success')}}</strong>
    </div>
@endif

@if (Session::has('error'))  
    <div class="alert alert-danger text-center sessionMessage" role="alert">
        <strong>{{Session::get('error')}}</strong>
    </div>
@endif

@if (Session::has('warning'))  
    <div class="alert alert-warning text-center sessionMessage" role="alert">
        <strong>{{Session::get('warning')}}</strong>
    </div>
@endif