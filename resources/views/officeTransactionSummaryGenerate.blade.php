@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Offce Transaction Summary</div>
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
                            <br>
                            <b>Office :</b> {{strtoupper($officeName)}}
						</div>
					</div>
                    @php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
					@foreach($persons as $index=> $val)
                        <b>Employee Name :</b> {{strtoupper($val->name)}}
                        
                        <table id="table" class="table table-striped table-bordered">
                            <thead>
                                <tr style="text-align: center; background-color: #DCDCDC;">
                                    <td style="border: 1px solid black;" rowspan="2" style="width: 250px;"><b>Document Type</b></td>
                                    <td style="border: 1px solid black;" colspan="3"><b>Transaction</b></td>
                                    <td style="border: 1px solid black;" colspan="2"><b>Document Status</b></td>
                                </tr>
                                <tr style="text-align: center; background-color: #DCDCDC;">
                                    <td style="border: 1px solid black;"><b>Received</b></td>
                                    <td style="border: 1px solid black;"><b>Released</b></td>
                                    <td style="border: 1px solid black;"><b>Total</b></td>
                                    <td style="border: 1px solid black;"><b>On-Time</b></td>
                                    <td style="border: 1px solid black;"><b>On-Delayed</b></td>
                                </tr>
                            </thead>
                            @php
                                $totalReceived = 0;
                                $totalReleased = 0;

                                $totalOnTime = 0;
                                $totalDelayed = 0;

                                $qryDocType = mysqli_query($connection,"select * from document_type where status='1'");
                                for($a=1;$a<=mysqli_num_rows($qryDocType);$a++){
                                    $result = mysqli_fetch_array($qryDocType);  


                                    $qryReceived = mysqli_query($connection,"select * from document_transaction a, document b where a.document_id=b.id and b.document_type_id='$result[id]' and a.receive_person='$val->name' and a.receive_date_time between '$dateFrom' and '$dateTo'");
                                    $received = mysqli_num_rows($qryReceived);
                                    
                                    $qryReleased = mysqli_query($connection,"select * from document_transaction a, document b where a.document_id=b.id and b.document_type_id='$result[id]' and a.release_person='$val->name' and a.release_date_time between '$dateFrom' and '$dateTo'");
                                    $released = mysqli_num_rows($qryReleased);

                                    $qryOnTime = mysqli_query($connection,"select * from document_transaction a, document b where a.document_id=b.id and b.document_type_id='$result[id]' and a.receive_person='$val->name' and a.receive_date_time between '$dateFrom' and '$dateTo' and transaction_status='O'");
                                    $onTime = mysqli_num_rows($qryOnTime);

                                    $qryDelayed = mysqli_query($connection,"select * from document_transaction a, document b where a.document_id=b.id and b.document_type_id='$result[id]' and a.receive_person='$val->name' and a.receive_date_time between '$dateFrom' and '$dateTo' and transaction_status='D'");
                                    $delayed = mysqli_num_rows($qryDelayed);

                                    $totalReceived += $received;
                                    $totalReleased += $released;

                                    $totalTransaction = $totalReceived+$totalReleased;

                                    $totalOnTime += $onTime;
                                    $totalDelayed += $delayed;

                                    @endphp
                                        <tr style="text-align: right;">
                                            <td style="text-align: left; border: 1px solid black;">{{$result['document_type']}}</td>
                                            <td style="border: 1px solid black;">{{number_format($received)}}</td>
                                            <td style="border: 1px solid black;">{{number_format($released)}}</td>
                                            <td style="border: 1px solid black;"><b>{{number_format($released+$received)}}</b></td>
                                            <td style="border: 1px solid black;">{{number_format($onTime)}}</td>
                                            <td style="border: 1px solid black;">{{number_format($delayed)}}</td>
                                        </tr>
                                    @php
                                }

                            @endphp
                                <tr style="text-align: right; font-weight: bold;">
                                    <td style="border: 1px solid black;">Total:</td>
                                    <td style="border: 1px solid black;">{{number_format($totalReceived)}}</td>
                                    <td style="border: 1px solid black;">{{number_format($totalReleased)}}</td>
                                    <td style="border: 1px solid black;">{{number_format($totalTransaction)}}</td>
                                    <td style="border: 1px solid black;">{{number_format($totalOnTime)}}</td>
                                    <td style="border: 1px solid black;">{{number_format($totalDelayed)}}</td>
                                </tr>
                        </table>
                        

                    @endforeach
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