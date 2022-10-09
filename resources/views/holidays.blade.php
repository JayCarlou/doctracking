@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Holidays</div>
                <div class="card-body">
                	<form method="post" action="{{ url('holiday_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Holiday Name *</label>
								<input type="text" class="form-control" id="holidayName" name="holidayName"  required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		
							<div class="form-group col-md-12">
								<label for="officeName">Date *</label>
								<input class="date form-control" type="text" id="holidayDate" name="holidayDate" required="required" value="{{date('Y-m-d')}}" required="required">
							</div>
							
						</div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Holiday
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Holidays</div>
                <div class="card-body">
                	

                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th width="120px;">Date</th>
                                <th>Name</th>
                                <th width="50px;">Action</th>
                            </tr>
                        </thead>
                        @foreach($holidayList as $val)
                            <tr>
                                <td>{{$val->holidate}}</td>
                                <td>{{strtoupper($val->holiday)}}</td>
                                <td style="text-align: center;">
                                    <a href="{{URL::to('holiday_delete/'.$val->id)}}">
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

<script type="text/javascript">
        $('.date').datepicker({  
            format: 'yyyy-mm-dd'
        });  

        // $("#selectAll").click(function(){
        //     $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
        // });
    </script>
@stack('scripts')


@endsection
