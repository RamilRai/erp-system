<div class="modal-header">
    <h5 class="modal-title">Customer Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="errorLogin"></div>
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="customerForm">
        @csrf
        <div class="row">
            <input type="hidden" class="form-control hidden" id="customerId" value="{{isset($customer)?$customer->id: ''}}" name="id" >
            <div class="form-group col-4">
                <label class="form-label" for="company_name">Company Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{isset($customer)?$customer->company_name:''}}">
                </div>
                <p class="form-text text-danger company_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="owner_name">Owner Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{isset($customer)?$customer->owner_name:''}}">
                </div>
                <p class="form-text text-danger owner_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="email">Email <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="email" name="email" value="{{isset($customer)?$customer->email:''}}">
                </div>
                <p class="form-text text-danger email"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="address">Address <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="address" name="address" value="{{isset($customer)?$customer->address:''}}">
                </div>
                <p class="form-text text-danger address"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="mobile_number">Mobile Number <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="mobile_number" name="mobile_number" value="{{isset($customer)?$customer->mobile_number:''}}">
                </div>
                <p class="form-text text-danger mobile_number"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="landline_number">Landline Number </label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="landline_number" name="landline_number" value="{{isset($customer)?$customer->landline_number:''}}">
                </div>
                <p class="form-text text-danger landline_number"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="service_id">Service Type <code>*</code> <a href="#" id="addService" class="text-primary" data-bs-toggle="modal" data-bs-target="#modalForm">Add Service Type</a></label>
                <div class="form-control-wrap">
                    <select name="service_id" id="options" class="form-control">
                        <option value="">-- Select Service Type --</option>
                        @isset($serviceType)
                            @foreach ($serviceType as $s_item)
                                <option value="{{$s_item->id}}" {{$s_item->id == @$customer->service_id ? 'selected':''}}>{{$s_item->service_type}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <p class="form-text text-danger service_id"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="service_name">Service Name <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="service_name" name="service_name" value="{{isset($customer)?$customer->service_name:''}}">
                </div>
                <p class="form-text text-danger service_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="domain_name">Domain Name </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="domain_name" name="domain_name" value="{{isset($customer)?$customer->domain_name:''}}">
                </div>
                <p class="form-text text-danger domain_name"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="company_website">Company Website </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="company_website" name="company_website" value="{{isset($customer)?$customer->company_website:''}}">
                </div>
                <p class="form-text text-danger company_website"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="contracted_date">Contracted Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="contracted_date" name="contracted_date" value="{{isset($customer)?$customer->contracted_date:''}}">
                </div>
                <p class="form-text text-danger contracted_date"></p>
            </div>
            <div class="form-group col-4">
                <label class="form-label" for="contract_end_date">Contract End Date <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="contract_end_date" name="contract_end_date" value="{{isset($customer)?$customer->contract_end_date:''}}">
                </div>
                <p class="form-text text-danger contract_end_date"></p>
            </div>
            <input type="hidden" name="contract_end_date_ad" id="contract_end_date_ad" value="{{isset($customer)?$customer->contract_end_date_ad:''}}">
            @php
                $authProfile = App\Models\Profile::where('id', Auth::user()->id)->first();
                $authFullName = $authProfile->first_name . ' ' . $authProfile->middle_name . ' ' . $authProfile->last_name;
            @endphp
            <div class="form-group col-4">
                <label class="form-label" for="contracted_by">Contracted By <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="contracted_by" name="contracted_by" value="{{isset($customer)?$customer->contracted_by:$authFullName}}">
                </div>
                <p class="form-text text-danger contracted_by"></p>
            </div>
            <div class="form-group col-8">
                <label class="form-label" for="remarks">Remarks </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{isset($customer)?$customer->remarks:''}}">
                </div>
                <p class="form-text text-danger remarks"></p>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveCustomerInfo"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Informations</button>
        </div>
    </form>
</div>

<script>

    //================== nepali date picker start ==================
    $("#contracted_date").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english"
    });

    $("#contract_end_date").nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        language: "english",
        onChange: function() {
            var dateInBs = $('#contract_end_date').val();
            var dateinAD = NepaliFunctions.BS2AD(dateInBs, "YYYY-MM-DD");
            $("#contract_end_date_ad").val(dateinAD);
            $("#contract_end_date").trigger('change');
        }
    });
    //================== nepali date picker end ==================

    //================== store data start ==================
    $("#saveCustomerInfo").on('click', function(e){
        e.preventDefault();
        $('#customerForm').validate({
            rules: {
                company_name: {
                    required: true,
                    maxlength: 100
                },
                owner_name: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                address: {
                    required: true,
                    maxlength: 100
                },
                mobile_number: {
                    required: true,
                    maxlength: 20
                },
                landline_number: {
                    maxlength: 20
                },
                service_id: 'required',
                service_name: {
                    required: true,
                    maxlength: 100
                },
                domain_name: {
                    maxlength: 100
                },
                company_website: {
                    maxlength: 100
                },
                contracted_date: 'required',
                contract_end_date: 'required',
                contracted_by: {
                    required: true,
                    maxlength: 100
                }
            },
            messages: {
                company_name: {
                    required: "Please input company name.",
                    maxlength: "Please input not more than 100 characters."
                },
                owner_name: {
                    required: "Please input owner name.",
                    maxlength: "Please input not more than 100 characters."
                },
                email: {
                    required: "Please input email.",
                    email: "Please input valid email",
                    maxlength: "Please input not more than 100 characters."
                },
                address: {
                    required: "Please input address.",
                    maxlength: "Please input not more than 100 characters."
                },
                mobile_number: {
                    required: "Please input mobile number.",
                    maxlength: "Please input not more than 20 characters."
                },
                landline_number: {
                    maxlength: "Please input not more than 20 characters."
                },
                service_id: {
                    required: "Please select service type."
                },
                service_name: {
                    required: "Please input service name.",
                    maxlength: "Please input not more than 100 characters."
                },
                domain_name: {
                    maxlength: "Please input not more than 100 characters."
                },
                company_website: {
                    maxlength: "Please input not more than 100 characters."
                },
                contracted_date: {
                    required: "Please select contracted date."
                },
                contract_end_date: {
                    required: "Please select contracted date."
                },
                contracted_by: {
                    required: "Please input contracted by name.",
                    maxlength: "Please input not more than 100 characters."
                }
            }
        });
        if ($('#customerForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#customerForm').ajaxSubmit({
                url: '{{route('customer.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveCustomerInfo').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveCustomerInfo').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#customerForm')[0].reset();
                        $('#customerModalShow').hide();
                        customerTable.fnDraw();
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

    //================== load options start ==================
    function loadServiceType(){
        var url = '{{route('serviceType.load')}}';
        $.get(url, function (response) {
            var result = JSON.parse(response);
            if(result.type == 'success'){
                $('#options').html('');
                $.each(result.response,function(key,items){
                    $('#options').append("<option value='"+items.id+"'>"+items.service_type+"</option>")
                });
            }
        });
    } 
    //================== load options end ==================

</script>