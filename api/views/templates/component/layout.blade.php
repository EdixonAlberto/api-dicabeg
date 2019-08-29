<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml" />
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    @include('component.fontStyle')

</head>
<body>
    <table class="header" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td-logo">
                <img id="logo-dicabeg"
                    src="{{ DATASERVER_URL }}/img/email/dicabeg.png"
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
                <p id="about">
                    Copyright &copy; 2019 Dicabeg. Todos los derechos reservados. Dicabeg y el logotipo de Dicabeg son productos desarrollados por <strong>Dicapp</strong>. Consulte nuestra <a href="https://edixonalberto.github.io/doc-dicabeg/menu/policy.html" target="_blank">política de privacidad</a> o escribanos a <a href="mailto:{{ SUPPORT_EMAIL }}" target="_blank", title="Envianos un Email">{{ SUPPORT_EMAIL }}</a> para poder brindarle soporte.
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p id="networks">
                    <a href="https://edixonalberto.github.io/doc-dicabeg" title="Visita Nuestra Web" target="_blank">Página Web</a> |
                    <a href="https://twitter.com/Dicapp1" title="Síguenos en Twitter" target="_blank">Twitter</a> |
                    <a href="https://instagram.com/dicabeg" title="Síguenos en Instagram" target="_blank">Instagram</a>
                    {{-- <a href="" title="Síguenos en Facebook" target="_blank">Facebook</a> --}}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>