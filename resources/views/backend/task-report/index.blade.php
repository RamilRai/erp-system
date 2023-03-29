@extends('layouts.adminpanel.design')

@section('title') Task Report @endsection

@section('main-content')

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
                                <option value="2079">2079</option>
                                <option value="2080">2080</option>
                            </select>
                        </li>
                        <li class="nav-item">
                            <select class="form-select" id="month" disabled aria-label="Month">
                                <option hidden>Select Month</option>
                                <option value="01">Baishakh</option>
                                <option value="02">Jestha</option>
                                <option value="03">Asar</option>
                                <option value="04">Shrawan</option>
                                <option value="05">Bhadra</option>
                                <option value="06">Aswin</option>
                                <option value="07">Kartik</option>
                                <option value="08">Mangsir</option>
                                <option value="09">Poush</option>
                                <option value="10">Magh</option>
                                <option value="11">Falgun</option>
                                <option value="12">Chaitra</option>
                            </select>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <a class="btn btn-primary me-2" id="exportExcel" hidden>Export All</a>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-primary" id="viewData" disabled type="button">View</button>
                    </div>
                </div>
            </form>
        </div>
    </nav>
</div>

<div id="showTable" class="mt-5" style="margin: 0% -2%">
</div>

@endsection

@section('main-scripts')
    <script>
        $(document).ready(function () {

            $("#year").on('change', function(){
                $("#month").attr('disabled', false);
            });

            $("#month").on('change', function(){
                $("#viewData").attr('disabled', false);
            });

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
                window.open(url);
            });
            //================== export data end ==================

            
        });
    </script>    
@endsection