@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
    use Modules\ForTheBuilder\Entities\HouseFlat;
    use Modules\ForTheBuilder\Entities\Constants;
@endphp
@section('title')
    {{ translate('JK') }}
@endsection
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a onclick="history.back()" class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="{{ asset('backend-assets/forthebuilders/images/icons/arrow-left.png') }}" alt=""></a>
                    <h2 class="panelUprText">{{ translate('Add') }}</h2>
                </div>
            </div>

            <div class="sozdatSpisokData">
                {{-- <form id="modal-form" action="{{ route('forthebuilder.booking.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf --}}
                <form action="{{ route('forthebuilder.house-flat.update', $model->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="width: 100%;" class="d-flex">
                        <div class="lidiMarginRight1272">
                            <div class="sozdatImyaSpsok">
                                @if (is_int($model->room_count))
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Apartment number') }}</h3>
                                @elseif ($model->room_count == 'p')
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Parking number') }}</h3>
                                @else
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Commercial number') }}</h3>
                                @endif
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('number_of_flat') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $model->number_of_flat }}" name="number_of_flat">
                                <span class="error-data">
                                    @error('number_of_flat')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Entrance') }}</h3>
                                <select
                                    class="form-control sozdatImyaSpisokSelectOption1272 @error('entrance') error-data-input is-invalid @enderror"
                                    id="exampleFormControlSelect1" name="entrance">
                                    @empty(!$model->house)
                                        @for ($i = 1; $i <= $model->house->entrance_count; $i++)
                                            @php
                                                $selectedI = '';
                                                if ($i == $model->entrance) {
                                                    $selectedI = 'selected';
                                                }
                                            @endphp
                                            <option value="{{ $i }}" {{ $selectedI }}>{{ $i }}
                                            </option>
                                        @endfor
                                    @endempty
                                </select>
                                <span class="error-data">
                                    @error('entrance')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Floor') }}</h3>
                                <select
                                    class="form-control sozdatImyaSpisokSelectOption1272 @error('floor') error-data-input is-invalid @enderror"
                                    id="exampleFormControlSelect1" name="floor">
                                    @empty(!$model->house)
                                        @for ($j = 1; $j <= $model->house->floor_count; $j++)
                                            @php
                                                $selectedJ = '';
                                                if ($j == $model->floor) {
                                                    $selectedJ = 'selected';
                                                }
                                            @endphp
                                            <option value="{{ $j }}" {{ $selectedJ }}>{{ $j }}
                                            </option>
                                        @endfor
                                    @endempty
                                </select>
                                <span class="error-data">
                                    @error('floor')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            @php
                                $areas = json_decode($model->areas);
                            @endphp
                            <div class="sozdatImyaSpsok d-none">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Living area m2') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('area_housing') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $areas->housing ?? 0 }}" name="area_housing">
                                <span class="error-data">
                                    @error('area_housing')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            @if ($model->room_count != null)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3 d-flex justify-content-between">
                                        {{ translate('Hotel area m2') }}
                                        <span>
                                            <span class="btn btn-sm btn-danger minus_hotel">
                                                <i class="fa fa-minus"></i>
                                            </span>
                                            <span class="btn btn-sm btn-success plus_hotel">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </span>
                                    </h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_hotel') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->hotel ?? 0}}" name="area_hotel">
                                    <span class="error-data">
                                        @error('area_hotel')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @php 
                                $hotel_count = 2;
                                $hotel_total_count = 0;
                                if (isset($areas->hotel2) && $areas->hotel2 > 0)
                                    $hotel_count++;
                                if (isset($areas->hotel3) && $areas->hotel3 > 0)
                                    $hotel_count++;
                                if (isset($areas->hotel4) && $areas->hotel4 > 0)
                                    $hotel_count++;
                                if (isset($areas->hotel5) && $areas->hotel5 > 0)
                                    $hotel_count++;
                            @endphp

                            <div class="add_hotel_rooms" data-count="{{ $hotel_count }}">
                                @if (isset($areas->hotel2) && $areas->hotel2 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $hotel_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Hotel - 2 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->hotel2 ?? 0}}" name="area_hotel_2">
                                    </div>
                                @endif
                                @if (isset($areas->hotel3) && $areas->hotel3 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $hotel_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Hotel - 3 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->hotel3 ?? 0}}" name="area_hotel_3">
                                    </div>
                                @endif
                                @if (isset($areas->hotel4) && $areas->hotel4 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $hotel_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Hotel - 4 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->hotel4 ?? 0}}" name="area_hotel_4"> 
                                    </div>
                                @endif
                                @if (isset($areas->hotel5) && $areas->hotel5 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $hotel_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Hotel - 5 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->hotel5 ?? 0}}" name="area_hotel_5">
                                    </div>
                                @endif
                            </div>
                            <div id="hotel_total_count" data-count="{{ $hotel_total_count}}"></div>

                            @if ($model->room_count != null)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3 d-flex justify-content-between">
                                        {{ translate('Bedroom area m2') }}
                                        <span>
                                            <span class="btn btn-sm btn-danger minus_bedroom">
                                                <i class="fa fa-minus"></i>
                                            </span>
                                            <span class="btn btn-sm btn-success plus_bedroom">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </span>
                                    </h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_bedroom') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->bedroom ?? 0}}" name="area_bedroom">
                                    <span class="error-data">
                                        @error('area_bedroom')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @php 
                                $bedroom_count = 2;
                                $bedroom_total_count = 0;
                                if (isset($areas->bedroom2) && $areas->bedroom2 > 0)
                                    $bedroom_count++;
                                if (isset($areas->bedroom3) && $areas->bedroom3 > 0)
                                    $bedroom_count++;
                                if (isset($areas->bedroom4) && $areas->bedroom4 > 0)
                                    $bedroom_count++;
                                if (isset($areas->bedroom5) && $areas->bedroom5 > 0)
                                    $bedroom_count++;
                            @endphp

                            <div class="add_bedroom_rooms" data-count="{{ $bedroom_count }}">
                                @if (isset($areas->bedroom2) && $areas->bedroom2 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $bedroom_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Bedroom - 2 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->bedroom2 ?? 0}}" name="area_bedroom_2">
                                    </div>
                                @endif
                                @if (isset($areas->bedroom3) && $areas->bedroom3 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $bedroom_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Bedroom - 3 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->bedroom3 ?? 0}}" name="area_bedroom_3">
                                    </div>
                                @endif
                                @if (isset($areas->bedroom4) && $areas->bedroom4 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $bedroom_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Bedroom - 4 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->bedroom4 ?? 0}}" name="area_bedroom_4"> 
                                    </div>
                                @endif
                                @if (isset($areas->bedroom5) && $areas->bedroom5 > 0)
                                    <div class="sozdatImyaSpsok" data-total="{{ $bedroom_total_count++ }}">
                                        <h3 class="sozdatImyaSpisokH3">{{ translate('Bedroom - 5 area m2') }}</h3>
                                        <input
                                            class="sozdatImyaSpisokInput1272 keyup_input_area"
                                            type="text" value="{{ $areas->bedroom5 ?? 0}}" name="area_bedroom_5">
                                    </div>
                                @endif
                            </div>

                            <div id="bedroom_total_count" data-count="{{ $bedroom_total_count}}"></div>

                            @if ($areas->kitchen > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Kitchen (Ground) m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_kitchen') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->kitchen }}" name="area_kitchen">                                    
                                </div>
                            @endif

                            @if ($areas->basement > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Area (Ground) m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_basement') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->basement }}" name="area_basement">
                                    <span class="error-data">
                                        @error('area_basement')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->terraca) && $areas->terraca > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Area (Terrace) m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_terraca') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->terraca }}" name="area_terraca">
                                    <span class="error-data">
                                        @error('area_terraca')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->attic) && $areas->attic > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Area (Attic) m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_attic') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->attic }}" name="area_attic">
                                    <span class="error-data">
                                        @error('area_attic')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->balcony) && $areas->balcony > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Balcony m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_balcony') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->balcony }}" name="area_balcony">
                                    <span class="error-data">
                                        @error('area_balcony')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->corridor) && $areas->corridor > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Corridor m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_balcony') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->corridor }}" name="area_corridor">
                                    <span class="error-data">
                                        @error('area_balcony')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->bathroom) && $areas->bathroom > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Bathroom m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_balcony') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->bathroom }}" name="area_bathroom">
                                    <span class="error-data">
                                        @error('area_balcony')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if (isset($areas->other) && $areas->other > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Other m2') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 keyup_input_area @error('area_other') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $areas->other }}" name="area_other">
                                    <span class="error-data">
                                        @error('area_other')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Total area m2') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('area_total') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $areas->total }}" name="area_total">
                                <span class="error-data">
                                    @error('area_total')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div>
                            

                            <div class="sozdatImyaSpsok">
                                @if (is_int($model->room_count))
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Registry number') }}</h3>
                                @elseif ($model->room_count == 'p')
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Registry parking number') }}</h3>
                                @else
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Registry commercial number') }}</h3>
                                @endif
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('doc_number') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $model->doc_number ?? $model->number_of_flat }}"
                                    name="doc_number" readonly>
                                <span class="error-data">
                                    @error('doc_number')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            @php
                                $ares_price = json_decode($model->ares_price);
                            @endphp
                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('price') error-data-input is-invalid @enderror"
                                    type="text" name="price" value="{{ $ares_price->hundred->total ?? 0.0 }}">
                                <span class="error-data">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (30%)') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('price_30') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $ares_price->thirty->total ?? 0.0 }}" name="price_30">
                                <span class="error-data">
                                    @error('price_30')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (50%)') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('price_50') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $ares_price->fifty->total ?? 0.0 }}" name="price_50">
                                <span class="error-data">
                                    @error('price_50')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="sozdatImyaSpsok">
                                <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (70%)') }}</h3>
                                <input
                                    class="sozdatImyaSpisokInput1272 @error('price_70') error-data-input is-invalid @enderror"
                                    type="text" value="{{ $ares_price->seventy->total ?? 0.0 }}" name="price_70">
                                <span class="error-data">
                                    @error('price_70')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            @if(isset($ares_price->hundred->total_with_installment) && !empty($ares_price->hundred->total_with_installment))
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price m2 with initial payment') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price') error-data-input is-invalid @enderror"
                                        type="text" name="price_initial_payment" value="{{ $ares_price->hundred->total_with_installment ?? 0.0 }}">
                                </div>
                            @endif

                            @if(isset($ares_price->seventy->total_with_installment) && !empty($ares_price->seventy->total_with_installment))
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price m2 with initial payment (70%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price') error-data-input is-invalid @enderror"
                                        type="text" name="price_70_initial_payment" value="{{ $ares_price->seventy->total_with_installment ?? 0.0 }}">
                                </div>
                            @endif

                            @if(isset($ares_price->fifty->total_with_installment) && !empty($ares_price->fifty->total_with_installment))
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price m2 with initial payment (50%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price') error-data-input is-invalid @enderror"
                                        type="text" name="price_50_initial_payment" value="{{ $ares_price->fifty->total_with_installment ?? 0.0 }}">
                                </div>
                            @endif

                            @if(isset($ares_price->thirty->total_with_installment) && !empty($ares_price->thirty->total_with_installment))
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price m2 with initial payment (30%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price') error-data-input is-invalid @enderror"
                                        type="text" name="price_30_initial_payment" value="{{ $ares_price->thirty->total_with_installment ?? 0.0 }}">
                                </div>
                            @endif

                            @if ($areas->basement > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Ground)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_basement') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->hundred->basement ?? 0.0 }}"
                                        name="price_basement">
                                    <span class="error-data">
                                        @error('price_basement')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Ground 30%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_basement_30') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->thirty->basement ?? 0.0 }}"
                                        name="price_basement_30">
                                    <span class="error-data">
                                        @error('price_basement_30')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Ground 50%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_basement_50') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->fifty->basement ?? 0.0 }}"
                                        name="price_basement_50">
                                    <span class="error-data">
                                        @error('price_basement_50')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if ($areas->attic > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Attic)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_attic') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->hundred->attic ?? 0.0 }}"
                                        name="price_attic">
                                    <span class="error-data">
                                        @error('price_attic')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Attic 30%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_attic_30') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->thirty->attic ?? 0.0 }}"
                                        name="price_attic_30">
                                    <span class="error-data">
                                        @error('price_attic_30')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Attic 50%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_attic_50') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->fifty->attic ?? 0.0 }}"
                                        name="price_attic_50">
                                    <span class="error-data">
                                        @error('price_attic_50')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif

                            @if ($areas->terraca > 0)
                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Terrace)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_terrace') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->hundred->terraca ?? 0.0 }}"
                                        name="price_terrace">
                                    <span class="error-data">
                                        @error('price_terrace')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Terrace 30%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_terrace_30') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->thirty->terraca ?? 0.0 }}"
                                        name="price_terrace_30">
                                    <span class="error-data">
                                        @error('price_terrace_30')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="sozdatImyaSpsok">
                                    <h3 class="sozdatImyaSpisokH3">{{ translate('Price for 1m2 (Terrace 50%)') }}</h3>
                                    <input
                                        class="sozdatImyaSpisokInput1272 @error('price_terrace_50') error-data-input is-invalid @enderror"
                                        type="text" value="{{ $ares_price->fifty->terraca ?? 0.0 }}"
                                        name="price_terrace_50">
                                    <span class="error-data">
                                        @error('price_terrace_50')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- <button type="submit"
                        class="">{{ translate('Attach file') }}</button> --}}
                    @if ($model->room_count != null)
                        <input type="file" class="sozdatImyaSpisokSozdatButton1272" id="" name="files">
                        {{-- @dd($model->files); --}}
                        @if (!empty($model->files))
                            @foreach ($model->files as $img)
                                {{-- @dd($model); --}}
                                <br>
                                <img style="max-width: 200px;" 
                                    src="{{ asset('/uploads/house-flat/' . $model->house_id . '/m_' . $img->guid) }}"
                                    alt="Home">
                            @endforeach
                        @endif
                    @endif
                    {{-- <div>
                        <div class="seaDiv1272">
                            <img width="30" height="30"
                                src="{{ asset('backend-assets/forthebuilders/images/trash.png') }}" alt="Trash">
                        </div>
                        <img class="homeSozdatImage"
                            src="{{ asset('backend-assets/forthebuilders/images/m_b36a3adc8277086c6618f837ba24a5ce 1.png') }}"
                            alt="House">
                    </div> --}}
                    <button type="submit" class="sozdatImyaSpisokSozdatButton1272sozdat"
                        style="cursor: pointer;">{{ translate('Save') }}</button>
                </form>
            </div>


        </div>
    </div>
    <script>
        let page_name = 'house-flat';
        function round(value, exp) {
          if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

          value = +value;
          exp = +exp;

          if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

          // Shift
          value = value.toString().split('e');
          value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

          // Shift back
          value = value.toString().split('e');
          return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
        }

        $(document).on('click','.plus_hotel',function(){
            var count = $('.add_hotel_rooms').attr('data-count')

            if (count <= 5) {
                $('.add_hotel_rooms').append(`<div class="mt-2">
                    <h3 class="sozdatImyaSpisokH3 d-flex justify-content-between">
                        ${count} - {{ translate('Hotel') }}
                    </h3>
                    <input type="number" name="area_hotel_${count}" class="sozdatImyaSpisokInput1272 text-left keyup_input_area">                       
                </div>`
                );
                $('.add_hotel_rooms').attr('data-count',++count)    
            }
            
        })

        $(document).on('click','.minus_hotel',function(){
            var count = $('.add_hotel_rooms').attr('data-count')
            var hotel_total_count = parseInt($('#hotel_total_count').attr('data-count'))+2

            if (count > hotel_total_count) {
                $('.add_hotel_rooms .mt-2').last().remove()
                $('.add_hotel_rooms').attr('data-count',--count) 
                var sum = 0;
                $('.keyup_input_area').each(function(){
                    if ($(this).val() != '') {
                        sum += parseFloat($(this).val());   
                        sum = round(sum,2);    
                    }
                    $('input[name="area_total"]').val(sum)
                    
                });   
            }
        })

        $(document).on('click','.plus_bedroom',function(){
            var count = $('.add_bedroom_rooms').attr('data-count')
            if (count <= 5) {
                $('.add_bedroom_rooms').append(`<div class="mt-2">
                        <h3 class="sozdatImyaSpisokH3 d-flex justify-content-between">
                            ${count} - {{ translate('Bedroom') }}
                        </h3>
                        <input type="number" name="area_bedroom_${count}" class="sozdatImyaSpisokInput1272 text-left keyup_input_area">                       
                    </div>`
                );
                $('.add_bedroom_rooms').attr('data-count',++count)
            }
        })

        $(document).on('click','.minus_bedroom',function(){
            var count = $('.add_bedroom_rooms').attr('data-count')
            var bedroom_total_count = parseInt($('#bedroom_total_count').attr('data-count'))+2
            if (count > bedroom_total_count) {
                $('.add_bedroom_rooms .mt-2').last().remove()
                $('.add_bedroom_rooms').attr('data-count',--count)    
                var sum = 0;
                $('.keyup_input_area').each(function(){
                    if ($(this).val() != '') {
                        sum += parseFloat($(this).val());  
                        sum = round(sum,2);     
                    }
                });
                $('input[name="area_total"]').val(sum)
            }
        })

        $(document).on('keyup','.keyup_input_area',function(){
            var sum = 0;
            $('.keyup_input_area').each(function(){
                if ($(this).val() != '') {
                    sum += parseFloat($(this).val());
                    sum = round(sum,2);       
                }
                
            });

            $('input[name="area_total"]').val(sum)
        })
    </script>
@endsection



