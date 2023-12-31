@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
    use Modules\ForTheBuilder\Entities\HouseFlat;
    use Modules\ForTheBuilder\Entities\Constants;
@endphp
@section('title')
    {{ translate('JK') }}
@endsection
<style>
    
    .trashFlex {
        margin-top: -90px !important;
    }


    .select-items {
        background-color: transparent !important;
    }
</style>
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a onclick="history.back()" class="plus2 profileMaxNazadInformatsiyaKlient">
                        <img src="{{ asset('backend-assets/forthebuilders/images/icons/arrow-left.png') }}" alt="">
                    </a>
                    <h2 class="panelUprText">
                        @if ($model->room_count == 'c')
                            {{ $model->number_of_flat }} - {{ translate('commercial') }}
                        @elseif ($model->room_count == 'p')
                            {{ $model->number_of_flat }} - {{ translate('parking') }}
                        @else
                            {{ $model->number_of_flat }} - {{ translate('flat') }} {{ $model->total_area }} м <sup>2</sup>
                        @endif
                    </h2>
                </div>
                <button class="downloadColor" style="margin-right: 30px !important">
                    <div class="d-flex" style="cursor: pointer" data-toggle="modal" data-target="#exampleModal2">
                        <div class="raspechat">{{ translate('Print') }}</div>
                        <div>
                            <ion-icon class="raspechatIcon" name="print-outline"></ion-icon>
                        </div>
                    </div>
                </button>
            </div>

            @if (!empty($model->files) && count($model->files) > 0)
                {{-- @dd(ceil(count($model->files) / 3)); --}}
                <div id="carouselExampleControls" class="carousel slide jkEditNewFatherDiv" data-ride="carousel">
                    <div class="carousel-inner caruselMyJkEdit">
                        @php
                            $n = 0;
                        @endphp
                        @for ($i = 1; $i <= ceil(count($model->files) / 3); $i++)
                            @php
                                $active = $n == 0 ? 'active' : '';
                            @endphp
                            <div class="carousel-item {{ $active }}" data-interval="10000000">
                                <div class="cards-wrapper">

                                    @for ($j = $i * 3 - 3; $j <= $i * 3 - 1 && $j < count($model->files); $j++)
                                        <div data-toggle="modal" data-target="#exampleModalKatalog">
                                            <img class="jkImageEditSee"
                                                src="{{ asset('/uploads/house-flat/' . $model->house_id . '/l_' . $model->files[$n++]->guid) }}"
                                                alt="Home">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endfor

                    </div>

                    <div class="d-flex justify-content-between" style="width: 1200px;">
                        <a class="jkEditImgeLeft" href="#carouselExampleControls" role="button" data-slide="prev">
                            <!-- <span class="carousel-control-prev-icon" aria-hidden="true"></span> -->
                            <span class="sr-only">Previous</span>
                            <img src="{{ asset('backend-assets/forthebuilders/images/arrow-left.png') }}" alt="Left">

                        </a>

                        <a class="jkEditImgeRight" href="#carouselExampleControls" role="button" data-slide="next">
                            <!-- <span class="carousel-control-next-icon" aria-hidden="true"></span> -->
                            <span class="sr-only">Next</span>
                            <img src="{{ asset('backend-assets/forthebuilders/images/icons/arrow-left.png') }}"
                                alt="Left">
                        </a>
                    </div>
                </div>
            @endif

            <div class="jkEditData" style="width: 95%;">
                <div class="jkDataEdit1" style="width: 48%;">
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit">{{ translate('Attribute') }}</div>
                        <div class="jkAttributEdit">{{ translate('Data') }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('JK') }}</div>
                        <div class="jkAttributEdit2">{{ $model->house->name }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Information') }}</div>
                        <div class="jkAttributEdit3">{{ $model->house->description ?? '' }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Number house') }}</div>
                        <div class="jkAttributEdit3">{{ $model->house->house_number ?? '' }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Entrance') }}</div>
                        <div class="jkAttributEdit2">{{ $model->entrance }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Floor') }}</div>
                        <div class="jkAttributEdit2">{{ $model->floor }}</div>
                    </div>

                    @if (is_int($model->rool_count))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Apartment number') }}</div>
                            <div class="jkAttributEdit2">{{ $model->number_of_flat }}</div>
                        </div>
                    @endif

                    @php
                        $areas = json_decode($model->areas);
                    @endphp
                    {{-- {"attic": 43, "total": 35, "balcony": 43, "housing": 37, "kitchen": 43, "terraca": 43, "basement": 43} --}}
                    @if (isset($areas->housing))
                        <div class="jkEditText1 d-none">
                            <div class="jkAttributEdit2">{{ translate('Area') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->housing ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->hotel))
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Hotel') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->hotel ?? 0 }}</div>
                    </div>
                    @endif
                    @if (!empty($areas->hotel2))
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">2 - {{ translate('Hotel') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->hotel2 ?? 0 }}</div>
                    </div>
                    @endif

                    @if (!empty($areas->hotel3))
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">3 - {{ translate('Hotel') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->hotel3 ?? 0 }}</div>
                    </div>
                    @endif

                    @if (!empty($areas->hotel4))
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">4 - {{ translate('Hotel') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->hotel4 ?? 0 }}</div>
                    </div>
                    @endif

                    @if (!empty($areas->hotel5))
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">5 - {{ translate('Hotel') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->hotel5 ?? 0 }}</div>
                    </div>
                    @endif

                    @if (isset($areas->bedroom))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Bedroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bedroom ?? 0 }}</div>
                        </div>
                    @endif
                    
                    @if (!empty($areas->bedroom2))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">2 - {{ translate('Bedroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bedroom2 ?? 0 }}</div>
                        </div>
                    @endif

                     @if (!empty($areas->bedroom3))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">3 - {{ translate('Bedroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bedroom3 ?? 0 }}</div>
                        </div>
                    @endif

                     @if (!empty($areas->bedroom4))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">4 - {{ translate('Bedroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bedroom4 ?? 0 }}</div>
                        </div>
                    @endif


                     @if (!empty($areas->bedroom5))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">5 -{{ translate('Bedroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bedroom5 ?? 0 }}</div>
                        </div>
                    @endif

                     

                    @if (isset($areas->terraca) && $areas->terraca > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Area (Terrace)') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->terraca ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->basement) && $areas->basement > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Area (Sokolny)') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->basement ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->attic) && $areas->attic > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Area (Attic)') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->attic ?? 0 }}</div>
                        </div>
                    @endif

                    @if (is_int($model->rool_count))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Number rooms') }}</div>
                            <div class="jkAttributEdit2">{{ $model->rool_count ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->balcony) && $areas->balcony > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Balcony') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->balcony ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->corridor) && $areas->corridor > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Corridor') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->corridor ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->bathroom) && $areas->bathroom > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Bathroom') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->bathroom ?? 0 }}</div>
                        </div>
                    @endif

                    @if (isset($areas->other) && $areas->other > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Other') }}</div>
                            <div class="jkAttributEdit2">{{ $areas->other ?? 0 }}</div>
                        </div>
                    @endif
                </div>

                <div class="jkDataEdit1" style="width: 48%;">
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit">{{ translate('Attribute') }}</div>
                        <div class="jkAttributEdit">{{ translate('Data') }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Total area') }}</div>
                        <div class="jkAttributEdit2">{{ $areas->total }} {{ translate('m2') }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Status') }}</div>
                        <div class="custom-select klientNameInformatsiaButtonKontact"
                            style="width:200px;background-color: transparent;margin-top: -3px;margin-right: -14px;">
                            <input type="hidden" name="status" id="flat_status_value" value="{{ $model->status }}">
                            @php
                                $selectedBooking = '';
                                $selectedFree = '';
                                $selectedSold = '';
                                if ($model->status == Constants::STATUS_BOOKING) {
                                    $selectedBooking = 'selected';
                                } elseif ($model->status == Constants::STATUS_FREE) {
                                    $selectedFree = 'selected';
                                } elseif ($model->status == Constants::STATUS_SOLD) {
                                    $selectedSold = 'selected';
                                }
                            @endphp

                            @if ($model->status == Constants::STATUS_SOLD)
                                <div class="client-show-buttons showDetailsStatus"
                                    style="background-color: #FF9D9D; width: 184px; border-radius: 20px; height: 31px; display: flex; justify-content: center; align-items: center; text-align: center; border: none;">
                                    {{ translate('Sold') }}
                                </div>
                            @else
                                <select class="selectModal">
                                    <option value="{{ Constants::STATUS_BOOKING }}">{{ translate('Busy') }}</option>
                                    <option value="{{ Constants::STATUS_SOLD }}"
                                        data-select="{{ HouseFlat::STATUS_SOLD }}" {{ $selectedSold }}>
                                        {{ translate('Sold') }}
                                    </option>
                                    <option value="{{ Constants::STATUS_BOOKING }}"
                                        data-select="{{ HouseFlat::STATUS_BOOKING }}" {{ $selectedBooking }}>
                                        {{ translate('Busy') }}</option>
                                    <option value="{{ Constants::STATUS_FREE }}"
                                        data-select="{{ HouseFlat::STATUS_FREE }}" {{ $selectedFree }}>
                                        {{ translate('Free') }}
                                    </option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Date') }}</div>
                        <div class="jkAttributEdit2"><small>{{ date('d.m.Y H:i', strtotime($model->created_at)) }}</small>
                        </div>
                    </div>

                    @if (is_int($model->rool_count))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Registry number') }}</div>
                            <div class="jkAttributEdit2">{{ $model->doc_number ?? $model->number_of_flat }}</div>
                        </div>
                    @endif

                    @php
                        $ares_price = json_decode($model->ares_price);
                    @endphp
                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Price for 1m2') }}</div>
                        <div class="jkAttributEdit2">{{ $ares_price->hundred->total ?? 0.0 }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Price for 1m2 (30%)') }}</div>
                        <div class="jkAttributEdit2">{{ $ares_price->thirty->total ?? 0.0 }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Price for 1m2 (50%)') }}</div>
                        <div class="jkAttributEdit2">{{ $ares_price->fifty->total ?? 0.0 }}</div>
                    </div>

                    <div class="jkEditText1" style="width: 100%;">
                        <div class="jkAttributEdit2">{{ translate('Price for 1m2 (70%)') }}</div>
                        <div class="jkAttributEdit2">{{ $ares_price->seventy->total ?? 0.0 }}</div>
                    </div>

                    @if(isset($ares_price->hundred->total_with_initial) && !empty($ares_price->hundred->total_with_initial))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price m2 with initial payment') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->hundred->total_with_initial ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if(isset($ares_price->seventy->total_with_initial) && !empty($ares_price->seventy->total_with_initial))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price m2 with initial payment (70%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->seventy->total_with_initial ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if(isset($ares_price->fifty->total_with_initial) && !empty($ares_price->fifty->total_with_initial))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price m2 with initial payment (50%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->fifty->total_with_initial ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if(isset($ares_price->thirty->total_with_initial) && !empty($ares_price->thirty->total_with_initial))
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price m2 with initial payment (30%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->thirty->total_with_initial ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if ($areas->basement > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Ground)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->hundred->basement ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Ground 30%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->thirty->basement ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Ground 50%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->fifty->basement ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if ($areas->attic > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Attic)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->hundred->attic ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Attic 30%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->thirty->attic ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Attic 50%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->fifty->attic ?? 0.0 }}</div>
                        </div>
                    @endif

                    @if ($areas->terraca > 0)
                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Terrace)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->hundred->terraca ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Terrace 30%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->thirty->terraca ?? 0.0 }}</div>
                        </div>

                        <div class="jkEditText1" style="width: 100%;">
                            <div class="jkAttributEdit2">{{ translate('Price for 1m2 (Terrace 50%)') }}</div>
                            <div class="jkAttributEdit2">{{ $ares_price->fifty->terraca ?? 0.0 }}</div>
                        </div>
                    @endif
                </div>

            </div>

            @if ($model->status != Constants::STATUS_SOLD)
                <div style="max-width: 1144px;" class="trashFlex">
                    <div class="d-flex">
                        <a href="{{ route('forthebuilder.house-flat.edit', $model->id) }}" class="trashBigButton"
                            style="cursor: pointer;">
                            <img class="deleteEditButton"
                                src="{{ asset('backend-assets/forthebuilders/images/edit2.png') }}" alt="Delete">
                        </a>
                        <button class="trashBigButton2" style="cursor: pointer;">
                            <img class="deleteEditButton" data-toggle="modal" data-target="#exampleModalLong"
                                src="{{ asset('backend-assets/forthebuilders/images/Vector.png') }}" alt="Delete">
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="exampleModalKatalog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog myModalWdthGallery" role="document">
            <div class="modal-content galareModalFather">
                <div class="modal-body">
                    <div id="carouselExampleControls2" data-interval="10000000" class="carousel slide"
                        data-ride="carousel">
                        <div class="carousel-inner">

                            @if (!empty($model->files))
                                @php
                                    $first = true;
                                @endphp
                                @foreach ($model->files as $img)
                                    @php
                                        $class = '';
                                        if ($first) {
                                            $first = false;
                                            $class = 'active';
                                        }
                                    @endphp
                                    <div class="carousel-item {{ $class }}">
                                        <img class="madlImageJkEdit"
                                            src="{{ asset('/uploads/house-flat/' . $model->house_id . '/l_' . $img->guid) }}"
                                            alt="Home">
                                    </div>
                                @endforeach
                            @endif

                            {{-- <div class="carousel-item">
                                <img class="madlImageJkEdit"
                                    src="{{ asset('backend-assets/forthebuilders/images/unsplash_L1Nt0O4Cv3g.png') }}"
                                    alt="Home">
                            </div>
                            <div class="carousel-item">
                                <img class="madlImageJkEdit"
                                    src="{{ asset('backend-assets/forthebuilders/images/unsplash_Bo-fW5KvdQI.png') }}"
                                    alt="Home">
                            </div> --}}
                        </div>
                        <div>
                            <a class="carousel-control-prev nextPrevioudFather" href="#carouselExampleControls2"
                                role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">{{ translate('Previous') }}</span>
                            </a>
                            <a class="carousel-control-next nextPrevioudFather" href="#carouselExampleControls2"
                                role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">{{ translate('Next') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
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
                            {{-- @dd($model->price); --}}
                            <input type="hidden" class="house_flat_id" value="{{ $model->id }}">
                            <input type="hidden" class="house_number_of_flat" value="{{ $model->number_of_flat }}">
                            <input type="hidden" class="house_house_id" value="{{ $model->house_id }}">
                            <input type="hidden" class="house_house_name" value="{{ $model->house->name }}">
                            <input type="hidden" class="house_contract_number" value="{{ $model->doc_number }}">
                            <input type="hidden" class="house_entrance" value="{{ $model->entrance }}">
                            <input type="hidden" class="house_floor" value="{{ $model->room_count }}">
                            <p class="flat_price d-none">{{ $model->price }}</p>
                            @php
                                $price_m2 = 0;
                                if ($model->ares_price)
                                    $price_m2 = json_decode($model->ares_price)->hundred->total;

                            @endphp
                            <input type="hidden" class="house_price_m2" value="{{ $price_m2 }}">
                            {{-- @dd($model); --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border: none;">
                <div class="modal-body">
                    <h2 class="modalVideystvitelno">{{ translate('Do you really want to delete') }}</h2>
                    <div class="d-flex justify-content-center mt-5">
                        <form style="display: inline-block; margin-right: 15px;"
                            action="{{ route('forthebuilder.house-flat.destroy', $model->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="modalVideystvitelnoDa">{{ translate('Yes') }}</button>
                        </form>
                        <button class="modalVideystvitelnoNet" style="cursor: pointer;">{{ translate('No') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="jkAttributEdit">{{ translate('Print') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <form class="modal-body" action="{{ route('forthebuilder.house-flat.print', $model->id) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body modalPechat">
                        
                       

                        <div class="sozdatImyaSpsok">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Valid until:') }}</h3>
                            <input class="sozdatImyaSpisokInput date_picker" style="padding-right: 10px;" type="text"
                                name="date_picker" autocomplete="off" id="datePicker" value="{{ date('d.m.Y', strtotime('+14 days')) }}">
                        </div>

                        <div class="sozdatImyaSpsok">
                            <h3 class="sozdatImyaSpisokH3">{{ translate('Coupon') }}</h3>
                            <input class="sozdatImyaSpisokInput" style="padding-right: 10px;" type="text"
                                name="coupon" autocomplete="off" id="coupon" value="">
                            <span id="applied" style="color: green"></span>
                            <input type="hidden" name="coupon_percent" id="coupon_percent">
                        </div>

                        <button type="submit" class="pechatButtonModal btn">{{ translate('Print') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
        let page_name = 'house';
        $(function(){
            $('.date_picker').datepicker({
                format: 'dd.mm.yyyy',
                autoclose: true
            })
            $(".date_picker").inputmask({"mask": "99.99.9999"});   
            
        })
        $(document).on('click','.pechatButtonModal',function(){
            $('#exampleModal2').modal('toggle')
        })

        
    </script>
@endsection
