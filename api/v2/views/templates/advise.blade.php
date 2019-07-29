@php
    V2\Modules\EmailTemplate::$subject = 'Aviso Importante';
@endphp

@extends('component.layout')

@section('content')

@component('component.content', [
        'imageName' => 'warn.png',
        'user' => V2\Middleware\Auth::$name
    ])
    @slot('content_first')
        <p>
			Hemos recibido una solicitud para actualizar tu cuenta de correo electrónico de Dicabeg: <span class="color">{{ $data->oldMail }}</span> a <span class="color">{{ $data->newMail }}</span>.
        </p>

        <hr />
    @endslot

    @component('component.data', ['support' => $support])
            {{ $data->date }} <br />
            {{-- {{ $data->device }} <br /> --}}
            {{ $data->location }} <br />
    @endcomponent

@endcomponent

@endsection