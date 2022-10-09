@extends('layouts.app')

@section('content')
<div class="container align-item-center">
	<!-- container-fluid -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                	<form method="post" action="{{ url('change_password_save') }}" autocomplete="off">
                		{{ csrf_field() }}
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Current Password *</label>
								<input type="password" class="form-control" id="currentPassword" name="currentPassword" required="required">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="officeName">New Password *</label>
								<input type="password" class="form-control" id="newPassword" name="newPassword" required="required">
							</div>
						</div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeName">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required="required">
                            </div>
                        </div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-check"></i> Save Changes
                                </button>
							</div>
						</div>
	               </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(function() {
	    $('#table').DataTable({
	    });
	});
</script>

@stack('scripts')


@endsection
