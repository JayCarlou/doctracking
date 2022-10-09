@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R"||Auth::user()->access=="U")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Office Trasaction Summary</div>
                <div class="card-body">
                	<form method="post" action="{{ url('office_transaction_summary') }}" autocomplete="off" target="_blank">
                		{{ csrf_field() }}
                		<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="office">Office:</label>
								<select class="form-control" id="office" name="office" required="required">
									<option value="">Select</option>
									@foreach($offices as $val)
										<option value="{{$val->id}}"><b>{{$val->office_code}}</b> - {{$val->office_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-6">
								<label for="dateFrom">Date Range: From</label>
								<input class="date form-control" type="text" id="dateFrom" name="dateFrom" required="required" value="{{date('Y-m-d')}}" required="required">
							</div>
							<div class="form-group col-md-6">
								<label for="dateTo">To</label>
								<input class="date form-control" type="text" id="dateTo" name="dateTo" required="required" value="{{date('Y-m-d')}}" required="required">
							</div>
							
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-file"></i> Generate Report
                                </button>
							</div>
						</div>
						
	            	</form>
	            </div>
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
    </script>

@endsection
