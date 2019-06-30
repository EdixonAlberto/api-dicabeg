@php
	define('SUBJECT', 'Actualizar Correo Electrónico');
@endphp

@extends('components.layout')

@section('content')
	@component('components.title',[
		'description' => 'Confirme su nueva dirección de correo electrónico',
		'imageName' => 'key.png'
	])@endcomponent

	<table class="content" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2">
				<p>
					Para continuar con la actualización de su correo electrónico debe ingresar el
					siguiente código dentro de la app.<br />
				</p>
				<div id="code">
					<p class="p-bold" id="button">{{ $code }}</p>
				</div>
				<p>
					<strong>Nota:</strong> Si se ha equivocado, porfavor elimine este mensaje, el codigo se destruirá en una hora.<br />
					<br />
					—El equipo de <strong>Dicapp</strong>
				</p>
			</td>
		</tr>
	</table>
@endsection