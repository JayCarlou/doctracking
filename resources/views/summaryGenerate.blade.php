@extends('layouts.app')

@section('content')
<div class="container-fluid align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Summary Report</div>
                <div class="card-body">
                	<div class="form-row">
                		<div class="form-group col-md-12">
							@php
                                $from = date_create($dateFrom);
                                $to = date_create($dateTo);

                                $displayFrom = date_format($from,"F d, Y");
                                $displayTo = date_format($to,"F d, Y");
                            @endphp
                            <b>Report Date :</b> {{$displayFrom}} to {{$displayTo}}
						</div>
					</div>
					<table id="table" class="table table-striped table-bordered">
                        @php
                            include('../storage/app/public/php/databaseConnection.php');
                        @endphp
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="border: 1px solid black;" rowspan="2" style="width: 250px;"><b>Document Type</b></td>
                                <td style="border: 1px solid black;"colspan="4"><b>Simple</b></td>
                                <td style="border: 1px solid black;" colspan="4"><b>Complex</b></td>
                                <td style="border: 1px solid black;" colspan="4"><b>Highly Technical</b></td>
                            </tr>
                            <tr style="text-align: center; background-color: #DCDCDC;" style="font-size: 10px;">
                                <td style="border: 1px solid black;"><b>On-Time</b></td>
                                <td style="border: 1px solid black;"><b>On-Going</b></td>
                                <td style="border: 1px solid black;"><b>Delayed</b></td>
                                <td style="border: 1px solid black;"><b>Total</b></td>

                                <td style="border: 1px solid black;"><b>On-Time</b></td>
                                <td style="border: 1px solid black;"><b>On-Going</b></td>
                                <td style="border: 1px solid black;"><b>Delayed</b></td>
                                <td style="border: 1px solid black;"><b>Total</b></td>

                                <td style="border: 1px solid black;"><b>On-Time</b></td>
                                <td style="border: 1px solid black;"><b>On-Going</b></td>
                                <td style="border: 1px solid black;"><b>Delayed</b></td>
                                <td style="border: 1px solid black;"><b>Total</b></td>
                            </tr>
                        </thead>
                        @php
                            $qrySOnTimeTotal = 0; 
                            $qrySOnGoingTotal = 0;
                            $qrySDelayedTotal = 0;
                            $simpleTotalTotal = 0;

                            $qryCOnTimeTotal = 0;
                            $qryCOnGoingTotal = 0;
                            $qryCDelayedTotal = 0 ;
                            $complexTotalTotal = 0;

                            $qryHOnTimeTotal = 0;
                            $qryHOnGoingTotal = 0;
                            $qryHDelayedTotal = 0;
                            $htTotalTotal = 0;
                        @endphp

                        @foreach($documentType as $val)
                            @php
                                //simple
                                $qrySOnTime = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='O' and transaction_type_id='1' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qrySOnTime = mysqli_num_rows($qrySOnTime);
                                $qrySOnTimeTotal += $qrySOnTime;

                                $qrySOnGoing = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='G' and transaction_type_id='1' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qrySOnGoing = mysqli_num_rows($qrySOnGoing);
                                $qrySOnGoingTotal += $qrySOnGoing;

                                $qrySDelayed = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='D' and transaction_type_id='1' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qrySDelayed = mysqli_num_rows($qrySDelayed);
                                $qrySDelayedTotal += $qrySDelayed;

                                $simpleTotal = $qrySOnTime+$qrySOnGoing+$qrySDelayed;
                                $simpleTotalTotal += $simpleTotal;

                                //complex
                                $qryCOnTime = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='O' and transaction_type_id='2' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryCOnTime = mysqli_num_rows($qryCOnTime);
                                $qryCOnTimeTotal += $qryCOnTime;

                                $qryCOnGoing = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='G' and transaction_type_id='2' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryCOnGoing = mysqli_num_rows($qryCOnGoing);
                                $qryCOnGoingTotal += $qryCOnGoing;

                                $qryCDelayed = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='D' and transaction_type_id='2' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryCDelayed = mysqli_num_rows($qryCDelayed);
                                $qryCDelayedTotal += $qryCDelayed;

                                $complexTotal = $qryCOnTime+$qryCOnGoing+$qryCDelayed;
                                $complexTotalTotal += $complexTotal;

                                //highly technical
                                $qryHOnTime = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='O' and transaction_type_id='3' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryHOnTime = mysqli_num_rows($qryHOnTime);
                                $qryHOnTimeTotal += $qryHOnTime;

                                $qryHOnGoing = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='G' and transaction_type_id='3' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");

                                $qryHOnGoing = mysqli_num_rows($qryHOnGoing);
                                $qryHOnGoingTotal += $qryHOnGoing;

                                $qryHDelayed = mysqli_query($connection,"select * from document where document_type_id='$val->id' and document_status='D' and transaction_type_id='3' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryHDelayed = mysqli_num_rows($qryHDelayed);
                                $qryHDelayedTotal += $qryHDelayed;

                                $htTotal = $qryHOnTime+$qryHOnGoing+$qryHDelayed;
                                $htTotalTotal += $htTotal;

                            @endphp                            
                            <tr style="text-align: right;">
                                <td style="text-align: left; border: 1px solid black;">{{$val->document_type}}</td>
                                <td style="border: 1px solid black;">{{number_format($qrySOnTime)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qrySOnGoing)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qrySDelayed)}}</td>
                                <td style="border: 1px solid black;"><b>{{number_format($simpleTotal)}}</b></td>

                                <td style="border: 1px solid black;">{{number_format($qryCOnTime)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryCOnGoing)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryCDelayed)}}</td>
                                <td style="border: 1px solid black;"><b>{{number_format($complexTotal)}}</b></td>

                                <td style="border: 1px solid black;">{{number_format($qryHOnTime)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryHOnGoing)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryHDelayed)}}</td>
                                <td style="border: 1px solid black;"><b>{{number_format($htTotal)}}</b></td>
                            </tr>

                        @endforeach
                        <tr style="text-align: right; font-weight: bold;">
                            <td style="border: 1px solid black;">Total:</td>
                            <td style="border: 1px solid black;">{{number_format($qrySOnTimeTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qrySOnGoingTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qrySDelayedTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($simpleTotalTotal)}}</td>

                            <td style="border: 1px solid black;">{{number_format($qryCOnTimeTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qryCOnGoingTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qryCDelayedTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($complexTotalTotal)}}</td>

                            <td style="border: 1px solid black;">{{number_format($qryHOnTime)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qryHOnGoing)}}</td>
                            <td style="border: 1px solid black;">{{number_format($qryHDelayed)}}</td>
                            <td style="border: 1px solid black;">{{number_format($htTotalTotal)}}</td>
                        </tr>
                    </table>
                    <br>
                    <table id="table" class="table table-striped table-bordered" style="width: 650px;">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td rowspan="2" style="width: 250px; border: 1px solid black;"><b>Document Type</b></td>
                                <td style="border: 1px solid black;" colspan="2"><b>Source Type</b></td>
                                <td style="border: 1px solid black;" rowspan="2"><b>Total</b></td>
                            </tr>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="border: 1px solid black;"><b>Internal</b></td>
                                <td style="border: 1px solid black;"><b>External</b></td>
                            </tr>
                        </thead>
                        @php
                            $InternalTotal = 0;
                            $ExternalTotal = 0;
                        @endphp
                        @foreach($documentType as $val)
                            @php
                                //internal
                                $qryInternal = mysqli_query($connection,"select * from document where document_type_id='$val->id' and source_type_id='1' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryInternal = mysqli_num_rows($qryInternal);
                                $InternalTotal += $qryInternal;

                                //external
                                $qryExternal = mysqli_query($connection,"select * from document where document_type_id='$val->id' and source_type_id='2' and  receive_date BETWEEN '$dateFrom' and '$dateTo'");
                                $qryExternal = mysqli_num_rows($qryExternal);
                                $ExternalTotal += $qryExternal;

                                $total = $qryInternal+$qryExternal;

                                $totalTotal = $InternalTotal+$ExternalTotal
                            @endphp
                            <tr style="text-align: right;">
                                <td style="text-align: left; border: 1px solid black;">{{$val->document_type}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryInternal)}}</td>
                                <td style="border: 1px solid black;">{{number_format($qryExternal)}}</td>
                                <td style="border: 1px solid black;">{{number_format($total)}}</td>
                            </tr>
                        @endforeach
                        <tr style="text-align: right; font-weight: bold;">
                            <td style="border: 1px solid black;">Total</td>
                            <td style="border: 1px solid black;">{{number_format($InternalTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($ExternalTotal)}}</td>
                            <td style="border: 1px solid black;">{{number_format($totalTotal)}}</td>
                        </tr>
                    </table>
                    <br>
                    <table id="table" class="table table-striped table-bordered" style="width: 600px;">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="border: 1px solid black;" rowspan="2" style="width: 250px;"><b>Document Type</b></td>
                                <td style="border: 1px solid black;" colspan="3"><b>Average Processing Time</b></td>
                            </tr>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="border: 1px solid black;"><b>Simple </b></td>
                                <td style="border: 1px solid black;"><b>Complex</b></td>
                                <td style="border: 1px solid black;"><b>Highly<br>Technical</b></td>
                            </tr>
                        </thead>
                        @php

                        @endphp
                        @foreach($documentType as $val)
                            @php
                                $qrySimple = mysqli_query($connection,"select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id='$val->id' and transaction_type_id='1' and receive_date between '$dateFrom' and '$dateTo'");

                                $qrySimple = mysqli_fetch_assoc($qrySimple);

                                $qryComp = mysqli_query($connection,"select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id='$val->id' and transaction_type_id='2' and receive_date between '$dateFrom' and '$dateTo'");

                                $qryComp = mysqli_fetch_assoc($qryComp);

                                $qryHT = mysqli_query($connection,"select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id='$val->id' and transaction_type_id='2' and receive_date between '$dateFrom' and '$dateTo'");

                                $qryHT = mysqli_fetch_assoc($qryHT);
                                

                        
                            @endphp
                            <tr style="text-align: center;">
                                <td style="text-align: left; border: 1px solid black;">{{$val->document_type}}</td>
                                <td style="border: 1px solid black;">{{number_format($qrySimple['average'],2)}} days</td>
                                <td style="border: 1px solid black;">{{number_format($qryComp['average'],2)}} days</td>
                                <td style="border: 1px solid black;">{{number_format($qryHT['average'],2)}} days</td>
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <table id="table" class="table table-striped table-bordered" style="width: 350px;">
                        <thead>
                            <tr style="text-align: center;">
                                <td style="width: 250px; border: 1px solid black; background-color: #DCDCDC;"><b>Client Sex</b></td>
                                <td style="border: 1px solid black; background-color: #DCDCDC;"><b>Total</b></td>
                            </tr>
                           
                        </thead>
                         <tr>
                            <td style="border: 1px solid black;"><b>Male</b></td>
                            <td style="text-align: right; border: 1px solid black;">{{number_format($maleClient)}}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;"><b>Female</b></td>
                            <td style="text-align: right; border: 1px solid black;">{{number_format($femaleClient)}}</td>
                        </tr>
                        <tr style="text-align: right; font-weight: bold;">
                            <td style="border: 1px solid black;"><b>Total</b></td>
                            <td style="border: 1px solid black;"><b>{{number_format($femaleClient+$maleClient)}}</b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // $(function() {
    //     $('#table').DataTable({
    //     });
    // });
</script>

@stack('scripts')
@endsection