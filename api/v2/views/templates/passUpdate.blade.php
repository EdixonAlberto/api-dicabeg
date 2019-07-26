@php
    V2\Modules\EmailTemplate::$subject = 'Actualizar Contraseña';
@endphp

@extends('component.layout')

@section('content')

@component('component.content', [
        'imageName' => 'pass.png',
        'user' => V2\Middleware\Auth::$name
    ])
    @slot('content_first')
        <p>
            Hemos recibido una solicitud para actualizar tu contraseña.
        </p>

        <p>
            Para continuar con el cambio de contraseña de Dicabeg por favor ingrese el siguiente código en la aplicación:
        </p>

        @include('component.code')

        <p>
            Si no solicitó esto, <a href="mailto:{{ $support }}" target="_blank">informenos de inmediato</a>. Es importante que nos informe, ya que nos permitirá asegurarnos de que nadie tenga acceso a tu cuenta.
        </p>
    @endslot

    @slot('footer')
        <strong id="information">El código caducará dentro de 1 hora</strong>
    @endslot
@endcomponent

@endsection