@extends('layouts.layout')
@section('content')
<div class="row mt-4 d-flex justify-content-center"><!-- PLATOS -->
    <div class="col-10 col-md-11 col-lg-10">

        <h1>PLATOS PARA LLEVAR</h1>
        <div class="row gy-3 mt-4">

            <div class="col-12 col-md-3"><!-- Col del groupList -->
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="{{ route('productos.catalogo') }}" role="tab" aria-controls="list-profile"> TODOS </a>
                    @foreach($tipos as $tipo)
                        <a class="list-group-item list-group-item-action" href="{{ route('productos.catalogo', $tipo->id) }}" role="tab" aria-controls="list-profile">{{ $tipo->nombre }}</a>
                    @endforeach
                </div>
            </div>

            <!-- COL DEL CONTENIDO -->
                <div class="col-12 col-md-9 tab-content" id="nav-tabContent">
                    <div class=" row tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="col-12 px-3 plato card-group">
                            <div class="row">
                            @foreach($productos as $producto)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 px-3 card border-light">
                                    <img class="card-img-top img-fluid img-thumbnail" alt="Bootstrap Thumbnail Second" src="{{ asset('images/croqueta.png') }}" />
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5> {{ $producto->nombre }} </h5>
                                        </div>
                                        <div class="card-text">
                                            <p> Precio: {{ $producto->precio }}€</p>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted d-flex justify-content-between">
                                        <a class="btn btn-outline-secondary" href="{{ route('productos.show', $producto) }}"> Editar </a>
                                        <form action="{{ route('productos.destroy', $producto) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button class="btn btn-outline-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
        {{ $productos->links() }}

        <!-- <div class="row">

            @foreach($tipos as $tipo)
            <a class="col-6 mt-4 ms-2 col-md btn filtro btn-outline-secondary " href="{{ route('productos.catalogo',$tipo->id) }}">{{ $tipo->nombre }}</a>
            @endforeach



        </div>FIN BOTONES FILTROS -->


        <!-- <div class="row mt-4 d-flex gy-3">
            @foreach($productos as $producto)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 px-3 plato card-group">
                <div class="card border-light">
                    <img class="card-img-top img-fluid img-thumbnail" alt="Bootstrap Thumbnail Second" src="{{ asset('images/croqueta.png') }}" />
                    <div class="card-body">
                        <div class="card-title">
                            <h5> {{ $producto->nombre }} </h5>
                        </div>
                        <div class="card-text">
                            <p> Precio: {{ $producto->precio }}€</p>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <a class="btn btn-outline-secondary" href="#">Opciones</a> 
                    </div>
                </div>
            </div>
            @endforeach
            {{ $productos->links() }}

        </div> -->
    </div>
</div>
@endsection