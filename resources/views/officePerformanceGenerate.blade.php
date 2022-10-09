@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Office Performance Report</div>
                <div class="card-body">
                	<div class="form-row">
                		<div class="form-group col-md-12">
							@php
                                $from = date_create($dateFrom);
                                $to = date_create($dateTo);

                                $displayFrom = date_format($from,"F d, Y");
                                $displayTo = date_format($to,"F d, Y");
                            @endphp
                            Report Date : {{$displayFrom}} to {{$displayTo}}
						</div>
					</div>
					<table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC; font-weight: bold;">
                                <td rowspan="2" style="width: 70%; border: 1px solid black;"><b>Office Name</b></td>
                                <td colspan="3" style="border: 1px solid black;"><b>Transaction</b></td>
                            </tr>
                            <tr style="text-align: center; background-color: #DCDCDC; font-weight: bold;">
                                <td style="width: 10%; border: 1px solid black;"><b>On-time</b></td>
                                <td style="width: 10%; border: 1px solid black;"><b>Delinquent</b></td>
                                <td style="width: 10%; border: 1px solid black;"><b>Total</b></td>
                            </tr>
                        </thead>
                        @php
                            include('../storage/app/public/php/databaseConnection.php');
                        @endphp
                        @foreach($offices as $val)
                            @php
                                $onTime = mysqli_query($connection,"select * from document_transaction where office_code='$val->office_code' and transaction_status='O' and receive_date_time between '$dateFrom' and '$dateTo'");
                                $onTimeResult = mysqli_num_rows($onTime);

                                $delinquent = mysqli_query($connection,"select * from document_transaction where office_code='$val->office_code' and transaction_status='D' and receive_date_time between '$dateFrom' and '$dateTo'");
                                $delinquentResult = mysqli_num_rows($delinquent);

                                $total = $onTimeResult+$delinquentResult;
                            @endphp
                            <tr>
                                <td style="border: 1px solid black;"><b>{{$val->office_code}}</b> - {{$val->office_name}}</td>
                                <td style="text-align: right; border: 1px solid black;">{{number_format($onTimeResult)}}</td>
                                <td style="text-align: right; border: 1px solid black;">{{number_format($delinquentResult)}}</td>
                                <td style="text-align: right; border: 1px solid black;">{{number_format($total)}}</td>
                            </tr>
                        @endforeach


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