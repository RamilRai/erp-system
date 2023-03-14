<div class="modal-header">
    <h5 class="modal-title">Service Type</h5>
    <a href="#" class="closeServiceType" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="errorList"></div>
    <form  class="form-validate is-alter" enctype="multipart/form-data" id="serviceTypeForm">
        @csrf
        <div class="row">
            <div class="form-group col-12">
                <label class="form-label" for="service_type">Service Type <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="service_type" name="service_type">                   
                </div>
                <p class="form-text text-danger service_type"></p>
            </div>
        </div>
        
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveServiceType">Submit</button>
        </div>
    </form>
</div>

<script>
    //save category ---Start
    $('#saveServiceType').click(function(e){
        e.preventDefault();
        $('#serviceTypeForm').validate({
            rules: {
                service_type: {
                    required: true,
                    maxlength: 100
                }
            },
            messages: {
                service_type: {
                    required: "Please input service type.",
                    maxlength: "Please input not more than 100 characters."
                }
            }
        });
        if ($('#serviceTypeForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#serviceTypeForm').ajaxSubmit({
                url: '{{route('serviceType.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveServiceType').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveServiceType').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#serviceTypeForm')[0].reset();
                        $('#serviceTypeModalShow').hide();
                        loadServiceType();
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
    //save category ---End
</script>