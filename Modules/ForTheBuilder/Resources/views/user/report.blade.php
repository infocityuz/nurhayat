@section('title')
    {{ __('locale.apartment_sale') }}
@endsection
@extends('forthebuilder::layouts.forthebuilder')

@section('content')
@include('forthebuilder::layouts.content.navigation')
@include('forthebuilder::layouts.content.header')
    
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid py-3 px-2">
            <div class="card">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div class="row align-items-center w-100">
                        <div class="col-md-12 d-flex align-items-center">                            
                            <h4 class="me-2">
                                {{ translate('Reports') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div>
                        <a href="{{ route('forthebuilder.user.report-clients') }}" class="btn btn-outline-info w-100 mb-2 text-start">{{ translate('Report on clients') }}</a>
                        <a href="{{ route('forthebuilder.user.report-deals') }}" class="btn btn-outline-info w-100 mb-2 text-start">{{ translate('Deal report') }}</a>
                        <a href="{{ route('forthebuilder.user.report-houses') }}" class="btn btn-outline-info w-100 mb-2 text-start">{{ translate('Report on the object') }}</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
















@endsection