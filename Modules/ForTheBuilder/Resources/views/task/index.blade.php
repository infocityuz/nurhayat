@php
    $pathInfo = explode('/', $_SERVER['REQUEST_URI']);
    $path = end($pathInfo);
    $indexCheck = 'checked';
    $indexFilterCheck = '';
    if ($path == 'filter-index') {
        $indexCheck = '';
        $indexFilterCheck = 'checked';
    }
    use Modules\ForTheBuilder\Entities\Constants;
@endphp

{{-- @extends('forthebuilder::task.extra') --}}
@extends('forthebuilder::layouts.forthebuilder')
<link rel="stylesheet" href="{{asset('/backend-assets/forthebuilders/datatables/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/backend-assets/forthebuilders/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/backend-assets/forthebuilders/toastr/css/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('/backend-assets/forthebuilders/select/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('/backend-assets/forthebuilders/fullcalendar/main.css')}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" integrity="sha512-aEe/ZxePawj0+G2R+AaIxgrQuKT68I28qh+wgLrcAJOz3rxCP+TwrK5SPN+E5I+1IQjNtcfvb96HDagwrKRdBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .fc-toolbar-chunk{
        display: flex;
    }
    .fc-daygrid-event{
        display: flex;
        justify-content: center;
    }
    .calendar-text-z{
        margin-bottom: 0px;
        padding: 6px 0px;
    }
    .fc-prev-button, .fc-next-button{
        background-color: white !important;
        border: 0px !important;
    }
    .fc-prev-button span, .fc-next-button span{
        color: black;
    }
    .fc-toolbar-chunk h2{
        background-color: #94B2EB;
        padding: 20px 80px;
        border-radius: 18px;
        color: white;
    }
    .fc-today-button, .fc-dayGridMonth-button, .fc-timeGridWeek-button, .fc-timeGridDay-button{
        background-color: #94B2EB !important;
        border: 0px !important;
    }
    .fc-toolbar-chunk .btn-group .active{
        background-color: #44B2EB !important;
    }
    .fc-daygrid-day{
        background-color: rgba(0, 0, 0, 0);
    }
    .fc-daygrid-day-events a{
        border-radius: 24px;
    }
    .fc-daygrid-day-events{
        z-index: -1;
    }

    .fc-col-header-cell{
        background-color: #94B2EB;
        padding:14px 24px !important;
        color:white;
    }

    .task-calendar{
        display: flex;
        height: 80px;
    }
    .task-calendar .tasks{
        display: flex;
        align-items: flex-start;
    }
    .task-calendar .calendar{
        display: flex;
        align-items: flex-end;
    }
    .modal-header .row{
        width:100%;
    }
    .arrow, .plus{
        background-color: white;
        transition: 1s;
        color: black;
    }
    .arrow{
        height: 50px;
        padding: 0px 20px;
        display: flex;
        align-items: center;
        border-radius: 30px;
        margin-right: 40px;
    }
    .arrow:hover{
        transform: scale(1.2);
        box-shadow: 1px 1px 6px silver;
        color: black;
    }
    .plus{
        height: 40px;
        padding: 0px 14px;
        display: flex;
        align-items: center;
        border-radius:20px;
        margin-left: 40px;
    }
    .plus:hover{
        transform: scale(1.2);
        box-shadow: 1px 1px 6px silver;
        color: black;
    }
    .calendar_type{
        display: flex;
        padding: 10px;
        border-radius: 18px;
        background-color: white;
        margin: 0px 24px 6px 0px;
    }
    .calendar_type label{
        margin:0px 0px 0px 7px;
    }
    .add-task{
        display: flex;
        height: 64px;
        padding: 14px;
    }
    .modal-calendar-buttons{
        display: flex;
    }
    .modal-calendar-buttons input{
        margin-right: 14px;
    }
    .display-none{
        display: none;
    }

    #day_calendar_body{
        width: 100%;
        height: auto;
        flex-direction: column;
    }

    .fc-toolbar.fc-header-toolbar{
        padding: 0px 14px;
    }
    .add-task>img{
        height: 16px;
        margin: 4px 4px 0px 4px;

    }
    .choise-date{
        width: 120px;
        margin-bottom: 14px;
        border-radius: 4px;
        border:0px;
    }
    #deal_id{
        z-index: 101;
    }
    .search_deal{
        border: 1px solid silver;
    }
    .opacity_button{
        opacity: 0.6;
    }
    .select2-container--default{
        width: 100% !important;
    }
    .bootstrap-datetimepicker-widget{
        position: absolute !important;
        top: 0 !important;
        z-index: 9999 !important;
    }
