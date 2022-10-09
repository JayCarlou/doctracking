@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>
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
                            <b>No. of Result :</b> {{$resultCounter}}
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered" style="font-size: 10px;">
                        <thead style="font-size: 12px;">
                            <tr style="text-align: center; background-color: #DCDCDC; font-weight: bold;">
                                @if($receiveDate=="1")
                                    <th style="border: 1px solid black; width: 90px;">Receive Date</th> 
                                @else
                                @endif

                                @if($barcode=="1")
                                    <th style="border: 1px solid black;">Barcode</th>
                                @else
                                @endif

                                @if($documentType=="1")
                                    <th style="border: 1px solid black;">Document Type</th>
                                @else
                                @endif

                                @if($sourceType=="1")
                                    <th style="border: 1px solid black;">Source Type</th>
                                @else
                                @endif

                                @if($sourceName=="1")
                                    <th style="border: 1px solid black;">Source Name</th>
                                @else
                                @endif

                                @if($deliveryMethod=="1")
                                    <th style="border: 1px solid black;">Delivery Method</th>
                                @else
                                @endif

                                @if($transactionType=="1")
                                    <th style="border: 1px solid black;">Transaction Type</th>
                                @else
                                @endif

                                @if($sex=="1")
                                    <th style="border: 1px solid black;">Sex</th>
                                @else
                                @endif

                                @if($contactNo=="1")
                                    <th style="border: 1px solid black;">Contact No.</th>
                                @else
                                @endif

                                @if($email=="1")
                                    <th style="border: 1px solid black;">Email</th>
                                @else
                                @endif
                                
                                @if($subjectMatter=="1")
                                    <th style="border: 1px solid black;">Subject Matter</th>
                                @else
                                @endif

                                @if($totalTime=="1")
                                    <th style="border: 1px solid black;">Total Time </th>
                                @else
                                @endif
                                
                                
                            </tr>
                        </thead>
                        @foreach($result as $val)
                            <tr>
                                @if($receiveDate=="1")
                                    <td style="border: 1px solid black;">{{$val->receive_date}}</td>
                                @else
                                @endif

                                @if($barcode=="1")
                                    <td style="border: 1px solid black;">
                                        <a href="/view_tracking/{{$val->aid}}">{{$val->barcode}}</a>
                                    </td>
                                @else
                                @endif

                                @if($documentType=="1")
                                    <td style="border: 1px solid black;">{{$val->document_code}}</td>
                                @else
                                @endif

                                @if($sourceType=="1")
                                    <td style="border: 1px solid black;">{{$val->source}}</td>
                                @else
                                @endif

                                @if($sourceName=="1")
                                    <td style="border: 1px solid black;">{{$val->source_name}}</td>
                                @else
                                @endif

                                @if($deliveryMethod=="1")
                                    <td style="border: 1px solid black;">{{$val->method}}</td>
                                @else
                                @endif

                                @if($transactionType=="1")
                                    <td style="border: 1px solid black;">{{$val->transaction_type}}</td>
                                @else
                                @endif

                                @if($sex=="1")
                                    <td style="border: 1px solid black;">{{$val->sex}}</td>
                                @else
                                @endif

                                 @if($contactNo=="1")
                                    <td style="border: 1px solid black;">{{$val->contact_no}}</td>
                                @else
                                @endif

                                @if($email=="1")
                                    <td style="border: 1px solid black;">{{$val->email_address}}</td>
                                @else
                                @endif
                                
                                @if($subjectMatter=="1")
                                    <td style="border: 1px solid black;">{{$val->subject_matter}}</td>
                                @else
                                @endif

                                @if($totalTime=="1")
                                    <td style="border: 1px solid black;">
                                        @if($val->days==0)
                                        @else
                                            {{$val->days}} days
                                            <br>
                                        @endif

                                        @if($val->hours==0)
                                        @else
                                            {{$val->hours}} hours
                                            <br>
                                        @endif

                                        @if($val->minutes==0)
                                        @else
                                            {{$val->minutes}} minutes
                                            <br>
                                        @endif

                                    </td>
                                @else
                                @endif
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