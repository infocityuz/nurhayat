@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\Constants;
@endphp
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            <div style="max-width: 1394px;">
                @include('forthebuilder::layouts.content.header')
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <h2 class="panelUprText">{{ translate('Clients') }}</h2>
                        <a href="{{ route('forthebuilder.clients.create', '0') }}" class="plus2">+</a>
                    </div>
                    <div class="miniSearchDiv">
                        <ion-icon class="miniSearchIconInput" name="search-outline"></ion-icon>
                        <input placeholder="{{ translate('Deal search') }}" class="miniInputSdelka searchTable"
                            type="text">
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('forthebuilder.clients.index') }}" class="cdelki_c_klientami">
                    {{ translate('Deals with clients') }}
                </a>
                <a href="{{ route('forthebuilder.clients.all-clients') }}" class="cdelki_c_klientamiBlue">
                    {{ translate('All clients') }}
                </a>
            </div>

            <div class="sdelkaData">
                <div class="jkMiniData2">
                    <div class="checkboxDivInput">
                        №
                    </div>
                    <div class="checkboxDivTextInput">
                        {{ translate('F.I.O. Clients') }}
                    </div>
                    <div class="ObjextSdelki">
                        {{ translate('Deal object') }}
                    </div>
                    <div class="ObjextSdelki">
                        {{ translate('Sum') }}
                    </div>
                    <div class="dataSdelkaJk">
                        {{ translate('Last action') }}
                    </div>
                    <div class="deystvieSdelka">
                        {{ translate('Action') }}
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
                                <div class="d-flex lidiHrefBigLidiData">
                                    <a href="{{ route('forthebuilder.clients.show', [$value->client_id, '0', '0']) }}" class="checkboxDivInput">
                                        {{-- {{ $n++ }} --}}
                                        {{ $models->firstItem() + $key }}
                                    </a>
                                    <a href="{{ route('forthebuilder.clients.show', [$value->client_id, '0', '0']) }}" class="checkboxDivTextInput">
                                        {{ $value->client_id ? $value->client_first_name . ' ' . $value->client_last_name . ' ' . $value->client_middle_name : '' }}
                                    </a>
                                    <a href="{{ route('forthebuilder.clients.show', [$value->client_id, '0', '0']) }}" class="ObjextSdelki">
                                        {{ $value->house_name ?? '' }}
                                    </a>
                                    <a href="{{ route('forthebuilder.clients.show', [$value->client_id, '0', '0']) }}" class="ObjextSdelki">
                                        {{ $value->price_sell ?? '' }}
                                    </a>
                                    @php
                                        $sdelkaClass = '';
                                        if ($value->deal_type == Constants::FIRST_CONTACT) {
                                            $sdelkaClass = 'dataSdelkaJkPinkNthChild';
                                        } elseif ($value->deal_type == Constants::NEGOTIATION) {
                                            $sdelkaClass = 'dataSdelkaJkYellowNthChild';
                                        } else {
                                            $sdelkaClass = 'dataSdelkaJkGreenNthChild';
                                        }
                                    @endphp
                                    <a href="{{ route('forthebuilder.clients.show', [$value->client_id, '0', '0']) }}" class="dataSdelkaJk {{ $sdelkaClass }}">
                                        {{-- Последнее действие --}}
                                        {{ $value->task_title ? $value->task_title : $defaultAction[$value->deal_type] }}
                                    </a>
                                </div>

                                <div class="deystvieSdelka">
                                    <a style='margin-right: 10px;' href="{{ route('forthebuilder.clients.edit', $value->client_id) }}" class="seaDiv">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/edit.png') }}" alt="Edit">
                                    </a>
                                    <button type="button" style="border: none; cursor: pointer;"
                                        class="seaDiv clientDelete model_delete"
                                        data-url="{{ route('forthebuilder.clients.destroy', $value->client_id) }}">
                                        <img data-toggle="modal" data-target="#exampleModalLong" class="mt-1" width="20"
                                            height="20" src="{{ asset('backend-assets/forthebuilders/images/trash.png') }}"
                                            alt="Trash">
                                    </button>
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

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border: none;">
                <div class="modal-body">
                    <h2 class="modalVideystvitelno">Вы действительно хотите удалить</h2>
                    <div class="d-flex justify-content-center mt-5">
                        <form style="display: inline-block;" action="" method="POST" id="form_delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="modalVideystvitelnoDa">Да</button>
                        </form>
                        <button class="modalVideystvitelnoNet" data-dismiss="modal">Нет</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/backend-assets/forthebuilders/javascript/jquery.min.js') }}"></script>
    <script>
        let page_name = 'clients';
        $(document).ready(function() {
            $('.model_delete').on('click', function() {
                {{-- let action = {{route('forthebuilder.clients.edit', $model['id'])}}; --}}
                $('#form_delete').attr('action', $(this).attr('data-url'))
            });
        });
    </script>
@endsection
