<div class="modal-header">
    <h5 class="modal-title">View Info</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <div class="card-inner">
        <div class="nk-block">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Body</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Company Name</th>
                        <td>{{$result->company_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Owner Name</th>
                        <td>{{$result->owner_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email Address</th>
                        <td>{{$result->email}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Address</th>
                        <td>{{$result->address}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Mobile Number</th>
                        <td>{{$result->mobile_number}}</td>
                    </tr>
                    @if ($result->landline_number)
                        <tr>
                            <th scope="row">Landline Number</th>
                            <td>{{$result->landline_number}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Service Type</th>
                        <td>{{$result->services->service_type}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Service Name</th>
                        <td>{{$result->service_name}}</td>
                    </tr>
                    @if ($result->domain_name)
                        <tr>
                            <th scope="row">Domain Name</th>
                            <td>{{$result->domain_name}}</td>
                        </tr>
                    @endif
                    @if ($result->company_website)
                        <tr>
                            <th scope="row">Company Website</th>
                            <td>{{$result->company_website}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Contracted Date</th>
                        <td>{{$result->contracted_date}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Contract End Date</th>
                        <td>{{$result->contract_end_date}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Contracted By</th>
                        <td>{{$result->contracted_by}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Remarks</th>
                        <td>{{$result->remarks}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>