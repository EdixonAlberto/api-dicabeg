@php
    define('SUBJECT', 'Recuperación de Cuenta');
@endphp

@extends('components.layout')

@section('content')
    @component('components.title', [
        'description' => 'Recupere facilmente su cuenta Dicabeg',
        'imageName' => 'key.png'
    ])@endcomponent

    <table class="content" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2">
				<p>
                    ¿Olvidó su contraseña?, no se preocupe, podemos solucionarlo,<br />
                    solo ingresa este codigo en la app y podra crear una nueva, así de facil.
                </p>
                <div id="code">
                    <p class="p-bold" id="button">{{ $code }}</p>
                </div>
                <p>
                    <strong>Nota:</strong>Este codigo se destruirá en una hora.<br />

                    <br />
					—El equipo de <strong>Dicapp</strong>
                </p>
			</td>
		</tr>
	</table>
@endsection