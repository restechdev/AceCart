@extends('backend.layouts.layout')

<style>
    /* -------------------------- DONWLOADER ----------------------------- */

    /*Persian Color*/
    .PersianGreen {
        color: #00A693;
    }

    .PersianBlue {
        color: #1C39DD;
    }

    .PersianMediumBlue {
        color: #0065A5;
    }

    .PersianIndigo {
        color: #321279;
    }

    .PersianPink {
        color: #F77FBC;
    }

    .PersianRose {
        color: #FE28A2;
    }

    .PersianRed {
        color: #C81D11;
    }

    .PersianPlum {
        color: #701C1C;
    }

    .PersianOrange {
        color: #D99058;
    }

    .main-form {
        max-width: 95% !important;
    }

    .shared-links {
        max-width: 95% !important;
    }

    .box-info {
        max-width: 50% !important;
    }

    .shared-links .main-btn {
        max-width: calc(100% - 6.5em);
        /*max-width: 90% !important;*/
    }

    .shared-links .itemsize {
        white-space: nowrap;
        padding-left: .5em;
    }

    .shared-links .wrap-title {
        position: relative;
        display: inline-block;
        overflow: hidden;
    }

    .shared-links .wrap-title > .overflowed {
        position: relative;
        display: inline-block;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1;
    }

    .shared-links .wrap-title:hover > .overflowed {
        -moz-transition: -moz-transform 2s ease-in-out .1s, margin 2s ease-in-out .2s;
        -o-transition: -o-transform 2s ease-in-out .2s, margin 2s ease-in-out .2s;
        -webkit-transition: -webkit-transform 2s ease-in-out .2s, margin 2s ease-in-out .2s;
        transition: transform 2s ease-in-out .2s, margin 2s ease-in-out .2s;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        margin-left: 100%;
        width: auto;
        min-width: 100%;
    }

    @keyframes passing {
        0% {
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            opacity: 0;
        }

        50% {
            -webkit-transform: translateX(0%);
            -ms-transform: translateX(0%);
            transform: translateX(0%);
            opacity: 1;
        }

        100% {
            -webkit-transform: translateX(50%);
            -ms-transform: translateX(50%);
            transform: translateX(50%);
            opacity: 0;
        }
    }

    @keyframes passing_reverse {
        0% {
            -webkit-transform: translateX(50%);
            -ms-transform: translateX(50%);
            transform: translateX(50%);
            opacity: 0;
        }

        50% {
            -webkit-transform: translateX(0%);
            -ms-transform: translateX(0%);
            transform: translateX(0%);
            opacity: 1;
        }

        100% {
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            opacity: 0;
        }
    }

</style>

@section('content')

