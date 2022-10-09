@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A" || Auth::user()->access=="R")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Reactivate Ended Document Transaction</div>
                <div class="card-body">
                	<form method="post" action="{{ url('reactivate_transaction') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Barcode *</label>
								<input type="text" class="form-control" id="barcode" name="barcode" required="required">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="officeName">Office Ended *</label>
								<select class="form-control" required="required" id="officeCode" name="officeCode">
                                    <option value="">Select One</option>
                                    @foreach($offices as $offices)
                                        <option value="{{$offices->office_code}}">{{$offices->office_code}} - {{$offices->office_name}}</option>
                                    @endforeach
                                </select>
							</div>
						</div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeName">Confirm Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required="required">
                            </div>
                        </div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-check"></i> Confirm
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