</style>
@section('content')

    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <h2 class="panelUprText">{{ translate('Tasks') }}</h2>
                    {{-- <button data-toggle="modal" data-target="#exampleModal" type="button" href="./sozdatZadachi.html"
                        class="plus2">+</button> --}}
                    <a href="{{ route('forthebuilder.clients.calendar') }}"
                        class="kalendarButton">{{ translate('Calendar') }}</a>
                    <button class="plus2" data-toggle="modal" data-target="#exampleModal">+</button>
                </div>
            </div>

            @if (Auth::user()->role_id != Constants::MANAGER)
                <div class="zadachiFlexCenter">
                    <div class="d-flex">
                        <button class="zadachiFlexVse">
                            <input class="zadachiFlexInputCheck" id="filter_all" {{ $indexCheck }} type="radio"
                                name="filter" value='0'>
                            <label for="filter_all" style="margin-bottom: 0;">{{ translate('All') }}</label>
                            {{-- Все --}}
                        </button>
                        <button class="zadachiFlexMoiZadachi">
                            <input class="zadachiFlexInputCheck" id="filter_my_tasks" {{ $indexFilterCheck }} type="radio"
                                name="filter" value='1'>
                            <label for="filter_my_tasks" style="margin-bottom: 0;">{{ translate('My tasks') }}</label>
                            {{-- Мои задачи --}}
                        </button>
                    </div>
                </div>
            @endif
           

            <div class="zadachiData">
                <div class="zadachiJustify" style="width: 100%; display: flex; justify-content: space-around; ">
                    @empty(!$arr)
                        @php
                            $i = true;
                            $zadachi = 'zadachiRed';
                            $zadachiH3 = 'zadachiRedH3';
                            $zadachiP = 'zadachiRedP';
                            $style = 'margin-left: 2%';
                        @endphp
                        @foreach ($arr as $key => $value)
                            <div style="{{ $style }}">
                                <div class="{{ $zadachi }} zadachiMarginRight">
                                    <h3 class="{{ $zadachiH3 }}">{{ $key }}</h3>
                                    <p class="{{ $zadachiP }}">{{ translate('All tasks') }}: {{ count($value) }}</p>
                                </div>
                                @php
                                    $zadachiH3 = 'zadachiBlueH3';
                                    $zadachi = 'zadachiBlue';
                                    $zadachiP = 'zadachiBlueP';
                                @endphp
                                <div style="height: 550px; overflow-y: auto; overflow-x: hidden;">
                                    @if (isset($value) && !empty($value))
                                        @foreach ($value as $val)
                                            <a href="{{ route('forthebuilder.clients.show', [$val['client_id'], '0', '0']) }}"
                                                class="zadachiBlueName zadachiMarginRight">
                                                <p class="zadachiSenderName">{{ translate('Responsible') }} :
                                                    <b>{{ $val['responsible'] }}</b>
                                                </p>
                                                <h3 class="zadachiBlueNameZ">{{ $val['client'] }} <br>
                                                    {{ $val['client_middle_name'] }}</h3>
                                                <p class="zadachiBlueTime">
                                                    {{ translate('Date') . ': ' . date('d.m.Y', strtotime($val['day'])) }}<br>
                                                    {{ translate('Time') . ': ' . date('H:i:s', strtotime($val['time'])) }}</p>
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @php
                                if ($i) {
                                    $i = false;
                                    $style = '';
                                }
                            @endphp
                        @endforeach
                    @endempty
                </div>

                <div class="zadachiBorder1221">

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
                            <button class="modalVideystvitelnoDa" data-dismiss="modal">Да</button>
                            <button class="modalVideystvitelnoNet" data-dismiss="modal">Нет</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('forthebuilder.task.calendar_store') }}" method="POST" enctype="multipart/form-data" id="chees-modal"> @csrf @method('POST')
                        <div>
                            <select name="deal_id" class="form-control select2" id="deal_select" data-placeholder="{{ translate('Choose client') }}">
                                <option value=""></option>
                                @empty(!$deals)
                                    @foreach ($deals as $deal)
                                        @if(isset($deal->client))
                                            <option value="{{$deal->id}}">
                                                {{$deal->client->first_name.' '.$deal->client->last_name.' '.$deal->client->middle_name}}
                                            </option>
                                        @endif
                                    @endforeach
                                @endempty
                            </select>
                            <br>
                            <br>
                            <select name="performer_id" id="performer_id"  class="form-control select2 @error('performer_id') is-invalid error-data-input @enderror" data-placeholder="Select a state">
                                <option value=""></option>
                                @empty(!$users)
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                                {{ Auth::user()->id == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }}</option>
                                    @endforeach
                                @endempty
                            </select>
                            <br>
                            <br>
                            <select name="type" id="type" data-placeholder="{{ translate('Call') }}" class="form-control select2 @error('type') is-invalid error-data-input @enderror">
                                <option value="Связаться">{{translate('Call')}}</option>
                                <option value="Встреча">{{translate('Meeting')}}</option>
                            </select>
                            <br>
                            <br>
                            <input name="title" type="text" class="form-control" placeholder="{{ translate('Description')}}">
                            <br>
                            <input name="task_date_2" id="task_date2" type="text" class="form-control @error('task_date') error-data-input is-invalid @enderror" value="{{ date('d.m.Y H:i') }}">
                            
                        </div>
                        <br>


                        <div class="modal-footer p-0">
                            <button type="submit" class="btn opacity_button PostavitButton">{{ translate('Put') }}</button>
                            <button class="btn OtmenitButton" data-dismiss="modal" aria-label="Close">{{ translate('Cancell') }}</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>



