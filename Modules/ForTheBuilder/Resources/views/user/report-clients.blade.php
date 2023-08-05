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
            <div class="container d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <a onclick="history.back()" href="#" class="plus2 profileMaxNazadInformatsiyaKlient"><img
                    src="{{ asset('/backend-assets/forthebuilders/images/icons/arrow-left.png') }}"
                    alt=""></a>
                    <h2 class="panelUprText" style="margin: 0; margin-left: 10px;">
                        @if ($status == 'report-clients')
                            {{ translate('Report on clients') }}
                        @elseif($status == 'report-deals')
                            {{ translate('Report on deals') }}
                        @else
                            {{ translate('Report on houses') }}
                        @endif
                    </h2>
                </div>
                <div class="miniSearchDiv5">
                    <ion-icon class="miniSearchIconInput md hydrated" name="search-outline" role="img"
                        aria-label="search outline"></ion-icon>
                    <input placeholder="{{ translate('Search by objects') }}" class="miniInputSdelka5 searchTable"
                        type="text">
                </div>
            </div>
            <div class="jkData">
                <div class="jkMiniData2" >
                    <div class="checkboxDivInput jkNumberInputChick">
                        <input class="checkBoxInput" type="checkbox">
                    </div>
                    <div class="checkboxDivInput jkNumberInputChick">
                        №
                    </div>
                    <div class="checkboxDivTextInput">
                        {{ translate('house_name') }}
                    </div>
                    <div class="checkboxDivTextInput2">
                        {{ translate('corpas') }}
                    </div>
                    <div class="checkboxDivTextInput">
                        {{ translate('info') }}
                    </div>
                    <div class="checkboxDivTextInput">
                        {{ translate('Number house') }}
                    </div>
                    
                </div>

                @if (!empty($models))
                    @foreach ($models as $key => $model)
                        <div class="jkMiniData mt-1 hideData" >
                            <input type="hidden" class="hiddenData"
                                value="{{ $model->name }} {{ $model->corpus }} {{ $model->description }}">
                            @php
                                if ($status == 'report-clients') {
                                    $house_url = route('forthebuilder.user.report-clients-index', [$model->id]);
                                }
                                elseif($status == 'report-deals'){
                                    $house_url = route('forthebuilder.user.report-deals-index', [$model->id]);
                                }
                                else{
                                    $house_url = route('forthebuilder.user.report-houses-index', [$model->id]);   
                                }
                            @endphp
                            <div class="jkMiniData" >
                                <a href="{{ $house_url }}" class="checkboxDivInput jkNumberInputChick">
                                    <input class="checkBoxInput" type="checkbox">
                                </a>
                                <a href="{{ $house_url }}" class="checkboxDivInput jkNumberInputChick">
                                    {{ $models->firstItem() + $key }}
                                </a>
                                <a href="{{ $house_url }}" class="checkboxDivTextInput">
                                    {{ $model->name }}
                                </a>
                                <a href="{{ $house_url }}" class="checkboxDivTextInput2">
                                    @if (!empty($model->corpus))
                                        {{ $model->corpus }}
                                    @else
                                        -
                                    @endif
                                </a>
                                <a href="{{ $house_url }}" class="checkboxDivTextInput48">
                                    {{ $model->description }}
                                </a>
                                <a href="{{ $house_url }}" class="checkboxDivTextInput48">
                                    {{ $model->house_number }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="aiz-pagination mt-4">
                    {{ $models->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let page_name = 'report';
    </script>
@endsection
