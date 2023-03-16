<div class="modal-header">
    <h5 class="modal-title">Task Management Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="taskManagementForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="taskManagementId" value="{{isset($taskManagement)?$taskManagement->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="task_title">Task Title <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="task_title" name="task_title" value="{{isset($taskManagement)?$taskManagement->task_title:''}}">
                </div>
                <p class="form-text text-danger task_title"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="project_id">Project Name <code>*</code></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="project_id" id="project_id">
                        <option value="" hidden>-- Select Project Name --</option>
                        @foreach ($projectName as $pn_item)
                            <option class="projectName" value="{{$pn_item->id}}" {{$pn_item->id == @$taskManagement->project_id ? 'selected':''}} data-project-name="{{$pn_item->project_name}}">{{$pn_item->project_name}}</option>
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
                        <option value="New Task" @if (@$taskManagement->task_type == 'New Task') selected @endif>New Task</option>
                        <option value="Fix Bug" @if (@$taskManagement->task_type == 'Fix Bug') selected @endif>Fix Bug</option>
                        <option value="Correction" @if (@$taskManagement->task_type == 'Correction') selected @endif>Correction</option>
                        <option value="Testing" @if (@$taskManagement->task_type == 'Testing') selected @endif>Testing</option>
                        <option value="Documentation" @if (@$taskManagement->task_type == 'Documentation') selected @endif>Documentation</option>
                        <option value="Support" @if (@$taskManagement->task_type == 'Support') selected @endif>Support</option>
                    </select>
                </div>
                <p class="form-text text-danger task_type"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="task_start_date_bs">Task Start Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="task_start_date_bs" name="task_start_date_bs" value="{{isset($taskManagement)?$taskManagement->task_start_date_bs:''}}">
                </div>
                <p class="form-text text-danger task_start_date_bs"></p>
            </div>
            <input type="hidden" id="task_start_date_ad" name="task_start_date_ad" value="{{isset($taskManagement)?$taskManagement->task_start_date_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="task_end_date_bs">Task End Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="task_end_date_bs" name="task_end_date_bs" value="{{isset($taskManagement)?$taskManagement->task_end_date_bs:''}}">
                </div>
                <p class="form-text text-danger task_end_date_bs"></p>
            </div>
            <input type="hidden" id="task_end_date_ad" name="task_end_date_ad" value="{{isset($taskManagement)?$taskManagement->task_end_date_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="estimated_hour">Estimated Hour <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="estimated_hour" name="estimated_hour" value="{{isset($taskManagement)?$taskManagement->estimated_hour:''}}">
                </div>
                <p class="form-text text-danger estimated_hour"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="priority">Priority Level <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="priority" id="priority" class="form-control">
                        <option value="" hidden>-- Choose Priority Level --</option>
                        <option value="Urgent" @if (@$taskManagement->priority == 'Urgent') selected @endif>Urgent</option>
                        <option value="High" @if (@$taskManagement->priority == 'High') selected @endif>High</option>
                        <option value="Medium" @if (@$taskManagement->priority == 'Medium') selected @endif>Medium</option>
                        <option value="Low" @if (@$taskManagement->priority == 'Low') selected @endif>Low</option>
                    </select>
                </div>
                <p class="form-text text-danger priority"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="assigned_to">Assign Member/Members <code>*</code></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="assigned_to[]" id="assigned_to" multiple="multiple">
                        @foreach ($teamMembers as $tm_item)
                            <option value="{{$tm_item->user_id}}" @isset($assignedTeamMembers) {{ in_array($tm_item->user_id, $assignedTeamMembers) ? 'selected' : '' }} @endisset>{{$tm_item->first_name}} {{$tm_item->middle_name}} {{$tm_item->last_name}}</option>
                        @endforeach 
                    </select>
                </div>
                <p class="form-text text-danger assigned_to"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="task_point">Task Point <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" id="task_point" name="task_point" value="{{isset($taskManagement)?$taskManagement->task_point:''}}">
                </div>
                <p class="form-text text-danger task_point"></p>
            </div>
            <div class="form-group col-12">
                <label class="form-label" for="task_description">Task Description <code>*</code></label>
                <div class="form-control-wrap">
                    <textarea class="form-control" name="task_description" id="task_description" cols="30" rows="10">{{isset($taskManagement)?$taskManagement->task_description:''}}</textarea>
                </div>
                <p class="form-text text-danger task_description"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveTaskManagementInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== select2 start ==================
    $('#assigned_to').select2();
    //================== select2 end ==================

    //================== ckeditor start ==================
    CKEDITOR.replace('task_description',{
        filebrowserUploadUrl: '{{route('taskManagement.ckeditor.fileupload',['_token' => csrf_token() ]) }}',
        filebrowserUploadMethod: "form" 
    });
    //================== ckeditor end ==================

    //================== task start date start ==================
    $("#task_start_date_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#task_start_date_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#task_start_date_ad").val(dateinAD);
            $("#task_start_date_bs").trigger('change');
        }
    });
    //================== task start date end ==================

    //================== task end date start ==================
    $("#task_end_date_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#task_end_date_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#task_end_date_ad").val(dateinAD);
            $("#task_end_date_bs").trigger('change');
        }
    });
    //================== task end date end ==================

    //================== Remove non-numeric characters from the input value start ==================
    $('#task_point').on('input', function() {
        var inputVal = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(inputVal);
    });
    //================== Remove non-numeric characters from the input value end ==================

    //================== store data start ==================
    $('#saveTaskManagementInfo').on('click', function(e){
        e.preventDefault();
        $('#taskManagementForm').validate({
            rules: {
                task_title: {
                    required: true,
                    maxlength: 100
                },
                project_id: 'required',
                task_type: 'required',
                task_start_date_bs: 'required',
                task_end_date_bs: 'required',
                estimated_hour: {
                    required: true,
                    maxlength: 100
                },
                priority: 'required',
                "assigned_to[]": 'required',
                task_point: 'required'
            },
            messages: {
                task_title: {
                    required: "Please input task name.",
                    maxlength: "Please input not more than 100 characters."
                },
                project_id: {
                    required: "Please select a project."
                },
                task_type: {
                    required: "Please select task type."
                },
                task_start_date_bs: {
                    required: "Please select task start date."
                },
                task_end_date_bs: {
                    required: "Please select task end date."
                },
                estimated_hour: {
                    required: "Please input task estimated hour.",
                    maxlength: "Please input not more than 100 characters."
                },
                priority: {
                    required: "Please select task priority."
                },
                "assigned_to[]": {
                    required: "Please select atleast 1 team member."
                },
                task_point: {
                    required: "Please input task point."
                }
            }
        });
        if ($('#taskManagementForm').valid()) {
            var formData = new FormData($('#taskManagementForm')[0]);
            formData.append('task_description', CKEDITOR.instances['task_description'].getData());
            var projectName = $('.projectName').data('project-name');
            formData.append('projectName', projectName);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('task-management.submit')}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#saveTaskManagementInfo').html(
                        "<svg width='25' viewBox='-2 -2 55 55' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> &nbsp;&nbsp; Updating Information..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveTaskManagementInfo').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send block mx-auto"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>&nbsp;Update Information').removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            text: result.message
                        });
                        $('#taskManagementForm')[0].reset();
                        $('#taskManagementModalShow').hide();
                        taskManagementTable.fnDraw();
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