<div class="modal-header">
    <h5 class="modal-title">Extra Task Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="extraTaskForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="extraTaskId" value="{{isset($extraTask)?$extraTask->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="task_title">Task Title <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="task_title" name="task_title" value="{{isset($extraTask)?$extraTask->task_title:''}}">
                </div>
                <p class="form-text text-danger task_title"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="project_id">Project Name <code>*</code></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="project_id" id="project_id">
                        <option value="" hidden>-- Select Project Name --</option>
                        @foreach ($projectName as $pn_item)
                            <option value="{{$pn_item->id}}" {{$pn_item->id == @$extraTask->project_id ? 'selected':''}} data-project-name="{{$pn_item->project_name}}">{{$pn_item->project_name}}</option>
                        @endforeach
                    </select>
                </div>
                <p class="form-text text-danger project_id"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="task_type">Task Type <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="task_type" id="task_type" class="form-control">
                        <option value="" hidden>-- Select Task Type --</option>
                        <option value="New Task" @if (@$extraTask->task_type == 'New Task') selected @endif>New Task</option>
                        <option value="Fix Bug" @if (@$extraTask->task_type == 'Fix Bug') selected @endif>Fix Bug</option>
                        <option value="Correction" @if (@$extraTask->task_type == 'Correction') selected @endif>Correction</option>
                        <option value="Testing" @if (@$extraTask->task_type == 'Testing') selected @endif>Testing</option>
                        <option value="Documentation" @if (@$extraTask->task_type == 'Documentation') selected @endif>Documentation</option>
                        <option value="Support" @if (@$extraTask->task_type == 'Support') selected @endif>Support</option>
                    </select>
                </div>
                <p class="form-text text-danger task_type"></p>
            </div>
            <div class="form-group col-12">
                <label class="form-label" for="task_description">Task Description </label>
                <div class="form-control-wrap">
                    <textarea class="form-control" name="task_description" id="task_description" cols="30" rows="10">{{isset($extraTask)?$extraTask->task_description:''}}</textarea>
                </div>
                <p class="form-text text-danger task_description"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveExtraTaskInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== ckeditor start ==================
    CKEDITOR.replace('task_description',{
        filebrowserUploadUrl: '{{route('extraTask.ckeditor.fileupload',['_token' => csrf_token() ]) }}',
        filebrowserUploadMethod: "form"
    });
    //================== ckeditor end ==================

    //================== store data start ==================
    $('#saveExtraTaskInfo').on('click', function(e){
        e.preventDefault();
        $('#extraTaskForm').validate({
            rules: {
                task_title: {
                    required: true,
                    maxlength: 100
                },
                project_id: {
                    required: function(e){
                        return $('#extraTaskId').val() == "";
                    }
                },
                task_type: 'required'
            },
            messages: {
                task_title: {
                    required: "Please input task title.",
                    maxlength: "Please input not more than 100 characters."
                },
                project_id: {
                    required: "Please select a project."
                },
                task_type: {
                    required: "Please select task type."
                }
            }
        });
        if ($('#extraTaskForm').valid()) {
            var formData = new FormData($('#extraTaskForm')[0]);
            formData.append('task_description', CKEDITOR.instances['task_description'].getData());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('extra-task.submit')}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#saveExtraTaskInfo').html(
                        "<svg width='25' viewBox='-2 -2 55 55' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> &nbsp;&nbsp; Updating Information..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveExtraTaskInfo').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send block mx-auto"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>&nbsp;Update Information').removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            text: result.message
                        });
                        $('#extraTaskForm')[0].reset();
                        $('#extraTaskModalShow').hide();
                        extraTaskTable.fnDraw();
                    } else if(result.type=='error') {
                        Swal.fire({
                            position: 'center',
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
    //================== store data end ==================

</script>
