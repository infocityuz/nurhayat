@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\Constants; 
@endphp
@section('title')
    {{ translate('Reports') }}
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
            <div class="container d-flex justify-content-between px-5">
                <div class="d-flex align-items-center">
                    <a style="margin-left: 0;" onclick="history.back()" href="#" class="plus2 profileMaxNazadInformatsiyaKlient">
                        <img
                    src="{{ asset('/backend-assets/forthebuilders/images/icons/arrow-left.png') }}"alt=""></a>
                    <h2 class="panelUprText" style="margin: 0; margin-left: 10px;">
                       {{ translate('Report on deals') }}
                    </h2>
                </div>
                <div class="miniSearchDiv5" style="width: 330px; padding-right: 10px;">
                    <h4 class="m-0 mr-2">{{translate('Period')}}: </h4>
                    <input type="text" class="ms-2 form-control daterange" value="{{ date('01.m.Y').' - '.date('t.m.Y') }}">
                </div>
                <div class="miniSearchDiv5">
                    <span class="btn btn-outline-success btn-sm">
                        <i class="fa fa-file-excel mdi-20"></i>
                    </span>
                </div>
            </div>
            <div class="container px-5">
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
                </div>


                <div class="row">
                    <div class="col-6">
                        <div class="ovalChart m-0 w-100" style="height: auto !important">
                            <h2>{{translate('Sources')}}</h2>
                            <div id="piechart2" style="width: 430px;"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="ovalChart m-0 w-100" style="height: auto !important">
                            <h2>{{translate('Individual sales')}}</h2>
                            <div id="piechart" style="width: 430px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let page_name = 'report';
    </script>

    <div id="lang_app" lang="{{ translate('Apply') }}"></div>
    <div id="lang_cancel" lang="{{ translate('Cancel') }}"></div>
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

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart_2);
        function drawChart_2() {
            var data = google.visualization.arrayToDataTable([
              ['Task', 'Hours per Day'],
              <?php echo $data['sources']; ?>
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

            var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
            chart.draw(data, options);
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




