@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R"||Auth::user()->access=="U")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Custom Report</div>
                <div class="card-body">
                	<form method="post" action="{{ url('report_custom') }}" autocomplete="off" target="_blank">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<input type="text" class="form-control" id="title" name="title" placeholder="Title" required="required">
							</div>
						</div>
						
						<div class="form-row">
	                		<div class="form-group col-md-6">
								<label for="barcode">Date Range: From</label>
								<input class="date form-control" type="text" id="dateFrom" name="dateFrom" required="required" value="{{date('Y-m-d')}}" required="required">
							</div>
							<div class="form-group col-md-6">
								<label for="barcode">To</label>
								<input class="date form-control" type="text" id="dateTo" name="dateTo" required="required" value="{{date('Y-m-d')}}" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="sourceType">Table Column:</label>
								<br>
								<input type="checkbox" name="selectAll" id="selectAll"> Select All Columns
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-3">
								<input type="checkbox" name="receiveDate" id="receiveDate" value="1"> Receive Date
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="barcode" id="barcode" value="1"> Barcode
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="documentType" id="documentType" value="1"> Document Type
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="sourceType" id="sourceType" value="1"> Source Type
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-3">
								<input type="checkbox" name="sourceName" id="sourceName" value="1"> Source Name
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="sex" id="sex" value="1"> Sex
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="contactNo" id="contactNo" value="1"> Contact No.
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="email" id="email" value="1"> Email
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-3">
								<input type="checkbox" name="deliveryMethod" id="deliveryMethod" value="1"> Delivery Method
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="transactionType" id="transactionType" value="1"> Transcation Type
							</div>
	                		<div class="form-group col-md-3">
								<input type="checkbox" name="subjectMatter" id="subjectMatter" value="1"> Subject Matter
							</div>
							<div class="form-group col-md-3">
								<input type="checkbox" name="totalTime" id="totalTime" value="1"> Total Time
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="conTransactionType">Conditions:</label>
								<select class="form-control" name="conTransactionType" id="conTransactionType">
									<option value="">Transaction Type</option>
									@foreach($transactionType as $val)
										<option value="{{$val->id}}">{{$val->transaction_type}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="conDocumentType">&nbsp;</label>
								<select class="form-control" id="conDocumentType" name="conDocumentType">
									<option value="">Document Type</option>
									@foreach($documentType as $val)
										<option value="{{$val->id}}">{{$val->document_type}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="conSourceType">&nbsp;</label>
								<select class="form-control" id="conSourceType" name="conSourceType">
									<option value="">Source Type</option>
									@foreach($sourceType as $val)
										<option value="{{$val->id}}">{{$val->source}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-row">
	                		<div class="form-group col-md-4">
								<select class="form-control" id="conDeliveryMethod" name="conDeliveryMethod">
									<option value="">Delivery Method</option>
									@foreach($deliveryMethod as $val)
										<option value="{{$val->id}}">{{$val->method}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<select class="form-control" id="conClientSex" name="conClientSex">
									<option value="">Client Sex</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
							<div class="form-group col-md-4">
								<select class="form-control" id="documentStatus" name="documentStatus">
									<option value="">Document Status</option>
									<option value="G">On-going</option>
									<option value="O">On-time</option>
									<option value="D">Delayed</option>
								</select>
							</div>
						</div>

						<div class="form-row">
	                		<div class="form-group col-md-12">
								<input class="form-control" type="text" name="subjectMat" id="subjectMat" placeholder="Subject Matter">
							</div>
							
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-file"></i> Generate Report
                                </button>
							</div>
						</div>
						
	                </div>
	            </form>
            </div>
        </div>
        
    </div>
</div>
@endif

<script>
	
</script>

@stack('scripts')
	<script type="text/javascript">
      	$('.date').datepicker({  
            format: 'yyyy-mm-dd'
        });  

       	$("#selectAll").click(function(){
			$("input[type=checkbox]").prop('checked', $(this).prop('checked'));
		});
    </script>

@endsection
