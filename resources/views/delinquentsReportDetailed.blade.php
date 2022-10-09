@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Delinquent Offices</div>
                <div class="card-body">
                    @php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
                  
                    <b>Month:</b> {{$cm}}
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="border: 1px solid black;"><b>Barcode</b></td>
                                <td style="border: 1px solid black;"><b>Office Name</b></td>
                                <td style="border: 1px solid black;"><b>Received</b></td>
                                <td style="border: 1px solid black;"><b>Released</b></td>
                                <td style="border: 1px solid black;"><b>Duration</b></td>
                            </tr>
                        </thead>
                        @foreach($queryList as $index=> $val)
                            @php
                                        
                                $start = new DateTime($val->rcvdate);
                                $end = new DateTime($val->reldate);

                                $days = $start->diff($end, true)->days;

                                $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
                                $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

                                $weekends = $saturdays+$sundays; 


                                $dateRange1 = explode(' ',$val->rcvdate);
                                $dateRange2 = explode(' ',$val->reldate);

                                $holidayRangeFrom = $dateRange1[0];
                                $holidayRangeTo = $dateRange2[0];

                                $qryHoliday = db::select(db::raw("select count(id)totalHolidays from holidays where status='1' and holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));
                                
                                foreach($qryHoliday as $val2){
                                    $totalHoliday = $val2->totalHolidays;
                                }

                                $totalDays = $totalHoliday+$weekends;
                            @endphp


                            <tr>
                                <td style="border: 1px solid black;">{{$index+1}}. <a href="/view_tracking/{{$val->aid}}" target="_blank">{{$val->barcode}}</a></td>
                                <td style="border: 1px solid black;"><b>{{$val->office_code}}</b> - {{$val->office_name}}</td>                                
                                <td style="border: 1px solid black;">{{$val->date1}}</td>
                                <td style="border: 1px solid black;">{{$val->date}}</td>
                                <td style="border: 1px solid black;">


                                    @if($val->tdays==0)

                                    @else
                                        {{$val->tdays-$totalDays}} 
                                        @if($val->tdays>=1)
                                            days
                                        @else
                                            day
                                        @endif
                                        <br>
                                    @endif

                                    @if($val->thours==0)

                                    @else
                                        {{$val->thours}}
                                        @if($val->thours>=1)
                                            hours
                                        @else
                                            hour
                                        @endif
                                        <br>
                                    @endif

                                    @if($val->tminutes==0)
                                        @if(Auth::user()->access=="A")
                                            <br>
                                            <br>
                                            <a href="{{URL::to('correct_transaction_status/'.$val->aid.'/'.$val->bid)}}" title="Correct Transaction Status">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-btn fa-check"></i>
                                                </button>
                                            </a>
                                        @else
                                    @else
                                        {{$val->tminutes}}
                                        @if($val->tminutes>=1)
                                            minutes
                                        @else
                                            minute
                                        @endif
                                        
                                        @if(Auth::user()->access=="A")
                                            <br>
                                            <br>
                                            <a href="{{URL::to('correct_transaction_status/'.$val->aid.'/'.$val->bid)}}" title="Correct Transaction Status">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-btn fa-check"></i>
                                                </button>
                                            </a>
                                        @else

                                        @endif
                                    @endif
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
    // $(function() {
    //     $('#table').DataTable({
    //     });
    // });
</script>

@stack('scripts')
@endsection