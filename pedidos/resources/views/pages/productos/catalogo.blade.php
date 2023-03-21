@extends('layouts.layout')
@section('content')
<div class="row mt-4 d-flex justify-content-center"><!-- PLATOS -->
    <div class="col-10 col-md-11 col-lg-10">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
            Session::forget('success');
            @endphp
        </div>
        @endif
        <a class="btn btn-outline-success float-end" href="{{ route('productos.create') }}"> Nuevo Producto </a>

        <div class="row gy-3 mt-4">

            <div class="col-12 col-md-3"><!-- Col del groupList -->
                <div class="list-group" id="list-tab" role="tablist">
                    @if ($id)
                    <a class="list-group-item list-group-item-action " href="{{ route('productos.catalogo') }}" role="tab" aria-controls="list-profile"> TODOS </a>

                    @else
                    <a class="list-group-item list-group-item-action active" href="{{ route('productos.catalogo') }}" role="tab" aria-controls="list-profile"> TODOS </a>
                    @endif
                    @foreach($tipos as $tipo)
                    @if($id== $tipo->id)
                    <a class="list-group-item list-group-item-action active" href="{{ route('productos.catalogo', $tipo->id) }}" role="tab" aria-controls="list-profile">{{ $tipo->nombre }}</a>
                    @else
                    <a class="list-group-item list-group-item-action" href="{{ route('productos.catalogo', $tipo->id) }}" role="tab" aria-controls="list-profile">{{ $tipo->nombre }}</a>
                    @endif
                    @endforeach
                </div>
            </div>


            <!-- COL DEL CONTENIDO -->
            <div class="col-12 col-md-9 mb-4 tab-content" id="nav-tabContent">
                <div class=" row tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                    <div class="col-12 px-3 plato card-group">
                        <div class="row">
                            @include('layouts.cards')

                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{ $productos->links() }}

    </div>
</div>
@endsection