<script>
    let page_name = 'tasks';
</script>

<script src="{{ asset('/backend-assets/forthebuilders/javascript/jquery-3.6.1.js')}}" type="text/javascript"></script>
<script src="{{asset('/backend-assets/forthebuilders/select/js/select2.min.js')}}"></script>
<script src="{{asset('/backend-assets/forthebuilders/toastr/js/toastr.min.js')}}"></script>
<script src="{{asset('/backend-assets/forthebuilders/moment/js/moment.min.js')}}"></script>
<script defer src="{{asset('/backend-assets/forthebuilders/fullcalendar/main.js')}}"></script>
<script src='{{asset('/backend-assets/plugins/fullcalendar/locales/ru.js')}}'></script>
<script src='{{asset('/backend-assets/js/datetimepicker.min.js')}}'></script>

<script>
    $(function(){
         $('#task_date2').datetimepicker({
            "allowInputToggle": true,
            "showClose": false,
            "showClear": true,
            "showTodayButton": true,
            "format": "DD.MM.YYYY HH:mm",
        });
        $('.select2').select2({
            dropdownParent: $("#exampleModal")
        })
        $('.performer').select2({
            // placeholder: "Select a state",
            // dropdownParent: $("#exampleModal"),  
        })
    })
</script>

<script defer>
    
    let calling_or_meeting = document.getElementById('calling_or_meeting')
    $(document).ready(function () {
        let sessionWarning = "{{session('warning')}}";
        if(sessionWarning){
            toastr.success(sessionWarning)
        }
        let sessionError = "{{session('error')}}";
        if(sessionError){
            toastr.success(sessionError)
        }
        $(document).on('click', '.choise-manager', function(e) {
            e.preventDefault();
            // $('select[name="performer_id"]').chosen();
            // $('#performer_id').show();
            var se = $("#performer_id");
            se.removeClass('d-none');
            se[0].size = 10;
        })
        $('.PostavitButton').on('click', function (e){
            var deal = $('#deal_select').val()
            if (deal == '') {
                e.preventDefault()    
            }
            
        });

        $(document).on('change','#deal_select',function(){
            $('.PostavitButton').removeClass('opacity_button')
        })

        if($('#selected_deal_id').val() != ""){
            if($('.PostavitButton').hasClass('opacity_button')){
                $('.PostavitButton').removeClass('opacity_button')
            }
        }
        $(document).on('keyup', '.select_deal', function(e) {
            var keyupText = $(this).val()
            $('#selected_deal_id').val("")
            $('.search_deal').each(function() {
                if (keyupText == '') {
                    if(!$('.PostavitButton').hasClass('opacity_button')){
                        $('.PostavitButton').addClass('opacity_button')
                    }
                    $(this).addClass('d-none')
                    $('.selectMainDeal').addClass('d-none')
                } else {
                    if($('.PostavitButton').hasClass('opacity_button')){
                        $('.PostavitButton').removeClass('opacity_button')
                    }
                    var myText = $(this).text();
                    if (myText.toLowerCase().includes(keyupText.toLowerCase())) {
                        $('.selectMainDeal').removeClass('d-none')
                        $(this).removeClass('d-none')
                    } else {
                        $(this).addClass('d-none')
                    }
                }
            });
        })
        $('#selected_deal_id').on('change', function (e) {
            if($(this).val == '') {
                if (!$('.PostavitButton').hasClass('opacity_button')) {
                    $('.PostavitButton').addClass('opacity_button')
                }
            }else{
                if($('.PostavitButton').hasClass('opacity_button')){
                    $('.PostavitButton').removeClass('opacity_button')
                }
            }
        })
        $('.search_deal').on('click', function () {
            // console.log()
            $('#selected_deal_id').val($(this).val())
            $('.select_deal').val($(this).text())
            if(!$('.selectMainDeal').hasClass('d-none')){
                $('.selectMainDeal').addClass('d-none')
            }
            if($('.PostavitButton').hasClass('d-none')){
                $('.PostavitButton').removeClass('d-none')
            }
        })
        $("#performer_id").on("click", function() {
            var se = $(this);
            se.addClass('d-none');
            $('.choise-manager').text($('#performer_id option:selected').text())
        });
        $(function () {
            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {
                    // create an Event Object (https://fullcalendar.io/docs/event-object)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    // $(this).draggable({
                    //     zIndex        : 1070,
                    //     revert        : true, // will cause the event to go back to its
                    //     revertDuration: 0  //  original position after the drag
                    // })
                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var full_date = new Date()
            var d    = full_date.getDate(),
                m    = full_date.getMonth()+1,
                y    = full_date.getFullYear()
            var date = y+'-'+m+'-'+d;
            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------
            let modal_content = false;
            let day_calendar_body = false
            let models = {'id':[], 'href':[], 'name':[], 'surname':[], 'email':[], 'task_date':[], 'type':[], 'user_name':[], 'user_surname':[], 'created_at':[], 'middlename':[]};
            let my_models = {'id':[], 'href':[], 'name':[], 'surname':[], 'email':[], 'task_date':[], 'type':[], 'user_name':[], 'user_surname':[], 'created_at':[], 'middlename':[]};
            let responsible = '{{translate('Responsible')}}'
            @foreach($models as $model_)
            models.id.push("{{$model_->id}}")
            models.href.push("{{route('forthebuilder.clients.show', [$model_->deal->client->id??'0', '0', '0'])}}")
            models.name.push("{{(($model_->performer) ? $model_->performer->first_name : '')}}")
            models.surname.push("{{(($model_->performer) ? $model_->performer->last_name : '')}}")
            models.middlename.push("{{(($model_->performer) ? $model_->performer->middle_name : '')}}")
            models.created_at.push("{{$model_->created_at}}")
            models.email.push("{{(($model_->performer) ? $model_->performer->email : '')}}")
            models.task_date.push("{{$model_->task_date}}")
            models.type.push("{{$model_->type}}")
            models.user_name.push("{{$model_->user->first_name??''}}")
            models.user_surname.push("{{$model_->user->last_name??''}}")
            @endforeach
            @foreach($my_models as $my_model_)
            my_models.id.push("{{$my_model_->id}}")
            models.href.push("{{route('forthebuilder.clients.show', [$my_model_->deal->client->id??'0', '0', '0'])}}")
            my_models.name.push("{{$my_model_->performer->first_name}}")
            my_models.surname.push("{{$my_model_->performer->last_name}}")
            my_models.middlename.push("{{$my_model_->performer->middle_name}}")
            my_models.created_at.push("{{$my_model_->created_at}}")
            my_models.email.push("{{$my_model_->performer->email}}")
            my_models.task_date.push("{{$my_model_->task_date}}")
            my_models.type.push("{{$my_model_->type}}")
            my_models.user_name.push("{{$my_model_->user->first_name??''}}")
            my_models.user_surname.push("{{$my_model_->user->lastname??''}}")
            @endforeach
            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
                    };
                }
            });

            var calendar = new Calendar(calendarEl, {
                locale: 'ru',
                headerToolbar: {
                    left  : 'today',
                    center: 'prev title next',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDays){
                    $('#day_calendar_body').html("")
                    let calendar_class = '';
                    let calendar_class_1 = '';
                    day_calendar_body = false;
                    models.task_date.forEach(myFunction);
                    $('.fc-daygrid-day').attr('data-toggle', 'modal')
                    $('.fc-daygrid-day').attr('data-target', '#day_calendar')
                    $('.fc-daygrid-day').attr('id', 'day_calendar_modal')
                    $('#calendar_task_date').text(start.startStr)
                    if(parseInt(Date.parse(date)) >= parseInt(Date.parse(start.startStr))+86400){
                        if(!$('#calendar_task_date').hasClass('modalKalendarDate')){
                            $('#calendar_task_date').addClass('modalKalendarDate')
                            $('#calendar_task_date').removeClass('modalKalendarDateBlue')
                        }
                    }else{
                        if(!$('#calendar_task_date').hasClass('modalKalendarDateBlue')) {
                            $('#calendar_task_date').addClass('modalKalendarDateBlue')
                            $('#calendar_task_date').removeClass('modalKalendarDate')
                        }
                    }
                    function myFunction(item, index){
                        if(item == start.startStr){
                            day_calendar_body = true;
                            if(parseInt(Date.parse(date)) >= parseInt(Date.parse(start.startStr))+86400){
                                $('#day_calendar_body').append(`
                                    <a class='d-flex justify-content-between modalKalendarDate kalendarModalBody' href='${models.href[index]}'>
                                        <div class="modalImyaKalendar">
                                            ${models.name[index]}&nbsp;${models.surname[index]}&nbsp;${models.middlename[index]}<br>
                                            ${models.created_at[index]}&nbsp;${models.type[index]}
                                        </div>
                                        <div class="modalDataKalendar">
                                            ${responsible}:<br>
                                            <b>${models.user_name[index]}&nbsp;${models.user_surname[index]}</b>
                                        </div>
                                    </a><br>`)
                            }else{
                                $('#day_calendar_body').append(`
                                    <a class='d-flex justify-content-between modalKalendarDateBlue kalendarModalBodyBlue' href='${models.href[index]}'>
                                        <div class="modalImyaKalendar">
                                            ${models.name[index]}&nbsp;${models.surname[index]}&nbsp;${models.middlename[index]}<br>
                                            ${models.created_at[index]}&nbsp;${models.type[index]}
                                        </div>
                                        <div class="modalDataKalendar">
                                            ${responsible}:<br>
                                            <b>${models.user_name[index]}&nbsp;${models.user_surname[index]}</b>
                                        </div>
                                    </a><br>`)
                            }
                        }
                    }
                    if(day_calendar_body == false){
                        $('#day_calendar_body').append(`
                            <div class="d-flex justify-content-center">
                                <h3 class="modalContentCalendarNet">{{translate('No task for today')}}</h3>
                            </div>`)
                    }
                },
                events: [
                        @foreach($models as $model)
                        @php $status = '';
                            if(strtotime("now") >= strtotime($model->task_date)+86400){
                                $back_color = '#FF9D9D';
                            }else{
                                $back_color = '#94B2EB';
                            }
                        @endphp
                    {
                        {{--url: '/forthebuilder/leads/show/{{$model->id}}',--}}
                        url: '#',
                        title : "{{(($model->performer) ? $model->performer->first_name : '')}} <br> {{$model->status}} {{$model->type}}",
                        customHtml : "<span>{{(($model->performer) ? $model->performer->first_name : '').' '}}{{(($model->performer) ? $model->performer->last_name : '')}} <br> {{(($model->user) ? $model->user->first_name : '')}}</span>",
                        start          : '{{$model->task_date}}',
                        backgroundColor: '{{$back_color}}',
                        borderColor    : '{{$back_color}}', //red
                        padding: '8px',
                        allDay         : true,
                        textEscape: true
                    },
                    @endforeach
                ],
                eventContent: function(eventInfo) {
                    return { html: eventInfo.event.extendedProps.customHtml }
                },
                editable  : false,
                droppable : false,
                // this allows things to be dropped onto the calendar !!!
                drop      : function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }
            });

            // calendar types
            calendar.render();

            $('.fc-daygrid-day').attr('data-toggle', 'modal')
            $('.fc-daygrid-day').attr('data-target', '#day_calendar')
            $('.fc-daygrid-day').attr('id', 'day_calendar_modal')

            var my_calendar = new Calendar(calendarEl, {
                locale: 'ru',
                headerToolbar: {
                    left  : 'today',
                    center: 'prev title next',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDays){
                    $('#day_calendar_body').html("")
                    let calendar_class = '';
                    let calendar_class_1 = '';
                    day_calendar_body = false;
                    my_models.task_date.forEach(myFunction);
                    $('.fc-daygrid-day').attr('data-toggle', 'modal')
                    $('.fc-daygrid-day').attr('data-target', '#day_calendar')
                    $('.fc-daygrid-day').attr('id', 'day_calendar_modal')
                    $('#calendar_task_date').text(start.startStr)
                    if(parseInt(Date.parse(date)) >= parseInt(Date.parse(start.startStr))+86400){
                        if(!$('#calendar_task_date').hasClass('modalKalendarDate')){
                            $('#calendar_task_date').addClass('modalKalendarDate')
                            $('#calendar_task_date').removeClass('modalKalendarDateBlue')
                        }
                    }else{
                        if(!$('#calendar_task_date').hasClass('modalKalendarDateBlue')) {
                            $('#calendar_task_date').addClass('modalKalendarDateBlue')
                            $('#calendar_task_date').removeClass('modalKalendarDate')
                        }
                    }
                    function myFunction(item, index){
                        if(item == start.startStr){
                            day_calendar_body = true;
                            if(parseInt(Date.parse(date)) >= parseInt(Date.parse(start.startStr))+86400){
                                $('#day_calendar_body').append(`
                                    <a class='d-flex justify-content-between modalKalendarDate kalendarModalBody' href='${my_models.href[index]}'>
                                        <div class="modalImyaKalendar">
                                            ${my_models.name[index]}&nbsp;${my_models.surname[index]}&nbsp;${my_models.middlename[index]}<br>
                                            ${my_models.created_at[index]}&nbsp;${my_models.type[index]}
                                        </div>
                                        <div class="modalDataKalendar">
                                            Ответственный:<br>
                                            <b>${my_models.user_name[index]}&nbsp;${my_models.user_surname[index]}</b>
                                        </div>
                                    </a><br>`)
                            }else{
                                $('#day_calendar_body').append(`
                                    <a class='d-flex justify-content-between modalKalendarDateBlue kalendarModalBodyBlue' href='${my_models.href[index]}'>
                                        <div class="modalImyaKalendar">
                                            ${my_models.name[index]}&nbsp;${my_models.surname[index]}&nbsp;${my_models.middlename[index]}<br>
                                            ${my_models.created_at[index]}&nbsp;${my_models.type[index]}
                                        </div>
                                        <div class="modalDataKalendar">
                                            Ответственный:<br>
                                            <b>${my_models.user_name[index]}&nbsp;${my_models.user_surname[index]}</b>
                                        </div>
                                    </a><br>`)
                            }
                        }
                    }
                    if(day_calendar_body == false){
                        $('#day_calendar_body').append(`
                                <div class="d-flex justify-content-center">
                                    <h3 class="modalContentCalendarNet">{{translate('No task for today')}}</h3>
                                </div>`)
                    }
                },

                events: [
                        @foreach($my_models as $my_model)
                        @php $status = '';
                                if(strtotime("now") >= strtotime($my_model->task_date)+86400){
                                    $back_color = '#FF9D9D';
                                }else{
                                    $back_color = '#94B2EB';
                                }
                        @endphp
                    {
                        {{--url: '/forthebuilder/leads/show/{{$model->id}}',--}}
                        url: '#',
                        title : "{{$my_model->performer->first_name}} <br> {{$my_model->status}} {{$my_model->type}}",
                        customHtml : "<span>{{$my_model->performer->first_name.' '}}{{$my_model->performer->last_name}} <br> {{$my_model->user->first_name}}</span>",
                        start          : '{{$my_model->task_date}}',
                        backgroundColor: '{{$back_color}}',
                        borderColor    : '{{$back_color}}', //red
                        padding: '8px',
                        allDay         : true,
                        textEscape: true
                    },
                    @endforeach
                ],
                eventContent: function(eventInfo) {
                    return { html: eventInfo.event.extendedProps.customHtml }
                },
                editable  : false,
                droppable : false,
                // this allows things to be dropped onto the calendar !!!
                drop      : function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }
            });
            $('#all').prop('checked', true)
            $('#my_tasks').prop('checked', false)
            $('#all').on('change', function (){
                if($(this).is(':checked')) {
                    $('#my_tasks').prop('checked', false)
                    $('#calendar').html("")
                    calendar.render();
                }
            });
            $('#my_tasks').on('change', function (){
                if($(this).is(':checked')) {
                    $('#all').prop('checked', false)
                    $('#calendar').html("")
                    my_calendar.render();
                }
            });

            // adding task to leads
            // $(document).on('click', '.choise-date', function(e) {
            //     e.preventDefault();
            //     $("#task_date").datetimepicker("show");
            // })
            $(document).on('click', '.choise-manager', function(e) {
                e.preventDefault();

            })

            // $("#deal_id").on("click", function() {
            //
            //     $('.choise-manager').text($('#deal_id option:selected').text())
            // });

            let call_png = '{{asset('/backend-assets/forthebuilders/images/Call.png')}}'
            let meeting_png = '{{asset('/backend-assets/forthebuilders/images/meeting.png')}}'
            let calling_or_meeting = document.getElementById('calling_or_meeting')
            $(document).on('click', '.choise-phone', function(e) {
                e.preventDefault();
                var se = $("#type");
                se.removeClass('d-none');
                se[0].size = 2;
            })

            $("#type").on("click", function() {
                var se = $(this);
                se.addClass('d-none');
                console.log($('#type option:selected').text())
                if($('#type option:selected').text() == 'Meeting'){
                    calling_or_meeting.setAttribute('src', meeting_png)
                }
                if($('#type option:selected').text() == 'Call'){
                    calling_or_meeting.setAttribute('src', call_png)
                }
                $('.choise-phone').text($('#type option:selected').text())
            });

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            // Color chooser button
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                // Save color
                currColor = $(this).css('color')
                // Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color'    : currColor
                })
            })

            $('#add-new-event').click(function (e) {
                e.preventDefault()
                // Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                // Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color'    : currColor,
                    'color'           : '#fff'
                }).addClass('external-event')
                event.text(val)
                $('#external-events').prepend(event)

                // Add draggable funtionality
                ini_events(event)

                // Remove event from text input
                $('#new-event').val('')
            })
        })
        $('#day_calendar_modal').on('click', function (){

        });

        
    });
</script>
@endsection
