@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\Constants; 
@endphp
@section('title')
    {{ translate('Reports') }}
@endsection
<link rel="stylesheet" href="{{ asset('/backend-assets/forthebuilders/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('/backend-assets/forthebuilders/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/backend-assets/forthebuilders/toastr/css/toastr.min.css') }}">

@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <h2 class="panelUprText">{{ translate('Reports') }}</h2>
                    {{-- <button class="plus2">+</button> --}}
                </div>
            </div>
            <div class="nastroykiData">
                

                <a href="{{ route('forthebuilder.user.report-clients') }}" class=" nastroykiCont">{{ translate('Report on clients') }}</a>
                <a href="{{ route('forthebuilder.user.report-deals') }}" class="nastroykiCont">{{ translate('Deal report') }}</a>
                <a href="{{ route('forthebuilder.user.report-houses') }}" class="nastroykiCont">{{ translate('Report on the object') }}</a>
            </div>
        </div>
    </div>
    
    <script>
        let page_name = 'report';
    </script>
@endsection











