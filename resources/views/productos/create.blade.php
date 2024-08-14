@extends('adminlte::page')

@section('title', 'Añadir Productos')

@section('content_header')
    <div class="lg:mx-20 my-4">
        <a href="{{ route('productos.index') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Volver</a>
    </div>
@stop

@section('content')
    <div class="lg:mx-20">
        <form action="{{ route('productos.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="descripcion"
                    class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Descripción</label>
                <input type="text" id="descripcion" name="descripcion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Descripción del producto" required />
            </div>
            <div class="mb-4">
                <label for="stock" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Stock</label>
                <input type="number" id="stock" name="stock"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Stock del producto" required />
            </div>
            <div class="mb-4">
                <label for="precio" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Precio</label>
                <input type="number" step="0.01" id="precio" name="precio"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Precio del producto" required />
            </div>
            <div class="mb-4">
                <label for="id_categoria"
                    class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Categoría</label>
                <select id="id_categoria" name="id_categoria"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="id_unidad" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Unidad</label>
                <select id="id_unidad" name="id_unidad"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>
                    @foreach($unidades as $unidad)
                    <option value="{{ $unidad->id }}">{{ $unidad->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Añadir</button>
        </form>
    </div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stop

@section('js')
@stop