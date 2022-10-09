@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Reset Password</div>
                <div class="card-body">
                	<form method="post" action="{{ url('reset_password') }}" autocomplete="off">
                		{{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeCode">Username *</label>
                                <input type="hidden" name="userId" value="{{$id}}">
                                <input type="text" class="form-control" id="username" name="username" required="required" value="{{$email}}" readonly="readonly">
                            </div>
                        </div>

	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">New Password *</label>
								<input type="password" class="form-control" id="newPassword" name="newPassword" value="" required="required">
							</div>
						</div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeCode">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" value="" required="required">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeCode">Administrator Password to Confirm *</label>
                                <input type="password" class="form-control" id="adminPassword" name="adminPassword" value="" required="required">
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
    function activeOffice(){
        var accessLevel = document.getElementById('accessLevel').value;

        if(accessLevel=="U"){
            document.getElementById("office").disabled = false;  
        }
        else{
            document.getElementById("office").disabled = true;    
            document.getElementById("office").value = "";    
        }
    }


	$(function() {
	    $('#table').DataTable({
	    });
	});
</script>

@stack('scripts')


@endsection
