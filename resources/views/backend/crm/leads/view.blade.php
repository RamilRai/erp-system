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
                        <th scope="row">Organization Type</th>
                        <td>{{$result->organization_type}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Organization Name</th>
                        <td>{{$result->organization_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Address</th>
                        <td>{{$result->address}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email Address</th>
                        <td>{{$result->email}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Contact Number</th>
                        <td>{{$result->contact_number}}</td>
                    </tr>
                    @if ($result->mobile_number)
                        <tr>
                            <th scope="row">Mobile Number</th>
                            <td>{{$result->mobile_number}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">Lead By Name</th>
                        <td>{{$result->lead_by_name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Lead Date (BS)</th>
                        <td>{{$result->lead_date}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Lead Status</th>
                        <td>{{$result->lead_status}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>