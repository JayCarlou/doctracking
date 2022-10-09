@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R")
<!-- <div class="container align-item-center"> -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">New Document Entry</div>
                <div class="card-body">
                	<form method="post" action="{{ url('new_document_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="inputEmail4">Barcode *</label>
								<input type="text" class="form-control" id="barcode" name="barcode" maxlength="10" placeholder="Max 10" required="required">
							</div>
							<div class="form-group col-md-4">
								<label for="inputPassword4">Transaction Type *</label>
								<select class="form-control" id="transactionType" name="transactionType" required="required">
									<option value="">Select One</option>
									@foreach($transactionType as $ttVal)
										<option value="{{$ttVal->id}}">{{$ttVal->transaction_type}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="inputPassword4">Document Type *</label>
								<select class="form-control" id="documentType" name="documentType" required="required">
									<option value="">Select One</option>
									@foreach($documentType as $dtVal)
										<option value="{{$dtVal->id}}">{{$dtVal->document_code}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="inputEmail4">Source Type *</label>
								<select class="form-control" id="sourceType" name="sourceType" required="required" onchange="sourceLocation()">
									<option value="">Select One</option>
									@foreach($sourceType as $sVal)
										<option value="{{$sVal->id}}">{{$sVal->source}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-8">
								<label for="inputPassword4">Source Location *</label>
								<select class="form-control" id="sourceLocationId" name="sourceLocationId" required="required" disabled="disabled">
									<option value="">Select One</option>
									@foreach($offices as $offices)
										<option value="{{$offices->id}}">{{$offices->office_code}} - {{$offices->office_name}}</option>
									@endforeach
								</select>
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="inputEmail4">Delivery Method *</label>
								<select class="form-control" id="deliveryMethod" name="deliveryMethod" id="sourceType" required="required">
									<option value="">Select One</option>
									@foreach($deliveryMethod as $dmVal)
										<option value="{{$dmVal->id}}">{{$dmVal->method}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-8">
								<label for="inputPassword4">Source Name *</label>
								<input type="text" class="form-control" id="sourceName" name="sourceName" placeholder="Name/Company/Agency/Office" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="inputEmail4">Sex</label>
								<select class="form-control" id="sex" name="sex">
									<option value="">Select One</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="inputPassword4">Contact No.</label>
								<input type="text" class="form-control" id="contactNo" name="contactNo" placeholder="Cellphone/Telephone">
							</div>
							<div class="form-group col-md-4">
								<label for="inputPassword4">Email Address</label>
								<input type="email" class="form-control" id="emailAddress" name="emailAddress" placeholder="juan@domain.com">
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="inputEmail4">Subject Matter *</label>
								<textarea class="form-control" id="subjectMatter" name="subjectMatter" rows="3" placeholder="Subject Matter" required="required"></textarea>
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="inputEmail4">Linked Documents</label>
								<input type="text" class="form-control" name="linkedDocuments" id="linkedDocuments" placeholder="Enter Barcode/s (Note: separate by spaces)">
			
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-5">
	                			@php
	                				$rand = strtoupper(substr(md5(microtime()),rand(0,26),6));
	                			@endphp
								<label for="inputEmail4">Access Code: <b>{{$rand}}</b></label>
								<input type="hidden" name="accessCode" id="accessCode" value="{{$rand}}">
							</div>
							<!-- <div class="form-group col-md-7">
								<label for="inputEmail4">To Office of the City Mayor?</label> &nbsp;
								<input type="checkbox" name="toOcm" id="toOcm" value="1">
							</div> -->
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Document
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Document List</div>
                <div class="card-body">
                	<form method="post" action="{{ url('document_search') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-2 col-form-label text-md-left"">
								<label>Search By:</label>
							</div>
							<div class="form-group col-md-3">
								<select class="form-control" id="searchBy" name="searchBy" required="required">
									<option value="">Select One</option>
									<option value="barcode">Barcode</option>
									<option value="subject_matter">Subject Matter</option>
									<option value="source_name">Source Name</option>
									
								</select>
							</div>
							<div class="form-group col-md-4">
								<input type="text" name="keyword" id="keyword" class="form-control" placeholder="Enter Keyword">
							</div>
							<div class="form-group col-md-3">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-search"></i> Search
                                </button>
							</div>
						</div>
					</form>
					@php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
                    <table id="example" class="table table-striped table-bordered" style="font-size: 10px;">
                        <thead style="font-size: 12px;">
                            <tr>
                            	<th>#</th>                                                      
                                <th>Barcode</th>
                                <th>Subject Matter</th>
                                <th>Name</th>
                                <th style="width: 25%;">Action</th>
                            </tr>
                        </thead>
                        @foreach($documents as $docVal)
                        	<tr>
                        		<td>{{$docVal->id}}</td>
                        		<td><a href="/view_tracking/{{$docVal->id}}" target="_blank">{{$docVal->barcode}}</a></td>
                        		<td>{{$docVal->subject_matter}}</td>
                        		<td>{{$docVal->source_name}}</td>
                        		<td style="text-align:center;">
                        			@if($docVal->document_status=="G")
                        			<center>
	                        			<a href="{{URL::to('document_attachment/'.$docVal->id)}}">
		                                    <button type="button" class="btn btn-secondary btn-sm">
		                                    	<i class="fa fa-btn fa-paperclip"></i>
		                                    </button>
		                                </a>
		                                @php
		                                	$qry = mysqli_query($connection,"select max(distinct(sequence))sq from document_transaction where document_id='$docVal->id'");

		                                	$resultSq = mysqli_fetch_assoc($qry);
		                                @endphp
		                                @if($resultSq['sq']<=1)
		                                <a href="{{URL::to('document_route/'.$docVal->id)}}">
		                                    <button type="button" class="btn btn-success btn-sm">
		                                    	<i class="fa fa-btn fa-forward"></i>
		                                    </button>
		                                </a>
		                               	@endif
		                                <a href="{{URL::to('document_edit/'.$docVal->id)}}">
		                                    <button type="button" class="btn btn-primary btn-sm">
		                                    	<i class="fa fa-btn fa-pencil"></i>
		                                    </button>
		                                </a>
		                                <a href="{{URL::to('document_delete/'.$docVal->id)}}">
		                                    <button type="button" class="btn btn-danger btn-sm">
		                                   
		                                    	<i class="fa fa-btn fa-times"></i>
		                                    </button>
		                                </a>
		                            </center>
		                            @else
										<a href="{{URL::to('document_attachment/'.$docVal->id)}}">
		                                    <button type="button" class="btn btn-secondary btn-sm">
		                                    	<i class="fa fa-btn fa-paperclip"></i>
		                                    </button>
		                                </a>
										<br>
		                            	Transaction Ended
		                            @endif
                        		</td>
                        	</tr>
                        @endforeach
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
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
	    	"order": [[ 0, "desc" ]]
	    });
	});
</script>

@stack('scripts')


@endsection
