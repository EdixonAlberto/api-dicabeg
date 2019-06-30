@php
    define('SUBJECT', '¡Bienvenid@ a Dicabeg! | Activacón tu Cuenta');
@endphp

@extends('components.layout')

@section('content')
    @component('components.title',[
        'description' => 'Le damos la bienbenida a Dicabeg',
        'imageName' => 'phone.png'
    ])@endcomponent

    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p>
                    Gracias por crear una cuenta Dicabeg. Solo falta que activemos tu cuenta, escribe el siguiente código dentro de la aplicación para proceder con la activación.
                </p>
                <div id="code">
                    <p class="p-bold" id="button">{{ $code }}</p>
                </div>
                <p>
                    Ahora será parte de nuestra comunidad.<br />

                    <br />
					—El equipo de <strong>Dicapp</strong>
                </p>
            </td>
        </tr>
    </table>
@endsection