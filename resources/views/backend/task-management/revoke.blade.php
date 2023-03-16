<div class="modal-header">
    <h5 class="modal-title">Cancel Or Hold Form</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="revokeForm">
        @csrf
        <div class="row">
            <input type="hidden" name="id" value="{{$taskId}}">
            <input type="hidden" name="value" value="{{$taskValue}}">
            <div class="form-group col-12">
                <label class="form-label" for="feedback">Remarks </label>
                <div class="form-control-wrap">
                    <textarea class="form-control" name="feedback" id="feedback" cols="30" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveTaskRevoke"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Information</button>
        </div>
    </form>
</div>

<script>
    //================== store data start ==================
    $("#saveTaskRevoke").on('click', function(e){
        e.preventDefault();
        $('#revokeForm').validate({
            rules: {
                feedback: 'required'
            },
            messages: {
                feedback: {
                    required: "Please input remarks."
                }
            }
        });
        if ($('#revokeForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#revokeForm').ajaxSubmit({
                url: '{{route('task-management-revoke.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveTaskRevoke').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveTaskRevoke').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#revokeForm')[0].reset();
                        $('#taskManagementModalShow').hide();
                        taskManagementTable.fnDraw();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: result.message
                        });
                    }
                }
            });
        }
    });
    //================== store data end ==================
</script>