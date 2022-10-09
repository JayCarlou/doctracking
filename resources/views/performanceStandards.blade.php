@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Performance Standards (Unit: Minutes)</div>
                <div class="card-body">
                    @php
                        include('../storage/app/public/php/databaseConnection.php');
                    @endphp
                	<form method="post" action="{{ url('performance_standards_update') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<table id="table" class="table table-striped table-bordered">
                            <thead style="font-size: 12px;">
                                <tr>                                                      
                                    <th style="text-align: center;">DEPARTMENT / OFFICES</th>
                                    @foreach($docTypes as $docTypes)
                                        <th style="text-align: center; width: 120px;">{{$docTypes->document_code}}</th>
                                    @endforeach
                                </tr>
                                @foreach($offices as $offices)
                                    <tr>
                                        <td><b>{{$offices->office_code}}</b> - {{$offices->office_name}}</td>
                                        @php
                                            $qry = mysqli_query($connection,"select * from office_performance where office_id='$offices->id' order by document_code_id asc");

                                            for($a=1;$a<=mysqli_num_rows($qry);$a++){
                                                $data = mysqli_fetch_assoc($qry);
                                                @endphp
                                                    <td>
                                                        <input type="number" class="form-control" name="performance{{$data['id']}}" id="performance{{$data['id']}}" value="{{$data['office_time']}}" style="text-align: right;">
                                                    </td>
                                                @php
                                            }
                                        @endphp
                                    </tr>
                                @endforeach
                            </thead>

                        </table>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save Changes
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
	    $('#tables').DataTable({
	    });
	});
</script>

@stack('scripts')


@endsection
