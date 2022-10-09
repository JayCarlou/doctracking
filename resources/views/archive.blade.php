@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Archive</div>

                <div class="card-body">
                    <form method="post" action="{{ url('archive') }}" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="inputPassword" class="col-md-2 col-form-label">Search By:</label>
                            <div class="col-md-2" style="margin-left: -100px;">
                                <select class="form-control" id="searchBy" style="width: 170px;" name="searchBy" required="required">
                                    @if($searchBy=="")
                                        <option value="">Select One</option>
                                        <option value="1">Subject Matter</option>
                                        <option value="2">Source Name</option>
                                        <option value="3">Barcode</option>
                                    @elseif($searchBy=="1")
                                        <option value="1">Subject Matter</option>
                                        <option value="2">Source Name</option>
                                        <option value="3">Barcode</option>
                                    @elseif($searchBy=="2")
                                        <option value="2">Source Name</option>
                                        <option value="1">Subject Matter</option>
                                        <option value="3">Barcode</option>
                                    @else
                                        <option value="3">Barcode</option>
                                        <option value="1">Subject Matter</option>
                                        <option value="2">Source Name</option>
                                    @endif
                                    
                                    <!-- <option value="4">Transaction Type</option>
                                    <option value="5">Document Type</option>
                                    <option value="6">Source Type</option>
                                    <option value="7">Delivery Method</option> -->
                                </select>
                            </div>
                            <div class="col-md-2 form-inline" >
                                <input class="form-control" type="text" name="Keyword" id="Keyword" placeholder="Keyword" required="required" value="{{$keyword}}">
                            </div>
                            <div class="col-md-2 form-inline" >
                                <button type="submit" class="btn btn-primary btn-block" onclick="click()" style="margin-left: 20px;">
                                    <i class="fa fa-btn fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th width="50px;">#</th>
                                <th width="100px;">Barcode</th>
                                <th width="100px;">Date</th>
                                <th width="50px;">Doc.Type</th>
                                <th width="120px;">Source</th>
                                <th>Subject Matter</th>
                                <th width="30px;">View</th>
                            </tr>
                        </thead>
                        @foreach($qry as $qry)
                            <tr>
                                <td>{{$qry->aid}}</td>
                                <td>{{$qry->barcode}}</td>
                                <td>{{$qry->rdate}}</td>
                                <td>{{$qry->document_code}}</td>
                                <td>{{ucwords($qry->source_name)}}</td>
                                <td>{{$qry->subject_matter}}</td>
                                <td>
                                    <center>
                                        <a href="{{URL::to('view_tracking/'.$qry->aid)}}" target="_blank">
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fa fa-btn fa-search"></i>
                                            </button>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                        @endforeach                                   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
    $(function() {
        $('#table').DataTable({
            "order": [[ 0, "desc" ]],

            // dom: 'Bfrtip',
            // buttons: [
            //     'print'
            //     //'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
    });

    // function search(){
    //     sb = document.getElementById("searchBy").value;
    // }
</script>
@endsection
