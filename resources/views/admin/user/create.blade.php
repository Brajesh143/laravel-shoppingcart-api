@extends('layouts.admin-app')
   
@section('content')
<div class="container">
    <div class="row">
        <div id="flRegistrationForm" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">                                
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Registration Forms</h4>
                        </div>                                                                        
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="{{ route('user.store') }}">
                        @csrf
                        
                        @include('admin.user.form')
                        
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection