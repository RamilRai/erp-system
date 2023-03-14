@extends('layouts.adminpanel.design')

@section('title') Project Management @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between" style="margin: -4% -5% 0%">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Project Management</h3>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="#" class="btn btn-icon btn-primary ps-2" id="loadProjectManagementForm" data-bs-toggle="modal" data-bs-target="#modalForm">Add<em class="icon ni ni-plus"></em></a>
                        </div>
                    </div>
                </div>

                <div class="card card-bordered card-preview" style="margin: -1% -5% 0%">
                    <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="projectManagementTable">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="whitespace-nowrap">S.No. </th>
                                    <th class="whitespace-nowrap">Project Name</th>
                                    <th class="whitespace-nowrap">Project Type</th>
                                    <th class="whitespace-nowrap">Start time - End Time</th>
                                    <th class="whitespace-nowrap">Project Lead By</th>
                                    <th class="whitespace-nowrap">Assigned Team Members</th>
                                    <th class="whitespace-nowrap">Work Progress</th>
                                    <th class="whitespace-nowrap">Project Status</th>
                                    <th class="whitespace-nowrap">Change Project Status</th>
                                    <th class="whitespace-nowrap" style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->

                <!-- Modal Open - Start -->
                <div id="loadProjectManagementModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="projectManagementModalLabel" id="projectManagementModalShow" aria-hidden="true">
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
            $('#loadProjectManagementForm').on('click', function(){
                var url = '{{route('project-management.create')}}';
                var _token = '{{csrf_token()}}';
                var data = {_token:_token};
                $.post(url, data, function(response){
                    $('#loadProjectManagementModal .modal-content').html(response);
                    $('#projectManagementModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== open modal form end ==================

            //================== close modal form start ==================
            $(document).on('click', '.close', function(){
                $("#projectManagementModalShow").hide();
            });
            //================== close modal form end ==================

            //================== data table start ==================
            projectManagementTable = $('#projectManagementTable').dataTable({
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
                "sAjaxSource" : '{{route('project-management.fetch')}}',
                "oLanguage" : {
                    "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
                },
                "aoColumnDefs" : [{
                    "bSortable" : false,
                    "aTargets" : [0,]
                },
                {"aTargets": [9,] }
                ],
                "aoColumns" : [
                    {data:'sn'},
                    {data:'projectName'},
                    {data:'projectType'},
                    {data:'timeDuration'},
                    {data:'leadBy'},
                    {data:'assignedMembers'},
                    {data:'workProgress'},
                    {data:'projectStatus'},
                    {data:'changeProjectStatus'},
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
                        type: "text"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    },
                    {
                        type: "null"
                    }
                ]
            });
            //================== data table end ==================

            //================== edit data start ==================
            $(document).on('click','.editProjectManagement',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('project-management.create')}}';
                var _token = "{{csrf_token()}}";
                var data = {id:id, _token:_token};
                $.post(url,data,function(response){
                    $('#loadProjectManagementModal .modal-content').html(response);
                    $('#projectManagementModalShow').css({"opacity": "1", "display": "block"});
                    $('#saveProjectManagementInfo').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
                    $('#project_type').trigger('change');
                });
            });
            //================== edit data end ==================

            //================== view data start ==================
            $(document).on('click', '.viewProjectManagement', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('project-management.view')}}';
                var token = "{{csrf_token()}}";
                var data = {id:id, '_token':token};
                $.post(url, data, function(response){
                    $('#loadProjectManagementModal .modal-content').html(response);
                    $('#projectManagementModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== view data end ==================

            //================== delete data start ==================
            $(document).on('click','.deleteProjectManagement' ,function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('project-management.delete')}}';
                var _token = "{{csrf_token()}}";
                var data = {
                    id:id,
                    '_token':_token,
                };
                Swal.fire({
                    title: 'Do you want to delete this data?',
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
                                    icon: 'success'
                                });
                                projectManagementTable.fnDraw();
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

            //================== change project status start ==================
            $(document).on('click', '.changeProjectStatus', function (e) {
                e.preventDefault();
                var value = $(this).val();
                var id = $(this).data('id');
                var url = '{{route('change.project.status')}}';
                var token = "{{csrf_token()}}";
                var data = {value:value, id:id, _token:token};
                Swal.fire({
                    title: 'Do you want to change project status to ' + value + ' ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change!'
                }).then((result)=>{
                    if(result.isConfirmed){
                        $.post(url,data,function(response){
                            var result = JSON.parse(response);
                            if(result.type == 'success'){
                                Swal.fire({
                                    title: 'Changed!',
                                    text: result.message,
                                    icon: 'success',
                                });
                                projectManagementTable.fnDraw();
                            }else{
                                Swal.fire({
                                    title: "Couldn't Change Project Status!",
                                    text: result.message,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
            //================== change project status end ==================

        });
    </script>

@endsection