<table class="content-title" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td id="td-title">
            <p class="p-bold">{{ V2\Modules\EmailTemplate::$subject }}</p>
        </td>
        <td>
            <img id="title-image" src="https://{{ $_SERVER['SERVER_NAME'] }}/v2/public/img/{{ $imageName }}" alt="Title Image" />
        </td>
    </tr>
</table>

<table class="content first" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="2">
            @if (isset($user))
                <p>{{ $user }}.</p>
            @endif
            {{ $content_first }}
        </td>
    </tr>
</table>

{{ $slot }}

<table class="content last" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <p>
                Atentamente,<br />
                El equipo de <strong>Dicapp</strong>
            </p>
        </td>
    </tr>
</table>

<table class="content-footer" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            @if (isset($footer))
                {{ $footer }}
            @endif
        </td>
    </tr>
</table>