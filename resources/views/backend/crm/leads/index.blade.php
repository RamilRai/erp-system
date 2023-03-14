@extends('layouts.adminpanel.design')

@section('title') Leads @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between" style="margin: -4% -5% 0%">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Leads</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="#" class="btn btn-icon btn-primary ps-2" id="loadLeadForm" data-bs-toggle="modal" data-bs-target="#modalForm">Add<em class="icon ni ni-plus"></em></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                <div class="card card-bordered card-preview" style="margin: -1% -5% 0%">
                    <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="leadTable">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="whitespace-nowrap">S.No.</th>
                                    <th class="whitespace-nowrap">Organization Type</th>
                                    <th class="whitespace-nowrap">Organization Name</th>
                                    <th class="whitespace-nowrap">Address</th>
                                    <th class="whitespace-nowrap">Lead By Name</th>
                                    <th class="whitespace-nowrap">Status</th>
                                    <th class="whitespace-nowrap">Change Status</th>
                                    <th class="whitespace-nowrap" width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->

                <!-- Form - Start -->
                <div id="loadLeadModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="leadModalLabel" id="leadModalShow" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form - End -->

            </div>
        </div>
    </div>
</div>

@endsection

@section('main-scripts')

    <script>
        $(document).ready(function () {

            //================== open modal form start ==================
            $('#loadLeadForm').on('click', function(){
                var url = '{{route('lead.create')}}';
                var _token = '{{csrf_token()}}';
                var data = {_token:_token};
                $.post(url, data, function(response){
                    $('#loadLeadModal .modal-content').html(response);
                    $('#leadModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== open modal form end ==================

            //================== close modal form start ==================
            $(document).on('click', '.close', function(){
                $("#leadModalShow").hide();
            });
            //================== close modal form end ==================

            //================== data table start ==================
            leadTable = $('#leadTable').dataTable({
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
                "sAjaxSource" : '{{route('lead.fetch')}}',
                "oLanguage" : {
                    "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
                },
                "aoColumnDefs" : [{
                        "bSortable" : false,
                        "aTargets" : [0,]
                    },
                    { "sWidth" : "15%", "aTargets": [6,] }
                    ],
                "aoColumns" : [
                    {data:'sn'},
                    {data:'organizationType'},
                    {data:'organizationName'},
                    {data:'address'},
                    {data:'leadByName'},
                    {data:'leadStatus'},
                    {data:'changeLeadStatus'},
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
                        type: "text"
                    },
                    {
                        type: "select",
                        values: ["Pending", "Active", "Cancelled"]
                    },
                    {
                        type: "null"
                    },

                ]
            });
            //================== data table end ==================

            //================== edit data start ==================
            $(document).on('click','.editLead',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('lead.create')}}';
                var _token = "{{csrf_token()}}";
                var data = { id:id, _token:_token};
                $.post(url,data,function(response){
                    $('#loadLeadModal .modal-content').html(response);
                    $('#leadModalShow').css({"opacity": "1", "display": "block"});
                    $('#saveLeadInfo').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
                });
            });
            //================== edit data end ==================

            //================== delete data start ==================
            $(document).on('click','.deleteLead' ,function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('lead.delete')}}';
                var _token = "{{csrf_token()}}";
                var data = {
                    id:id,
                    '_token':_token,
                };
                Swal.fire({
                    title: 'Do you want to delete this lead?',
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
                                leadTable.fnDraw();
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
            $(document).on('click', '.viewLead', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = '{{route('lead.view')}}';
                var token = "{{csrf_token()}}";
                var data = {id:id, '_token':token};
                $.post(url, data, function(response){
                    $('#loadLeadModal .modal-content').html(response);
                    $('#leadModalShow').css({"opacity": "1", "display": "block"});
                });
            });
            //================== view data end ==================

            //================== change lead status start ==================
            $(document).on('click', '.changeLeadStatus', function (e) {
                e.preventDefault();
                var value = $(this).val();
                var id = $(this).data('id');
                var url = '{{route('change.lead.status')}}';
                var token = "{{csrf_token()}}";
                var data = {value:value, id:id, _token:token};
                Swal.fire({
                    title: 'Do you want to change lead status to ' + value + ' ?',
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
                                leadTable.fnDraw();
                            }else{
                                Swal.fire({
                                    title: "Couldn't Change Lead Status!",
                                    text: result.message,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
            //================== change lead status end ==================

            //================== open modal for Call Log start ==================
            $(document).on('click','.callLog',function(){
                var url = '{{route('lead.callLog')}}';
                var id = $(this).data('id');
                var _token = "{{csrf_token()}}";
                var data = {
                    _token : _token,
                    id : id,
                };
                $.post(url,data,function(response){
                    $('#loadLeadModal .modal-content').html(response);
                    $('#leadModalShow').css({"opacity": "1", "display": "block"});
                })
            });
            //================== open modal for Call Log end ==================

        });
    </script>

@endsection
