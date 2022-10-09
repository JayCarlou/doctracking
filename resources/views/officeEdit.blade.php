@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Office Entry</div>
                <div class="card-body">
                	<form method="post" action="{{ url('office_edit_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Office Code *</label>
                                <input type="hidden" name="officeId" id="officeId" value="{{$id}}">
                                <input type="hidden" name="oldOfficeCode" id="oldOfficeCode" value="{{$oc}}">
								<input type="text" class="form-control" value="{{$oc}}" id="officeCode" maxlength="10" name="officeCode" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		
							<div class="form-group col-md-12">
								<label for="officeName">Office Name *</label>
								<input type="text" class="form-control" value="{{$on}}" id="officeName" name="officeName" required="required">
							</div>
							
						</div>
						<div class="form-row">
                            @if($oc=="REC")
                            <div class="form-group col-md-12">
                                <button type="button" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-times"></i> Updated for REC Disabled
                                </button>
                            </div>
                            @else
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Changes
                                </button>
							</div>
							@endif
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
	                                    	<i class="fas fa-redo"></i>
	                                    </button>
	                                </a>
	                                <a href="{{URL::to('office_delete/'.$offices->id)}}">
	                                    <button type="button" class="btn btn-danger btn-sm">
	                                    	<i class="fas fa-times"></i>
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

@stack('scripts')


@endsection