@php

        if (!function_exists('checkTime')) {
            function checkTime($time, $lifedays = false)
            {
                $lifedays = $lifedays ? (int)$lifedays : 1;
                $lifetime = 86400 * $lifedays;
                if (time() <= $time + $lifetime) {
                    return true;
                }
                return false;
            }
        }

        if (!function_exists('checkTimeBetween')) {
            function checkTimeBetween($time, $lifedays = false)
            {
                $lifedays = $lifedays ? (int)$lifedays : 1;
                $lifetime = 86400 * $lifedays;
                if (time() >= $time && time() <= $time + $lifetime) {
                    return true;
                }
                return false;
            }
        }

        if (!function_exists('checkTimeBiggerOrSmaler')) {
            function checkTimeBiggerOrSmaler($time, $lifedays = false)
            {
                $lifedays = $lifedays ? (int)$lifedays : 1;
                $lifetime = 86400 * $lifedays;
                if (time() >= $time && time() <= $time + $lifetime) {
                    return 10;
                } elseif (time() < $time) {
                    return 0;
                } elseif (time() > $time + $lifetime) {
                    return 1;
                }

            }
        }

        if (!function_exists('mbPathinfo')) {

            /**
             * Get pathinfo in UTF-8
             *
             * @param string $filepath to search
             *
             * @return array $ret
             */
            function mbPathinfo($filepath)
            {
                preg_match(
                    '%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',
                    $filepath,
                    $node
                );

                if (isset($node[1])) {
                    $ret['dirname'] = $node[1];
                } else {
                    $ret['dirname'] = '';
                }

                if (isset($node[2])) {
                    $ret['basename'] = $node[2];
                } else {
                    $ret['basename'] = '';
                }

                if (isset($node[3])) {
                    $ret['filename'] = $node[3];
                } else {
                    $ret['filename'] = '';
                }

                if (isset($node[5])) {
                    $ret['extension'] = $node[5];
                } else {
                    $ret['extension'] = '';
                }
                return $ret;
            }
        }

        if (!function_exists('getFileSize')) {
            /**
             * Determine the size of a file
             *
             * @param string $path file to calculate
             *
             * @return sizeInBytes
             * @since  3.0.3
             */
            function getFileSize($path)
            {
                $size = filesize($path);

                if (!($file = fopen($path, 'rb'))) {
                    return false;
                }
                if ($size >= 0) { // Check if it really is a small file (< 2 GB)
                    if (fseek($file, 0, SEEK_END) === 0) { // It really is a small file
                        fclose($file);
                        return $size;
                    }
                }
                // Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
                $size = PHP_INT_MAX - 1;
                if (fseek($file, $size) !== 0) {
                    fclose($file);
                    return false;
                }
                $length = 1024 * 1024;
                while (!feof($file)) { // Read the file until end
                    $read = fread($file, $length);
                    $size = bcadd($size, $length);
                }
                $size = bcsub($size, $length);
                $size = bcadd($size, strlen($read));

                fclose($file);
                return $size;
            }
        }


        if (!function_exists('formatSize')) {
            /**
             * Format file size
             *
             * @param string $size new format
             *
             * @return formatted size
             */
            function formatSize($size)
            {
                $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
                $syz = $sizes[0];
                for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
                    $size = $size / 1024;
                    $syz = $sizes[$i];
                }
                return round($size, 0) . ' ' . $syz;
            }
        }
        //return file uploaded via uploader
        if (!function_exists('uploaded_assetx')) {
            function uploaded_assetx($id)
            {
                if (($asset = \App\Models\Upload::find($id)) != null) {
                    return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
                }
                return static_asset('assets/img/bg.jpg');
            }
        }

    @endphp

<div class="position-absolute" id="particles-js"></div>
<div class="h-100 bg-cover bg-center py-5 d-flex align-items-center"
     style="background-image: url({{ uploaded_assetx(get_setting('admin_login_background')) }})">
