@extends('forthebuilder::layouts.forthebuilder')
@php
    use Modules\ForTheBuilder\Entities\House;
    use Modules\ForTheBuilder\Entities\Constants; 

@endphp
@section('title')
    {{ translate('JK') }}
@endsection
@section('content')
@include('forthebuilder::layouts.content.navigation')
@include('forthebuilder::layouts.content.header')
<style>
    .plus2{
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
        background: #F2F2F2;
        color: #555;
        width: 50px;
        height: 50px;
    }
</style> 

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid py-3 px-2">

            <div class="card">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div class="row w-100 m-0">
                        <div class="col-md-6 d-flex align-items-center">
                            <a onclick="history.back()" href="#" class="plus2 profileMaxNazadInformatsiyaKlient">
                                <i class="mdi mdi-keyboard-backspace mdi-20"></i>
                            </a>
                            <h4 class="ms-2">
                                {{ translate('Report on deals') }}
                            </h4>
                        </div>
                        <div class="col-md-5 text-end">
                            
                            <div class="ml-auto d-flex align-items-center" id="CurrentDayToday">
                                <h4>{{translate('Period')}}: </h4>
                                <input type="text" class="ms-2 form-control daterange" value="{{ date('01.m.Y').' - '.date('t.m.Y') }}">
                            </div>        
                        </div>
                        <div class="col-md-1 text-end">
                            <span class="btn btn-outline-success btn-sm">
                                <i class="mdi mdi-microsoft-excel mdi-20"></i>
                            </span>
                        </div>
                    </div>    
                    
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body pb-0">
                            <h4 class="header-title mt-0 mb-4">{{translate('First contact')}}</h4>

                            <div class="widget-chart-1">
                                <div class="widget-chart-box-1 float-start" dir="ltr">
                                    <input data-plugin="knob" data-width="70" data-height="70" data-fgColor="#f05050 "
                                           data-bgColor="#F9B9B9" value="{{ round((100/$data['all_clients_count'])*$data['new_clients'],2) }} %"
                                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                                           data-thickness=".15" type="text" />
                                </div>

                                <div class="widget-detail-1 text-end">
                                    <h2 class="fw-normal pt-2 mb-1"> {{$data['new_clients']}} </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body pb-0">

                            <h4 class="header-title mt-0 mb-3">{{translate('For a negotiation')}}</h4>

                            <div class="widget-box-2">
                                <div class="widget-detail-2 text-end">
                                    <span class="badge bg-warning rounded-pill float-start mt-3">{{
                                    
                                    round((100/$data['all_deals_count'])* $data['in_negotiations'],2);
                                   
                                    
                                    }}% <i class="mdi mdi-trending-up"></i> </span>
                                    <h2 class="fw-normal mb-1"> {{$data['in_negotiations']}} </h2>
                                    <p class="mb-3 text-white">Revenue today</p>
                                </div>
                                <div class="progress progress-bar-alt-warning progress-sm">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                            aria-valuenow="{{$data['in_negotiations']}}" aria-valuemin="0" aria-valuemax="100"
                                            style="width: {{$data['in_negotiations']}}%;">
                                        <span class="visually-hidden">{{$data['in_negotiations']}}% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body pb-0">
                            

                            <h4 class="header-title mt-0 mb-4">{{translate('Making a deal')}}</h4>

                            <div class="widget-chart-1">
                                <div class="widget-chart-box-1 float-start" dir="ltr">
                                    <input data-plugin="knob" data-width="70" data-height="70" data-fgColor="#0FC56A"
                                            data-bgColor="#12EA7E" value="{{
                                            round((100/$data['all_deals_count'])* $data['make_deal'],2);    
                                        }}"
                                            data-skin="tron" data-angleOffset="180" data-readOnly=true
                                            data-thickness=".15"/>
                                </div>
                                <div class="widget-detail-1 text-end">
                                    <h2 class="fw-normal pt-2 mb-1"> {{$data['make_deal']}} </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>

            <div class="row mt-3">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-3">{{translate('Sources')}}</h4>
                            
                            <div class="chartjs-chart">
                                <canvas id="polarArea" height="300"> </canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-3">{{translate('Individual sales')}}</h4>
                            <div class="chartjs-chart">
                                <canvas id="doughnut" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="{{asset('/backend-assets/Admin/dist/assets/libs/flot-charts/jquery.flot.js')}}"></script>
<script src="{{asset('/backend-assets/Admin/dist/assets/libs/flot-charts/jquery.flot.pie.js')}}"></script>
<script src="{{asset('/backend-assets/Admin/dist/assets/libs/flot-charts/jquery.flot.crosshair.js')}}"></script>
<script src="{{asset('/backend-assets/Admin/dist/assets/libs/morris.js06/morris.min.js')}}"></script>
<script src="{{asset('/backend-assets/Admin/dist/assets/libs/raphael/raphael.min.js')}}"></script>
<!--<script src="{{asset('/backend-assets/Admin/dist/assets/js/pages/morris.init.js')}}"></script>-->

<script src="{{ asset('/backend-assets/Admin/dist/assets/libs/chart.js/Chart.bundle.min.js') }}"></script>

    <script>
        let page_name = 'report-clients';
        $(document).on('click','tbody tr',function(){
            location.href = $(this).attr('data-href')
        })
    </script>

    <script>
        var s_names = <?php echo $data['source_name']; ?>;
        var s_counts = <?php echo $data['source_data']; ?>;
        var s_colors = <?php echo $data['source_color']; ?>;
        var names = <?php echo $data['names']; ?>;
        var counts = <?php echo $data['counts']; ?>;
        var colors = <?php echo $data['colors']; ?>;

        !(function (s) {
            function r() {}
            (r.prototype.respChart = function (r, o, a, e) {
                var t = r.get(0).getContext("2d"),
                    n = s(r).parent();
                function i() {
                    r.attr("width", s(n).width());
                    switch (o) {
                        case "Doughnut":
                            new Chart(t, { type: "doughnut", data: a, options: e });
                            break;
                        case "PolarArea":
                            new Chart(t, { data: a, type: "polarArea", options: e });
                    }
                }
                s(window).resize(i), i();
            }),
                (r.prototype.init = function () {
                    
                    this.respChart(s("#doughnut"), "Doughnut", {
                        labels: names,
                        datasets: [{ 
                            data: counts, 
                            backgroundColor: colors, 
                            hoverBackgroundColor: colors, 
                            hoverBorderColor: "#fff" }],
                    });
                    
                    this.respChart(s("#polarArea"), "PolarArea", {
                        
                        datasets: [
                            { data: s_counts, 
                            backgroundColor:s_colors, 
                            label: "", hoverBorderColor: "#fff" }],
                        labels: s_names,
                    });
                }),
                (s.ChartJs = new r()),
                (s.ChartJs.Constructor = r);
        })(window.jQuery),
            window.jQuery.ChartJs.init();
    </script>
@endsection
