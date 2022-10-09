@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">To-do List</div>
                <div class="card-body">
                    @php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
                  
                    <b>Month:</b> {{$cm}}
                    <br>
                    <b>No of Results:</b> {{$count}}
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style=" border: 1px solid black; width: 150px;"><b>Barcode</b></td>
                                <td style=" border: 1px solid black;"><b>Received Date</b></td>
                                <td style=" border: 1px solid black;"><b>Document Type</b></td>
                                <td style=" border: 1px solid black;"><b>Source Name</b></td>
                                <td style=" border: 1px solid black;"><b>Subject Matter</b></td>
                            </tr>
                        </thead>
                        @foreach($queryList as $index=> $val)
                            @php
                                $date = date_create($val->receive_date);
                                $date = date_format($date,"m/d/Y");
                            @endphp
                            <tr>
                                
                                <td style=" border: 1px solid black;">{{$index+1}}. <a href="/view_tracking/{{$val->id}}" target="_blank">{{$val->barcode}}</a></td>
                                <td style=" border: 1px solid black;">{{$date}}</td>
                                <td style=" border: 1px solid black;">{{$val->document_code}}</td>
                                <td style=" border: 1px solid black;">{{$val->source_name}}</td>
                                <td style=" border: 1px solid black;">{{$val->subject_matter}}</td>
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