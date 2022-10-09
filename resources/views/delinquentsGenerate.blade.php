@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">No of Delinquent Transactions per Office</div>
                <div class="card-body">
                    @php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
                  
                    <b>Month:</b> {{$cm}}
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style=" border: 1px solid black;"><b>Office Name</b></td>
                                <td style=" border: 1px solid black;"><b>No of Delinquent Transactions</b></td>
                            </tr>
                        </thead>
                        @php

                            $qryList = mysqli_query($connection,"select *,count(b.office_code)no_of_del from document a, document_transaction b, offices c where a.id=b.document_id and c.office_code=b.office_code and b.transaction_status='D' and b.receive_date_time like '$cm-%' group by c.office_code having count(b.office_code)>=1 order by count(b.office_code)desc");

                            for($a=1;$a<=mysqli_num_rows($qryList);$a++){
                                $record = mysqli_fetch_assoc($qryList);
                                @endphp
                                <tr>
                                    <td style=" border: 1px solid black;"><b>{{$record['office_code']}}</b> - {{$record['office_name']}}</td>
                                    <td style="text-align: center; border: 1px solid black;">{{number_format($record['no_of_del'])}}</td>
                                </tr>
                                @php
                            }
                        @endphp
                    </table>
                    <a href="/delinquents_report_detailed/{{$cm}}" target="_blank">Click here</a> to view deliquent transactions
                    
                   
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