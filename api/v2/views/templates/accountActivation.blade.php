@include('header')
@include('title')
    <table class="content-title" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="p-content" id="content-title">Estas a solo un paso de abrir tu cuenta Dicabeg</p>
            </td>
            <td>
                <img id="image-title"
                    src="https://marketing-image-production.s3.amazonaws.com/uploads/6c4762dd02d31c09466f2271a4bea39d1ba7e4c43c3c8dfe0576251fdde04cac0fa4dd3871fee780a5e1a5c112a512c31db336580389384e276268a4984335fb.png"
                    alt="image-phone" />
            </td>
        </tr>
    </table>

    <table class="content" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <p class="p-content" style="margin-top:0px;">
                    Dentro de la app, escribe el siguiente código para activar tu cuenta.
                </p>
                <p id="activation-code"> {{ $code }} </p>
                <p class="p-content">Le damos la bienvenida a la comunidad.</p>
                <p class="p-content" style="margin-bottom:0px;">
                    [El equipo de <strong>Dicapp</strong>]
                </p>
            </td>
        </tr>
    </table>
@include('footer')
