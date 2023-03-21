@extends('layouts.layout')
@section('content')
<div class="row justify-content-center">
    <div class="col-11 col-md-8 my-5">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
            Session::forget('success');
            @endphp
        </div>
        @endif
        <div class="card">
            @if($producto)
            <div class="card-header">Editar producto</div>
            @else
            <div class="card-header">Nuevo producto</div>
            @endif
            <div class="card-body">
                <form class="row g-3" method="POST" @if($producto) action="{{ route('productos.update', $producto) }}" @else action="{{ route('productos.store') }}" @endif enctype='multipart/form-data'>
                    @csrf
                    <div class="col-md-6 required">
                        <label for="nombre" class="form-label">Nombre Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" @if ($producto) value="{{$producto->nombre}}" @endif>
                        @if ($errors->has('nombre'))
                        <span class="text-danger">{{ $errors->first('nombre') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 required">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" class="form-select" name="idTipo">
                            <option value="0" hidden> Tipo... </option>
                            @foreach($tipos as $tipo)
                            <option value="{{$tipo->id}}" @if ($producto && $producto->idTipo == $tipo->id) selected @endif>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('idTipo'))
                        <span class="text-danger">{{ $errors->first('idTipo') }}</span>
                        @endif
                    </div>
                    <div class="col-6 required">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="precio" id="precio" @if ($producto) value="{{$producto->precio}}" @endif>
                        @if ($errors->has('precio'))
                        <span class="text-danger">{{ $errors->first('precio') }}</span>
                        @endif
                    </div>
                    <div class="col-6 required">
                        <label for="pedidoMinimo" class="form-label">Pedido Mínimo</label>
                        <input type="number" min="0" class="form-control" id="pedidoMinimo" name="pedidoMinimo" @if ($producto) value="{{$producto->pedidoMinimo}}" @endif>
                        @if ($errors->has('pedidoMinimo'))
                        <span class="text-danger">{{ $errors->first('pedidoMinimo') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label for="observacion" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observacion" rows="3" name="observacion">@if($producto){{$producto->observacion}}@endif</textarea>
                        @if ($errors->has('observacion'))
                        <span class="text-danger">{{ $errors->first('observacion') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label for="fotoFile" class="form-label">Imagen producto</label>
                        <input class="form-control" type="file" id="fotoFile" name="file" accept=".png">
                    </div>
                    <div class="col-12">
                        @if ($errors->has('file'))
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                        @endif
                        <img id="previoImagen" @if($producto) @if(file_exists('images/'.$producto->id.'.png'))
                        src="{{ asset('images/'.$producto->id.'.png') }}"
                        @else
                        src="{{ asset('images/placeholder.png') }}"
                        @endif
                        @else
                        src=""
                        @endif
                        />
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary float-end">Guardar</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Way 1: Display All Error Messages -->
        <!-- @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif -->
    </div>
</div>


@stop