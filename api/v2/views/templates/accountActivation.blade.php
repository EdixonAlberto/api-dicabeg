@php
    V2\Modules\EmailTemplate::$subject = 'Activación de Cuenta';
@endphp

@extends('component.layout')

@section('content')

@component('component.content',[
        'imageName' => 'dicabeg_black.png',
        'user' => V2\Middleware\Auth::$name
    ])
    @slot('content_first')
        <p>
            Gracias por crear una cuenta Dicabeg. Solo falta que activemos tu cuenta, por favor ingresa el siguiente código en la aplicación para continuar:
        </p>

        @include('component.code')
    @endslot

    @slot('footer')
        <strong id="information">El código caducará dentro de 24 horas</strong>
    @endslot
@endcomponent

@endsection