<div class="container main-form">

            <div class="row">
                <div class="col-lg-9 col-xl-9 mx-auto">
                    <div class="card text-left">
                        <div class="card-body">

                            {{--//3 titlse and logo on top off screen--}}
                            <div class="mb-5 text-center">
                                @if(get_setting('system_logo_black') != null)
                                    <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}"
                                         class="mw-100 mb-4" height="80">
                                @else
                                    <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mb-4"
                                         height="80">
                                @endif
                                <h1 class="fs-20 fs-md-24 fw-700 text-primary">{{ translate('Welcome My Friend')}}</h1>
                                {{--                                <h5 class="fs-14 fw-400 text-dark">{{ translate('Dear friend, if you need a password to download the file, it has been sent to you in your email')}} &#128512; &#11088; &#128512;</h5>--}}
                                <h5 class="fs-16 fw-500 text-dark">
                                    &#11088; {{ translate('Dear friend, if you need a password to download the file, it has been sent to you in your email')}}
                                    &#11088;</h5>
                            </div>
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <ul>
                                        <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if (\Session::has('error'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{!! \Session::get('error') !!}</li>
                                    </ul>
                                </div>
                            @endif

                            @php
                                $expired = true;
                                $share_path = base_path('databasebackups/share');

                                $share_json = $share_path . DIRECTORY_SEPARATOR . 'shares.json';
                            @endphp

                            @php
                                if ($linkkey && file_exists($share_json)) {
                                    $expired = false;
                                    $arr = json_decode(file_get_contents($share_json), true);

                                    $find_val = $linkkey;

                                    $linkkeyvalidate = false;
                                    $TimeIsBetweenStartAndEnd = false;
                                    $data_time_startx = time();
                                    $data_time_endx = time();
                                    $bigger_smaller = 10;
                                    foreach ($arr as $key => $value) {
                                        if ($value['idfilename'] == $find_val) {
                                            $datarray = $value;
                                            $time = $datarray['time'];
                                            $lifetime = isset($datarray['lifetime']) ? (int)$datarray['lifetime'] : 1;
                                            $bigger_smaller = checkTimeBiggerOrSmaler($time, $lifetime);

                                            if ((isset($datarray['attachmentsxx']) && !empty($datarray['attachmentsxx']) && checkTimeBetween($time, $lifetime))) {
                                                // value is not null
                                                $expired = false;
                                                $linkkeyvalidate = true;
                                                $TimeIsBetweenStartAndEnd = true;
                                                $data_time_startx = $time;
                                                $lifedays = $lifetime ? (int)$lifetime : 1;
                                                $data_time_endx = (86400 * $lifedays) + $time;
                                            } else {
                                                // value is null or empty
                                                $expired = true;
                                                $linkkeyvalidate = false;
                                                $TimeIsBetweenStartAndEnd = false;
                                                $data_time_startx = $time;
                                                $lifedays = $lifetime ? (int)$lifetime : 1;
                                                $data_time_endx = (86400 * $lifedays) + $time;
                                            }
                                            break;
                                        } else {
                                            $datarray = [];
                                            $TimeIsBetweenStartAndEnd = false;
                                            $linkkeyvalidate = false;
                                            $expired = true;
                                        }


                                    }

                                    $passa = true;
                                    $passcap = true;
                                    $passpass = true;
                                    $capcha = true;

                                   if ($linkkeyvalidate) { //all ok
                                        $pass = (isset($datarray['pass']) ? $datarray['pass'] : false);
                                        if ($pass) {
                                            $passa = false;
                                            $passpass = false;
                                            $postpass = isset($_GET['dwnldpwd']) ? $_GET['dwnldpwd'] : false;
                                            if ($postpass) {
                                                if (md5($postpass) === $pass) {
                                                    $passa = true;
                                                    $passpass = true;
                                                } else {
                                                    $passa = false;
                                                    $passpass = false;
                                                    flash(translate('Invalid login credentials'))->error();
                                               }
                                            }
                                        }

                                        if ($capcha) {
                                            $passcap = false;
                                            $mandil2 = isset($_GET['default_recaptcha_id_customer_login']) ? $_GET['default_recaptcha_id_customer_login'] : false;
                                            if ($mandil2) {
                                                if (strtolower($mandil2) === strtolower(Session('default_recaptcha_id_customer_login'))) {
                                                    $passcap = true;
                                                } else {
                                                   $passcap = false;
                                                   flash(translate('Invalid captcha credentials'))->error();
                                               }
                                            }
                                        }

                                        if (!$passcap || !$passa) {
                                           $passa = false;
                                           $passpass = false;
                                           $passcap = false;
                                        }
                                        if ($passcap && $passa) {
                                           $passa = true;
                                           $passpass = true;
                                           $passcap = true;
                                        }

                                        $hash = $datarray['hash'];
                                        $time = $datarray['time'];
                                        $lifetime = isset($datarray['lifetime']) ? (int)$datarray['lifetime'] : 1;
                                        $onetime_download = isset($datarray['onetime']) ? (int)$datarray['onetime'] : 0;
                                        $sh = md5($time . $hash);
                                        if ($passa && $passcap) {
                                            $countfiles = 0;
                                            if (checkTimeBetween($time, $lifetime)) {
                                                $onetime_download = $onetime_download ? $linkkey : '0'; ///=====================================> check???
                                                $pieces = explode(",", $datarray['attachments']);
                                                $piecesxx = $datarray['attachmentsxx'];
                                                $totalsize = 0;

                            @endphp

                            <div class="row shared-links" style="margin: 50px;">
                                @php
                                    $piecesxxarraysize = count($piecesxx);
                                    $numberfiles = 0;
                                    foreach ($piecesxx as $count => $pezzo) {
                                        $numberfiles++;
                                        $myfile = urldecode(base64_decode($pezzo)); //==> databasebackups/2024-01-03-13-42-40/database-2024-01-03-13-42-40.zip

                                        if (file_exists($myfile)) {
                                            $filepathinfo = mbPathinfo($myfile);
                                            $filename = $filepathinfo['basename'];
                                            $extension = strtolower($filepathinfo['extension']);
                                            $filesize = getFileSize($myfile);
                                            $totalsize += $filesize;
                                            $thisicon = 'file-zip-o';
                                            $parameters = array();
                                            array_push($parameters, $countfiles);
                                            $paramatts = join(',', $parameters);
                                            array_push($parameters, $sh);
                                            $paramatts = join(',', $parameters);
                                            array_push($parameters, $linkkey);
                                            $paramatts = join(',', $parameters);
                                            array_push($parameters, $pezzo);
                                            $paramatts = join(',', $parameters);
                                @endphp


{{--                            SHOW FILES FOR DOWNLOAD SECTION--}}
                                @if($countfiles > 0 && ($countfiles % 2) == 0)
                                    <div style="width: 100%; max-width:100%;">
                                        <br>
                                    </div>
                                @endif
                                @if($numberfiles == $piecesxxarraysize && ($piecesxxarraysize % 2) != 0)
                                    <div class="col-md-12" style="margin-left:22% ">
                                        @else
                                            <div class="col-md-6">
                                                @endif
                                                <div class="btn-group">
                                                    <a href="{{ route('backups.pelpelak1', $paramatts) }}"
                                                       class="btn btn-info service-btn d-flex align-items-center justify-content-center"
                                                       onMouseOver="window.status = ''">
                                                        <div style="width:.8rem;"><i
                                                                style="margin-left:-8px; margin-right:0px;"
                                                                class="las la-archive la-2x"></i></div>
                                                    </a>

                                                    <a href="#!" onclick="window.location.reload();"
                                                       class="btn btn-primary service-btn d-flex align-items-center justify-content-center">
                                                        <div style="width:320px; max-width:320px;">
                                                            <div class="wrap-title">
                                                    <span class="ms-auto small itemsize"
                                                          style="color:#ffffff;font-size:13px;text-align:left;margin-left:-5px;margin-right:-5px;height:18px;font-weight: bold">
                                                        <?php echo $filename; ?>
                                                    </span>
                                                                <span class="ms-auto small itemsize"
                                                                      style="color:#ffffff;font-size:13px;margin-left:7px; margin-right:7px; text-align:left;height:18px;">
                                                       <?php echo ' ' . formatsize($filesize) . ' '; ?>
                                                    </span>
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <a href="{{ route('backups.pelpelak1', $paramatts) }}"
                                                       onclick="window.location.reload(true);"
                                                       class="btn btn-info service-btn d-flex align-items-center justify-content-center"><i
                                                            style="width:1.2rem;margin-left:-8px; margin-right:0px;"
                                                            class="las la-download la-2x la-spin"></i></a>
                                                </div>
                                            </div>

                                            @php
                                                $countfiles++;
                                                        } //END if (file_exists($myfile))
                                                    } //END foreach ($piecesxx as $count => $pezzo)
                                            @endphp
                                    </div>
                                    @php
                                        } //END if (checkTime($time, $lifetime))
                                    } // END if if ($passa && $passcap) == true
                                    @endphp

                                    @if ($passcap !== true)

                                        {{--                        //enter password and captcha for download--}}
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 mx-auto">
                                                <div class="card shadow-none rounded-0 border">

                                                    <div class="row">


                                                        <div class="col-lg-6 mx-auto">
                                                            {{--                                    <div class="col-lg-6 col-md-7 p-4 p-lg-5">--}}
                                                            <div class="text-center">
                                                                <br>
                                                                <h1 class="fs-20 fs-md-24 fw-700 text-primary">{{ translate('Enter the required information !')}}</h1>
                                                            </div>

                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <form id="persian" name="persian" action="">

                                                                    @if ($passa !== true)
                                                                        @if (strlen($pass) > 0)
                                                                            @if ($postpass && $passpass !== true)
                                                                            @endif
                                                                            {{-- //input password section  --}}
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label"
                                                                                       style="font-size: 1.05rem;font-weight: bold;color: #000d80;margin-top:3px;margin-right:5px;display:block;width:100px;"
                                                                                       for="dwnldpwd">{{translate('password')}}</label>
                                                                                <input type="password" name="dwnldpwd"
                                                                                       class="form-control"
                                                                                       placeholder="******"
                                                                                       autocomplete="off"
                                                                                       readonly
                                                                                       onfocus="this.removeAttribute('readonly');">
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                        {{-- //input captcha section  --}}
                                                                        <div class="row py-2">
                                                                            <div class="col-5 pr-2">
                                                                                <input dir="ltr" type="text"
                                                                                       class="form-control border __h-40"
                                                                                       name="default_recaptcha_id_customer_login"
                                                                                       id="default_recaptcha_id_customer_login"
                                                                                       value=""
                                                                                       placeholder="{{translate('Captcha..')}}"
                                                                                       required="required"
                                                                                       autocomplete="off">
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <div class="input-group">

                                                                                        <div
                                                                                            class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                                                                            <a onclick="refresh_captcha_img();"
                                                                                               class="d-flex align-items-center align-items-center">
                                                                                                <img
                                                                                                    src="{{ URL('/backups/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}"
                                                                                                    class="input-field rounded __h-40"
                                                                                                    id="customer_login_recaptcha_id">
                                                                                                <i class="tio-refresh icon cursor-pointer p-2"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                                                                            <a href="#"
                                                                                               onclick='refresh_captcha_img();'
                                                                                               class="btn btn-info service-btn d-flex align-items-center justify-content-center">
                                                                                                <div class="aiz-side-nav-icon">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 17 17">
                                                                                                        <path d="M12.319,5.792L8.836,2.328C8.589,2.08,8.269,2.295,8.269,2.573v1.534C8.115,4.091,7.937,4.084,7.783,4.084c-2.592,0-4.7,2.097-4.7,4.676c0,1.749,0.968,3.337,2.528,4.146c0.352,0.194,0.651-0.257,0.424-0.529c-0.415-0.492-0.643-1.118-0.643-1.762c0-1.514,1.261-2.747,2.787-2.747c0.029,0,0.06,0,0.09,0.002v1.632c0,0.335,0.378,0.435,0.568,0.245l3.483-3.464C12.455,6.147,12.455,5.928,12.319,5.792 M8.938,8.67V7.554c0-0.411-0.528-0.377-0.781-0.377c-1.906,0-3.457,1.542-3.457,3.438c0,0.271,0.033,0.542,0.097,0.805C4.149,10.7,3.775,9.762,3.775,8.76c0-2.197,1.798-3.985,4.008-3.985c0.251,0,0.501,0.023,0.744,0.069c0.212,0.039,0.412-0.124,0.412-0.34v-1.1l2.646,2.633L8.938,8.67z M14.389,7.107c-0.34-0.18-0.662,0.244-0.424,0.529c0.416,0.493,0.644,1.118,0.644,1.762c0,1.515-1.272,2.747-2.798,2.747c-0.029,0-0.061,0-0.089-0.002v-1.631c0-0.354-0.382-0.419-0.558-0.246l-3.482,3.465c-0.136,0.136-0.136,0.355,0,0.49l3.482,3.465c0.189,0.186,0.568,0.096,0.568-0.245v-1.533c0.153,0.016,0.331,0.022,0.484,0.022c2.592,0,4.7-2.098,4.7-4.677C16.917,9.506,15.948,7.917,14.389,7.107 M12.217,15.238c-0.251,0-0.501-0.022-0.743-0.069c-0.212-0.039-0.411,0.125-0.411,0.341v1.101l-2.646-2.634l2.646-2.633v1.116c0,0.174,0.126,0.318,0.295,0.343c0.158,0.024,0.318,0.034,0.486,0.034c1.905,0,3.456-1.542,3.456-3.438c0-0.271-0.032-0.541-0.097-0.804c0.648,0.719,1.022,1.659,1.022,2.66C16.226,13.451,14.428,15.238,12.217,15.238"></path>
                                                                                                    </svg>
                                                                                                </div>

                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>

                                                                        <div class="card-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">{{translate('Send')}}
                                                                                <i
                                                                                    class="las la-check la-1x"></i>
                                                                            </button>
                                                                        </div>

                                                                        {{-- if you want to show your location in google map, plase uncomment this with your lat and long values  --}}
                                                                        {{--                                            <div class="form-style map-responsive justify-content-center">--}}
                                                                        {{--                                                <a itemprop="url" class="direction-link" target="_blank" href="//maps.google.com/maps?f=d&amp;daddr=35.720230,51.396764&amp;hl=en">Get Directions</a>--}}
                                                                        {{--                                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12956.83516080484!2d51.396764!3d35.720230!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e012f804045ad%3A0xed972aebc6806e82!2zMzXCsDQzJzEyLjkiTiA1McKwMjMnNDkuMCJF!5e0!3m2!1sen!2s!4v1633164199199!5m2!1sen!2s"--}}
                                                                        {{--                                                        width="200" height="150" style="border:0;" allowfullscreen="" loading="lazy"></iframe>--}}
                                                                        {{--                                            </div>--}}


                                                                    </form>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <!-- Right Side Image -->
                                                        <div class="col-lg-6 col-md-5 py-3 py-md-0">
                                                            <img
                                                                src="{{ uploaded_asset(get_setting('admin_login_page_image')) }}"
                                                                alt=""
                                                                class="img-fit h-100">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endif
                                    @php
                                        } // END all ok
                                    } // END if ($linkkey && file_exists($share_json))
                                    @endphp

                                    @if ($bigger_smaller === 0)
                                        <div class="col-12 text-center">
                                            <a class="btn btn-primary btn-lg"
                                               href="./">{{ translate('Sorry, your link will be activated on the date') }} {{ date('d-m-Y H:i:s', $data_time_startx) }}</a>
                                        </div>
                                    @elseif ($bigger_smaller === 1)
                                        <div class="col-12 text-center">
                                            <a class="btn btn-danger btn-lg"
                                               href="./">{{ translate('Sorry but your link/s has expired in') }} {{ date('d-m-Y H:i:s', $data_time_endx) }}</a>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

@endsection

@section('script')
    <script type="text/javascript">
        AIZ.plugins.particles();

        //Refresh Captcha
        function refresh_captcha_img() {
            $url = "{{ URL('/backups/captcha') }}";
            $url = $url + "/" + Math.random() + '?captcha_session_id=default_recaptcha_id_customer_login';
            document.getElementById('customer_login_recaptcha_id').src = $url;
            console.log('url==>: ' + $url);
        }

        function autoFill() {
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }

        //MD5('rezakia')
        function MD5(r) {
            var o, e, n,
                f = [-680876936, -389564586, 606105819, -1044525330, -176418897, 1200080426, -1473231341, -45705983, 1770035416, -1958414417, -42063, -1990404162, 1804603682, -40341101, -1502002290, 1236535329, -165796510, -1069501632, 643717713, -373897302, -701558691, 38016083, -660478335, -405537848, 568446438, -1019803690, -187363961, 1163531501, -1444681467, -51403784, 1735328473, -1926607734, -378558, -2022574463, 1839030562, -35309556, -1530992060, 1272893353, -155497632, -1094730640, 681279174, -358537222, -722521979, 76029189, -640364487, -421815835, 530742520, -995338651, -198630844, 1126891415, -1416354905, -57434055, 1700485571, -1894986606, -1051523, -2054922799, 1873313359, -30611744, -1560198380, 1309151649, -145523070, -1120210379, 718787259, -343485551],
                t = [o = 1732584193, e = 4023233417, ~o, ~e], c = [], a = unescape(encodeURI(r)) + "\u0080",
                d = a.length;
            for (r = --d / 4 + 2 | 15, c[--r] = 8 * d; ~d;) c[d >> 2] |= a.charCodeAt(d) << 8 * d--;
            for (i = a = 0; i < r; i += 16) {
                for (d = t; 64 > a; d = [n = d[3], o + ((n = d[0] + [o & e | ~o & n, n & o | ~n & e, o ^ e ^ n, e ^ (o | ~n)][d = a >> 4] + f[a] + ~~c[i | 15 & [a, 5 * a + 1, 3 * a + 5, 7 * a][d]]) << (d = [7, 12, 17, 22, 5, 9, 14, 20, 4, 11, 16, 23, 6, 10, 15, 21][4 * d + a++ % 4]) | n >>> -d), o, e]) o = 0 | d[1],
                    e = d[2];
                for (a = 4; a;) t[--a] += d[a];
            }
            for (r = ""; 32 > a;) r += (t[a >> 3] >> 4 * (1 ^ a++) & 15).toString(16);
            return r;
        }
    </script>

@endsection

