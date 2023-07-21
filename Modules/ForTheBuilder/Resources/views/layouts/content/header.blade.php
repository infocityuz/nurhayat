<style>
    .display-none {
        display: none;
    }

    .notification_content {
        position: absolute;
        z-index: 100;
    }

    .language_flag {
        position: absolute;
        z-index: 1;
        margin-top: 70px;
    }
</style>
<div class="d-flex ajaxCliDiv">
    <div class="inputDiv">
        <div class="affflo">
            <ion-icon class="searchIconInput" name="search-outline"></ion-icon>
        </div>
        @php
            $arrSearch = [
                route('forthebuilder.index') => translate('Home'),
                route('forthebuilder.task.index') => translate('Task list'),
                route('forthebuilder.deal.index') => translate('Deal list'),
                route('forthebuilder.clients.index') => translate('Deals with clients'),
                route('forthebuilder.clients.all-clients') => translate('All clients'),
                route('forthebuilder.clients.create', '0') => translate('Creating a new client'),
                route('forthebuilder.house.index') => translate('JK list'),
                route('forthebuilder.house.create') => translate('Create a new JK'),
                route('forthebuilder.clients.calendar') => translate('Task calendar'),
                route('forthebuilder.booking.index') => translate('Booking list'),
                route('forthebuilder.installment-plan.index') => translate('Installment plan list'),
                route('forthebuilder.user.index') => translate('User list'),
                route('forthebuilder.user.create') => translate('Create a new user'),
                route('forthebuilder.settings.index') => translate('Settings list'),
                route('forthebuilder.currency.index') => translate('Currencies list'),
            ];
        @endphp
        <div class="inputCustomDiv" style="width: 100%; margin-top: -16px;">
            <input class="searchInput" type="text" value="">
        </div>
        <div style="background-color: white; border-radius: 20px; position: absolute; width: 54%; top: 70px; z-index: 9999;"
            class="searchMainDiv d-none">
            <ul style="list-style: none;">
                @foreach ($arrSearch as $key => $value)
                    <a href="{{ $key }}">
                        <li style="margin: 15px; border-bottom: 1px solid black;" class="searchLi d-none">
                            {{ $value }}
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
    </div>
    <div style="max-width: 169px; display: flex; margin-right: 80px;">
        <a style="margin-top: 22px; margin-right: 5px" href="{{ route('forthebuilder.clients.calendar') }}" class="hrefDrop">
                <span class="icon">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M13.3333 25.0007C12.4167 25.0007 11.6667 24.2507 11.6667 23.334C11.6667 22.4173 12.4167 21.6673 13.3333 21.6673C14.25 21.6673 15 22.4173 15 23.334C15 24.2507 14.25 25.0007 13.3333 25.0007ZM20 25.0007C19.0833 25.0007 18.3333 24.2507 18.3333 23.334C18.3333 22.4173 19.0833 21.6673 20 21.6673C20.9167 21.6673 21.6667 22.4173 21.6667 23.334C21.6667 24.2507 20.9167 25.0007 20 25.0007ZM25 23.334C25 24.2507 25.75 25.0007 26.6667 25.0007C27.5833 25.0007 28.3333 24.2507 28.3333 23.334C28.3333 22.4173 27.5833 21.6673 26.6667 21.6673C25.75 21.6673 25 22.4173 25 23.334ZM13.3333 30.0007C12.4167 30.0007 11.6667 29.2507 11.6667 28.334C11.6667 27.4173 12.4167 26.6673 13.3333 26.6673C14.25 26.6673 15 27.4173 15 28.334C15 29.2507 14.25 30.0007 13.3333 30.0007ZM18.3333 28.334C18.3333 29.2507 19.0833 30.0007 20 30.0007C20.9167 30.0007 21.6667 29.2507 21.6667 28.334C21.6667 27.4173 20.9167 26.6673 20 26.6673C19.0833 26.6673 18.3333 27.4173 18.3333 28.334ZM26.6667 30.0007C25.75 30.0007 25 29.2507 25 28.334C25 27.4173 25.75 26.6673 26.6667 26.6673C27.5833 26.6673 28.3333 27.4173 28.3333 28.334C28.3333 29.2507 27.5833 30.0007 26.6667 30.0007ZM10 33.334C9.08167 33.334 8.33333 32.5857 8.33333 31.6673V19.1673H31.6667V31.6673C31.6667 32.5857 30.9183 33.334 30 33.334H10ZM11.6667 10.0007V11.6673C11.6667 12.584 12.4167 13.334 13.3333 13.334C14.25 13.334 15 12.584 15 11.6673V10.0007H25V11.6673C25 12.584 25.75 13.334 26.6667 13.334C27.5833 13.334 28.3333 12.584 28.3333 11.6673V10.0007H30C30.9183 10.0007 31.6667 10.749 31.6667 11.6673V15.834H8.33333V11.6673C8.33333 10.749 9.08167 10.0007 10 10.0007H11.6667ZM28.3333 6.66732V5.00065C28.3333 4.08398 27.5833 3.33398 26.6667 3.33398C25.75 3.33398 25 4.08398 25 5.00065V6.66732H15V5.00065C15 4.08398 14.25 3.33398 13.3333 3.33398C12.4167 3.33398 11.6667 4.08398 11.6667 5.00065V6.66732H10C7.24333 6.66732 5 8.91065 5 11.6673V31.6673C5 34.424 7.24333 36.6673 10 36.6673H30C32.7567 36.6673 35 34.424 35 31.6673V11.6673C35 8.91065 32.7567 6.66732 30 6.66732H28.3333Z"
                              fill="#3E3E3E" />
                        <mask id="mask0_1903_18492" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                              x="5" y="3" width="30" height="34">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M13.3333 25.0007C12.4167 25.0007 11.6667 24.2507 11.6667 23.334C11.6667 22.4173 12.4167 21.6673 13.3333 21.6673C14.25 21.6673 15 22.4173 15 23.334C15 24.2507 14.25 25.0007 13.3333 25.0007ZM20 25.0007C19.0833 25.0007 18.3333 24.2507 18.3333 23.334C18.3333 22.4173 19.0833 21.6673 20 21.6673C20.9167 21.6673 21.6667 22.4173 21.6667 23.334C21.6667 24.2507 20.9167 25.0007 20 25.0007ZM25 23.334C25 24.2507 25.75 25.0007 26.6667 25.0007C27.5833 25.0007 28.3333 24.2507 28.3333 23.334C28.3333 22.4173 27.5833 21.6673 26.6667 21.6673C25.75 21.6673 25 22.4173 25 23.334ZM13.3333 30.0007C12.4167 30.0007 11.6667 29.2507 11.6667 28.334C11.6667 27.4173 12.4167 26.6673 13.3333 26.6673C14.25 26.6673 15 27.4173 15 28.334C15 29.2507 14.25 30.0007 13.3333 30.0007ZM18.3333 28.334C18.3333 29.2507 19.0833 30.0007 20 30.0007C20.9167 30.0007 21.6667 29.2507 21.6667 28.334C21.6667 27.4173 20.9167 26.6673 20 26.6673C19.0833 26.6673 18.3333 27.4173 18.3333 28.334ZM26.6667 30.0007C25.75 30.0007 25 29.2507 25 28.334C25 27.4173 25.75 26.6673 26.6667 26.6673C27.5833 26.6673 28.3333 27.4173 28.3333 28.334C28.3333 29.2507 27.5833 30.0007 26.6667 30.0007ZM10 33.334C9.08167 33.334 8.33333 32.5857 8.33333 31.6673V19.1673H31.6667V31.6673C31.6667 32.5857 30.9183 33.334 30 33.334H10ZM11.6667 10.0007V11.6673C11.6667 12.584 12.4167 13.334 13.3333 13.334C14.25 13.334 15 12.584 15 11.6673V10.0007H25V11.6673C25 12.584 25.75 13.334 26.6667 13.334C27.5833 13.334 28.3333 12.584 28.3333 11.6673V10.0007H30C30.9183 10.0007 31.6667 10.749 31.6667 11.6673V15.834H8.33333V11.6673C8.33333 10.749 9.08167 10.0007 10 10.0007H11.6667ZM28.3333 6.66732V5.00065C28.3333 4.08398 27.5833 3.33398 26.6667 3.33398C25.75 3.33398 25 4.08398 25 5.00065V6.66732H15V5.00065C15 4.08398 14.25 3.33398 13.3333 3.33398C12.4167 3.33398 11.6667 4.08398 11.6667 5.00065V6.66732H10C7.24333 6.66732 5 8.91065 5 11.6673V31.6673C5 34.424 7.24333 36.6673 10 36.6673H30C32.7567 36.6673 35 34.424 35 31.6673V11.6673C35 8.91065 32.7567 6.66732 30 6.66732H28.3333Z"
                                  fill="#3E3E3E" />
                        </mask>
                        <g mask="url(#mask0_1903_18492)">
                        </g>
                    </svg>
                </span>
            </a>
        <div class="dropdown">
            @php
                if(isset($all_notifications['all_task']) && isset($all_notifications['all_booking']) && isset($all_notifications['all_installment_plan'])){
                   $all_count = count($all_notifications['all_task']) + count($all_notifications['all_booking']) + count($all_notifications['all_installment_plan']);
               }else{
                   $all_count = 0;
               }
            @endphp
            <button class="d-flex buttonUzbDropDownHeader" type="button" id="dropdownMenuButton2"
                src="{{ asset('/backend-assets/forthebuilders/images/notification.png') }}" alt="Notification">
                <img class="notifRegion" src="{{ asset('/backend-assets/forthebuilders/images/notification.png') }}"
                    alt="Notification">
                <div class="notifRegionNumber">{{ $all_count }}</div>
            </button>
            <div id="notificate_" class="notification_content display-none"
                style="border: none; background-color: transparent;" aria-labelledby="dropdownMenuButton2">
                <div class="up-arrow2"></div>
                <div class="dropdownMenyApplyNotification">
                    @if ($all_count > 0)
                        <div class="px-1 py-2">
                            <button type="button" class="btn btn-sm btn-danger clear_notification">
                                <i class="fa fa-trash"></i>
                                {{ translate('Clear all') }}
                            </button>
                        </div>
                        <hr class="my-1">
                        @foreach ($all_notifications['all_booking'] as $booking_notification)
                            @php
                              $notification_data = json_decode($booking_notification->data);
                            @endphp
                            @switch($booking_notification->type)
                                @case('Booking')
                                    <a href="{{ route('forthebuilder.booking.show', $notification_data->id) }}">
                                        <div class="d-flex">
                                            <img class="dropdownHumanImage"
                                                 src="{{ asset('/uploads/flat/booking.png') }}"
                                                 alt="HUman">
                                            <h4 class="dropDownHumanIamgeName">
                                                {{ $notification_data->first_name . ' ' . $notification_data->last_name }}
                                            </h4>
                                        </div>
                                        <span
                                                class="humoanSmsDropdownYellow">{{ translate('booking period is expired') }}</span>
                                        <p class="humoanSmsDropdown">
                                                <span class="humoanSmsDropdownSpan float-right text-sm">
                                                    {{ date('m/d h:i', strtotime($notification_data->updated_at)) }}
                                                </span>
                                        </p>
                                    </a>
                                    <hr style="margin: 0px 12px; margin-top: 44px;">
                                @break
                                @case('BookingPrepayment')
                                    <a href="{{ route('forthebuilder.booking.show', $notification_data->id) }}">
                                        <div class="d-flex">
                                            <img class="dropdownHumanImage"
                                                 src="{{ asset('/uploads/flat/booking.png') }}"
                                                 alt="HUman">
                                            <h4 class="dropDownHumanIamgeName">
                                                {{ $notification_data->first_name . ' ' . $notification_data->last_name }}
                                            </h4>
                                        </div>
                                        <span
                                                class="humoanSmsDropdownYellow">{{ translate('1 day left until the booking period ends') }}</span>
                                        <p class="humoanSmsDropdown">
                                                <span class="humoanSmsDropdownSpan float-right text-sm">
                                                    {{ date('m/d h:i', strtotime($notification_data->updated_at)) }}
                                                </span>
                                        </p>
                                    </a>
                                    <hr style="margin: 0px 12px; margin-top: 44px;">
                                @break
                            @endswitch
                        @endforeach
                        @foreach ($all_notifications['all_installment_plan'] as $installment_notification)
                            @php
                                $installment_data = json_decode($installment_notification->data);
                            @endphp
                            <a href="{{ route('forthebuilder.installment-plan.show', $installment_data->id) }}">
                                <div class="d-flex">
                                    <img class="dropdownHumanImage"
                                         src="{{ asset('/uploads/flat/installment_plan.avif') }}"
                                         alt="HUman">
                                    <h4 class="dropDownHumanIamgeName">
                                        {{ $installment_data->first_name . ' ' . $installment_data->last_name }}
                                    </h4>
                                </div>
                                <span class="humoanSmsDropdownYellow">{{ translate('Installment plan period is expired') }}</span>
                                <p class="humoanSmsDropdown">
                                    <span class="humoanSmsDropdownSpan float-right text-sm">
                                        {{ date('m/d h:i', $installment_data->expire_dates) }}
                                    </span>
                                </p>
                            </a>
                            <hr style="margin: 0px 12px; margin-top: 44px;">
                        @endforeach
                        @foreach($all_notifications['all_task'] as $task_notification)
                            @php
                                $notification_data = json_decode($task_notification->data);
                            @endphp
                            <a href="{{ route('forthebuilder.clients.show', [$notification_data->client_id, '0', $notification_data->id]) }}">
                                <div class="d-flex">
                                    @php
                                        $sms_avatar = public_path('/uploads/user/' . $notification_data->performer_id . '/s_' . $notification_data->performer_avatar);
                                    @endphp
                                    @if(file_exists($sms_avatar))
                                        <img class="dropdownHumanImage"
                                             src="{{ asset('/uploads/user/' . $notification_data->performer_id . '/s_' . $notification_data->performer_avatar) }}"
                                             alt="Human">
                                    @else
                                        <img class="dropdownHumanImage"
                                             src="{{ asset('/backend-assets/forthebuilders/images/X.png') }}"
                                             alt="Human">
                                    @endif
                                    <h4 class="dropDownHumanIamgeName">{{ $notification_data->performer_fio }}</h4>
                                </div>
                                <span class="humoanSmsDropdown">{{ $notification_data->title }}
                                    <span class="humoanSmsDropdownSpan">{{ $notification_data->user_fio }}</span>
                                </span>
                                <p class="humoanSmsDropdown">{{ translate('Task') }}:&nbsp;
                                    @if ($notification_data->type == 'Связаться')
                                        <img src="{{ asset('/backend-assets/forthebuilders/images/Call.png') }}"
                                            alt="Call icon"><b>&nbsp;{{ translate('call') }}</b>
                                        {{ translate('with') . ' ' }}
                                        <span>{{ $notification_data->client_fio }}</span><br>
                                    @else
                                        <img src="{{ asset('/backend-assets/forthebuilders/images/meeting.png') }}"
                                            alt="Call icon" height="18px"><b>&nbsp;{{ translate('meeting') }}</b>
                                        {{ translate('with' . ' ') }}
                                        <span class="humoanSmsDropdownSpan">{{ $notification_data->client_fio }}</span><br>
                                    @endif
                                    <span class="humoanSmsDropdownSpan"> {{ $notification_data->task_date }}</span>
                                </p>
                                <hr style="margin: 0px 12px; margin-top: -8px;">
                            </a>
                        @endforeach
                    @else
                        <h4 class="netNovixUvidomleniy">{{ translate('No new notifications') }}</h4>
                    @endif
                </div>
            </div>
        </div>

        <div>
            @php
                if (session()->has('locale')) {
                    $locale = session('locale');
                } else {
                    $locale = env('DEFAULT_LANGUAGE', 'ru');
                }
                // $locale=app()->getLocale()?? env('DEFAULT_LANGUAGE');
            @endphp
            <div class="align-items-stretch d-flex dropdown" id="lang-change">
                <a class="buttonUzbDropDownHeader dropdown-toggle" type="button" id="dropdownMenuButton" role="button"
                    data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="javascript:void(0);">
                    @switch($locale)
                        @case('uz')
                            <img class="notifRegion2" id="selected_language"
                                src="{{ asset('/backend-assets/forthebuilders/images/region.png') }}" alt="region">
                        @break

                        @case('en')
                            <img class="notifRegion2" id="selected_language"
                                src="{{ asset('/backend-assets/forthebuilders/images/GB.png') }}" alt="region">
                        @break

                        @case('ru')
                            <img class="notifRegion2" id="selected_language"
                                src="{{ asset('/backend-assets/forthebuilders/images/RU.png') }}" alt="region">
                        @break
                    @endswitch
                </a>
                <div id="language_flag" class="language_flag display-none"
                    style="border: none; background-color: transparent;" aria-labelledby="dropdownMenuButton">
                    <div class="up-arrow"></div>
                    <div class="dropdownMenyApplyUzbFlag">
                        @foreach (\Modules\ForTheBuilder\Entities\Language::all() as $key => $language)
                        <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                           class="dropdown-item dropdown-item dropdownLanguageItem @if ($locale == $language->code) active @endif" >
                            @switch($language->code)
                                @case('uz')
                                    <img class="dropdownRegionBayroq" id="lang_uz" style="margin-right: 8px;" src="{{asset('/backend-assets/forthebuilders/images/region.png')}}" alt="region">
                                    {{ $language->name }}
                                    @break

                                    @case('ru')
                                        <img class="dropdownRegionBayroq" id="lang_ru" style="margin-right: 8px;"
                                            src="{{ asset('/backend-assets/forthebuilders/images/RU.png') }}" alt="region">
                                        {{ $language->name }}
                                    @break

                                    @case('en')
                                        <img class="dropdownRegionBayroq" id="lang_en" style="margin-right: 8px;"
                                            src="{{ asset('/backend-assets/forthebuilders/images/GB.png') }}" alt="region">
                                        {{ $language->name }}
                                    @break
                                @endswitch
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirm_notification_clear" data-text="{{ translate("Are you sure ?") }}"></div>

