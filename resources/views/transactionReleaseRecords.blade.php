@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R"||Auth::user()->access=="U")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transaction Release (Records Module)</div>
                <div class="card-body">
                	<form method="post" action="{{ url('transaction_release_records') }}" autocomplete="off">
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

								<label for="inputEmail4">Route to Office/s *</label>
								@php
		                            $b=0
		                        @endphp
								<div style="height: 200px; overflow-y: scroll; padding-left: 25px;">
									<table style="font-size: 13px; width: 100%">
										<tr>
											<td width="5%"><input type="checkbox" name="routeToAll" id="routeToAll" value="1" class="form-control" onclick="selectAll()"></td>
											<td style="padding-left: 10px;" colspan="2"><b>Route of All Offices</b></td>
										</tr>
										@foreach($offices as $indexKey => $offices)
											@php
					                            $classRow = "row".($b++ & 1);
					                        @endphp
											<tr class="{{$classRow}}">
												<td width="5%"><input type="checkbox" name="checkOffice{{$offices->id}}" id="checkOffice{{$indexKey}}" class="form-control" value="{{$offices->office_code}}"></td>
												<td width="20%" style="padding-left: 10px;"><b>{{$offices->office_code}}</b></td>

												<td width="">{{$offices->office_name}}</td>
											</tr>
										@endforeach
									</table>
								</div>
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
								<button type="submit" class="btn btn-primary btn-block" onclick="click()" id="checkBtn">
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

<script>
	function selectAll(){
		
		all = document.getElementById("routeToAll");

		if(all.checked){
			for(var i=0; i<=100; i++){
	            document.getElementById("checkOffice"+[i]).checked = true;
	        }
		}
		else{
			for(var b=0; b<=100; b++){
	            document.getElementById("checkOffice"+[b]).checked = false;
	        }
		}
    }
</script>

<script type="text/javascript">
	$(window).load(function(){
		//alert("hello");
	});

	$(document).ready(function () {
	    $('#checkBtn').click(function() {
	      checked = $("input[type=checkbox]:checked").length;

	      if(!checked) {
	        alert("You must check at least one checkbox.");
	        return false;
	      }

	    });
	});
</script>

<style style="text/css">
    /*.table tr:hover {
          background-color: #ffff99;
    }*/
    .row0{
        background-color: #FAFAFA;
    }
    .row0:hover{
        background-color: #FBFCEA;
    }
    .row1{
        background-color: #fff;
    }
    .row1:hover{
        background-color: #FBFCEA;
    }
</style>

@stack('scripts')


@endsection
