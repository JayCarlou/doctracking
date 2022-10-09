@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A")
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Edit User</div>
                <div class="card-body">
                	<form method="post" action="{{ url('edit_user_save') }}" autocomplete="off">
                		{{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeCode">Username *</label>
                                <input type="hidden" name="userId" value="{{$id}}">
                                <input type="text" class="form-control" id="username" name="username" required="required" value="{{$username}}" readonly="readonly">
                            </div>
                        </div>
	                	<div class="form-row">
	                		<div class="form-group col-md-12">
								<label for="officeCode">Full Name *</label>
								<input type="text" class="form-control" id="name" name="name" value="{{$name}}" required="required">
							</div>
						</div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeName">Access Level *</label>
                                <select class="form-control" name="accessLevel" onchange="activeOffice()" id="accessLevel" required="required">
                                    @if($access=="U")
                                        <option value="U">Office User</option>
                                        <option value="R">Records Manager</option>
                                        <option value="A">System Administrator</option>
                                    @elseif($access=="R")
                                        <option value="R">Records Manager</option>
                                        <option value="U">Office User</option>
                                        <option value="A">System Administrator</option>
                                    @else
                                        <option value="A">System Administrator</option>
                                        <option value="U">Office User</option>
                                        <option value="R">Records Manager</option>
                                    @endif
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="officeName">Office *</label>
                                <select class="form-control" name="office" id="office" required="required">
                                    <option value="{{$officeId}}">{{$officeCode}} - {{$officeName}}</option>
                                    @foreach($offices as $offices)
                                        <option value="{{$offices->id}}">{{$offices->office_code}} - {{$offices->office_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
						<div class="form-row">
	                		<div class="form-group col-md-12">
								<button type="submit" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Save User
                                </button>
							</div>
							
						</div>
	                </div>
	            </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User List</div>
                <div class="card-body">
                	

                    <table id="table" class="table table-striped table-bordered">
                        <thead style="font-size: 12px;">
                            <tr>                                                      
                                <th width="20%">Username</th>
                                <th width="20%">Full Name</th>
                                <th width="20%">Office Code</th>
                                <th width="20%">Access Level</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        @foreach($list as $list)
                            <tr>
                                <td>{{$list->email}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->office_code}}</td>
                                @php
                                    if($list->access=="U"){
                                        $user = "Office User";
                                    }
                                    else if($list->access=="R"){
                                        $user = "Records Manager";
                                    }
                                    else{
                                        $user = "Administrator";
                                    }
                                @endphp
                                <td>{{$user}}</td>
                                <td>
                                    <center>
                                        <a href="{{URL::to('edit_user/'.$list->aid)}}">
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="fa fa-btn fa-pencil"></i>
                                            </button>
                                        </a>
                                        <a href="{{URL::to('reset_password/'.$list->aid.'/'.$list->email)}}">
                                            <button type="button" class="btn btn-success btn-sm" title="reset password">
                                                <i class="fa fa-btn fa-undo"></i>
                                            </button>
                                        </a>
                                        <a href="{{URL::to('user_delete/'.$list->aid)}}">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fa fa-btn fa-times"></i>
                                            </button>
                                        </a>
                                    </center>
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
    