@php
	V2\Modules\EmailTemplate::$subject = 'Actualizar Correo Electrónico';
@endphp

@extends('component.layout')

@section('content')

@component('component.content', [
		'imageName' => 'mail.png',
		'user' => V2\Modules\User::$names ?? V2\Modules\User::$username
	])
	@slot('content_first')
		<p>
			Para continuar con la actualización de tu correo debes ingresar el siguiente código en la aplicación:
		</p>

		@include('component.code')
	@endslot

	@slot('footer')
		<strong id="information">El código caducará dentro de 24 horas</strong>
	@endslot
@endcomponent

@endsection