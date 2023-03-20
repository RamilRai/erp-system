<div class="modal-header">
    <h5 class="modal-title">Verify Form</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="marksForm">
        @csrf
        <div class="row">
            <input type="hidden" name="id" value="{{$currentTask->id}}">
            <div class="form-group col-12">
                @if ($currentTask->feedback)
                    <label class="form-label" for="achieved_point">Feedback/Remarks From Assigned Members:</label>
                    <p>{{$currentTask->feedback}}</p>
                @endif
                <label class="form-label" for="achieved_point">Achieved Points <code>*</code> (Task Point: {{$currentTask->task_point}})</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="achieved_point" name="achieved_point">
                </div>
            </div>
            <div class="form-group col-12">
                <label class="form-label" for="response_from_supervisor">Response From Supervisor </label>
                <div class="form-control-wrap">
                    <textarea class="form-control" name="response_from_supervisor" id="response_from_supervisor" cols="30" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveTaskMarks"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Information</button>
        </div>
    </form>
</div>

<script>
    //================== Remove non-numeric characters from the input value start ==================
    $('#achieved_point').on('input', function() {
        var inputVal = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(inputVal);
    });
    //================== Remove non-numeric characters from the input value end ==================

    //================== store data start ==================
    $("#saveTaskMarks").on('click', function(e){
        e.preventDefault();
        $('#marksForm').validate({
            rules: {
                achieved_point: 'required'
            },
            messages: {
                achieved_point: {
                    required: "Please input task marks."
                }
            }
        });
        if ($('#marksForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#marksForm').ajaxSubmit({
                url: '{{route('task-management-marks.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveTaskMarks').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveTaskMarks').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#marksForm')[0].reset();
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