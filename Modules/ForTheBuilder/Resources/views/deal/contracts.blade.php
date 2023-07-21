@extends('forthebuilder::layouts.forthebuilder')
@section('title') {{ translate('Contracts') }} @endsection
@php use Modules\ForTheBuilder\Entities\Constants; @endphp
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            <div style="max-width: 1394px;">
                @include('forthebuilder::layouts.content.header')
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <h2 class="panelUprText">{{ translate('Sales') }}</h2>
                </div>
                <div class="miniSearchDivaffloDour">
                    <ion-icon class="miniSearchIconInput" name="search-outline"></ion-icon>
                    <input placeholder="{{ translate('Search by sales') }}" class="miniInputSdelka5 searchTable"
                        type="text">
                </div>
            </div>
            <div class="sdelkaData" style="width: 95%;">
                <div class="jkMiniData2">
                    <div class="checkboxDivInput" style="width: 5%;">
                        №
                    </div>
                    <div class="checkboxDivTextInput" style="width: 35%;">
                        {{ translate('F.I.O. Clients') }}
                    </div>
                    <div class="ObjextSdelki" style="width: 15%;">
                        {{ translate('Deal object') }}
                    </div>
                    <div class="ObjextSdelki" style="width: 15%;">
                        {{ translate('Sum') }}
                    </div>
                    <div class="dataSdelkaJk" style="width: 15%;">
                        {{ translate('Date deal') }}
                    </div>
                    <div class="deystvieSdelka" style="width: 15%;">
                        {{ translate('Print') }}
                    </div>
                </div>

                @empty(!$models)
                {{-- @dd($models) --}}
                    {{-- @dd($_GET['page']) --}}
                    @php
                        $n = 1;
                    @endphp
                    @foreach ($models as $key => $value)
                        {{-- @dd($value->deal_id)    --}}
                        @if (isset($value))
                            <div class="jkMiniData mb-1 hideData">
                                <input type="hidden" class="hiddenData"
                                    value="{{ $value->client_id ? $value->client_first_name . ' ' . $value->client_last_name . ' ' . $value->client_middle_name : '' }} {{ $value->house_name ?? '' }} {{ $value->price_sell ?? '' }} {{ $value->task_title ? $value->task_title : $defaultAction[$value->deal_type] }}">
                                <div class="d-flex lidiHrefBigLidiData" style="width: 100%;max-width: 100%;">
                                    <a href="{{ route('forthebuilder.deal.contract-show', [$value->deal_id]) }}" class="checkboxDivInput" style="width: 5%;">
                                        {{-- {{ $n++ }} --}}
                                        {{ $models->firstItem() + $key }}
                                    </a>
                                    <a href="{{ route('forthebuilder.deal.contract-show', [$value->deal_id]) }}" class="checkboxDivTextInput"  style="width: 35%;">
                                        {{ $value->client_id ? $value->client_first_name . ' ' . $value->client_last_name . ' ' . $value->client_middle_name : '' }}
                                    </a>
                                    <a href="{{ route('forthebuilder.deal.contract-show', [$value->deal_id]) }}" class="ObjextSdelki" style="width: 15%;">
                                        {{ $value->house_name ?? '' }}
                                    </a>
                                    <a href="{{ route('forthebuilder.deal.contract-show', [$value->deal_id]) }}" class="ObjextSdelki" style="width: 15%;">
                                        {{ $value->price_sell ?? '' }}
                                    </a>
                                    <a href="{{ route('forthebuilder.deal.contract-show', [$value->deal_id]) }}" class="dataSdelkaJk" style="width: 15%;">
                                        {{-- Последнее действие --}}
                                        {{ $value->updated_at ? date('d.m.Y H:i', strtotime($value->updated_at)) : '' }}
                                    </a>
                                    <a class="dataSdelkaJk text-primary" href="{{ route('forthebuilder.deal.printContract', $value->deal_id) }}" style="width: 15%;">
                                        <ion-icon class="raspechatIcon" name="print-outline"></ion-icon>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endempty
                <div class="aiz-pagination mt-4">
                    {{ $models->links() }}
                </div>

            </div>

        </div>
    </div>
    

@endsection

{{-- @section('scripts') --}}


<script src="{{ asset('/backend-assets/forthebuilders/javascript/jquery.min.js') }}"></script>
<script>
    let page_name = 'booking';
    console.log(page_name)
    $(document).ready(function() {
        $('.modalVideystvitelnoDa').on('click', function() {
            // var model_id=document.getElementById("model_id").value;
            var model_id = $('.model_id').val();
            //    console.log(model_id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "booking/destroy/" + model_id,
                type: 'DELETE',
                data: {
                    booking_id: model_id
                },
                success: function(response) {
                    console.log(response);
                    // location.reload();
                }
            });
        });
    });
</script>
