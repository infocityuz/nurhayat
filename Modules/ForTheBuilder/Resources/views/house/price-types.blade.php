@extends('forthebuilder::layouts.forthebuilder')

@section('title')
    {{ translate('Currency') }}
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
                    <a href="{{ route('forthebuilder.settings.index') }}"
                        class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="{{ asset('/backend-assets/forthebuilders/images/icons/arrow-left.png') }}"
                            alt=""></a>
                    <h2 class="panelUprText">{{ translate('Price types') }}</h2>
                    <a href="#" class="plus2 addNewCoupon">+</a>
                </div>
            </div>

            <div class="kuponData">
                <div class="sozdatKupon" style="width: 100%;">
                    <div class="checkboxDivInput">
                        №
                    </div>
                    <div class="checkboxDivTextInput3565" style="width: 20%;">
                        {{ translate('Name') }}
                    </div>
                    <div class="checkboxDivTextInput3565" style="width: 20%;">
                        {{ translate('Name RU') }}
                    </div>
                    <div class="checkboxDivTextInput3565" style="width: 20%;">
                        {{ translate('Name EN') }}
                    </div>
                    <div class="checkboxDivTextInput3565" style="width: 20%;">
                        {{ translate('Status') }}
                    </div>
                    <div class="checkboxDivTextInput3565" style="width: 20%;">
                        {{ translate('Action') }}
                    </div>
                </div>

                @if (!empty($model))
                    @foreach ($model as $key => $value)
                        <div class="sozdatKupon2" style="width: 100%;">
                            <div class="checkboxDivInput">
                                {{ $model->firstItem() + $key }}
                                <input type="hidden" class="checkboxDivTextInput3565 couponId"
                                    value="{{ $value->id }}">
                            </div>
                            <div class="checkboxDivTextInput3565" style="width: 20%;">
                                <span>{{ $value->name }}</span>
                                <input type="hidden" class="checkboxDivTextInput3565 couponName" style="width: 100%;margin-right: 0;" 
                                    value="{{ $value->name }}">
                            </div>
                            <div class="checkboxDivTextInput3565" style="width: 20%;">
                                <span>{{ $value->name_ru }}</span>
                                <input type="hidden" class="checkboxDivTextInput3565 couponNameRU" style="width: 100%;margin-right: 0;" 
                                    value="{{ $value->name_ru }}">
                            </div>
                            <div class="checkboxDivTextInput3565" style="width: 20%;">
                                <span>{{ $value->name_en }}</span>
                                <input type="hidden" class="checkboxDivTextInput3565 couponNameEN" style="width: 100%;margin-right: 0;" 
                                    value="{{ $value->name_en }}">
                            </div>
                            <div class="checkboxDivTextInput3565" style="width: 20%;">
                                @if($value->status == 1) 
                                    <span class="badge badge-success">{{ translate('Active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                                @endif
                            </div>
                            
                            <div class="checkboxDivTextInput3565" style="width: 20%;">
                                <div class="seaDiv typeUpdate d-none" style="cursor: pointer;" data-id="{{ $value->id }}">
                                    <img class="mt-1" width="20" height="20"
                                        src="{{ asset('/backend-assets/forthebuilders/images/verifed.png') }}"
                                        alt="Trash">
                                </div>
                                <div class="seaDiv typeEdit" style="cursor: pointer;">
                                    <img class="mt-1" width="20" height="20"
                                        src="{{ asset('/backend-assets/forthebuilders/images/edit.png') }}" alt="Trash">
                                </div>
                                
                                <button type="button" class="btn p-0 seaDiv typeDelete" data-id="{{ $value->id }}">
                                    <img class="mt-1" width="20"
                                        height="20" src="{{ asset('backend-assets/forthebuilders/images/trash.png') }}"
                                        alt="Trash">
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endempty

                <div class="sozdatKupon2 formNewCoupon d-none" style="width: 100%;">
                    <div class="checkboxDivInput">
                        {{ $model->firstItem() + ($key ?? 0) + 1 }}
                    </div>
                    <input type="text" class="checkboxDivTextInput3565 couponCreateName" style="width: 33%;" placeholder="{{ translate('Name in uzbek') }}">
                    <input type="text" class="checkboxDivTextInput3565 couponCreateNameRU" style="width: 33%;" placeholder="{{ translate('Name in russian') }}">
                    <input type="text" class="checkboxDivTextInput3565 couponCreateNameEN" style="width: 33%;" placeholder="{{ translate('Name in english') }}">
                    <div class="checkboxDivTextInput35652">
                        <div class="seaDiv typeSave" style="cursor: pointer;">
                            <img class="mt-1" width="20" height="20"
                                src="{{ asset('/backend-assets/forthebuilders/images/Verifed.png') }}" alt="Trash">
                        </div>
                        <div class="seaDiv removeFormType" style="cursor: pointer;">
                            <img class="mt-1" width="20" height="20"
                                src="{{ asset('/backend-assets/forthebuilders/images/trash.png') }}" alt="Trash">
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<div id="confirm" data-text="{{ translate('Are you sure ?') }}"></div>
<script>
    let page_name = 'settings';

    $(document).on('click', '.typeSave', function(e) {
        var name = $('.couponCreateName').val()
        var name_ru = $('.couponCreateNameRU').val()
        var name_en = $('.couponCreateNameEN').val()

        if (name != '') {
            $.ajax({
                url: `/forthebuilder/house/store-type`,
                data: {
                    name: name,
                    name_ru: name_ru,
                    name_en: name_en,
                },
                type: 'POST',
                success: function(data) {
                    if (data == true) {
                        location.reload()
                    }
                },
            });
        }
    })

    $(document).on('click', '.removeFormType', function(e) {
        $('.formNewCoupon').addClass('d-none')
    })

    $(document).on('click', '.typeDelete', function(e) {
        if(confirm($('#confirm').attr('data-text'))){
            var id = $(this).attr('data-id')
            $.ajax({
                url: `/forthebuilder/house/destroy-type`,
                data: {
                    id: id
                },
                type: 'POST',
                success: function(data) {
                    if (data == true) {
                        location.reload()
                    }
                },
            });
        }
    })

    $(document).on('click', '.typeUpdate', function(e) {
        var id = $(this).attr('data-id');
        var name = $(this).parent().parent().find('.couponName').val();
        var name_ru = $(this).parent().parent().find('.couponNameRU').val();
        var name_en = $(this).parent().parent().find('.couponNameEN').val();

        if (name != '') {
            $.ajax({
                url: `/forthebuilder/house/update-type`,
                data: {
                    name: name,
                    name_ru: name_ru,
                    name_en: name_en,
                    id: id,
                },
                type: 'POST',
                success: function(data) {
                    if (data == true) {
                        location.reload()
                    }
                },
            });
        }
    })

    $(document).on('click', '.typeEdit', function(e) {
            var typeName = $(this).parent().parent().find('.couponName').attr('type')
            $(this).siblings('.typeUpdate').toggleClass('d-none');
            // $(this).addClass('d-none');
            $(this).parent().parent().find('.couponName').siblings('span').toggle();

            $(this).parent().parent().find('.couponName').attr('type', 'hidden');
            if (typeName == 'hidden')
                $(this).parent().parent().find('.couponName').attr('type', 'text');

            var typeNameRU = $(this).parent().parent().find('.couponNameRU').attr('type')
            $(this).siblings('.typeUpdate').toggleClass('d-none');
            // $(this).addClass('d-none');
            $(this).parent().parent().find('.couponNameRU').siblings('span').toggle();

            $(this).parent().parent().find('.couponNameRU').attr('type', 'hidden');
            if (typeNameRU == 'hidden')
                $(this).parent().parent().find('.couponNameRU').attr('type', 'text');

            var typeNameEN = $(this).parent().parent().find('.couponNameEN').attr('type')
            $(this).siblings('.typeUpdate').toggleClass('d-none');
            // $(this).addClass('d-none');
            $(this).parent().parent().find('.couponNameEN').siblings('span').toggle();

            $(this).parent().parent().find('.couponNameEN').attr('type', 'hidden');
            if (typeNameEN == 'hidden')
                $(this).parent().parent().find('.couponNameEN').attr('type', 'text');

            

        })
</script>
@endsection
