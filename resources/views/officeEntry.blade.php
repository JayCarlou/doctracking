@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Office Entry</div>
                <div class="card-body">
                	<form method="post" action="{{ url('office_entry_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Office Code *</label>
								<input type="text" class="form-control" id="officeCode" name="officeCode" maxlength="10" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		
							<div class="form-group col-md-12">
								<label for="officeName">Office Name *</label>
								<input type="text" class="form-control" id="officeName" name="officeName" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Office
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
                	

                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th width="100px;">Office Code</th>
                                <th>Office Name</th>
                                <th width="50px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($offices as $offices)
                        	<tr>
                        		<td>{{$offices->office_code}}</td>
                        		<td>{{$offices->office_name}}</td>
                        		<td>
                        			<a href="{{URL::to('office_edit/'.$offices->id)}}">
	                                    <button type="button" class="btn btn-primary btn-sm">
	                                    	<i class="fa fa-btn fa-pencil"></i>
	                                    </button>
	                                </a>
	                                <a href="{{URL::to('office_delete/'.$offices->id)}}">
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

<script>
	$(function() {
	    $('#table').DataTable({
	    });
	});
</script>


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
