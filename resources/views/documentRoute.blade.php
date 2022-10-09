@extends('layouts.app')

@section('content')
@if(Auth::user()->access=="A"||Auth::user()->access=="R")
@php
    include('../storage/app/public/php/databaseConnection.php');
@endphp
<div class="container align-item-center">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Document Detail</div>
                <div class="card-body">
                    <form>
                        <div class="form-inline col-md-8">
                            <label for="inputEmail4">Barcode : {{$barcode}}</label>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Office List</div>
                <div class="card-body">
                    <table style="font-size: 12px;">
                        <tr>
                           
                            <td colspan="2" style="width: 95%;"><b>Route to all Offices</b></td>
                            <td style="width: 5%">
                                <a href="{{URL::to('document_route_office_all/'.$id)}}">
                                    <button type="button" class="btn btn-primary btn-sm">
                                        <i class="fa fa-angle-right"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @php
                            $b=0
                        @endphp
                        @foreach($listOfOffices as $offices)
                            @php
                            $classRow = "row".($b++ & 1);
                            
                            $selectedOffice = mysqli_query($connection,"select * from document_transaction where document_id='$id' and route_office_code='$offices->office_code'");

                            $counter = mysqli_num_rows($selectedOffice);
                            if($counter==0){             
                                @endphp
                                <tr class="{{$classRow}}">
                                    <td><b>{{$offices->office_code}}</b></td>
                                    <td style="font-size: 9px;">{{$offices->office_name}}</td>
                                    <td>
                                        <a href="{{URL::to('document_route_office/'.$id.'/'.$offices->office_code)}}">
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="fa fa-angle-right"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @php
                            }
                            else{
                                //no display
                            }    
                            @endphp
                            
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Routed To</div>
                <div class="card-body">
                    <table style="font-size: 12px;">
                        <tr>
                            
                            <td colspan="2" style="width: 95%;"><b>Remove all Offices</b></td>
                            <td style="width: 5%">
                                <a href="{{URL::to('document_route_office_remove_all/'.$id)}}">
                                    <button type="button" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-angle-left"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @php
                            $b=0
                        @endphp
                        @foreach($listRoutedOffice as $offices)
                            @php
                                $classRow = "row".($b++ & 1);

                                $office = mysqli_query($connection,"select * from offices where office_code='$offices->route_office_code'");
                                $officeResult = mysqli_fetch_assoc($office);
                                
                            @endphp
                            <tr class="{{$classRow}}">
                                <td><b>{{$officeResult['office_code']}}</b></td>
                                <td style="font-size: 9px;">{{$officeResult['office_name']}}</td>
                                <td>
                                    <a href="{{URL::to('document_route_office_remove/'.$id.'/'.$officeResult['office_code'])}}">
                                        <button type="button" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-angle-left"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <a href="{{URL::to('/new_document')}}">
                                <button type="button" class="btn btn-primary btn-block" onclick="click()">
                                    <i class="fa fa-btn fa-save"></i> Done Adding Office/s
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style style="text/css">
    /*.table tr:hover {
          background-color: #ffff99;
    }*/
    .row0{
        background-color: #FAFAFA;
    }
    .row0:hover{
        background-color: #FBFCEA;
    }
    .row1{
        background-color: #fff;
    }
    .row1:hover{
        background-color: #FBFCEA;
    }
</style>

@stack('scripts')
@endsection
