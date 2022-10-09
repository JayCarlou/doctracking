@extends('layouts.app')

@section('content')
<div class="container">
    <form method="post" action="{{ url('generate_dashboard') }}" autocomplete="off">
        {{ csrf_field() }}
        <div class="row justify-content-left" style="padding-left: 50px;">
            <div class="col-md-10">
                <div class="form-row">
                    <label for="password" class="col-md-2 col-form-label">{{ __('Dashboard Report :') }}</label>
                    <div class="form-group col-md-2">
                        <select class="form-control" id="month" name="month">
                            <option value="{{$chosenMonth}}">{{$chosenMonthName}}</option>
                            @foreach($months as $months)
                                <option value="{{$months->month_value}}">{{$months->month}}</option>
                            @endforeach 

                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control" name="year" id="year" value="{{date('Y')}}" maxlength="4">
                    </div>
                    <div class="form-group col-md-3">
                        <select class="form-control" id="basis" name="basis">
                            <option value="1">Basis: Received Date</option>
                            <option value="2">Basis: End Date</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary btn-block" onclick="click()">
                            <i class="fa fa-btn fa-refresh"></i> GENERATE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="col-md-2">
            <div class="card">
                <center>
                    <div class="card-header" style="background: #7DC4FA;">
                        ON-TIME
                    </div>
                    <a class="dropdown-item"  href="show_report/1/O/1/{{$currentMonth}}/Simple Documents (On-time)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Simple Documents</div>
                        <b>{{$ontimeSimple}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/O/2/{{$currentMonth}}/Complex Documents (On-time)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Complex Documents</div>
                        <b>{{$ontimeComplex}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/O/3/{{$currentMonth}}/Highly Technical Documents (On-time)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Highly Technical</div>
                        <b>{{$ontimeHigh}}</b>
                    </a>
                </center>
            </div>
        </div>
        <div class="col-md-2" style="margin-left: -20px;">
            <div class="card">
                <center>
                    <div class="card-header" style="background: #7DC4FA;">
                        ON-GOING
                    </div>
                    <a class="dropdown-item"  href="show_report/1/G/1/{{$currentMonth}}/Simple Documents (On-going)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Simple Documents</div>
                        <b>{{$ongoingSimple}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/G/2/{{$currentMonth}}/Complex Documents (On-going)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Complex Documents</div>
                        <b>{{$ongoingComplex}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/G/3/{{$currentMonth}}/Highly Technical Documents (On-going)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Highly Technical</div>
                        <b>{{$ongoingHigh}}</b>
                    </a>
                </center>
            </div>
        </div>
        <div class="col-md-2" style="margin-left: -20px;">
            <div class="card">
                <center>
                    <div class="card-header" style="background: #7DC4FA;">
                        DELAYED
                    </div>
               
                    <a class="dropdown-item"  href="show_report/1/D/1/{{$currentMonth}}/Simple Documents (Delayed)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Simple Documents</div>
                        <b>{{$delayedSimple}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/D/2/{{$currentMonth}}/Complex Documents (Delayed)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Complex Documents</div>
                        <b>{{$delayedComplex}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="show_report/1/D/3/{{$currentMonth}}/Highly Technical (Delayed)" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">Highly Technical</div>
                        <b>{{$delayedHigh}}</b>
                    </a>
                </center>
            </div>
        </div>
        <div class="col-md-2" style="margin-left: -20px;">
            <div class="card">
                <center>
                <div class="card-header" style="background: #FFCC99;">FOR DUE TOM.</div>
                    <a class="dropdown-item"  href="for_due/{{$currentOffice}}" style="padding: 20px;" target="_blank">
                        <div style="font-size: 12px;">No. of Documents</div>
                        <b>{{$forDue}}</b>
                    </a>
                </center>
            </div>
            <div class="card" style="margin-top: 10px;">
                <center>
                <div class="card-header" style="background: #FAA1A1;">DELINQUENTS</div>
                    <a class="dropdown-item"  href="delinquents_report/{{$currentMonth}}" target="_blank" style="padding: 20px;">
                        <div style="font-size: 12px;">No. of Transactions</div>
                        <b>{{$delinquentTransactions}}</b>
                    </a>
                </center>
            </div>
        </div>
        <div class="col-md-2" style="margin-left: -20px;">
            <div class="card">
                <center>
                <div class="card-header" style="background: #B6E5B6;">DOCUMENTS</div>
                    <a class="dropdown-item"  href="document_list/{{$currentMonth}}" target="_blank" style="padding: 20px;">
                        <div style="font-size: 12px;">No. of Documents</div>
                        <b>{{$noOfDocs}}</b>
                    </a>
                </center>
            </div>
            <div class="card" style="margin-top: 10px;">
                <center>
                <div class="card-header" style="background: #DDF5D7;">TO-DO LIST</div>
                    <a class="dropdown-item"  href="to_do_list/{{$currentMonth}}" target="_blank" style="padding: 20px;">
                        <div style="font-size: 12px;">No. of Documents</div>
                        <b>{{$toDoList}}</b>
                    </a>
                </center>
            </div>
        </div>
        <div class="col-md-2" style="margin-left: -20px;">
            <div class="card">
                <center>
                <div class="card-header" style="background: #B6E5B6;">CLIENTS</div>
                    <a class="dropdown-item"  href="client_source/2/{{$currentMonth}}/1" target="_blank" style="padding: 20px;">
                        <div style="font-size: 12px;">External Clients</div>
                        <b>{{$externalClients}}</b>
                    </a>
                </center>
            </div>
            <div class="card">
                <center>
                    <a class="dropdown-item"  href="client_source/1/{{$currentMonth}}/1" target="_blank" style="padding: 20px;">
                        <div style="font-size: 12px;">Internal Clients</div>
                        <b>{{$internalClients}}</b>
                    </a>
                </center>
            </div>
        </div>
    </div>
    <div class="row justify-content-left" style="padding-top: 10px; padding-bottom: 10px; padding-left: 50px;">
        <div class="col-md-4">
            <select class="form-control" id="transaction" name="transaction" onchange="showDiv()">
                <option value="1">Simple Transaction</option>
                <option value="2">Complex Transaction</option>
                <option value="3">Highly Technical</option>
            </select>
        </div>
    </div>

    <div id="simple">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchace Order</div>
                            <b>{{number_format($avgSimplePO,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Voucher</div>
                            <b>{{number_format($avgSimpleVC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Payroll</div>
                            <b>{{number_format($avgSimplePY,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Communication</div>
                            <b>{{number_format($avgSimpleCOM,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchase Request</div>
                            <b>{{number_format($avgSimplePR,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Abstract of Canvass</div>
                            <b>{{number_format($avgSimpleAOC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div id="complex" style="display:none;">
        
        <div class="row justify-content-center">
            <div class="col-md-2">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchace Order</div>
                            <b>{{number_format($avgComplexPO,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Voucher</div>
                            <b>{{number_format($avgComplexVC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Payroll</div>
                            <b>{{number_format($avgComplexPY,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Communication</div>
                            <b>{{number_format($avgComplexCOM,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchase Request</div>
                            <b>{{number_format($avgComplexPR,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Abstract of Canvass</div>
                            <b>{{number_format($avgComplexAOC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div id="highlyTechnical" style="display:none">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchace Order</div>
                            <b>{{number_format($avgHighlyPO,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Voucher</div>
                            <b>{{number_format($avgHighlyVC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Payroll</div>
                            <b>{{number_format($avgHighlyPY,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Communication</div>
                            <b>{{number_format($avgHighlyCOM,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Purchase Request</div>
                            <b>{{number_format($avgHighlyPR,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
            <div class="col-md-2" style="margin-left: -20px;">
                <div class="card">
                    <center>
                        <a class="dropdown-item"  href="" style="padding: 20px;">
                            <div style="font-size: 12px;">Abstract of Canvass</div>
                            <b>{{number_format($avgHighlyAOC,2)}} days</b>
                        </a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    include('../storage/app/public/php/databaseConnection.php');
@endphp

@foreach($qryTransactions as $val)
    @php
        $resultOfficeId = $val->id;
        $resultDocumentType = $val->document_type_id;
        $resultMinutes = $val->inMinute;
        $resultBarcode = $val->barcode;
        $resultDocId = $val->did;

        $qryPerformance = mysqli_query($connection,"select * from office_performance where office_id='$resultOfficeId' and document_code_id='$resultDocumentType'");

        for($a=1;$a<=mysqli_num_rows($qryPerformance);$a++){
            $result = mysqli_fetch_assoc($qryPerformance);
            $officeTime = $result['office_time'];
        }
       
        if($resultMinutes>$officeTime){

            @endphp
                <div class="alert alert-danger alert-dismissible flash_alert" style="width: 450px; left: 55%;
                  top: 55px; position: absolute; z-index: 100;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <a href="/view_tracking/{{$resultDocId}}" target="_blank" style="color: black;">
                        Document <b>{{$resultBarcode}}</b> is beyond the prescribed time!
                    </a>
                </div>
            @php
        }
        else{
        }

    @endphp
@endforeach






@endsection

<script type="text/javascript">
    function showDiv(){
        var selectBox = document.getElementById("transaction");

        if(selectBox.value == "1"){
            document.getElementById('simple').style.display = "block";
            document.getElementById('complex').style.display = "none";
            document.getElementById('highlyTechnical').style.display = "none";
        }
        else if(selectBox.value == "2"){
            document.getElementById('simple').style.display = "none";
            document.getElementById('complex').style.display = "block";
            document.getElementById('highlyTechnical').style.display = "none";
        }
        else{
            document.getElementById('simple').style.display = "none";
            document.getElementById('complex').style.display = "none";
            document.getElementById('highlyTechnical').style.display = "block";
        }
    }
</script>

@stack('scripts')



<!-- @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

You are logged in! -->