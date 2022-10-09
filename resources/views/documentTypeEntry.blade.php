@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Document Type Entry</div>
                <div class="card-body">
                	<form method="post" action="{{ url('document_type_entry_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="documentCode">Document Code *</label>
								<input type="text" class="form-control" id="documentCode" name="documentCode" required="required" maxlength="5">
							</div>
							
						</div>
						<div class="form-row">
	                		
							<div class="form-group col-md-12">
								<label for="documentType">Document Type *</label>
								<input type="text" class="form-control" id="documentType" name="documentType" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Document Type
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Document Type List</div>
                <div class="card-body">
                	

                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th width="100px;">Document Code</th>
                                <th>Document Type</th>
                                <th width="50px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($documentType as $documentType)
                        	<tr>
                        		<td>{{$documentType->document_code}}</td>
                        		<td>{{$documentType->document_type}}</td>
                        		<td>
                        			<a href="{{URL::to('document_type_edit/'.$documentType->id)}}">
	                                    <button type="button" class="btn btn-primary btn-sm">
	                                    	<i class="fa fa-btn fa-pencil"></i>
	                                    </button>
	                                </a>
	                                <a href="{{URL::to('document_type_delete/'.$documentType->id)}}">
	                                    <button type="button" class="btn btn-danger btn-sm">
	                                    	<i class="fa fa-btn fa-times"></i>
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
	$(function() {
	    $('#table').DataTable({
	    });
	});
</script>

@stack('scripts')


@endsection
