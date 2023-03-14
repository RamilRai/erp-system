<div class="modal-header">
    <h5 class="modal-title">Lead Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="errorLogin"></div>
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="leadForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="leadId" value="{{isset($lead)?$lead->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="organization_type">Organization Type <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="organization_type" id="organization_type" class="form-control">
                        <option value="" hidden>-- Select Organization Type --</option>
                        <option value="School" @if (@$lead->organization_type == 'School') selected @endif>School</option>
                        <option value="Organization" @if (@$lead->organization_type == 'Organization') selected @endif>Organization</option>
                        <option value="Agent" @if (@$lead->organization_type == 'Agent') selected @endif>Agent</option>
                        <option value="Local Government" @if (@$lead->organization_type == 'Local Government') selected @endif>Local Government</option>
                    </select>
                </div>
                <p class="form-text text-danger organization_type"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="organization_name">Organization Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="organization_name" name="organization_name" value="{{isset($lead)?$lead->organization_name:''}}">
                </div>
                <p class="form-text text-danger organization_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="address">Address <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="address" name="address" value="{{isset($lead)?$lead->address:''}}">
                </div>
                <p class="form-text text-danger address"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="email">Email <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="email" name="email" value="{{isset($lead)?$lead->email:''}}">
                </div>
                <p class="form-text text-danger email"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="contact_number">Contact Number <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" value="{{isset($lead)?$lead->contact_number:''}}">
                </div>
                <p class="form-text text-danger contact_number"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="mobile_number">Mobile Number </label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="mobile_number" name="mobile_number" value="{{isset($lead)?$lead->mobile_number:''}}">
                </div>
                <p class="form-text text-danger mobile_number"></p>
            </div>
            @php
                $authProfile = App\Models\Profile::where('id', Auth::user()->id)->first();
                $authFullName = $authProfile->first_name . ' ' . $authProfile->middle_name . ' ' . $authProfile->last_name;
            @endphp
            <div class="form-group col-4">
                <label class="form-label" for="lead_by_name">Lead By Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="lead_by_name" name="lead_by_name" value="{{isset($lead)?$lead->lead_by_name:$authFullName}}">
                </div>
                <p class="form-text text-danger lead_by_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="lead_date">Lead Date (BS) <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="lead_date" name="lead_date" value="{{isset($lead)?$lead->lead_date:''}}">
                </div>
                <p class="form-text text-danger lead_date"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="lead_status">Lead Status <code>*</code></label>
                <div class="form-control-wrap">
                    <select name="lead_status" id="lead_status" class="form-control">
                        <option value="Pending" @if (@$lead->lead_status == 'Pending') selected @endif>Pending</option>
                        <option value="Active" @if (@$lead->lead_status == 'Active') selected @endif>Active</option>
                        <option value="Cancelled" @if (@$lead->lead_status == 'Cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>
                <p class="form-text text-danger lead_status"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveLeadInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== nepali date picker start ==================
    $("#lead_date").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english"
    });
    //================== nepali date picker end ==================

    //================== store data start ==================
    $("#saveLeadInfo").on('click', function(e){
        e.preventDefault();
        $('#leadForm').validate({
            rules: {
                organization_type: 'required',
                organization_name: {
                    required: true,
                    maxlength: 100
                },
                address: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 50
                },
                contact_number: 'required',
                mobile_number: {
                    minlength: 10
                },
                lead_by_name: {
                    required: true,
                    maxlength: 100
                },
                lead_date: 'required',
                lead_status: 'required'
            },
            messages: {
                organization_type: {
                    required: "Please select organization type."
                },
                organization_name: {
                    required: "Please input organization name.",
                    maxlength: "Please input not more than 100 characters."
                },
                address: {
                    required: "Please input address.",
                    maxlength: "Please input not more than 100 characters."
                },
                email: {
                    required: "Please input email.",
                    email: "Please input valid email",
                    maxlength: "Please input not more than 50 characters."
                },
                contact_number: {
                    required: "Please input contact number."
                },
                mobile_number: {
                    minlength: "Please input not less than 10 numbers."
                },
                lead_by_name: {
                    required: "Please input lead by name.",
                    maxlength: "Please input not more than 100 characters."
                },
                lead_date: {
                    required: "Please input lead date."
                },
                lead_status: {
                    required: "Please select lead status."
                }
            }
        });
        if ($('#leadForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#leadForm').ajaxSubmit({
                url: '{{route('lead.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveLeadInfo').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveLeadInfo').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#leadForm')[0].reset();
                        $('#leadModalShow').hide();
                        leadTable.fnDraw();
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
