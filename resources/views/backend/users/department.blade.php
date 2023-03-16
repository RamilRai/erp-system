<div class="modal-header">
    <h5 class="modal-title">Department</h5>
    <a href="#" class="closeDepartment" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="errorList"></div>
    <form  class="form-validate is-alter" enctype="multipart/form-data" id="departmentForm">
        @csrf
        <div class="row">
            <div class="form-group col-12">
                <label class="form-label" for="department_name">Department Name <span style="color:#ff0000">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="department_name" name="department_name">                   
                </div>
                <p class="form-text text-danger department_name"></p>
            </div>
        </div>
        
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveDepartment">Submit</button>
        </div>
    </form>
</div>

<script>
    //save department ---Start
    $('#saveDepartment').click(function(e){
        e.preventDefault();
        $('#departmentForm').validate({
            rules: {
                department_name: {
                    required: true,
                    maxlength: 100
                }
            },
            messages: {
                department_name: {
                    required: "Please input department name.",
                    maxlength: "Please input not more than 100 characters."
                }
            }
        });
        if ($('#departmentForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#departmentForm').ajaxSubmit({
                url: '{{route('department.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveDepartment').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveDepartment').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#departmentForm')[0].reset();
                        $('#departmentModalShow').hide();
                        loadDepartment();
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
    //save department ---End
</script>