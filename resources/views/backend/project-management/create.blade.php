<div class="modal-header">
    <h5 class="modal-title">Project Management Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="projectManagementForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="projectManagementId" value="{{isset($projectManagement)?$projectManagement->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="project_name">Project Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="project_name" name="project_name" value="{{isset($projectManagement)?$projectManagement->project_name:''}}">
                </div>
                <p class="form-text text-danger project_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="pdf">PDF File </label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control" id="pdf" name="pdf">
                </div>
                <p class="form-text text-danger pdf"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="project_type">Project Type <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="project_type" id="project_type" class="form-control">
                        <option value="" hidden>-- Select Project Type --</option>
                        <option value="Client" @if (@$projectManagement->project_type == 'Client') selected @endif>Client</option>
                        <option value="In-House" @if (@$projectManagement->project_type == 'In-House') selected @endif>In-House</option>
                    </select>
                </div>
                <p class="form-text text-danger project_type"></p>
            </div>
            <div class="form-group col-4 clientInfo" style="display:none;">
                <label class="form-label" for="client_company_name">Client Company Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control resetValue" id="client_company_name" name="client_company_name" value="{{isset($projectManagement)?$projectManagement->client_company_name:''}}">
                </div>
                <p class="form-text text-danger client_company_name"></p>
            </div>
            <div class="form-group col-4 clientInfo" style="display:none;">
                <label class="form-label" for="contact_person">Contact Person <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control resetValue" id="contact_person" name="contact_person" value="{{isset($projectManagement)?$projectManagement->contact_person:''}}">
                </div>
                <p class="form-text text-danger contact_person"></p>
            </div>
            <div class="form-group col-4 clientInfo" style="display:none;">
                <label class="form-label" for="phone_number">Phone Number <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control resetValue" id="phone_number" name="phone_number" value="{{isset($projectManagement)?$projectManagement->phone_number:''}}">
                </div>
                <p class="form-text text-danger phone_number"></p>
            </div>
            <div class="form-group col-4 clientInfo" style="display:none;">
                <label class="form-label" for="email">Email Address <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="email" class="form-control resetValue" id="email" name="email" value="{{isset($projectManagement)?$projectManagement->email:''}}">
                </div>
                <p class="form-text text-danger email"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="project_time_duration">Project Time Duration <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="project_time_duration" name="project_time_duration" value="{{isset($projectManagement)?$projectManagement->project_time_duration:''}}">
                </div>
                <p class="form-text text-danger project_time_duration"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="start_date_bs">Start Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="start_date_bs" name="start_date_bs" value="{{isset($projectManagement)?$projectManagement->start_date_bs:''}}">
                </div>
                <p class="form-text text-danger start_date_bs"></p>
            </div>
            <input type="hidden" id="start_date_ad" name="start_date_ad" value="{{isset($projectManagement)?$projectManagement->start_date_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="end_date_bs">End Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="end_date_bs" name="end_date_bs" value="{{isset($projectManagement)?$projectManagement->end_date_bs:''}}">
                </div>
                <p class="form-text text-danger end_date_bs"></p>
            </div>
            <input type="hidden" id="end_date_ad" name="end_date_ad" value="{{isset($projectManagement)?$projectManagement->end_date_ad:''}}">
            <div class="form-group col-4">
                <label class="form-label" for="project_lead_by">Project Lead By <code>*</code></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="project_lead_by" id="project_lead_by">
                        <option value="" hidden>-- Select Project Leader --</option>
                        @foreach ($projectLeader as $pl_item)
                            <option value="{{$pl_item->user_id}}" {{$pl_item->user_id == @$projectManagement->project_lead_by ? 'selected':''}}>{{$pl_item->first_name}} {{$pl_item->middle_name}} {{$pl_item->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <p class="form-text text-danger project_lead_by"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="assign_team_members">Assign Team Members <code>*</code></label>
                <div class="form-control-wrap">
                    <select class="form-control" name="assign_team_members[]" id="assign_team_members" multiple="multiple">
                        @foreach ($teamMembers as $tm_item)
                            <option value="{{$tm_item->user_id}}" @isset($assignedTeamMembers) {{ in_array($tm_item->user_id, $assignedTeamMembers) ? 'selected' : '' }} @endisset>{{$tm_item->first_name}} {{$tm_item->middle_name}} {{$tm_item->last_name}}</option>
                        @endforeach 
                    </select>
                </div>
                <p class="form-text text-danger assign_team_members"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="project_status">Project Status <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="project_status" id="project_status" class="form-control">
                        <option value="Not Started Yet" @if (@$projectManagement->project_status == 'Not Started Yet') selected @endif>Not Started Yet</option>
                        <option value="On Progress" @if (@$projectManagement->project_status == 'On Progress') selected @endif>On Progress</option>
                        <option value="Testing" @if (@$projectManagement->project_status == 'Testing') selected @endif>Testing</option>
                        <option value="Bug Fixing" @if (@$projectManagement->project_status == 'Bug Fixing') selected @endif>Bug Fixing</option>
                        <option value="Completed" @if (@$projectManagement->project_status == 'Completed') selected @endif>Completed</option>
                        <option value="Dropped" @if (@$projectManagement->project_status == 'Dropped') selected @endif>Dropped</option>
                        <option value="Hold" @if (@$projectManagement->project_status == 'Hold') selected @endif>Hold</option>
                        <option value="Cancelled" @if (@$projectManagement->project_status == 'Cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>
                <p class="form-text text-danger project_status"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveprojectManagementInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== select2 start ==================
    $('#assign_team_members').select2();
    //================== select2 end ==================

    //================== hide and show client info start ==================
    $('#project_type').on('change', function() {
        if ($(this).val() == 'Client') {
            $('.clientInfo').show();
        } else {
            $('.clientInfo').hide();
            $('.resetValue').val('');
        }
    });
    //================== hide and show client info end ==================

    //================== project start date start ==================
    $("#start_date_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#start_date_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#start_date_ad").val(dateinAD);
            $("#start_date_bs").trigger('change');
        }
    });
    //================== project start date end ==================

    //================== project end date start ==================
    $("#end_date_bs").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#end_date_bs').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#end_date_ad").val(dateinAD);
            $("#end_date_bs").trigger('change');
        }
    });
    //================== project end date end ==================

    //================== store data start ==================
    $("#saveprojectManagementInfo").on('click', function(e){
        e.preventDefault();
        $('#projectManagementForm').validate({
            rules: {
                project_name: {
                    required: true,
                    maxlength: 100
                },
                project_type: 'required',
                client_company_name: {
                    required:function() {
                        return $("#project_type").val() === 'Client';
                    },
                    maxlength: 100
                },
                contact_person: {
                    required:function() {
                        return $("#project_type").val() === 'Client';
                    },
                    maxlength: 100
                },
                phone_number: {
                    required:function() {
                        return $("#project_type").val() === 'Client';
                    },
                    maxlength: 100
                },
                email: {
                    required:function() {
                        return $("#project_type").val() === 'Client';
                    },
                    maxlength: 100,
                    email: true
                },
                project_time_duration: {
                    required: true,
                    maxlength: 100
                },
                start_date_bs: 'required',
                end_date_bs: 'required',
                project_lead_by: 'required',
                "assign_team_members[]": 'required',
                project_status: 'required'
            },
            messages: {
                project_name: {
                    required: "Please input project name.",
                    maxlength: "Please input not more than 100 characters."
                },
                project_type: {
                    required: "Please select project type."
                },
                client_company_name: {
                    required: "Please input client company name.",
                    maxlength: "Please input not more than 100 characters."
                },
                contact_person: {
                    required: "Please input contact person name.",
                    maxlength: "Please input not more than 100 characters."
                },
                phone_number: {
                    required: "Please input client's phone number.",
                    maxlength: "Please input not more than 100 characters."
                },
                email: {
                    required: "Please input client's email address.",
                    maxlength: "Please input not more than 100 characters.",
                    email: "Please input valid email"
                },
                project_time_duration: {
                    required: "Please input project time duration.",
                    maxlength: "Please input not more than 100 characters."
                },
                start_date_bs: {
                    required: "Please select start date."
                },
                end_date_bs: {
                    required: "Please select end date."
                },
                project_lead_by: {
                    required: "Please select project leader."
                },
                "assign_team_members[]": {
                    required: "Please select at least 1 team member."
                },
                project_status: {
                    required: "Please select project status."
                }
            }
        });
        if ($('#projectManagementForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#projectManagementForm').ajaxSubmit({
                url: '{{route('project-management.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveprojectManagementInfo').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveprojectManagementInfo').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#projectManagementForm')[0].reset();
                        $('#projectManagementModalShow').hide();
                        projectManagementTable.fnDraw();
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
    //================== store data end ==================

</script>