@php
    V2\Modules\EmailTemplate::$subject = 'Recuperación de Cuenta';
@endphp

@extends('component.layout')

@section('content')

@component('component.content', [
        'imageName' => 'key.png',
        'user' => $data->user
    ])
    @slot('content_first')
        <p>
            ¿Olvidaste tu contraseña?, no te preocupes, podemos solucionarlo, solo ingresa este código en la aplicación y podrás crear una contraseña nueva:
        </p>

        @include('component.code')

        <p>
            Así de facil es recuperar tu cuenta Dicabeg, si tienes algun problema al respecto no dudes en <a href="mailto:{{ $support }}">escribirnos</a>.
        </p>
    @endslot

    @slot('footer')
        <strong id="information">El código caducará dentro de 1 hora</strong>
    @endslot
@endcomponent

@endsection