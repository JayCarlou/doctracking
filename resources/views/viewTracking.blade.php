@extends('layouts.app')
@php
    include('../storage/app/public/php/databaseConnection.php');
@endphp
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card" style="padding: 15px; height: 275px;">
                <table>
                    <tr>
                        <td colspan="2"><h4>Document Details</h4></td>
                    </tr>
                    <tr>
                        <td width="130px;"><b>Barcode: </b></td>
                        <td>{{$barcode}}</td>
                    </tr>
                    <!-- <tr>
                        <td><b>Access Code: </b></td>
                        <td>{{$accessCode}}</td>
                    </tr> -->
                    <tr>
                        <td><b>Date Start: </b></td>
                        <td>{{$startDate}}</td>
                    </tr>
                    <tr>
                        <td><b>Date End: </b></td>
                        <td>{{$endDate}}</td>
                    </tr>
                    <tr>
                        <td><b>Transaction Type: <b></td>
                        <td>{{strtoupper($transactionType)}}</td>
                    </tr>
                    <tr>
                        <td><b>Document Type: </b></td>
                        <td>{{strtoupper($documentType)}}</td>
                    </tr>
                    <tr>
                        <td><b>Delivery Method: </b></td>
                        <td>{{strtoupper($deliveryMethod)}}</td>
                    </tr>
                    <tr>
                        <td><b>Source Type: </b></td>
                        <td>{{strtoupper($source)}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="height: 250px; height: 275px;">
                <div class="card-body">
                   <table>
                        <tr>
                            <td width="120px;"><b>Source: </b></td>
                            <td>{{strtoupper($sourceName)}}</td>
                        </tr>
                        <tr>
                            <td><b>Subject Matter: </b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">{{$subjectMatter}}</td>
                        </tr>
                    </table>
        
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="height: 250px; height: 275px;">
                <div class="card-body">
                   <table>
                        <tr>
                            <td><b>Documents Linked: </b></td>
                        </tr>
                        <tr>
                            <td>
                                @php
                                    $count = str_word_count($documentsLinked)-1;
                                    $docs = explode(" ",$documentsLinked);
                                    
                                    for($a=0;$a<=$count;$a++){

                                        $qry = mysqli_query($connection,"select id from document where barcode='$docs[$a]'");

                                        $result = mysqli_fetch_assoc($qry);
                                     
                                        echo "<a href='/view_tracking/$result[id]' target='_blank'>".$docs[$a]."</a> ";
                                    }
                                    
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td><b>Attachments: </b></td>
                        </tr>
                        @foreach($attachments as $attachments)
                        <tr>
                            <td><a href="../document_download/{{$attachments->id}}" target="_blank">{{$attachments->document_name}}</a></td>
                        </tr>
                        @endforeach
                    </table>
        
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table" style="border: 1px solid black;">
                       
                        <tr style="text-align: center; background-color: #DCDCDC; font-weight: bold;">
                            <td colspan="4" style="border: 1px solid black;">RECEIVED</td>
                            <td colspan="4" style="border: 1px solid black;">RELEASED</td>
                            <td colspan="2" style="border: 1px solid black;">DURATION</td>
                            <td rowspan="2" style="border: 1px solid black;"><br>REMARKS</td>
                        </tr>
                        <tr style="text-align: center; background-color: #DCDCDC; font-weight: bold;" >
                            <td style="border: 1px solid black;">Office</td>
                            <td style="border: 1px solid black;">By</td>
                            <td style="border: 1px solid black;">Datetime</td>
                            <td style="border: 1px solid black;">Action</td>
                            <td style="border: 1px solid black;">By</td>
                            <td style="border: 1px solid black;">Datetime</td>
                            <td style="border: 1px solid black;">Action</td>
                            <td style="border: 1px solid black;">To</td>
                            <td style="border: 1px solid black;">Office</td>
                            <td style="border: 1px solid black;">Transit</td>
                        </tr>

                        @php
                            $b=0
                        @endphp
                        @foreach($docTransactions as $index=> $result)
                        @php
                            $classRow = "row".($b++ & 1);
                        @endphp
                        <tr style="font-size: 12px;" class="{{$classRow}}">
                            <td style="border: 1px solid #C0C0C0;">{{$result->office_code}}</td>
                            <td style="border: 1px solid #C0C0C0;">{{$result->receive_person}}</td>
                            <td style="border: 1px solid #C0C0C0;">{{$result->date}}<br>{{$result->time}}</td>
                            <td style="border: 1px solid #C0C0C0;">{{$result->receive_action}}</td>
                            @php
                                    
                                $start = new DateTime($result->receive_date_time);
                                $end = new DateTime($result->release_date_time);

                                $days = $start->diff($end, true)->days;

                                $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
                                $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

                                $weekends = ($saturdays+$sundays);


                                $dateRange1 = explode(' ',$result->receive_date_time);
                                $dateRange2 = explode(' ',$result->release_date_time);

                                $holidayRangeFrom = $dateRange1[0];
                                $holidayRangeTo = $dateRange2[0];

                                $qryHoliday = db::select(db::raw("select count(id)totalHolidays from holidays where status='1' and (DAYOFWEEK(holiday_date)!=1 or DAYOFWEEK(holiday_date)!=7) and  holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));

                                foreach($qryHoliday as $val){
                                    $totalHolidays = $val->totalHolidays;
                                }

                                $totalDays = $totalHolidays+$weekends;

                            @endphp
                            @if($result->current_action=="END")
                                <td style="border: 1px solid #C0C0C0;">
                                    {{$result->release_person}}
                                </td>
                                <td>
                                    {{$result->reldate}}
                                    <br>
                                    {{$result->reltime}}
                                </td>

                                <td colspan="2" style="border: 1px solid #C0C0C0;">
                                    {{$result->release_action}}
                                </td>
                                <td style="border: 1px solid #C0C0C0;">
                                    @if($result->odays==0)

                                    @else
                                        {{$result->odays-$totalDays}} 
                                        @if($result->odays>=1)
                                            days
                                        @else
                                            day
                                        @endif
                                        <br>
                                    @endif

                                    @if($result->ohours==0)
                                        
                                    @else
                                        {{$result->ohours}}
                                        @if($result->ohours>=1)
                                            hours
                                        @else
                                            hour
                                        @endif
                                        <br>
                                    @endif

                                    @if($result->ominutes==0)
                                        @if($result->osec=="")
                                            -
                                        @else
                                            {{$result->osec}} seconds                                     
                                        @endif
                                        
                                    @else
                                        {{$result->ominutes}}
                                        @if($result->ominutes>=1)
                                            minutes
                                        @else
                                            minute
                                        @endif
                                        <br>
                                    @endif

                                </td>
                                <td style="border: 1px solid #C0C0C0; text-align: center;">N/A</td>
                            @else
                                <td style="border: 1px solid #C0C0C0;">{{$result->release_person}}</td>
                                <td style="border: 1px solid #C0C0C0;">{{$result->reldate}}<br>{{$result->reltime}}</td>
                                <td style="border: 1px solid #C0C0C0;">{{$result->release_action}}</td>
                                <td style="border: 1px solid #C0C0C0;">{{$result->route_office_code}}</td>
                                <td style="border: 1px solid #C0C0C0;">


                                    @if($result->odays==0)

                                    @else
                                        {{$result->odays-$totalDays}}
                                        @if($result->odays>=1)
                                            days
                                        @else
                                            day
                                        @endif
                                        <br>
                                    @endif

                                    @if($result->ohours==0)

                                    @else
                                        {{$result->ohours}}
                                        @if($result->ohours>=1)
                                            hours
                                        @else
                                            hour
                                        @endif
                                        <br>
                                    @endif

                                    @if($result->ominutes==0)
                                        @if($result->osec=="")
                                        @else
                                            {{$result->osec}} seconds                                     
                                        @endif
                                        
                                    @else
                                        {{$result->ominutes}}
                                        @if($result->ominutes>=1)
                                            minutes
                                        @else
                                            minute
                                        @endif
                                        <br>
                                    @endif
                                </td>
                                <td style="border: 1px solid #C0C0C0;">
                                    @if($result->tdays==0)

                                    @else
                                        {{$result->tdays-$totalDays}} days
                                        <br>
                                    @endif

                                    @if($result->thours==0)

                                    @else
                                        {{$result->thours}} hours
                                        <br>
                                    @endif

                                    @if($result->tminutes==0)
                                        @if($result->tsec=="")
                                        @else
                                            {{$result->tsec}} seconds
                                        @endif
                                    @else
                                        {{$result->tminutes}} minutes
                                        <br>
                                    @endif
                                </td>
                            @endif  
                            <td style="border: 1px solid #C0C0C0;">
                                @if($result->transaction_status=="D")
                                    <span style="color: red;"><b>Delinquent</b></span>
                                    <br>
                                @else
                                @endif
                                
                                
                                {{$result->remarks}}
                            </td>
                        </tr>
                        @endforeach
                        
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
    $(function() {
        $('#table').DataTable({
            "order": [[ 0, "desc" ]]
        });
    });

    // function search(){
    //     sb = document.getElementById("searchBy").value;
    // }
</script>
<style style="text/css">
    /*.table tr:hover {
          background-color: #ffff99;
    }*/
    .row0{
        background-color: #FAFAFA;
    }
    .row0:hover{
        background-color: #FBFCEA;
    }
    .row1{
        background-color: #fff;
    }
    .row1:hover{
        background-color: #FBFCEA;
    }
</style>



@endsection
