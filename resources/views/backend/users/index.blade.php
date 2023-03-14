@extends('layouts.adminpanel.design')

@section('title') User @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between" style="margin: -4% -5% 0%">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Users Lists</h3>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="#" class="btn btn-icon btn-primary ps-2" id="loadUserForm" data-bs-toggle="modal" data-bs-target="#modalForm">Add<em class="icon ni ni-plus"></em></a>
                        </div>
                    </div>
                </div>

                <div class="card card-bordered card-preview" style="margin: -1% -5% 0%">
                    <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="userTable">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="whitespace-nowrap">ID </th>
                                    <th class="whitespace-nowrap">Full Name</th>
                                    <th class="whitespace-nowrap">Profile Image</th>
                                    <th class="whitespace-nowrap">Email</th>
                                    <th class="whitespace-nowrap">Username</th>
                                    <th class="whitespace-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->

                <!-- Modal Open - Start -->
                <div id="loadUserModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="userModalLabel" id="userModalShow" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Open - End -->

            </div>
        </div>
    </div>
</div>

@endsection

@section('main-scripts')

    <script>
        $(document).ready(function () {

            //================== open modal form start ==================
            $('#loadUserForm').on('click', function(){
                var url = '{{route('user.create')}}';
                var _token = '{{csrf_token()}}';
                var data = {_token:_token};
                $.post(url, data, function(response){
                    $('#loadUserModal .modal-content').html(response);
                    $('#userModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== open modal form end ==================

            //================== close modal form start ==================
            $(document).on('click', '.close', function(){
                $("#userModalShow").hide();
            });
            //================== close modal form end ==================

            //================== data table start ==================
            userTable = $('#userTable').dataTable({
                "sPaginationType" : "full_numbers",
                "bSearchable" : false,
                "lengthMenu" : [
                    [10, 30, 50, 70, 90, -1],
                    [10, 30, 50, 70, 90, "All"]
                ],
                'iDisplayLength' : 10,
                "sDom" : 'ltipr',
                "bAutoWidth" : false,
                "aaSorting" : [
                    [0, 'desc']
                ],
                "bProcessing" : true,
                "bServerSide" : true,
                "sAjaxSource" : '{{route('user.fetch')}}',
                "oLanguage" : {
                    "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
                },
                "aoColumnDefs" : [{
                    "bSortable" : false,
                    "aTargets" : [0,]
                },
                { "sWidth" : "15%", "aTargets": [5,] }
                ],
                "aoColumns" : [
                    {data:'id'},
                    {data:'fullName'},
                    {data:'profile'},
                    {data:'email'},
                    {data:'username'},
                    {data:'action'},
                ]
            }).columnFilter({
                sPlaceHolder: "head:after",
                aoColumns: [
                    {
                        type: "null"
                    },
                    {
                        type: "text"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "text"
                    },
                    {
                        type: "text"
                    },
                    {
                        type: "null"
                    }

                ]
            });
            //================== data table end ==================

            //================== edit data start ==================
            $(document).on('click','.editUser',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('user.create')}}';
                var _token = "{{csrf_token()}}";
                var data = {id:id, _token:_token};
                $.post(url,data,function(response){
                    $('#loadUserModal .modal-content').html(response);
                    $('#userModalShow').css({"opacity": "1", "display": "block"});
                    $('#saveUserInfo').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
                });
            });
            //================== edit data end ==================

            //================== delete data start ==================
            $(document).on('click','.deleteUser' ,function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('user.delete')}}';
                var _token = "{{csrf_token()}}";
                var data = {
                    id:id,
                    '_token':_token,
                };
                Swal.fire({
                    title: 'Do you want to delete this user?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result)=>{
                    if(result.isConfirmed){
                        $.post(url,data,function(response){
                            var result = JSON.parse(response);
                            if(result.type == 'success'){
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: result.message,
                                    icon: 'success',
                                });
                                userTable.fnDraw();
                            }else{
                                Swal.fire({
                                    title: "Couldn't Delete!",
                                    text: result.message,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
            //================== delete data end ==================

            //================== view data start ==================
            $(document).on('click', '.viewUser', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('user.view')}}';
                var token = "{{csrf_token()}}";
                var data = {id:id, '_token':token};
                $.post(url, data, function(response){
                    $('#loadUserModal .modal-content').html(response);
                    $('#userModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== view data end ==================

        });
    </script>

@endsection