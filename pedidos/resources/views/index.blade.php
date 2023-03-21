@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        @php
        Session::forget('success');
        @endphp
    </div>
    @endif
    <div class="row img-cover test d-flex justify-content-center my-3">
        <div class="col-11">
            <img class="img-fluid w-100 h-100" src="{{ asset('images/imagen2.jpeg') }}" alt="Imagen presentacion">
        </div>
    </div>
    <div>

    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-10 card mb-3" id="cardIndex">
            <div class="row g-0 d-flex justify-content-center">

                <div class="col-11 col-md-5 bg-light shadow d-flex align-items-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="titulo col-10">
                                ESCUELA DE HOSTELER&Iacute;A DE EGIBIDE MENDIZORROTZA
                            </div>
                            <span class="slogan col-10 mt-3 ">La Escuela de Hosteler&iacute;a de EGIBIDE&#45;MENDIZORROTZA ofrece una variada oferta gastron&oacute;mica, que podr&aacute;s disfrutar en nuestro restaurante o disponible tambi&eacute;n para llevar.</span>

                        </div>
                        <div class="d-flex justify-content-end mt-5 pe-5 me-5">
                            <a href="{{ route('productos.index', 0) }}" class="btn btn-outline-secondary">Carta</a>
                        </div>
                    </div>


                </div>
                <div class="d-xl-none d-block">
                    <div class="w-100"></div>
                </div>
                <div id="carouselExampleControls" class="carousel slide col-11 col-md-5" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active ">
                            <img src="{{ asset('images/plato1.jpg') }}" class="d-block w-100 img-fluid" alt="Imagen plato 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/plato2.jpg') }}" class="d-block w-100 img-fluid" alt="Imagen plato 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/plato3.jpg') }}" class="d-block w-100 img-fluid" alt="Imagen plato 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>



            </div>

        </div>
    </div>
</div>


@endsection