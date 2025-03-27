@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <a href="{{ $url }}" class="button button-{{ $color ?? 'green' }}" target="_blank" style="
                            background-color: #3a7631;
                            border-radius: 5px;
                            color: #ffffff;
                            display: inline-block;
                            font-size: 16px;
                            padding: 14px 24px;
                            text-decoration: none;
                            font-weight: bold;
                        ">
                            {{ $slot }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    
