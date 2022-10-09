@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Add Attachments</div>
                <div class="card-body">
                	<form method="post" action="{{ url('document_attachment_save') }}" enctype="multipart/form-data" autocomplete="off">
                		{{ csrf_field() }}
	                	
                		<div class="form-group col-md-12">
							<label for="inputEmail4">Barcode</label>
							<input type="text" class="form-control" id="barcode" name="barcode" readonly="readonly" value="{{$docBarcode}}">
                            <input type="hidden" name="parentId" id="parentId" value="{{$parentId}}">
						</div>
						<div class="form-group col-md-12">
							<label for="inputEmail4">Select PDF File</label>
							<input type="file" class="form-control" id="pdfFile" name="pdfFile" >
						</div>
							
                		<div class="form-group col-md-12">
							<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                <i class="fa fa-btn fa-upload"></i> Upload File
                            </button>
						</div>
						
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Uploaded Files</div>
                <div class="card-body">
                	
                    <table  class="table table-striped table-bordered" style="font-size: 10px;">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th>Document Name</th>
                                <th width="90px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($attached as $attachedVal)
                            <tr>
                                <td>{{$attachedVal->document_name}}</td>
                                <td>
                                    <a href="{{URL::to('document_download/'.$attachedVal->id)}}" target="_blank">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-file"></i>
                                        </button>
                                    </a>
                                    <a href="{{URL::to('document_attachment_delete/'.$attachedVal->id)}}">
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                                   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        $("form").submit(function() {
            $(this).submit(function() {
                return false;
            });
                return true;
        }); 
    }); 
</script>

<script>
	function sourceLocation(){
		var sourceType = document.getElementById('sourceType').value;
		if(sourceType=="1"){
			document.getElementById("sourceLocationId").disabled = false;  
		}
		else{
			document.getElementById("sourceLocationId").disabled = true;    
			document.getElementById("sourceLocationId").value = "";    
		}
	}



	$(function() {
	    $('#example').DataTable({
	    });
	});
</script>

@stack('scripts')


@endsection
