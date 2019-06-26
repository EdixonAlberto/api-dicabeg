@extends('emailLayout')

@section('content')
    @component('components.title',[
        'description' => 'Estas a solo un paso de abrir tu cuenta Dicabeg',
        'imagePath' => 'public/img/phone.png'
    ])@endcomponent

    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p>Dentro de la app, escribe el siguiente código para activar tu cuenta.</p>

                <div id="code">
                    <p class="p-bold" id="button">{{ $code }}</p>
                </div>

                <p>Le damos la bienvenida a la comunidad.</p>
                <p>El equipo de <strong>Dicapp</strong></p>
            </td>
        </tr>
    </table>
@endsection