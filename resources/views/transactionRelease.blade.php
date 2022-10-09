@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R"||Auth::user()->access=="U")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transaction Release</div>
                <div class="card-body">
                	<form method="post" action="{{ url('transaction_release') }}" autocomplete="off">
                		{{ csrf_field() }}
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="barcode">Barcode</label>
								<input type="text" class="form-control" value="{{$barcode}}" id="barcode" name="barcode" readonly="readonly">
								<input type="hidden" name="userOfficeCode" id="userOfficeCode" value="{{$officeCode}}">
								<input type="hidden" name="documentId" id="documentId" value="{{$documentId}}">
								<input type="hidden" name="documentTransactionId" id="documentTransactionId" value="{{$documentTransactionId}}">
							</div>
							<div class="form-group col-md-4">
								<label for="transactionType">Transaction Type</label>
								<input type="text" class="form-control" value="{{$transactionType}}" id="transactionType" name="transactionType" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="documentType">Document Type</label>
								<input type="text" class="form-control" value="{{$documentType}}" id="documentType" name="documentType" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="sourceType">Source Type</label>
								<input type="text" class="form-control" value="{{$source}}" id="sourceType" name="sourceType" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="sourceName">Source Name</label>
								<input type="text" class="form-control" value="{{$sourceName}}" id="sourceName" name="sourceName" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="inputEmail4">Office</label>
								<input type="text" class="form-control" value="{{$officeName}}" id="office" name="office" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Subject Matter</label>
								<textarea class="form-control" readonly="readonly" name="subjectMatter" id="subjectMatter">{{$subjectMatter}}</textarea>
							</div>
						</div>
						<div style="width: 100%; text-align: right;">

							<a href="{{URL::to('transaction_end/'.$documentTransactionId)}}">
								<button type="button" class="btn btn-danger" onclick="click()">
	                                <i class="fa fa-flag-checkered"></i>
	                            </button>
							</a>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Action *</label>
								<input type="text" class="form-control" name="action" id="action" required="required">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Route to Office *</label>
								<select class="form-control" name="route" id="route" required="required">
									<option value="">- Select Office -</option>
									@foreach($offices as $offices)
										<option value="{{$offices->office_code}}">{{$offices->office_code}} - {{$offices->office_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Remarks </label>
								<input type="text" class="form-control" name="remarks" id="remarks">
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-file"></i> Release Document
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

@stack('scripts')


@endsection
