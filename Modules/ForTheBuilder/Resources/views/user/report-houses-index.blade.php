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
                                {{ translate('Report on houses') }}
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
                        <div class="card-body widget-user">
                            <div class="text-center">
                                <i class="mdi mdi-home-city-outline font-20"></i>
                                <h2 class="fw-normal text-danger" data-plugin="counterup">
                                    {{$data['house_count']}}
                                </h2>
                                <h5>{{translate('Count of flats')}}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body widget-user">
                            <div class="text-center">
                                <i class="mdi mdi-home-import-outline font-20"></i>
                                <h2 class="fw-normal text-warning" data-plugin="counterup">
                                    {{$data['house_flat_status_free']}}
                                </h2>
                                <h5>{{translate('Free house')}}</h5>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body widget-user">
                            <div class="text-center">
                                <i class="mdi mdi-shield-lock-outline font-20"></i>
                                <h2 class="fw-normal text-pink" data-plugin="counterup">
                                    {{$data['house_flat_status_booking']}}
                                </h2>
                                <h5>{{translate('On armor')}}</h5>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body widget-user">
                            <div class="text-center">
                                <i class="mdi mdi-calculator-variant-outline font-20"></i>
                                <h2 class="fw-normal text-info" data-plugin="counterup">
                                    {{$data['installment_count']}}
                                </h2>
                                <h5>{{translate('On installments')}}</h5>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body widget-user">
                            <div class="text-center">
                                <i class=" dripicons-trophy font-20"></i>
                                <h2 class="fw-normal text-success" data-plugin="counterup">
                                    {{$data['house_flat_status_sold']}}
                                </h2>
                                <h5>{{translate('Successful transactions')}}</h5>
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
