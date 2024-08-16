@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    @if (session('success'))
    <div id="success-message"
        class="flex items-center p-4 lg:mx-20 mt-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium">¡Éxito!</span> {{ session('success') }}
        </div>
    </div>
    @endif
    <div class="lg:mx-20 my-4">
        <h1 class="text-2xl font-bold mb-4">Ventas</h1>
        <div class="flex justify-between items-center">
            <form action="{{ route('ventas.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" class="form-input px-4 py-2 h-full rounded-l-md"
                    placeholder="Buscar venta" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 h-full rounded-r-md hover:bg-blue-700">
                    <i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i>
                </button>
            </form>
            <a href="{{ route('ventas.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Añadir</a>
        </div>
    </div>
@stop

@section('content')
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg lg:mx-20">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
            <thead class="text-md text-gray-700 uppercase bg-blue-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cliente
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Documento
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subtotal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ventas as $venta)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $venta->id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $venta->cliente->nombre . ' ' . $venta->cliente->apellido }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $venta->tipo->descripcion }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $venta->fecha_venta }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $venta->subtotal }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $venta->total }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('ventas.edit', $venta->id) }}"
                                class="items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">Editar</a>
                            <button type="button" onclick="confirmDelete('{{ $venta->id }}')" class="items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">Eliminar</button>
                            <a href="{{ route('exportar', ['id' => $venta->id, 'tipo' => $venta->id_tipo]) }}"
                                target="_blank"
                                class="items-center cursor-pointer justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                                Emitir
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="lg:mx-20 mt-4">
        {{ $ventas->links() }}
    </div>
    {{-- <div class="mt-4">
        {{ $categorias->appends(['search' => request('search')])->links() }}
    </div> --}}
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            var successMessage = document.getElementById('success-message');
                            if (successMessage) {
                                successMessage.style.transition = 'opacity 0.5s ease';
                                successMessage.style.opacity = '0';
                                setTimeout(function() {
                                    successMessage.remove();
                                }, 500); // Espera el tiempo de la transición para eliminar el elemento
                            }
                        }, 3000); // 3 segundos antes de empezar a desvanecer
                    });
    </script>
    <script>
        function confirmDelete(id){
                alertify.confirm("¿Seguro que quieres eliminar el venta?",
                function(){
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/ventas/' + id;
                    form.innerHTML = '@csrf @method("DELETE")';
                    document.body.appendChild(form);
                    form.submit();
                },
                function(){
                    alertify.error('Cancelado');
                });
            }
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($ventas as $venta)
                var generateReportButton = document.getElementById('reportButton-{{ $venta->id }}');

                generateReportButton.addEventListener('click', function() {
                    var tipo = this.getAttribute('data-tipo');

                    if (tipo == 1) {
                        window.open("{{ route('exportBoleta') }}", '_blank');
                    } else if (tipo == 2) {
                        window.open("{{ route('exportFactura') }}", '_blank');
                    }
                });
            @endforeach
        });
    </script> --}}
@stop
