@extends('components.email')

@section('content')
    @component('components.title', [
        'description' => 'Ahora podras recuperar tu cuenta Dicabeg',
        'imagePath' => 'public/img/image_key.png'
    ])@endcomponent

    <table class="content" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2">
				<p>Dentro de la app, ingresa este código
                    para reestablecer tu contraseña.</p>

                <div id="code">
                    <p class="p-bold" id="button">{{ $code }}</p>
                </div>

				<p>En seguida podrá usar su cuenta nuevamente.</p>
                <p>[El equipo de <strong>Dicapp</strong>]</p>
			</td>
		</tr>
	</table>
@endsection