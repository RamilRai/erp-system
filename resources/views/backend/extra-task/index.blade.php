@extends('layouts.adminpanel.design')

@section('title') Extra Task @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between" style="margin: -4% -3% 0%">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Extra Task</h3>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="#" class="btn btn-icon btn-primary ps-2" id="loadExtraTaskForm" data-bs-toggle="modal" data-bs-target="#modalForm">Add<em class="icon ni ni-plus"></em></a>
                        </div>
                    </div>
                </div>

                <div class="card card-bordered card-preview" style="margin: -1% -5% 0%">
                    <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="extraTaskTable">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="whitespace-nowrap">Ticket No.</th>
                                    <th class="whitespace-nowrap">Task Title</th>
                                    <th class="whitespace-nowrap">Project Name</th>
                                    <th class="whitespace-nowrap">Task Type</th>
                                    <th class="whitespace-nowrap">Task Status</th>
                                    <th class="whitespace-nowrap">Change Task Status</th>
                                    <th class="whitespace-nowrap" style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->

                <!-- Modal Open - Start -->
                <div id="loadExtraTaskModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="extraTaskModalLabel" id="extraTaskModalShow" aria-hidden="true">
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

        //================== list projects in searchable field of datatable start ==================
        var projectArray = JSON.parse('{!! json_encode($projects) !!}') ;
        var updatedProjectsArray = projectArray.map(obj => {
                                return {
                                    value: obj.id,
                                    label: obj.project_name
                                };
                            });
        //================== list projects in searchable field of datatable end ==================

        //================== open modal form start ==================
        $('#loadExtraTaskForm').on('click', function(){
            var url = '{{route('extra-task.create')}}';
            var _token = '{{csrf_token()}}';
            var data = {_token:_token};
            $.post(url, data, function(response){
                $('#loadExtraTaskModal .modal-content').html(response);
                $('#extraTaskModalShow').css({"opacity": "1", "display": "block"});
                $('.modal-dialog').removeClass('modal modal-lg');
                $('.modal-dialog').addClass('modal-xl');
            });
        });
        //================== open modal form end ==================

        //================== close modal form start ==================
        $(document).on('click', '.close', function(){
            $("#extraTaskModalShow").hide();
        });
        //================== close modal form end ==================

        //================== data table start ==================
        extraTaskTable = $('#extraTaskTable').dataTable({
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
            "sAjaxSource" : '{{route('extra-task.fetch')}}',
            "oLanguage" : {
                "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
            },
            "aoColumnDefs" : [{
                "bSortable" : false,
                "aTargets" : [0,]
            },
            {"aTargets": [6,] }
            ],
            "aoColumns" : [
                {data:'ticketNo'},
                {data:'taskTitle'},
                {data:'projectName'},
                {data:'taskType'},
                {data:'taskStatus'},
                {data:'changeTaskStatus'},
                {data:'action'}
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
                    type: "select",
                    values: updatedProjectsArray
                },
                {
                    type: "select",
                    values: ["New Task", "Fix Bug", "Correction", "Testing", "Documentation", "Support"]
                },
                {
                    type: "select",
                    values: ["On Progress", "Completed", "Verified"]
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
        $(document).on('click','.editExtraTask',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('extra-task.create')}}';
            var _token = "{{csrf_token()}}";
            var data = {id:id, _token:_token};
            $.post(url,data,function(response){
                $('#loadExtraTaskModal .modal-content').html(response);
                $('#extraTaskModalShow').css({"opacity": "1", "display": "block"});
                $('#saveExtraTaskInfo').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
                $('.modal-dialog').removeClass('modal modal-lg');
                $('.modal-dialog').addClass('modal-xl');
            });
        });
        //================== edit data end ==================

        //================== view data start ==================
        $(document).on('click', '.viewExtraTask', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('extra-task.view')}}';
            var token = "{{csrf_token()}}";
            var data = {id:id, '_token':token};
            $.post(url, data, function(response){
                $('#loadExtraTaskModal .modal-content').html(response);
                $('#extraTaskModalShow').css({"opacity": "1", "display": "block"});
                $('.modal-dialog').removeClass('modal modal-lg');
                $('.modal-dialog').addClass('modal-xl');
            });
        });
        //================== view data end ==================

        //================== delete data start ==================
        $(document).on('click','.deleteExtraTask' ,function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('extra-task.delete')}}';
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
                            extraTaskTable.fnDraw();
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

        //================== change task status start ==================
        $(document).off('change', '.changeTaskStatus');
        $(document).on('change', '.changeTaskStatus', function () {
            var value = $(this).val();
            var id = $(this).data('id');
            var url = '{{route('change.extra-task.status')}}';
            var token = "{{csrf_token()}}";
            var data = {value:value, id:id, _token:token};
            Swal.fire({
                title: 'Do you want to change task status to ' + value + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change!'
            }).then((result)=>{
                if(result.isConfirmed){
                    $.post(url,data,function(response){
                        var result = JSON.parse(response);
                        console.log(result);
                        if(result.type == 'success' && result.response == false){
                            Swal.fire({
                                title: 'Changed!',
                                text: result.message,
                                icon: 'success',
                            });
                            extraTaskTable.fnDraw();
                        } else if (result.type == 'success' && result.response == 'completed') {
                            var url = '{{route('extra-task.documents')}}';
                            var _token = '{{csrf_token()}}';
                            var data = {_token:_token, id:id};
                            $.post(url, data, function(response){
                                $('#loadExtraTaskModal .modal-content').html(response);
                                $('#extraTaskModalShow').css({"opacity": "1", "display": "block"});
                                $('.modal-dialog').removeClass('modal modal-xl');
                                $('.modal-dialog').addClass('modal-lg');
                            });
                        } else if(result.type == 'success' && result.response == 'verified'){
                            var url = '{{route('extra-task.marks')}}';
                            var _token = '{{csrf_token()}}';
                            var data = {_token:_token, id:id};
                            $.post(url, data, function(response){
                                $('#loadExtraTaskModal .modal-content').html(response);
                                $('#extraTaskModalShow').css({"opacity": "1", "display": "block"});
                                $('.modal-dialog').removeClass('modal-lg modal-xl');
                                $('.modal-dialog').addClass('modal');
                            });
                        } else{
                            Swal.fire({
                                title: "Couldn't Change Task Status!",
                                text: result.message,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
        //================== change task status end ==================

    </script>

@endsection