<script src="{{ asset('/backend-assets/forthebuilders/javascript/jquery-3.6.1.js') }}"></script>
<script src="{{ asset('/backend-assets/js/custom.js') }}"></script>
<script defer>
    $(document).ready(function() {
        let language = '{{ $locale }}'
        let uz = `{{ asset('/backend-assets/forthebuilders/images/region.png') }}`
        let ru = `{{ asset('/backend-assets/forthebuilders/images/RU.png') }}`
        let en = `{{ asset('/backend-assets/forthebuilders/images/GB.png') }}`

        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdownMenyApplyUzbFlag a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    switch (locale) {
                        case 'uz':
                            $('#selected_language').attr('src', uz)
                            break;
                        case 'en':
                            $('#selected_language').attr('src', en)
                            break;
                        case 'ru':
                            $('#selected_language').attr('src', ru)
                            break;
                    }
                    $.post('{{ route('language.change') }}', {
                        _token: '{{ csrf_token() }}',
                        locale: locale
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }
    })
</script>
<script>
    let dropdownMenuButton2 = document.getElementById('dropdownMenuButton2')
    let dropdownMenuButton = document.getElementById('dropdownMenuButton')
    let language_flag = document.getElementById('language_flag')
    let notificate_ = document.getElementById('notificate_')
    dropdownMenuButton2.addEventListener('click', function() {
        if (notificate_.classList.contains('display-none')) {
            notificate_.classList.remove('display-none')
        } else {
            notificate_.classList.add('display-none')
        }
    });
    dropdownMenuButton.addEventListener('click', function() {
        if (language_flag.classList.contains('display-none')) {
            language_flag.classList.remove('display-none')
        } else {
            language_flag.classList.add('display-none')
        }
    });

    $(document).on('click','.clear_notification',function(){
        
        $.ajax({
            url: `/forthebuilder/clear-notification/`,
            type: 'GET',
            success: function(data) {
                if(data == true){
                   window.location.reload()
                }
            }
        });
        
    })
</script>

