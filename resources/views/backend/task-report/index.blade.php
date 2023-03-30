@extends('layouts.adminpanel.design')

@section('title') Task Report @endsection

@section('main-content')

<style>
    .nk-wrap{
        overflow: hidden;
    }
    .dataTables_filter label{
        margin-right: 40px;
    }
    .dataTables_wrapper{
        padding: 0px; margin: 0px;
    }
</style>

<div class="row">
    <div class="col-md-12">
 
        <div class="mb-2">
            <nav class="navbar navbar-expand-md navbar-light bg-light" style="margin: -2%">
                <div class="container-fluid">
                    <a class="navbar-brand" style="flex-basis: 10%" href="#">Task Report</a>
                    <form action="" style="flex-basis: 90%">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item me-1">
                                    <select class="form-select" id="year" aria-label="Year">
                                        <option hidden>Select Year</option>
                                        <option value="2079" @if($yearBS == '2079') selected @endif>2079</option>
                                        <option value="2080" @if($yearBS == '2080') selected @endif>2080</option>
                                    </select>
                                </li>
                                <li class="nav-item">
                                    <select class="form-select" id="month" aria-label="Month">
                                        <option hidden>Select Month</option>
                                        <option value="01" @if($monthBS == '01') selected @endif>Baishakh</option>
                                        <option value="02" @if($monthBS == '02') selected @endif>Jestha</option>
                                        <option value="03" @if($monthBS == '03') selected @endif>Asar</option>
                                        <option value="04" @if($monthBS == '04') selected @endif>Shrawan</option>
                                        <option value="05" @if($monthBS == '05') selected @endif>Bhadra</option>
                                        <option value="06" @if($monthBS == '06') selected @endif>Aswin</option>
                                        <option value="07" @if($monthBS == '07') selected @endif>Kartik</option>
                                        <option value="08" @if($monthBS == '08') selected @endif>Mangsir</option>
                                        <option value="09" @if($monthBS == '09') selected @endif>Poush</option>
                                        <option value="10" @if($monthBS == '10') selected @endif>Magh</option>
                                        <option value="11" @if($monthBS == '11') selected @endif>Falgun</option>
                                        <option value="12" @if($monthBS == '12') selected @endif>Chaitra</option>
                                    </select>
                                </li>
                            </ul>
                            <div class="d-flex">
                                <a class="btn btn-primary me-2" id="exportExcel">Export All</a>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-primary" id="viewData" type="button">View</button>
                            </div>
                        </div>
                    </form>
                </div>
            </nav>
        </div>

        <div id="showTable" class="mt-5 row">
        </div>
    </div>
</div>
@endsection

@section('main-scripts')
    <script>
        $(document).ready(function () {

            //================== load data with report start ==================
            $("#viewData").on('click', function(e){
                e.preventDefault();
                var year = $("#year").val();
                var month = $("#month").val();
                var url = '{{route('task-report.fetch')}}';
                var _token = '{{csrf_token()}}';
                var data = {_token:_token, year:year, month:month};
                $.post(url, data, function(response){
                    $("#showTable").html(response);
                    $('#exportExcel').attr('hidden', false);
                });
            });
            //================== load data with report end ==================
            
            //================== export data start ==================
            $("#exportExcel").on('click', function(e){
                var _token = '{{csrf_token()}}';
                var year = $("#year").val();
                var month = $("#month").val();
                var monthName = $("#month option[value='" + month + "']").text()
                var url = '{{route('task-report.fetch')}}';
                url = url + '?isExport=Y&month='+month+'&monthName='+monthName+'&year='+year+'&_token='+_token;
                Swal.fire({
                    title: "Export an Excel file containing all staff data for "+monthName+ ", "+year+"?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Export!'
                }).then((result)=>{
                    if(result.isConfirmed){
                        window.open(url);
                    }
                });
            });
            //================== export data end ==================

            
        });
    </script>    
@endsection