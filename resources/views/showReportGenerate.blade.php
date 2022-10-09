@extends('layouts.app')

@section('content')
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$lbl}}</div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <b>No of Results :</b> {{$counter}}
                        </div>
                    </div>
                        
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; background-color: #DCDCDC;">
                                <td style="width: 50px; border: 1px solid black;"><b>#</b></td>
                                <td style="width: 150px; border: 1px solid black;"><b>Barcode</b></td>
                                <td style="width: 150px; border: 1px solid black;"><b>Received Date</b></td>
                                <td style="width: 150px; border: 1px solid black;"><b>Document Type</b></td>
                                <td style="border: 1px solid black;"><b>Source</b></td>
                                <td style="border: 1px solid black;"><b>Subject Matter</b></td>
                            </tr>
                        </thead>
                        @foreach($queryList as $index=> $val)
                            <tr>
                                <td style="border: 1px solid black;">{{$index+1}}</td>
                                <td style="border: 1px solid black;"><a href="/view_tracking/{{$val->aid}}" target="_blank"> {{$val->barcode}}</a></td>
                                <td style="border: 1px solid black;">{{$val->dte}}</td>
                                <td style="border: 1px solid black;">{{$val->document_code}}</td>
                                <td style="border: 1px solid black;">{{$val->source_name}}</td>
                                <td style="border: 1px solid black;">{{$val->subject_matter}}</td>

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