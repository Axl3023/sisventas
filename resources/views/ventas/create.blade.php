@extends('adminlte::page')

@section('title', 'Registrar Venta')

@section('content_header')
    <div class="lg:mx-20 my-4">
        <a href="{{ route('ventas.index') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Volver</a>
    </div>
@stop

@section('content')
    @if (session('error'))
    <div id="error-message"
        class="flex items-center mb-4 p-4 lg:mx-20 mt-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium">¡Error!</span> {{ session('error') }}
        </div>
    </div>
    @endif
    <div class="lg:mx-20">
        <form action="{{ route('ventas.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="nro_doc" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">DNI o RUC del
                    Cliente</label>
                <div class="flex">
                    <input type="text" id="nro_doc" name="nro_doc"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="DNI o RUC del Cliente" required maxlength="11" pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"/>
                    <button type="button" id="buscar_cliente"
                        class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md px-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Buscar</button>
                </div>
            </div>
            <div id="cliente_info" class="hidden mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md">
                <input type="hidden" id="id_cliente" name="id_cliente" />
                <div class="flex items-center mb-2">
                    <span class="font-bold text-md text-gray-900 dark:text-white mr-2">Nombre:</span>
                    <p id="cliente_nombre" class="text-md text-gray-700 dark:text-gray-300"></p>
                </div>
                <div class="flex items-center mb-2">
                    <span class="font-bold text-md text-gray-900 dark:text-white mr-2">Email:</span>
                    <p id="cliente_email" class="text-md text-gray-700 dark:text-gray-300"></p>
                </div>
                <div class="flex items-center">
                    <span class="font-bold text-md text-gray-900 dark:text-white mr-2">Dirección:</span>
                    <p id="cliente_direccion" class="text-md text-gray-700 dark:text-gray-300"></p>
                </div>
            </div>
            <div id="nuevo_cliente" class="hidden mb-4">
                <label for="nombre" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Nombre</label>
                <input type="text" id="nombre" name="nombre"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nombre del Cliente" />
                <label for="apellido" class="block mb-2 text-md font-medium text-gray-900 dark:text-white mt-4">Apellido</label>
                <input type="text" id="apellido" name="apellido"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Apellido del Cliente" />
                <label for="email" class="block mb-2 text-md font-medium text-gray-900 dark:text-white mt-4">Email</label>
                <input type="email" id="email" name="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Email del Cliente" />
                <label for="direccion" class="block mb-2 text-md font-medium text-gray-900 dark:text-white mt-4">Dirección</label>
                <input type="text" id="direccion" name="direccion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Dirección del Cliente" />
            </div>
            <div class="mb-4">
                <label for="fecha_venta" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Fecha de
                    Venta</label>
                <input type="datetime-local" id="fecha_venta" name="fecha_venta"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required />
            </div>
            <div class="mb-4">
                <label for="id_tipo" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Tipo de Documento</label>
                <select id="id_tipo" name="id_tipo"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>
                    <option value="" disabled selected>Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="numeracion" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Número de
                    Documento</label>
                <input type="text" id="numeracion" name="numeracion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Número de Documento" required disabled/>
                <input type="hidden" id="numeracion_data" name="numeracion_data">
            </div>
            <label for="id_tipo" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Producto(s)</label>
            <div>
                <div id="productos_container">
                    <div class="flex items-center producto_item mb-4">
                        <select name="id_producto[]"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="cantidad[]"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 ml-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Cantidad" required pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                        <button type="button" class="ml-4 text-red-700 hover:text-red-800"
                            onclick="removeProduct(this)">Eliminar</button>
                    </div>
                </div>
                <button type="button" id="add_product" class="text-green-700 hover:text-green-800">Agregar Producto</button>
            </div>
            <button type="submit"
                class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar
                Venta</button>
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
    <script>
        document.getElementById('buscar_cliente').addEventListener('click', function() {
        let nro_doc = document.getElementById('nro_doc').value;

        fetch(`/api/clientes/${nro_doc}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('id_cliente').value = data.cliente.id;
                    document.getElementById('cliente_nombre').innerText = `${data.cliente.nombre} ${data.cliente.apellido}`;
                    document.getElementById('cliente_email').innerText = data.cliente.email;
                    document.getElementById('cliente_direccion').innerText = data.cliente.direccion;

                    document.getElementById('cliente_info').classList.remove('hidden');
                    document.getElementById('nuevo_cliente').classList.add('hidden');
                } else {
                    document.getElementById('cliente_info').classList.add('hidden');
                    document.getElementById('nuevo_cliente').classList.remove('hidden');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('add_product').addEventListener('click', function() {
            let productItem = document.querySelector('.producto_item');
            let newProductItem = productItem.cloneNode(true);

            // Limpiar los valores del nuevo producto clonado
            let inputs = newProductItem.querySelectorAll('input');
            inputs.forEach(input => input.value = '');

            let selects = newProductItem.querySelectorAll('select');
            selects.forEach(select => select.selectedIndex = 0);

            document.getElementById('productos_container').appendChild(newProductItem);
        });

        function removeProduct(element) {
            let productItems = document.querySelectorAll('.producto_item');
            if (productItems.length > 1) {
                element.parentNode.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var successMessage = document.getElementById('error-message');
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
        document.getElementById('id_tipo').addEventListener('change', function() {
        let id_tipo = this.value;
        let numeracion = '';

        if (id_tipo == 1) {
            numeracion = {{ $parametro_1 ? $parametro_1->numeracion : 'null' }};
        } else if (id_tipo == 2) {
            numeracion = {{ $parametro_2 ? $parametro_2->numeracion : 'null' }};
        }

        if (numeracion !== 'null') {
            numeracion = parseInt(numeracion, 10);
            if (numeracion < 10) {
                numeracion='0000' + numeracion;
            } else if (numeracion < 100) {
                numeracion='000' + numeracion;
            } else if (numeracion < 1000) {
                numeracion='00' + numeracion;
            } else if (numeracion < 10000) {
                numeracion='0' + numeracion;
            }
            document.getElementById('numeracion').value=numeracion;
            document.getElementById('numeracion_data').value = numeracion;
        } else {
            document.getElementById('numeracion').value='No disponible';
            document.getElementById('numeracion_data').value = '';
        }
    });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            let productSelects = document.querySelectorAll('select[name="id_producto[]"]');
            let selectedProducts = [];

            for (let i = 0; i < productSelects.length; i++) {
                let selectedValue = productSelects[i].value;

                if (selectedProducts.includes(selectedValue)) {
                    alert('No puedes seleccionar el mismo producto más de una vez.');
                    event.preventDefault(); // Evita que el formulario se envíe
                    return false;
                } else {
                    selectedProducts.push(selectedValue);
                }
            }
        });
    </script>
@stop
