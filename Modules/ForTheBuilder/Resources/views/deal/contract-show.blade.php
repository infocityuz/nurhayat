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
                <div class="miniSearchDivaffloDour" style="background: transparent;">
                    <a class="btn btn-xs btn-primary d-flex align-items-center justify-content-center" href="{{ route('forthebuilder.deal.printContract', $models->deal_id) }}">
                        <ion-icon class="raspechatIcon mr-2" name="print-outline"></ion-icon>
                        <span>{{ translate('Print') }}</span>
                    </a>
                </div>
            </div>
            <div class="sdelkaData px-3 align-items-start" style="width: 95%;">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>{{ translate('F.I.O. Clients') }}</th>
                        <td>{{ $models->client_id ? $models->client_first_name . ' ' . $models->client_last_name . ' ' . $models->client_middle_name : '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Deal object') }}</th>
                        <td> {{ $models->house_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Sum') }}</th>
                        <td>{{ $models->price_sell ? number_format($models->price_sell,0,'',' ') : '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Number of flat') }}</th>
                        <td>{{ $models->number_of_flat }}</td>
                    </tr>

                    <tr>
                        <th>{{ translate('Floor') }}</th>
                        <td>{{ $models->floor }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Room count') }}</th>
                        <td>{{ $models->room_count }}</td>
                    </tr>
                </table>

                <div class="row">
                    <div class="col-12">
                        <h4>{{ translate('Areas flat') }}</h4>        
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    @if(!empty($models->areas)) 
                        @foreach(json_decode($models->areas) AS $key => $value)
                            @if(!empty($value))
                            <tr>
                                <th class="text-uppercase">{{ translate($key) }}</th>
                                <td>{{ $value }} {{ translate('m2') }} </td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </table>
                

            </div>
        </div>
</div>
