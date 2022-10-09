@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Delivery Method Edit</div>
                <div class="card-body">
                	<form method="post" action="{{ url('delivery_method_entry_edit_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="deliveryMethod">Delivery Method *</label>
								<input type="text" class="form-control" id="deliveryMethod" name="deliveryMethod" value="{{$method}}" required="required">
                                <input type="hidden" name="deliveryMethodId" id="deliveryMethodId" value="{{$id}}">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Changes
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Delivery Method List</div>
                <div class="card-body">
                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th>Delivery Method</th>
                                <th width="50px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($deliveryMethod as $deliveryMethod)
                        	<tr>
                        		<td>{{$deliveryMethod->method}}</td>
                        		<td>
                        			<a href="{{URL::to('delivery_method_entry_edit/'.$deliveryMethod->id)}}">
	                                    <button type="button" class="btn btn-primary btn-sm">
	                                    	<i class="fa fa-btn fa-pencil"></i>
	                                    </button>
	                                </a>
	                                <a href="{{URL::to('delivery_method_delete/'.$deliveryMethod->id)}}">
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
