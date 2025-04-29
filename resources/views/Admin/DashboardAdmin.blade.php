@extends('Layouts.general')

@section('title', 'Tableau de bord')

@section('page_description')
<!-- Vous pouvez ajouter une description ici si besoin -->
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">

    <style>
        #lineChart {
            min-height: 270px !important;
            display: block;
            box-sizing: border-box;
            height: 270px !important;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <div class="container-xxl flex-grow-1 container-p-y mt-0">
            <div class="row">
                <top-card :reservations='@json($reservations)'></top-card>

                <div class="col-12 mb-4">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 mb-2">
                            <table-user :users='@json($users)'></table-user>
                        </div>

                        <div class="col-lg-5 col-md-5 col-sm-12 mb-2">
                            <div class="card card-1 cardDash">
                                <div class="card-header d-lg-flex d-md-flex d-sm-flex d-block">
                                    <h5>Évolution des Gains et Dépenses</h5>
                                </div>
                                <div class="card-body">
                                    <div id="lineAreaChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- Fin de #app --}}
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-drag-and-drop.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>

    <!-- Chart JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const areaChartEl = document.querySelector('#lineAreaChart');

            const areaChartConfig = {
                chart: {
                    height: 300,
                    type: 'area',
                    parentHeightOffset: 0,
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    curve: 'smooth',
                    width: 2
                },
                fill: { type: 'solid', opacity: 0 },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'start',
                    labels: {
                        colors: '#474747',
                        useSeriesColors: false
                    }
                },
                grid: {
                    borderColor: 'transparent',
                    xaxis: { lines: { show: true } }
                },
                colors: ['#FFA866', '#0093EE'],
                series: [
                    { name: 'Dépenses', data: [0, 400, 1000, 150, 1200, 100, 1600] },
                    { name: 'Gains', data: [100, 600, 200, 1300, 900, 0, 1700] }
                ],
                xaxis: {
                    categories: ['lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#636363',
                            fontSize: '13px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#636363',
                            fontSize: '13px'
                        }
                    }
                },
                tooltip: { shared: false }
            };

            if (areaChartEl !== null) {
                const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
                areaChart.render();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])

    @endsection
