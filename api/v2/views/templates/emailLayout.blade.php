<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es" data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="public/css/style.css" />
</head>
<body>
    <table class="header" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td-logo">
                <img id="logo-dicabeg"
                    src="public/img/dicabeg.png"
                    alt="Logo Dicabeg" />
            </td>
            <td>
                <p class="p-bold" id="app-name">Dicabeg</p>
            </td>
        </tr>
    </table>

    @yield('content')

    <table class="footer" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p>
                    ©Copyright 2019 Dicabeg. Todos los derechos reservados. Dicabeg y el logotipo de Dicabeg son productos desarrollados por <strong>Dicapp</strong>. Consulte nuestra <a href="https://edixonalberto.github.io/doc-dicabeg/menu/policy.html" target="_blank">política de privacidad</a> o escribanos a <a href="mailto:{{ $support }}" target="_blank", title="Enviar un Email">{{ $support }}</a> para poder brindarle soporte.
                </p>
                <div class="networks">
                    <a href="https://www.instagram.com/dicabeg" title="Síguenos en Instagram" target="_blank">Instagram</a> |
                    {{-- <a href="" title="Síguenos en Facebook" target="_blank">Facebook</a> | --}}
                    <a href="https://edixonalberto.github.io/doc-dicabeg" title="Web Dicabeg" target="_blank">Pagina Web</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>