{{--me add--}}
<div>
{{--me add--}}
    @php
        $logo = get_setting('header_logo');
    @endphp

{{--    <img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate('Make me into an QrCode!')) !!} ">--}}

    <div style="background: #eceff4;padding: 1.5rem;">
        <table>
            <tr>
                <td>
                    @if($logo != null)
                        <img loading="lazy" src="{{ uploaded_asset($logo) }}" height="40" style="display:inline-block;">
                    @else
                        <img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" height="40"
                             style="display:inline-block;">
                    @endif
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="font-size: 1.2rem;" class="strong">{{ get_setting('site_name') }}</td>
                <td class="text-right"></td>
            </tr>
            <tr>
                <td class="gry-color small">{{ get_setting('contact_address') }}</td>
                <td class="text-right"></td>
            </tr>
            <tr>
                <td class="gry-color small">{{  translate('Email') }}: {{ get_setting('contact_email') }}</td>
                <td class="text-right small"><span class="gry-color small">{{  translate('from') }}:</span> <span
                        class="strong">{{ get_setting('site_name') }}</span></td>
            </tr>
            <tr>
                <td class="gry-color small">{{  translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                <td class="text-right small"><span class="gry-color small">{{  translate('Create Date') }}:</span> <span
                        class=" strong">{{ date('Y-m-d H:i:s', $array['time']) }}</span></td>
            </tr>
            <tr>
                <td class="gry-color small">{{ $array['message'] }}</td>
                <td class="text-right"></td>
            </tr>
        </table>

    </div>

    <div style="padding: 1.5rem;">
        <table class="padding text-left small border-bottom">
            <thead>
            <tr class="gry-color" style="background: #eceff4;">
                <th width="45%">{{ translate('Downlod Link') }}</th>
                <th width="25%">{{ translate('File Name') }}</th>
                <th width="15%">{{ translate('File Size') }}</th>
                <th width="12%">{{ translate('Password') }}</th>
                <th width="15%">{{ translate('Download') }}</th>
            </tr>
            </thead>
            <tbody class="strong">
            <tr class="">
                <td>{{ $array['content'] }}</td>
                <td class="gry-color"><?php echo $array['myfilesnamex'] ?></td>
                <td class="gry-color"><?php echo $array['myfilessizex'] ?></td>
                <td style="padding: 1.2rem;" class="text-lg-right gry-color">{{ $array['password'] }}</td>
                <td style="text-align:center;">
                    <a style="padding:16px 30px; background:#4ECF7E; color:#fff; text-decoration:none; border-radius:4px; display: inline-block;"
                       href="{{ $array['content'] }}">
                        {{ translate('Download') }}
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

{{--        <div class="mb-3">--}}
{{--        <img src="{!!$message->embedData($array['qrjan'], 'QrCode.png', 'image/png')!!}">--}}
{{--        </div>--}}





    </div>

</div>
{{--me end--}}
