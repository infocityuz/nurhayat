@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
    use Modules\ForTheBuilder\Entities\Constants; 

@endphp
@section('title')
    {{ translate('JK') }}
@endsection
@section('content')
@include('forthebuilder::layouts.content.navigation')
@include('forthebuilder::layouts.content.header')
<style>
    .plus2{
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
        background: #F2F2F2;
        color: #555;
        width: 50px;
        height: 50px;
    }
</style>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid py-3 px-2">

            <div class="card">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div class="row w-100 m-0">
                        <div class="col-md-6 d-flex align-items-center">
                            <a href="{{route('forthebuilder.user.report')}}" class="plus2 profileMaxNazadInformatsiyaKlient">
                                <i class="mdi mdi-keyboard-backspace mdi-20"></i>
                            </a>
                            <h4 class="ms-2">
                                @if ($status == 'report-clients')
                                    {{ translate('Report on clients') }}
                                @elseif($status == 'report-deals')
                                    {{ translate('Report on deals') }}
                                @else
                                    {{ translate('Report on houses') }}
                                @endif
                            </h4>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-end">
                            <div class="miniSearchDiv5 ms-2">
                                <ion-icon class="miniSearchIconInput md hydrated" name="search-outline" role="img"
                                    aria-label="search outline"></ion-icon>
                                <input placeholder="{{ translate('Search by objects') }}" class="miniInputSdelka5 searchTable form-control"
                                    type="text">
                            </div>
                        </div>
                    </div>    
                    
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <table id="tech-companies-1" class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>{{ translate('house_name') }}</th>
                                <th>{{ translate('corpas') }}</th>
                                <th>{{ translate('info') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($models))
                                @foreach ($models as $key => $model)
                                    <tr style="cursor: pointer;" data-href="{{ route('forthebuilder.user.report-clients-index', $model->id) }}" class="jkMiniData mt-1 hideData">
                                        <td>
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
                                            <a href="{{ $house_url }}" class="checkboxDivInput jkNumberInputChick text-primary">
                                                {{ $models->firstItem() + $key }}
                                            </a>
                                        </td>
                                        <td>
                                             <a href="{{ $house_url }}" class="checkboxDivTextInput text-primary">
                                                {{ $model->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $house_url }}" class="checkboxDivTextInput2 text-primary">
                                                @if (!empty($model->corpus))
                                                    {{ $model->corpus }}
                                                @else
                                                    -
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $house_url }}" class="checkboxDivTextInput48 text-primary">
                                                {{ $model->description }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <br>
                    {{ $models->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    
    <script>
        let page_name = 'report-clients';
        $(document).on('click','tbody tr',function(){
            location.href = $(this).attr('data-href')
        })
    </script>
@endsection
