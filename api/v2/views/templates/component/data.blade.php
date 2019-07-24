<table class="content" align="center" cellpadding="0" cellspacing="0">
    <tr class="tr-data">
        <td colspan="2">
            <p class="p-data"><strong class="color">Donde y cuando sucedió:</strong></p>
        </td>
    </tr>

    <tr class="tr-data">
        <td>
            <p class="p-data">
                <strong class="colo">Fecha:</strong> <br />
                <strong class="colo">Dispositivo:</strong> <br />
                <strong class="colo">Ubicación:</strong> <br />
            </p>
        </td>
        <td>
            <p class="p-data">
               {{ $slot }}
            </p>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <p>
                Si algun dato no concuerda o te parece sospechoso, <a href="mailto:{{ $support }}" target="_blank">informenos</a> para proteger tu cuenta.
            </p>
        </td>
    </tr>
</table>