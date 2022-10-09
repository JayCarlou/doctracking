@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R"||Auth::user()->access=="U")

@php
 	$start = new DateTime('2020-02-13 01:00:00');
	$end = new DateTime('2020-02-14 12:00:00');
	$days = $start->diff($end, true)->days;

	$saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
	$sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

	$minutesWeekends = ($saturdays+$sundays)*1440;

@endphp
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transaction</div>
                <div class="card-body">
                	<form method="post" action="{{ url('transaction_result') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-9">
								<input type="text" class="form-control" id="barcodeSearch" name="barcodeSearch" placeholder="Enter Barcode" required="required">
							</div>
							<div class="form-group col-md-3">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-search"></i> Search
                                    
                                </button>
							</div>
						</div>
						
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="barcode">Barcode</label>
								<input type="text" class="form-control" id="barcode" name="barcode" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="transactionType">Transaction Type</label>
								<input type="text" class="form-control" id="transactionType" name="transactionType" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="documentType">Document Type</label>
								<input type="text" class="form-control" id="documentType" name="documentType" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-4">
								<label for="sourceType">Source Type</label>
								<input type="text" class="form-control" id="sourceType" name="sourceType" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="sourceName">Source Name</label>
								<input type="text" class="form-control" id="sourceName" name="sourceName" readonly="readonly">
							</div>
							<div class="form-group col-md-4">
								<label for="inputEmail4">Office</label>
								<input type="text" class="form-control" id="office" name="office" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Subject Matter</label>
								<textarea class="form-control" readonly="readonly" name="subjectMatter" id="subjectMatter"></textarea>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Route to Office</label>
								<input type="text" class="form-control" name="routeOffice" id="routeOffice" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="inputEmail4">Action</label>
								<input type="text" class="form-control" name="action" id="action" readonly="readonly">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="remarks">Remarks</label>
								<input type="text" class="form-control" name="remarks" id="remarks" readonly="readonly">
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
