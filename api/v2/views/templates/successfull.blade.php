@php
    V2\Modules\EmailTemplate::$subject = 'Proceso Exitoso';
@endphp

@extends('component.layout')

@section('content')

@component('component.content', [
        'imageName' => 'ok.png'
    ])
    @slot('content_first')
        <p>
            Has actualizado tu {{ $data->updatedData }} <strong>satisfactoriamente.</strong>
        </p>

        <strong class="color">¡Gracias por usar Dicabeg!</strong>
    @endslot
@endcomponent

@endsection