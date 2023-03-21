@extends('layouts.layout')
@section('content')

<div class="row my-4 justify-content-center">
	<div class="col-10 col-md-11">
		<div class="page-header">
            <div class="row">
                <div class="col-12 col-md-4 align-self-center">
                    <h1>
                        Detalles pedido #{{ $pedido->id }} 
                    </h1>
                </div>
                <div class="col-12 col-md-8">
                    <div class="card my-3"> 
                        <div class="card-header">
                            <h3>Estado pedido</h3>
                        </div>
                        <div class="row card-body">
                            <div class="col-6">
                                @switch($pedido->estadoPedido->nombre)
                                    @case('Recibido')
                                        <span class="badge bg-secondary rounded-pill d-inline"> {{ $pedido->estadoPedido->nombre }} </span>
                                    @break
                                    @case('En proceso')
                                        <span class="badge bg-warning rounded-pill d-inline">{{ $pedido->estadoPedido->nombre }}</span>
                                    @break
                                    @case('Proceso')
                                        <span class="badge bg-warning rounded-pill d-inline">{{ $pedido->estadoPedido->nombre }}</span>
                                    @break
                                    @case('Preparado')
                                        <span class="badge bg-success rounded-pill d-inline">{{ $pedido->estadoPedido->nombre }}</span>
                                    @break
                                    @case('Cancelado')
                                        <span class="badge bg-danger rounded-pill d-inline">{{ $pedido->estadoPedido->nombre }}</span>
                                    @break
                                    @case('Entregado')
                                        <span class="badge bg-primary rounded-pill d-inline">{{ $pedido->estadoPedido->nombre }}</span>
                                    @break
                                @endswitch
                            </div>
                            <div class="col-6">
                                <div class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"> </div>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li> <a class="dropdown-item" href="{{ route('pedidos.update', ['pedido'=> $pedido, 'estado'=>1]) }}">Recibido</a> </li>
                                    <li> <a class="dropdown-item" href="{{ route('pedidos.update', ['pedido'=> $pedido, 'estado'=>2]) }}">Proceso</a> </li>
                                    <li> <a class="dropdown-item" href="{{ route('pedidos.update', ['pedido'=> $pedido, 'estado'=>3]) }}">Preparado</a> </li>
                                    <li> <a class="dropdown-item" href="{{ route('pedidos.update', ['pedido'=> $pedido, 'estado'=>4]) }}">Cancelado</a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-4 pt-5">
                <div class="card my-3">
                    <div class="card-header">
                        <h3>Datos solicitante</h3>
                    </div>
                    <div class="card-body">
                      {{-- <h5 class="card-title"> </h5> --}}
                      <div class="table-responsive">
                        <table class="table align-middle mb-0 caption-top table-hover">
                            <tr>
                                <td>
                                    Nombre
                                </td>
                                <td>
                                    {{ $persona->nombre }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Correo
                                </td>
                                <td>
                                    {{ $persona->email }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tel&eacute;fono
                                </td>
                                <td>
                                    {{ $persona->telefono }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Fecha
                                </td>
                                <td>
                                    {{ $pedido->fecha }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    </div>
                </div>
				<div class="card my-3"> 
                    <div class="card-header">
                    <h3>Observaci&oacute;n</h3>
                    </div>
                    <div class="card-body">
                        @if ($pedido->observacion == "")
                        No tiene observaciones
                        @else
                        {{ $pedido->observacion }}
                        @endif
                    </div>
                </div>
			</div>
            <div class="col-md-8">
                <table class="table align-middle mb-0 caption-top table-hover">
                    <!-- <caption>Pedidos Pendientes:</caption> -->
                    <thead>
                        <tr>
                            <th scope="col">Foto</th>
                            <th scope="col">Platos</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->productosPedido as $producto)
                            <tr class='lineaPedido'>
                                <td>
                                    <img class="card-img-top img-fluid img-thumbnail" style="width: 110px" alt="{{$producto->producto->nombre}}" 
                                        @if( file_exists('thumbnails/'.$producto->idProducto.'.png') )
                                            src="{{ asset('thumbnails/'.$producto->idProducto.'.png') }}" 
                                        @else 
                                            src="{{ asset('images/placeholder.png') }}" 
                                        @endif
                                    />
                                </td>
                                <td>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $producto->producto->nombre }}
                                    </div>
                                </td>
                                <td>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info rounded-pill">{{ $producto->cantidad }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pt-5">
                    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-5">Volver</a>
                </div>
			</div>
		</div>
	</div>
</div>

@endsection
