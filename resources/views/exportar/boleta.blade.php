<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .container {
            width: 660px;
            margin: 0 auto;
            border: 2px solid #0c0b3a;
            padding: 20px;
            position: relative;
        }

        .header {
            font-weight: bold;
            text-align: center;
            background-color: #140b9c; /* Color de fondo */
            color: white;
        }

        .title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .factura-info,
        .client-info,
        .date,
        .totals {
            border: 2px solid black;
            padding: 10px;
            margin-bottom: 10px;
        }

        .factura-info {
            text-align: center;
            width: 40%;
            margin: 0 auto;
        }

        .factura-info td {
            padding: 5px 0;
        }

        .client-info,
        .date {
            font-size: 13px;
        }

        .client-info p,
        .date p {
            margin: 0 0 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 2px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        .totals-container {
            display: flex;
            justify-content: flex-end;
        }

        .totals {
            width: 300px;
            font-size: 13px;
        }

        .totals td {
            margin: 5px 0;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">SISVENTAS</div>
            <div>A.V. Juan Pablo II</div>
            <div>R.U.C. 09892389234</div>
        </div>

        <table class="factura-info">
            <tr>
                <td><strong>BOLETA ELECTRÓNICA</strong></td>
            </tr>
            <tr>
                <td>{{ $serie }} - {{ $venta->nro_doc }}</td>
            </tr>
        </table>

        <div class="client-info">
            <p><strong>Señor (a):</strong> {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}</p>
            <p><strong>Dirección:</strong> {{ $venta->cliente->direccion }} </p>
            <p><strong>DNI:</strong> {{ $venta->cliente->dni }}</p>
        </div>

        <div class="date">
            <p><strong>Fecha:</strong> {{ $venta->fecha_venta }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th>Precio Unit.</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                @php
                    $cant = $detalle->cantidad;
                    $price = $detalle->precio;
                    $producto = $detalle->productos->descripcion;
                @endphp
                <tr>
                    <td>{{ $cant }}</td>
                    <td>{{ $producto }}</td>
                    <td>{{ $price }}</td>
                    <td>{{ $cant * $price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-container">
            <table class="totals">
                <tr>
                    <td><strong>Total:</strong> {{ $venta->total }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
