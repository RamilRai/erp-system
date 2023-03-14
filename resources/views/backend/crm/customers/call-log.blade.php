<div class="modal-header">
    <h5 class="modal-title">Call Log Details</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="errorLogin"></div>
    <form  class="form-validate is-alter" enctype="multipart/form-data" id="callLogForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="customerId" name="customer_id" value="{{$post['id']}}">
            <input type="hidden" class="form-control hidden" id="callLogId" name="id" value="">
            <div class="form-group col-4">
                <label class="form-label" for="call_date">Called Date <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="call_date" name="call_date" value="">                   
                </div>  
                <p class="form-text text-danger call_date"></p>                
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="called_by">Called By <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="called_by" name="called_by" value="">                   
                </div>
                <p class="form-text text-danger called_by"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="received_by">Received By <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="received_by" name="received_by" value="">                   
                </div>
                <p class="form-text text-danger received_by"></p>
            </div>
            <div class="form-group col-12">
                <label class="form-label" for="remarks">Remarks <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="remarks" name="remarks" value="">                   
                </div>
                <p class="form-text text-danger remarks"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-secondary" id="resetCallLog"><i class="fa-solid fa-rotate"></i>&nbsp;Reset </button>
            <button type="submit" class="btn btn-lg btn-primary" id="saveCallLog"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save</button>
        </div>
    </form>
    <div class="nk-divider divider md"></div>
    <div class="components-preview">
        <div class="nk-block nk-block-lg mt-4">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Call Log Details</h4>
                    </div>
                </div>  
            </div>
            <div class="card card-bordered card-preview">
                <div class=" card-body intro-y col-span-12 overflow-auto lg:overflow-visible">
                    <table class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" id="callLogTable">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th text-center whitespace-nowrap>S.No. </th>
                                <th text-center whitespace-nowrap>Called Date</th>
                                <th text-center whitespace-nowrap>Called By</th>
                                <th text-center whitespace-nowrap>Received By</th>
                                <th text-center whitespace-nowrap>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div><!-- .card-preview -->
        </div> 
    </div>
</div>

<script>

    var customerId = '{{$post['id']}}';
    
    $(document).ready(function(){

        //================== nepali date picker start ==================
        $("#call_date").nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            language: "english"
        });
        //================== nepali date picker end ==================

        //================== function to reset fields start ==================
        function resetFields(){
            $('#callLogId').val('');
            $('#callLogForm')[0].reset(); 
        }
        //================== function to reset fields end ==================
        
        //================== reset form start ==================
        $("#resetCallLog").on('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Do you want to reset all input fields?',
                text: "You won't be able to save this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Reset fields!'
            }).then((result)=>{
                if(result.isConfirmed){
                    resetFields();
                    $('#saveCallLog').html('<i class="fa-solid fa-floppy-disk"></i>&nbsp;Save').removeAttr('disabled');
                    Swal.fire(
                        'Reset done successfully!',
                        'success',
                    );                    
                }
            });
        });
        //================== reset form end ==================

        //================== store call log info start ==================
        $('#saveCallLog').on('click', function(e){
            e.preventDefault();
            $('#callLogForm').validate({
                rules: {
                    call_date: 'required',
                    called_by: {
                        required: true,
                        maxlength: 100
                    },
                    received_by: {
                        required: true,
                        maxlength: 100
                    },
                    remarks: 'required'
                },
                messages: {
                    call_date: {
                        required: "Please select call date."
                    },
                    called_by: {
                        required: "Please input called by name.",
                        maxlength: "Please input not more than 100 characters."
                    },
                    received_by: {
                        required: "Please input received by name.",
                        maxlength: "Please input not more than 100 characters."
                    },
                    remarks: {
                        required: "Please input remarks."
                    }
                }
            });
            if ($('#callLogForm').valid()) {
                var token = "{{csrf_token()}}";
                $('#callLogForm').ajaxSubmit({
                    url: '{{route('customerCallLog.submit')}}',
                    type: 'POST',
                    data: {'_token':token},
                    beforeSend: function() {
                        $('#saveCallLog').html(
                            "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('#saveCallLog').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.type=='success') {
                            Swal.fire({
                                title: 'Success!',
                                icon: 'success',
                                text: result.message
                            });
                            $('#callLogForm')[0].reset();
                            callLogTable.fnDraw();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                icon: 'error',
                                text: result.message
                            });
                        }
                    },
                    error: function(response) {
                        var errors =  response.responseJSON.errors;
                        $.each(errors, function (key, val) {
                            $('.'+key).html('');
                            $('.'+key).append(val);
                            $('#'+key).addClass('border-danger');
                            $('#'+key).change(function(){
                                $('.'+key).html('');
                                $('#'+key).removeClass('border-danger');
                            });
                        });
                    }
                });
            }    
        });
        //================== store call log info end ==================

        //================== data table for loading call log data start ==================
        callLogTable = $('#callLogTable').dataTable({
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
            "sAjaxSource" : '{{route('customerCallLog.fetch')}}',
            "oLanguage" : {
                "sEmptyTable" : "<p class='no_data_message'>No data available.</p>"
            },
            "aoColumnDefs" : [{
                "bSortable" : false,
                "aTargets" : [0,]
            },
            { "sWidth" : "200px", "aTargets": [4,] }
            ],
            "fnServerParams": function (aoData) {
                aoData.push({ "name": "customer_id", "value":customerId});
            },
            "aoColumns" : [
                {data:'sn'},
                {data:'calledDate'},
                {data:'calledBy'},
                {data:'receivedBy'},
                {data:'action'}
            ]
        }).columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {
                    type: "null"
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
        //================== data table for loading call log data end ==================

        //================== call log editing start ==================
        $(document).on('click', '.editCallLog', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{route('customerCallLog.edit')}}';
            var token = '{{csrf_token()}}';
            var data = {id:id, _token:token};
            $.post(url, data, function(response){
                var result = JSON.parse(response);
                if (result.type = "success") {
                    $("#callLogId").val(result.response.id);
                    $("#call_date").val(result.response.call_date);
                    $("#called_by").val(result.response.called_by);
                    $("#received_by").val(result.response.received_by);
                    $("#remarks").val(result.response.remarks);
                    $('#saveCallLog').html('<i class="fa-solid fa-pen-to-square"></i>&nbsp;Update');
                }

            });
        });
        //================== call log editing end ==================

        //================== Delete call log Info Start ==================
        $(document).on('click','.deleteCallLog' ,function (e) {
            e.preventDefault();
            var id = $(this).data('id'); 
            var url = '{{route('customerCallLog.delete')}}';
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
                            Swal.fire(
                                'Deleted!',
                                result.message,
                                'success',
                            );
                            callLogTable.fnDraw();
                        }else{
                            Swal.fire(result.message, "error");
                        }
                    });
                }
            });
        });
        //================== Delete call log Info End ==================
        
    });
</script>