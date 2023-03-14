@extends('layouts.adminpanel.design')

@section('title') Customers @endsection

@section('main-content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between" style="margin: -4% -5% 0%">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Customers</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="#" class="btn btn-icon btn-primary ps-2" id="loadCustomerForm" data-bs-toggle="modal" data-bs-target="#modalForm">Add<em class="icon ni ni-plus"></em></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                <div class="card card-bordered card-preview" style="margin: -1% -5% 0%">
                    <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="customerTable">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="whitespace-nowrap">S.No.</th>
                                    <th class="whitespace-nowrap">Company Name</th>
                                    <th class="whitespace-nowrap">Owner Name</th>
                                    <th class="whitespace-nowrap">Email</th>
                                    <th class="whitespace-nowrap">Mobile Number</th>
                                    <th class="whitespace-nowrap">Service Name</th>
                                    <th class="whitespace-nowrap">Contract Status</th>
                                    <th class="whitespace-nowrap">Last Call Date</th>
                                    <th class="whitespace-nowrap" width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->

                <!-- Form - Start -->
                <div id="loadCustomerModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="customerModalLabel" id="customerModalShow" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form - End -->

                <!-- Modal Service Type Form - Start -->
                <div id="loadServiceTypeFormModal">
                    <div class="modal fade" tabindex="-1" aria-labelledby="servicesTypeModalLabel" id="serviceTypeModalShow" aria-hidden="true">
                        <div class="modal-dialog modal" role="document">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Service Type Form - End -->

            </div>
        </div>
    </div>
</div>

@endsection

@section('main-scripts')
    <script>

        //================== open modal form start ==================
        $('#loadCustomerForm').on('click', function(){
            var url = '{{route('customer.create')}}';
            var _token = '{{csrf_token()}}';
            var data = {_token:_token};
            $.post(url, data, function(response){
                $('#loadCustomerModal .modal-content').html(response);
                $('#customerModalShow').css({"opacity": "1", "display": "block"});
            });
        });
        //================== open modal form end ==================

        //================== close modal form start ==================
        $(document).on('click', '.close', function(){
            $("#customerModalShow").hide();
        });
        //================== close modal form end ==================

        //================== data table start ==================
        customerTable = $('#customerTable').dataTable({
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
            "sAjaxSource" : '{{route('customer.fetch')}}',
            "oLanguage" : {
                "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
            },
            "aoColumnDefs" : [{
                "bSortable" : false,
                "aTargets" : [0,]
            },
            { "sWidth" : "15%", "aTargets": [8,] }
            ],
            "aoColumns" : [
                {data:'sn'},
                {data:'companyName'},
                {data:'ownerName'},
                {data:'email'},
                {data:'mobileNumber'},
                {data:'serviceName'},
                {data:'contractStatus'},
                {data:'lastCallDate'},
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
                    type: "text"
                },
                {
                    type: "text"
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
                }
            ]
        });
        //================== data table end ==================

        //================== edit data start ==================
        $(document).on('click','.editCustomer',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('customer.create')}}';
            var _token = "{{csrf_token()}}";
            var data = { id:id, _token:_token};
            $.post(url,data,function(response){
                $('#loadCustomerModal .modal-content').html(response);
                $('#customerModalShow').css({"opacity": "1", "display": "block"});
                $('#saveCustomerInfo').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
            });
        });
        //================== edit data end ==================

        //================== view data start ==================
        $(document).on('click', '.viewCustomer', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('customer.view')}}';
            var token = "{{csrf_token()}}";
            var data = {id:id, '_token':token};
            $.post(url, data, function(response){
                $('#loadCustomerModal .modal-content').html(response);
                $('#customerModalShow').css({"opacity": "1", "display": "block"});
            });
        });
        //================== view data end ==================

        //================== delete data start ==================
        $(document).on('click','.deleteCustomer' ,function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('customer.delete')}}';
            var _token = "{{csrf_token()}}";
            var data = {
                id:id,
                '_token':_token,
            };
            Swal.fire({
                title: 'Do you want to delete this customer?',
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
                            customerTable.fnDraw();
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

        //================== Open Service Type Model start ==================
        $(document).on('click','#addService',function(e){
            e.preventDefault();
            var url = '{{route('serviceType.form')}}';
            var _token = "{{csrf_token()}}";
            var data={
                _token:_token,
            };
            $.post(url,data,function(response){
                $('#loadServiceTypeFormModal .modal-content').html(response);
                $('#serviceTypeModalShow').css({"opacity": "1", "display": "block"});
            })
        });
        //================== Open Service Type Model end ==================

        //================== close modal form start ==================
        $(document).on('click', '.closeServiceType', function(){
            $("#serviceTypeModalShow").hide();
        });
        //================== close modal form end ==================

        //================== open modal for Call Log start ==================
        $(document).on('click','.callLog',function(){
            var url = '{{route('customer.callLog')}}';
            var id = $(this).data('id');
            var _token = "{{csrf_token()}}";
            var data = {
                _token : _token,
                id : id,
            };
            $.post(url,data,function(response){
                $('#loadCustomerModal .modal-content').html(response);
                $('#customerModalShow').css({"opacity": "1", "display": "block"});
            })
        });
        //================== open modal for Call Log end ==================
        
    </script>
@endsection