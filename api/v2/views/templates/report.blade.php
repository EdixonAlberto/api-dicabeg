@php
    V2\Modules\EmailTemplate::$subject = 'Reporte de Transferencia';
@endphp

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

    <table class="content-title" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td-title">
                <p class="p-bold">{{ V2\Modules\EmailTemplate::$subject }}</p>
            </td>
            <td>
                <img id="title-image" src="{{ DATASERVER_URL }}/img/email/register.png" alt="Title Image" />
            </td>
        </tr>
        <tr>
            <td>
                {{-- <span>MES: {{ $data->month }}</span> --}}
            </td>
        </tr>
    </table>

    <table class="report" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="4" id="report-id">
                <strong>id: {{ $data->id }}</strong>
            </td>
        </tr>
        <tr id="report-leyenda">
            <td></td>
            <td>Monto</td>
            <td>Comisión</td>
            <td>Ultima Fecha</td>
        </tr>
        <tr id="report-value">
        <td>TOTAL</td>
            <td>{{ $data->amount }}</td>
            <td>{{ $data->commission }}</td>
            <td>{{ $data->dateLast }}</td>
        </tr>
    </table>

    <table class="content-footer" align="center" cellpadding="0" cellspacing="0">
    </table>
</body>
</html>