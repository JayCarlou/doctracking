@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Transaction Type Entry</div>
                <div class="card-body">
                	<form method="post" action="{{ url('transaction_type_entry_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="documentCode">Transaction Type *</label>
								<input type="text" class="form-control" id="transactionType" name="transactionType" required="required">
							</div>	
						</div>
						<div class="form-row">
	                		
							<div class="form-group col-md-12">
								<label for="documentType">Days *</label>
								<input type="number" class="form-control" id="days" name="days" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Transaction Type
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transaction Type List</div>
                <div class="card-body">
                	

                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th>Transaction Type</th>
                                <th>No. of Days</th>
                                <th width="50px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($transactionType as $transactionType)
                        	<tr>
                        		<td>{{$transactionType->transaction_type}}</td>
                        		<td>{{$transactionType->days}}</td>
                        		<td>
                        			<a href="{{URL::to('transaction_type_edit/'.$transactionType->id)}}">
	                                    <button type="button" class="btn btn-primary btn-sm">
	                                    	<i class="fa fa-btn fa-pencil"></i>
	                                    </button>
	                                </a>
	                                <a href="{{URL::to('transaction_type_delete/'.$transactionType->id)}}">
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
