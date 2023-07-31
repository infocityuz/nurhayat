@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
    use Modules\ForTheBuilder\Entities\HouseFlat;
@endphp
@section('title')
    {{ translate('JK') }}
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('/backend-assets/forthebuilders/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .client-show-buttons {
            left: 0 !important;
        }

        .select-items>div:nth-child(1) {
            background-color: #B1FF9D !important;
        }

        .select-items>div:nth-child(3) {
            background-color: #FF9D9D !important;
        }
        .add_flat{
            transition: .5s ease-in-out;
            opacity: 0;
        }
        .jkAllHouse:hover .add_flat{
            opacity: 1;
        }
    </style>
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="{{ route('forthebuilder.house.show-more', $model->id) }}"
                        class="plus2 profileMaxNazadInformatsiyaKlient">
                        <img src="{{ asset('backend-assets/forthebuilders/images/icons/arrow-left.png') }}" alt="">
                    </a>
                    <h2 class="panelUprText">{{ $model->name }} {{ ((!empty($model->house_number)) ? ' - № '.$model->house_number : '') }}</h2>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3" style="margin-bottom: 15px; max-width: 1300px;">
                <button class="vseButton btn-filter btn" data-filter="all">
                    {{ translate('All') }} ( {{ $arr['count_all'] }} )
                </button>
                {{-- (50 кв) --}}
                <button class="svobodnoButton btn-filter btn" style="background: {{ $colors[0] }};" data-filter="0">
                    {{ translate('Free') }} ( {{ $arr['count_free'] }} )
                </button>
                {{-- (20 кв.) --}}
                <button class="zanyatoButton btn-filter btn" style="background: {{ $colors[1] }};" data-filter="1">
                    {{ translate('Busy') }} ( {{ $arr['count_bookings'] }} )
                </button>
                {{-- (10 кв.) --}}
                <button class="prodnoButton btn-filter btn" style="background: {{ $colors[2] }};" data-filter="2">
                    {{ translate('Sales') }} ( {{ $arr['count_solds'] }} )
                </button>
                
                <button class="prodnoButton btn-filter btn" style="background: {{ $colors[3] ?? '' }};" data-filter="3">
                    {{ translate('Commercial') }} ( {{ $arr['count_commercial'] }} )
                </button>
                <button class="prodnoButton btn-filter btn" style="background: {{ $colors[4] ?? '' }};" data-filter="4">
                    {{ translate('Parking') }} ( {{ $arr['count_park'] }} )
                </button>
            </div>

            <div class="card card-body accordionData">
                <div class="d-flex" style="margin-top: 20px;">
                    <div class="checkboxDivTextInput7222">
                        {{ translate('Floor') }}
                    </div>
                    <div class="podyedzNumber">{{ translate('Entrance') }} № {{ $arr['entrance'] }}</div>
                </div>

                @empty(!$arr['list'])
                    @php
                        $i = 0;
                        $add_type = 0;
                    @endphp
                    @foreach ($arr['list'] as $key => $value)
                        @php
                           
                            if(is_numeric($key)){
                                $add_type = 1;
                            }
                            elseif($key == translate('basement')){
                                $add_type = 2;   
                            }
                            elseif($key == translate('attic')){
                                $add_type = 3;   
                            }
                            elseif($key == translate('Commercial')){
                                $add_type = 4;   
                            }
                            elseif($key == translate('Parking')){
                                $add_type = 5;   
                            } 
                           
                        @endphp
                        <div class="d-flex" style="margin-top: 10px;">
                            <div class="jkDomNumber">
                                {{ $key }}
                            </div>
                            <div class="jkAllHouse">
                                @empty(!$value)
                                    @foreach ($value as $val)
                                        @if ($status == 'client')
                                            @if($client_id == '0')
                                                <a class="jkHouseGreen border border-0 btn-filter-flat flat-button flat-button-open-modal"
                                                   href="{{ route('forthebuilder.clients.create', $val['id']) }}"
                                                   style="background-color: #44BE26">
                                                    <div class="jkHoueseBlueKomNumber">{{ $val['room_count'] }}
                                                        {{ translate('room') }}</div>
                                                    <div class="jkHouseGreeninData">{{ $val['areas'] }} {{ translate('m2') }}
                                                        <hr class="jkHouseGreeninDataHr"> <br>
                                                        {{ number_format($val['price'], 2, '.', '') }}
                                                        {{ translate('y.e') }}
                                                        <br>
                                                        {{ translate('per m2') }}
                                                    </div>
                                                </a>
                                            @else
                                                <a class="jkHouseGreen border border-0 btn-filter-flat flat-button flat-button-open-modal"
                                                    href="{{ route('forthebuilder.clients.show', [$client_id, $val['id'],'0','0']) }}"
                                                    style="background-color: #44BE26">
                                                    <div class="jkHoueseBlueKomNumber">{{ $val['room_count'] }}
                                                        {{ translate('room') }}</div>
                                                    <div class="jkHouseGreeninData">{{ $val['areas'] }} {{ translate('m2') }}
                                                        <hr class="jkHouseGreeninDataHr"> <br>
                                                        {{ number_format($val['price'], 2, '.', '') }}
                                                        {{ translate('y.e') }}
                                                        <br>
                                                        {{ translate('per m2') }}
                                                    </div>
                                                </a>
                                            @endif
                                        @else
                                            @php
                                                $perPrice = 0.0;
                                                if ($val['ares_price']) {
                                                    $ares_price = json_decode($val['ares_price']);
                                                    $perPrice = $ares_price->hundred->total;
                                                }
                                            @endphp
                                            <button
                                                class="jkHouseGreen border border-0 btn-filter-flat flat-button flat-button-open-modal"
                                                type="button" data-toggle="modal" data-target="#exampleModal"
                                                style="background: {{ $colors[$val['color_status']] }};"
                                                data-category="{{ $val['color_status'] }}"
                                                data-price="{{ $val['price'] }}"
                                                data-room_count="{{ $val['room_count'] }}" data-areas="{{ $val['areas'] }}"
                                                data-price_m2="{{ $perPrice }}" data-client="{{ $val['client'] }}"
                                                data-number_of_flat="{{ $val['number_of_flat'] }}"
                                                data-status="{{ $val['color_status'] }}" data-house_flat_id="{{ $val['id'] }}"
                                                data-house_house_id="{{ $val['house_id'] }}"
                                                data-house_house_name="{{ $val['color_status'] }}"
                                                data-house_contract_number="{{ $val['color_status'] }}"
                                                data-house_entrance="{{ $arr['entrance'] }}"
                                                data-house_floor="{{ $val['floor'] }}" data-doc="{{ $val['doc'] }}">
                                                <div class="jkHoueseBlueKomNumber">{{ $val['room_count'] }}
                                                    {{ translate('room') }}</div>
                                                <div class="jkHouseGreeninData">{{ $val['areas'] }} {{ translate('m2') }}
                                                    <hr class="jkHouseGreeninDataHr"> <br>
                                                    {{ number_format($perPrice, 0, ',', '.') }}
                                                    {{-- {{ translate('y.e') }} --}}
                                                    <br>
                                                    {{ translate('per m2') }}
                                                </div>
                                            </button>
                                        @endif
                                        @if ($i == 0)
                                            {{-- <input type="text" class="hidden-client" value="{{ $val['client'] }}">
                                            <br>
                                            <input type="text" class="detail_number_of_flat"
                                                value="{{ $val['number_of_flat'] }}">
                                            <br>
                                            <input type="text" class="detail_house_id" value="{{ $model->id }}">
                                            <br>
                                            <input type="text" class="detail_house_name" value="{{ $model->house_info }}">
                                            <br>
                                            <input type="text" class="detail_contract_number"
                                                value="{{ $val['contract_number'] }}">
                                            <br>
                                            <input type="text" class="detail_entrance" value="{{ $arr['entrance'] }}">
                                            <br>
                                            <input type="text" class="detail_floor" value="{{ $key }}">
                                            <br> --}}
                                        @endif
                                        @php
                                            $i = 1;
                                        @endphp
                                    @endforeach
                                @endempty
                                <span class="px-3"></span>
                                <a href="{{ route('forthebuilder.house-flat.add', $house_id.'_'.$add_type) }}" class="btn btn-primary ml-auto add_flat">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endempty

            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modalMyJk">
                <div class="modal-header border border-0">
                    <h5 class="modal-title" id="exampleModalLabel"
                        style="margin-left: 30px; font-family: Rubik; margin-top: 10px; margin-bottom: -20px;"><span
                            class="number_of_flat">0</span> -
                        {{ translate('Flat') }} <span class="flat_area">00.00</span> {{ translate('m') }} 2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <div>
                            <img width="364" height="260"
                                src="{{ asset('backend-assets/forthebuilders/images/a6d5ae15f8f52bd6b9db53be7746c650 1.png') }}"
                                alt="JkDom" style="margin-right: 10px">
                        </div>
                        <div>
                            <div class="modalJkData mb-4">
                                {{ translate('Price') }}
                                <div class="modalJkYeuro">
                                    <b><span class="flat_price">0000.00</span></b>
                                    {{-- &nbsp; {{ translate('y.e.') }} --}}
                                </div>
                            </div>

                            <div class="modalJkData mb-4">
                                {{ translate('Room count') }}
                                <div class="modalJkYeuro2 flat_room_count">
                                    0
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <div class="custom-select modalSelect" style="width:200px;">
                                    {{-- custom-select modalSelect --}}
                                    <select class="selectModal">
                                        <option value="0">{{ translate('Status') }}</option>
                                        <option data-select="{{ HouseFlat::STATUS_FREE }}"
                                            value="{{ HouseFlat::STATUS_FREE }}">
                                            {{ translate('Free') }}</option>
                                        <option data-select="{{ HouseFlat::STATUS_BOOKING }}" id="zanyatiOption"
                                            data-toggle="modal" data-target="#exampleModal2"
                                            value="{{ HouseFlat::STATUS_BOOKING }}">
                                            {{ translate('Busy') }}</option>
                                        <option data-select="{{ HouseFlat::STATUS_SOLD }}"
                                            value="{{ HouseFlat::STATUS_SOLD }}">
                                            {{ translate('Sold') }}</option>
                                    </select>

                                </div>
                                <div class="client-show-buttons showDetailsStatus d-none"
                                    style="background-color: #FF9D9D; width:200px; border-radius: 20px; height: 50px; display: flex; justify-content: center; align-items: center; text-align: center; border: none;">
                                    {{ translate('Sold') }}
                                </div>
                                <div>
                                    {{-- house-flat/show/${fId} --}}
                                    <a href="{{ route('forthebuilder.house-flat.show', 0) }}" type="button"
                                        class="modalPodrobnoButton">{{ translate('Details') }}</a>
                                </div>
                            </div>

                            <div class="modalJkDataFio flat_client_fio">
                                {{ translate('F.I.O') }}
                                <div class="modalJkFioM">
                                    {{ translate('No data') }}
                                </div>
                            </div>

                            <input type="hidden" class="house_flat_id" value="">
                            <input type="hidden" class="house_number_of_flat" value="">
                            <input type="hidden" class="house_house_id" value="">
                            <input type="hidden" class="house_house_name" value="">
                            <input type="hidden" class="house_contract_number" value="">
                            <input type="hidden" class="house_entrance" value="">
                            <input type="hidden" class="house_floor" value="">
                            <input type="hidden" class="room_count" value="">
                            <input type="hidden" class="house_price_m2" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="modal-form" action="{{ route('forthebuilder.booking.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content modalMyJk2" style="padding-left: 0;">
                    <div class="modal-header align-items-center">
                        <h4 class="m-0">{{ translate('To book') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span id="closeSpan" aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <table class="table-bordered table-striped table table-sm">
                            <tr>
                                <td>{{ translate('Number of rooms') }}: <b class="apartment_number"></b></td>
                                <td>{{ translate('Price per sq/m') }}: <b class="apartment_price_m2"></b></td>
                            </tr>
                            <tr>
                                <td>{{ translate('Total area') }}: <b class="apartment_area"></b><b> m2</b></td>
                                <td>{{ translate('Apartment price m2') }}: <b class="apartment_price"></b></td>
                            </tr>
                        </table>
                        <input class="booking-client_id" type="hidden" name="client_id"
                            value="{{ old('client_id') }}">
                        <input class="booking-house_flat_id" type="hidden" name="house_flat_id"
                            value="{{ old('house_flat_id') }}">
                        <input class="booking-house_number_of_flat" type="hidden" name="house_number_of_flat"
                            value="{{ old('house_number_of_flat') }}">
                        <input class="booking-house_house_id" type="hidden" name="house_house_id"
                            value="{{ old('house_house_id') }}">
                        <input class="booking-house_house_name" type="hidden" name="house_house_name"
                            value="{{ old('house_house_name') }}">
                        <input class="booking-house_contract_number" type="hidden" name="house_contract_number"
                            value="{{ old('house_contract_number') }}">
                        <input class="booking-house_entrance" type="hidden" name="house_entrance"
                            value="{{ old('house_entrance') }}">
                        <input class="booking-house_floor" type="hidden" name="house_floor"
                            value="{{ old('house_floor') }}">
                        <input class="booking-room_count" type="hidden" name="room_count"
                            value="{{ old('room_count') }}">
                        <div class="w-100">
                            <div class="sozdatImyaSpsok">
                                <label class="form-label font-weight-bold">{{ translate('First name') }}</label>
                                <input style="font-size: 18px;" 
                                    class="sozdatImyaSpisokInput keyUpName booking-first_name @error('first_name') error-data-input is-invalid @enderror"
                                    type="text" name="first_name" value="{{ old('first_name') }}"
                                    autocomplete="off">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="select2-dropdown select2-dropdown--below"
                                    style="width: 610px; position: absolute; background: lightgrey; display: none; max-height: 177px; overflow: scroll;">
                                    <span class="select2-results">
                                        <ul class="select2-results__options" role="tree" aria-multiselectable="true"
                                            id="select2-0obe-results" aria-expanded="true" aria-hidden="false"
                                            style="padding: 0;">
                                        </ul>
                                    </span>
                                </span>

                                <span class="error-data">
                                    @error('first_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="sozdatImyaSpsok">
                                <label class="form-label font-weight-bold">{{ translate('Last name') }}</label>
                                <input style="font-size: 18px;" 
                                    class="sozdatImyaSpisokInput keyUpName booking-last_name @error('last_name') error-data-input is-invalid @enderror"
                                    value="{{ old('last_name') }}" type="text" name="last_name">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="error-data">
                                    @error('last_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="sozdatImyaSpsok">
                                <label class="form-label font-weight-bold">{{ translate('Middle name') }}</label>
                                <input style="font-size: 18px;" 
                                    class="sozdatImyaSpisokInput keyUpName booking-middle_name @error('middle_name') error-data-input is-invalid @enderror"
                                    value="{{ old('middle_name') }}" type="text" name="middle_name">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="error-data">
                                    @error('middle_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label font-weight-bold">{{ translate('Phone number') }}</label>
                                <div class="d-flex">
                                    <div>
                                        <img style="max-width: 50px; margin-top: 4px;margin-right: 3px" src="{{ asset('backend-assets/forthebuilders/images/region.png') }}"
                                            alt="Region">
                                    </div>
                                    <div class="w-100">
                                        <label
                                            style="margin-bottom: -35px;z-index: 99;width: 50px;margin-left: 5px;margin-right: -55px;position: sticky;margin-top: 13px;padding-left: 6px;"
                                            for="+998">+998</label>
                                        <input style="font-size: 16px;padding-top: 6px;" 
                                            class="w-100 sozdatImyaSpisokInputTel keyUpName booking-phone @error('phone') error-data-input is-invalid @enderror"
                                            value="{{ old('phone') }}" type="tel" id="phone" name="phone"
                                            required="">
                                        <div class="keyUpNameResult d-none"
                                            style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                        </div>
                                        {{-- pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" --}}
                                        <span class="error-data">
                                            @error('phone')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold">{{ translate('additional_phone_number') }}</label>
                                <div class="d-flex">
                                    <div>
                                        <img style="max-width: 50px; margin-top: 4px;margin-right: 3px" src="{{ asset('backend-assets/forthebuilders/images/region.png') }}"
                                            alt="Region">
                                    </div>
                                    <div class="w-100">
                                        <label
                                            style="margin-bottom: -35px;z-index: 99;width: 50px;margin-left: 5px;margin-right: -55px;position: sticky;margin-top: 13px;padding-left: 6px;"
                                            for="+998">+998</label>
                                        <input style="font-size: 16px;padding-top: 6px;" 
                                            class="w-100 sozdatImyaSpisokInputTel keyUpName booking-additional_phone @error('additional_phone') error-data-input is-invalid @enderror"
                                            value="{{ old('additional_phone') }}" type="tel"
                                            id="additional_phone" name="additional_phone">
                                        <div class="keyUpNameResult d-none"
                                            style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                        </div>
                                        
                                        <span class="error-data">
                                            @error('additional_phone')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 mt-2">
                                <label class="form-label font-weight-bold">{{ translate('Serial number of the passport') }}</label>
                                <input style="font-size: 16px;" 
                                    class="sozdatImyaSpisokInput keyUpName booking-series_number @error('series_number') error-data-input is-invalid @enderror"
                                    value="{{ old('series_number') }}" type="text" name="series_number">
                                <div class="keyUpNameResult d-none"
                                    style="width: 65%; background: lightgrey; max-height: 220px; position: absolute; margin-top: 75px; overflow: scroll; border-radius: 15px;">
                                </div>
                                <span class="error-data">
                                    @error('series_number')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="col-6 mt-2">
                                <label class="form-label font-weight-bold">
                                    {{ translate('Issued by (Date of issue and expiration date)') }}</label>
                                <input style="width: 100% !important;" 
                                    class="sozdatImyaSpisokInputProdnoBig booking-given_date form-control @error('given_date') error-data-input is-invalid @enderror"
                                    value="{{ old('given_date') }}" type="text" name="given_date" id="given_date" placeholder="{{ date('d.m.2000') }}">
                                <span class="error-data">
                                    @error('given_date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="col-12 mt-2">
                                <label class="form-label font-weight-bold">{{ translate('Issued by') }}</label>
                                <input style="width: 100% !important;" 
                                    class="sozdatImyaSpisokInputProdnoBig booking-issued_by form-control @error('issued_by') error-data-input is-invalid @enderror"
                                    value="{{ old('issued_by') }}" type="text" name="issued_by" id="issued_by">
                                <span class="error-data">
                                    @error('issued_by')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            
                            <div class="col-6 mt-2">
                                <label class="form-label font-weight-bold">
                                    {{ translate('Prepayment amount') }}
                                    <input type="checkbox" name="prepayment" id="prepayment" class="@error('prepayment') error-data-input is-invalid @enderror" style="visibility: hidden;">
                                </label>

                                <input style="font-size: 16px;" 
                                    class="sozdatImyaSpisokInput booking-prepayment_summa @error('prepayment_summa') error-data-input is-invalid @enderror"
                                    value="{{ old('prepayment_summa') }}" type="text" name="prepayment_summa">
                                <span class="error-data">
                                    @error('prepayment_summa')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label font-weight-bold">
                                    {{ translate('Booking period') }}
                                </label>

                                <input style="font-size: 16px;"class="sozdatImyaSpisokInput booking_period" type="text" name="booking_period" placeholder="{{ translate('Indefinite period') }}">
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="sozdatImyaSpisokSozdatButton text-light">
                            {{ translate('Create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('/backend-assets/forthebuilders/toastr/js/toastr.min.js') }}"></script>
     <script src="{{ asset('/backend-assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('/backend-assets/plugins/jquery.maskedinput.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let page_name = 'house'

        $(document).on('click', '#closeSpan', function(e) {
            $('#exampleModal2').addClass('d-none')
            $('#exampleModal').removeClass('d-none')
        })
    </script>
     <script>
        $(document).ready(function() {
            $('input[type=tel]').mask("(999) 999-999");

            let sessionWarning = "{{ session('warning') }}";
            if (sessionWarning) {
                toastr.warning(sessionWarning)
            }
        });

        $(document).on('keyup','.booking-prepayment_summa',function(){
            if ($(this).val() != '') {
                $('#prepayment').prop('checked',true)
            }
            else{
                $('#prepayment').prop('checked',false)
            }
        })

        $(function(){
            $('.booking-given_date').datepicker({
                format: 'dd.mm.yyyy',
                autoclose: true
            })
            
            $('.booking_period').datepicker({
                format: 'dd.mm.yyyy',
                autoclose: true
            })
        })
    </script>
@endsection
@extends('forthebuilder::house.extra')

