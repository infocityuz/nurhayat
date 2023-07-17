@section('title') {{__('locale.apartment_sale')}}  @endsection
@extends('forthebuilder::layouts.forthebuilder')



@section('title')  {{translate('User show')}} @endsection
<style>
#chat_area
{
	min-height: 300px;
	/*overflow-y: scroll*/;
}

#chat_history
{
    width: 100%;
	min-height: 200px; 
	max-height: 230px; 
	overflow-y: scroll; 
	margin-bottom:16px; 
	background-color: #ffffff;
	padding: 16px;
}

#user_list
{
	min-height: 500px; 
	max-height: 750px; 
	overflow-y: scroll;
}
.sender_chat
{
    background-color: #94B2EB !important;
    border-radius: 20px !important;
}
.recever_chat
{
    background-color: #E8F0FF !important;
    border-radius: 20px !important;
}
.content_center {
  display: flex;
  justify-content: center !important;
  align-items: center !important; 

}
#CurrentDayToday {
    max-width: 100% !important;
    margin-top: 0 !important;
}


</style>
@section('content')
    <div class="d-flex aad">
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            

            <div class="container-fluid mb-3">
                <div class="row m-0 align-items-center">
                    
                    <div class="col-8 py-4">
                        <h2 class="m-0 panelUprText profileTextProfile">{{translate('Profile')}}</h2>
                    </div>
                    <div class="col-4">
                        <div class="ml-auto" id="CurrentDayToday">
                            {{translate('Date')}}: 
                            <input type="text" class="ml-2 form-control daterange" value="{{ date('01.m.Y').' - '.date('t.m.Y') }}">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="profileData p-2">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                @if(isset($model->id))
                                    @php
                                        $sms_avatar = public_path('uploads/user/'.$model->id.'/s_'.$model->avatar);
                                    @endphp
                                    @if(file_exists($sms_avatar))
                                        <img class="profileImageData mr-3"
                                             src="{{ asset('uploads/user/'.$model->id.'/s_'.$model->avatar) }}"
                                             alt="">
                                    @else
                                        @php
                                            $gender_img = 'men.png';
                                            if ($model->gender == 2) {
                                                $gender_img = 'women.png';
                                            }
                                        @endphp
                                        <img class="profileImageData mr-3" src="{{ asset('/backend-assets/img/'.$gender_img) }}" alt="">
                                    @endif
                                @else
                                    <img class="profileImageData mr-3" src="{{asset('/backend-assets/img/men.png')}}" alt="">
                                @endif
                            </div>

                            <div class="h-100 d-flex flex-column justify-content-center">
                                <h3 class="profileNameData">{{$model->first_name.' '}} {{$model->last_name.' '}} {{$model->middle_name}}</h3>
                                <p class="profileOtherData">{{$model->role->name}}</p>
                                @php
                                    if(isset($model->birth_date)){
                                        $birth_date_array = explode(' ', $model->birth_date);
                                        $now_time = strtotime('now');
                                        $birth_time = strtotime($model->birth_date);
                                        $month = date('m', ($now_time));
                                        $day = date('d', ($now_time));
                                        $birth_month = date('m', ($birth_time));
                                        $birth_date = date('d', ($birth_time));
                                        $year = date('Y', ($now_time));
                                        $birth_year = date('Y', ($birth_time));
                                        $year_old = 0;
                                        if($year > $birth_year){
                                            $year_old = $year - $birth_year - 1;
                                            if($month > $birth_month){
                                                $year_old = $year_old +1;
                                            }elseif($month == $birth_month){
                                                if($day >= $birth_date){
                                                    $year_old = $year_old +1;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @if(isset($year_old))
                                    <p class="profileOtherData">{{$year_old.' '.translate('years old')}}</p>
                                @endif
                            </div>

                            <div class="ml-auto h-100 d-flex flex-column justify-content-end align-items-end">
                                @if(isset($model->phone_number))
                                    <p class="profileOtherData mt-0 mb-2">{{'+998 '.$model->phone_number}}</p>
                                @endif
                                <p class="profileOtherData mt-0 mb-2">{{$model->email}}</p>
                                <div class="buttonProfileEditBlue">
                                    <a href="{{route('forthebuilder.user.edit',$model->id)}}">
                                        <img src="{{asset('/backend-assets/forthebuilders/images/edit.png')}}" alt="Edit">
                                    </a>
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-sm-5">
                        <div class="chartGreenMoyiZadachi">
                            <h5 class="MoyiZadachiTextCartGreenH5">{{translate('Tasks')}}</h5>
                            <div class="d-flex">
                                <div class="d-flex justify-content-center">
                                    <canvas class="chartGreenImageOne" id="circleCharts">
                                    </canvas>
                                </div>
                                <div>
                                    <div style="width: 200px; margin-top: 60px;" class="d-flex mobileWidthLg justify-content-between ovalNameRadius">
                                        <p class="greenChartZadachiName">{{translate('Completed tasks')}}</p>
                                        <div class="greenChartGreenRadius"></div>
                                    </div>
                                    <div style="width: 200px; margin-top: -15px;" class="d-flex mobileWidthLg justify-content-between">
                                        <p class="greenChartZadachiName">{{translate('Tasks not completed')}}</p>
                                        <div class="greenChartRedRadius"></div>
                                    </div>      
                                </div>
                                
                            </div>
                            
                              
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="d-flex">
                            @if($user->id == $model->id)
                                <h2 class="panelUprText m-0 mb-2">{{translate('My stats')}}</h2>
                            @else
                                <h2 class="panelUprText m-0 mb-2">{{translate('Stats')}}</h2>
                            @endif
                        </div>            
                    </div>
                    
                </div>

                <div class="row mb-5">
                    <div class="col-3">
                        <div class="novieLidi col lidiMarginRight2 w-100">
                            <h3>{{translate('New Clients')}}</h3>
                            <h2 class="lidi25">{{$data['new_clients']}}</h2>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="novieLidi col lidiMarginRight2 w-100">
                            <h3>{{translate('For a negotiation')}}</h3>
                            <h2>{{$data['in_negotiations']}}</h2>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="novieLidi col lidiMarginRight2 w-100">
                            <h3>{{translate('Making a deal')}}</h3>
                            <h2>{{$data['make_deal']}}</h2>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="zadachiLidi col lidiMarginRight2 w-100">
                            <h3>{{translate('Tasks')}}</h3>
                            <h2>{{$data['full_task']}}</h2>
                            <hr>
                            <p>{{translate('For today')}} : {{$data['today']}}<br>
                               {{translate('For tomorrow')}} : {{$data['tomorrow']}}<br>
                               {{translate('For a week')}} : {{$data['week']}}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-7">
                        <div class="chartDiv bigProdajZaMesyach lidiMarginRight2 m-0">
                            <h2>{{translate('Sales per month')}}</h2>
                            <div>
                                <canvas id="myChart_dash" width="400" height="150">
                                    <p>Hello Fallback World</p>
                                </canvas>
                              </div>
                        </div>
                        <br>
                        <div class="chartDiv lidiMarginRight2 m-0" >
                            <h2 class="chart1Individual ">{{translate('Individual sales managers')}}</h2>
                            <div>
                                <canvas id="myChart_2_dash" width="400" height="150">
                                    <p>Hello Fallback World</p>
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="ovalChart m-0 w-100">
                            <h2>{{translate('Individual sales')}}</h2>
                            <div id="piechart" style="width: 430px; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div_id" data-id="@php echo $id; @endphp"></div>
    <div id="lang_app" lang="{{ translate('Apply') }}"></div>
    <div id="lang_cancel" lang="{{ translate('Cancel') }}"></div>
    <div id="lang_months" lang="{{ $months }}"></div>
    <div id="line_months" lang="{{ $line_month }}"></div>
    <div id="no_data" data-text="{{ translate('No data') }}"></div>
    <div id="core_chart" data-arr="{{ $data['core_chart'] }}"></div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        const ctx_3 = document.getElementById('myChart_dash');
            var month_day = <?php echo json_encode($data['month_day']); ?>;
            var price_day_array = <?php echo json_encode($data['price_day_array']); ?>;
            var lang_months = $('#lang_months').attr('lang');
            var line_months = $('#line_months').attr('lang');

            line_months = line_months.split(",");

            new Chart(ctx_3, {
            type: 'line',
            data: {
                labels:month_day,
                datasets: [{
                label: '',
                data:price_day_array,
                borderWidth: 2,
                barPercentage:0.1
                }]
            },
            options: {
                scales: {
                y: {
                    beginAtZero: true
                }
                }
            }
        });

        const ctx_2 = document.getElementById('myChart_2_dash');
        var users = <?php echo json_encode($data['month_sales_price']); ?>;

        new Chart(ctx_2, {
            type: 'line',
            data: {
                labels: line_months,
                datasets: [{
                label: '',
                data:users,
                borderWidth: 2,
                barPercentage:0.1
                }]
            },
            options: {
                scales: {
                y: {
                    beginAtZero: true
                }
                }
            }
        });

        

        //  chart3
        const core_chart = $('#core_chart').attr('data-arr')
        if (core_chart != '') {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart_1);
            function drawChart_1() {
                var data = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  <?php echo $data['core_chart']; ?>
                  // [$('#no_data').attr('data-text'), 1]
                ]);

                var options = {
                    title: '',
                    width: 400,
                    height: 400,
                    slices: {0: {color: '#FF9D9D'}, 1:{color: '#F7FF9D'}, 2:{color: '#B1FF9D'}},
                    legend: { position: 'bottom'},
                    bars: 'vertical', // Required for Material Bar Charts.
                    axes: {
                        x: {
                            0: { side: 'button', label: 'Percentage'} // Top x-axis.
                        }
                    },
                    bar: { groupWidth: "90%" }
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }    
        }
        else{
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart_1);
            function drawChart_1() {
                var data = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  <?php echo $data['core_chart']; ?>
                  [$('#no_data').attr('data-text'), 1]
                ]);

                var options = {
                    title: '',
                    width: 400,
                    height: 400,
                    slices: {0: {color: '#FF9D9D'}, 1:{color: '#F7FF9D'}, 2:{color: '#B1FF9D'}},
                    legend: { position: 'bottom'},
                    bars: 'vertical', // Required for Material Bar Charts.
                    axes: {
                        x: {
                            0: { side: 'button', label: 'Percentage'} // Top x-axis.
                        }
                    },
                    bar: { groupWidth: "90%" }
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }   
        }
    </script>

    
    <script>
        let page_name = 'user';
        const ctx = document.getElementById('circleCharts');
            let circleCharts = document.getElementById("circleCharts").getContext('2d');
            // let graphicCharts = document.getElementById("graphicCharts").getContext('2d');
            let tasks = '{{$tasks}}';
            let taskdate = [];
            let taskcount = [];
            let m = [];
            @if(!empty($task_count))
                @foreach($task_count['task_date'] as $task_date_)
                    taskdate.push('{{$task_date_}}')
                @endforeach
                @foreach($task_count['count'] as $task_count_)
                    taskcount.push('{{$task_count_}}')
                @endforeach
            @endif
            @if(!empty($monthly_count))
                @foreach($monthly_count as $key => $monthly_count_)
                    m['{{$key}}'] = '{{(int)$monthly_count_-1}}'
                @endforeach
            @endif
            let tasks_not_ended = '{{$tasks_not_ended}}';
            let tasks_ended = '{{$tasks_ended}}';
            @if($tasks_ended == 0 && $tasks_not_ended == 0)
                tasks_ended = 1;
            @endif
            circleCharts.canvas.parentNode.style.height = '144px';
            circleCharts.canvas.parentNode.style.width = '244px';
           
            let two = new Chart(circleCharts, {
                type: 'doughnut',
                data: {
                    // labels: ['Completed tasks','In work', 'Tasks not completed'],
                    datasets:[{
                        label: ['Tasks'],
                        data: [parseInt(tasks_not_ended), parseInt(tasks_ended)],
                        backgroundColor: [
                            '#E44848',
                            '#65AF37',
                        ],
                        borderColor: [
                            '#E44848',
                            '#65AF37',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: true,
                }
            })

    </script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <script>
        $(document).ready(function(){
            
            $('.daterange').daterangepicker({
                locale: {
                    format: 'DD.MM.YYYY',
                     // "customRangeLabel": "Custom",
                    "applyLabel": $('#lang_app').attr('lang'),
                    "cancelLabel": $('#lang_cancel').attr('lang'),
                    "monthNames": $('#lang_months').attr('lang'),
                    "monthNames": line_months
                }
            });

          })

      $(document).on('click','.applyBtn',function(){
        var date = $('.daterange').val()
        var id = $('#div_id').attr('data-id')
        var arr = [id, date];

        location.href = `/forthebuilder/user/filtr/${arr}`;
        // $.ajax({
        //     dataType: 'json',
        //     url: `/user/filtr/${arr}`,
        //     type: 'GET'
        // });
      })

      console.log($('#lang_months').attr('lang'))
    </script>
   
@endsection


