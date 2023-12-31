@extends('forthebuilder::layouts.forthebuilder')
@section('content')

    <div class="d-flex aad">
        
   {{-- @dd($data['date_today']) --}}
        @include('forthebuilder::layouts.content.navigation')
        <div class="mainMargin">
            @include('forthebuilder::layouts.content.header')
            <div class="container-fluid">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2 class="ml-0 panelUprText">{{translate('Control Panel')}}</h2>        
                </div>
                <div class="col-md-6">
                    <div class="ml-auto" id="CurrentDayToday">
                        {{translate('Date')}}: 
                        <input type="text" class="ml-2 form-control daterange" value="{{ date('01.m.Y').' - '.date('t.m.Y') }}">
                    </div>        
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('New Clients')}}</h3>
                        <h2 class="lidi25">{{$data['new_clients']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('For a negotiation')}}</h3>
                        <h2>{{$data['in_negotiations']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('Making a deal')}}</h3>
                        <h2>{{$data['make_deal']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="zadachiLidi col lidiMarginRight2">
                        <h3>{{translate('Tasks')}}</h3>
                        <h2>{{$data['full_task']}}</h2>
                        <hr>
                        <p>{{translate('For today')}} : {{$data['today']}}<br>
                           {{translate('For tomorrow')}} : {{$data['tomorrow']}}<br>
                           {{translate('For a week')}} : {{$data['week']}}</p>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col">
                        <h3>{{translate('Overdue tasks')}}</h3>
                        <h2>{{$data['overdue_tasks']}}</h2>
                    </div>
                </div>
               
            </div>
            
            <div class="row">
                <div class="col-7">
                    <div class="chartDiv bigProdajZaMesyach lidiMarginRight2 m-0">
                        <h2>{{translate('Sales per month')}}</h2>
                        <div>
                            <canvas id="myChart" width="400" height="150">
                                <p>Hello Fallback World</p>
                            </canvas>
                          </div>
                    </div>
                    <br>
                    <div class="chartDiv lidiMarginRight2 m-0" >
                        <h2 class="chart1Individual ">{{translate('Individual sales managers')}}</h2>
                        <div>
                            <canvas id="myChart_2" width="400" height="150">
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
            
            <div class="row mt-3">
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('Count of flats')}}</h3>
                        <h2 class="lidi25">{{$data['house_count']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('Free house')}}</h3>
                        <h2>{{$data['house_flat_status_free']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('On armor')}}</h3>
                        <h2>{{$data['house_flat_status_booking']}}</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="novieLidi col lidiMarginRight2">
                        <h3>{{translate('On installments')}}</h3>
                        <h2>{{$data['installment_count']}}</h2>
                    </div>
                </div>
                <div class="col">
                     <div class="zadachiLidi col mb-3">
                        <h3>{{translate('Successful transactions')}}</h3>
                        <h2>{{$data['house_flat_status_sold']}}</h2>
                        <hr>
                        <p>
                            {{ number_format($data['price'],0,'.',' ')}}
                            @php 
                                if (isset($currency)) {
                                    echo (($currency->SUM) ? translate(' sum') : translate(' usd'));
                                }
                            @endphp 
                        </p>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>

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
        let page_name = 'index';
        // chart1
        const ctx = document.getElementById('myChart');
        var month_day = <?php echo json_encode($data['month_day']); ?>;
        var price_day_array = <?php echo json_encode($data['price_day_array']); ?>;
        var lang_months = $('#lang_months').attr('lang');
        var line_months = $('#line_months').attr('lang');

        line_months = line_months.split(",");


        new Chart(ctx, {
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

        // chart2
        const ctx_2 = document.getElementById('myChart_2');
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

        

        $(document).ready(function(){
            $('.daterange').daterangepicker({
                locale: {
                    format: 'DD.MM.YYYY',
                    "applyLabel": $('#lang_app').attr('lang'),
                    "cancelLabel": $('#lang_cancel').attr('lang'),
                    "monthNames": line_months
                }
            });
        })

        $(document).on('click','.applyBtn',function(){
            var date = $('.daterange').val()
            location.href = `/forthebuilder/filtr/${date}`;
        })

</script>
@endsection
