{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout> --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

<head>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

</head>

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
    <div class="pt-4 grid grid-cols-2 gap-4">
        <div class="bg-yellow-500 text-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="text-4xl font-bold">{{ $cantidadClientes }}</div>
                <div class="text-lg">Clientes</div>
            </div>
            <a href="{{route('clientes.index')}}">
                <div class="bg-yellow-600 p-3 text-center cursor-pointer hover:bg-yellow-700">
                    <span class="text-white">Más Información</span>
                </div>
            </a>
        </div>

        <div class="bg-teal-500 text-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="text-4xl font-bold">{{ $cantidadVentas }}</div>
                <div class="text-lg">Ventas</div>
            </div>
            <a href="{{route('ventas.index')}}">
                <div class="bg-teal-600 p-3 text-center cursor-pointer hover:bg-teal-700">
                    <span class="text-white">Más Información</span>
                </div>
            </a>
        </div>
    </div>
    <div class="flex flex-col mt-8 px-14" >
        <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="min-w-full rounded-lg border-b border-gray-200 shadow sm:rounded-lg bg-white">
                <div id="chartdiv" class="h-96 mb-20">
                    <h4 class="text-xl text-center py-4 font-semibold">Ventas</h4>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <!-- Chart code -->
    <script>
        am5.ready(function() {

        var root = am5.Root.new("chartdiv");

        root.setThemes([
        am5themes_Animated.new(root)
        ]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true,
        paddingLeft:0,
        paddingRight:1
        }));

        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 30,
        minorGridEnabled: true
        });

        xRenderer.labels.template.setAll({
        rotation: -90,
        centerY: am5.p50,
        centerX: am5.p100,
        paddingRight: 15
        });

        xRenderer.grid.template.setAll({
        location: 1
        })

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "fecha_venta",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
        }));

        var yRenderer = am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1
        })

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: yRenderer
        }));

        // Create series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Series 1",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "total_ventas",
        sequencedInterpolation: true,
        categoryXField: "fecha_venta",
        tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY}"
        })
        }));

        series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
        series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
        });


        am5.net.load("http://127.0.0.1:8000/api/venta-fecha").then(function(result) {
            var data=am5.JSONParser.parse(result.response);
            console.log(result.response);
            xAxis.data.setAll(data);
            series.data.setAll(data);

            series.appear(1000);
            chart.appear(1000, 100);
        }).catch(function(result) {
            // This gets executed if there was an error loading URL
            // ... handle error
            console.log("Error loading " + result.xhr.responseURL);
        });



        }); // end am5.ready()
    </script>
@stop
