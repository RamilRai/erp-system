@extends('layouts.adminpanel.design')

@section('title') Dashboard @endsection

@section('main-content')

<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between d-flex justify-content-center">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Welcome To Dashboard</h3>
            @include('layouts.include.alertMessage')
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->

@endsection

@section('main-styles')

@endsection

@section('main-scripts')
<script type="text/javascript">
    $(document).ready(function () {
        //================== fadeout error messages start ==================

        //================== fadeout error messages end ==================
    });
</script>
@endsection
