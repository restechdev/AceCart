@extends('backend.layouts.app')
<style>

    table {
        border-spacing: 0;
    }

    table thead tr,
    table thead tr th {
        background: #25bcf1;
        border: 1px solid #d6f5d6;
        background-clip: content-box;
    }

    table thead tr th {
        border: none;
        background-clip: content-box;
    }

    .modal-xls {
        max-width: 90% !important;
    }

    .modal-mediumlg {
        max-width: 40% !important;
    }


    .radio-button input[type="radio"] {
        display: none;
    }

    .radio-button label {
        display: inline-block;
        background-color: #d1d1d1;
        /*padding: 4px 11px;*/
        font-size: 15px;
        cursor: pointer;
        border-radius: 1.2rem;
        padding: 0.6rem 1.2rem;
    }

    .radio-button input[type="radio"]:checked + label {
        background-color: #76cf9f;
    }

    #frm_cht .ch_tables {
    }

    #frm_cht .ch_tables label {
    }


    #table_selection {
        display: none;
    }

    .currently-loading {
        opacity: 0.75;
        -moz-opacity: 0.75;
        filter: alpha(opacity=75);
        background-image: url({{ static_asset('backup_restore_loading.gif') }});
        background-repeat: no-repeat;
        position: absolute;
        height: 100%;
        width: 100%;
        z-index: 10;
        /*left: 50%;*/
        /*top: 50%;*/
        /*display:block;*/
        background-size: contain;
        /*background-size: cover;*/

    }

    .mailresponse {
        width: 100%;
        text-align: center;
        padding: 0;
        margin: 0;
    }

    .mailresponse p {
        padding: 4px 10px;
        margin: 0;
    }

    /*section table shared link*/
    table tr th, table tr td {
        font-size: 1rem;
    }

    .container {
        padding: 20px;
    }

    .container h1 {
        font-size: 40px;
        color: #000;
        text-align: center;
        margin-bottom: 27px;
    }

    .row h2 {
        font-size: 20px;
        color: #444;
    }

    .head h5 {
        float: left;
        width: 75%;
        margin-bottom: 0;
        margin-top: 10px;
    }

    i.plus {
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        vertical-align: middle;
    }

    #userData a.btn {
        padding: .1rem .5rem;
    }

    .alert-danger p {
        margin-bottom: 2px;
    }


    /*progrees bar for restructure backup json file*/
    .loader-container {
        /*background: rgba(0, 0, 0, .5);*/

        opacity: 0.75;
        -moz-opacity: 0.75;
        filter: alpha(opacity=75);

        display: flex;
        align-items: center;
        justify-content: center;

        /*position: fixed;*/
        position: relative;

        width: 100%;
        height: 100%;
    }

    .loader {
        z-index: 3;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }


    /* Safari */

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /*for progrees bar*/

    .checkbox-label {
        display: block;
        position: relative;
        margin: auto;
        cursor: pointer;
        font-size: 22px;
        line-height: 24px;
        height: 24px;
        width: 24px;
        clear: both;
    }

    .checkbox-label input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkbox-label .checkbox-custom {
        position: absolute;
        top: 0px;
        left: 0px;
        height: 24px;
        width: 24px;
        background-color: transparent;
        border-radius: 5px;
        transition: all 0.3s ease-out;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -ms-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        border: 2px solid #000;
    }


    .checkbox-label input:checked ~ .checkbox-custom {
        background-color: #ff006c;
        border-radius: 5px;
        -webkit-transform: rotate(0deg) scale(1);
        -ms-transform: rotate(0deg) scale(1);
        transform: rotate(0deg) scale(1);
        opacity:1;
        /*border: 2px solid #000;*/
        border: 2px solid #ff0202;
    }

    .checkbox-label .checkbox-custom::after {
        position: absolute;
        content: "";
        left: 12px;
        top: 12px;
        height: 0px;
        width: 0px;
        border-radius: 5px;
        border: solid #000;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(0deg) scale(0);
        -ms-transform: rotate(0deg) scale(0);
        transform: rotate(0deg) scale(0);
        opacity:1;
        transition: all 0.3s ease-out;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -ms-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
    }


    .checkbox-label input:checked ~ .checkbox-custom::after {
        -webkit-transform: rotate(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        transform: rotate(45deg) scale(1);
        opacity:1;
        left: 8px;
        top: 3px;
        width: 6px;
        height: 12px;
        border: solid #000000;
        border-width: 0 2px 2px 0;
        background-color: transparent;
        border-radius: 0;
    }


/*for checkbox clororized custom*/
    /* The container */
    .containerxx {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
        text-align:left;
        color: #2fae05;
        cursor: pointer;
        font-size: 16px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        /*margin-left:-40px; margin-right:5px; text-align:left;padding: 1px;*/
    }

    /* Hide the browser's default checkbox */
    .containerxx input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmarkxx {
        position: absolute;
        top: 0;
        left: 0;
        height: 15px;
        width: 15px;
        background-color: rgba(255, 255, 255, 0.03);
        /*background-color: #ccc;*/
        border: solid #4c4b4b;
        /*border:  white;*/
        border-width: 1px 1px 1px 1px;
        border-radius: 20%;
    }

     /*On mouse-over, add a soft-red background color */
    .containerxx:hover input ~ .checkmarkxx {
        background-color: #e17878;
    }
    .checkbox-wrapper-kia .containerxx:hover {
        color: #e17878;
    }

    /* When the checkbox is checked, add a red background */
    .containerxx input:checked ~ .checkmarkxx {
        background-color: #b52121;
    }


    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmarkxx:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .containerxx input:checked ~ .checkmarkxx:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .containerxx .checkmarkxx:after {
        left: 4px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }


</style>




{{--checkbox nice samples design--}}
{{--https://getcssscan.com/css-checkboxes-examples--}}
{{--https://codepen.io/bcmdr/pen/oEwqPX--}}


@section('content')
    @php
        if (!function_exists('getBackupIdsAndSize')) {
            function getBackupIdsAndSize($backupFoldername)
            {
                $path = base_path('databasebackups/') . $backupFoldername;
                $backup_details = array();
                foreach (scanFolder($path) as $file) {
                    $righ = 0;
                    $keyq = 0;
                    $valueq = '';
                    switch (true) {
                        case (strpos(basename($file), 'database') !== false):
                            $righ = formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                            $keyq = 1;
                            $valueq = 'DataBase';
                            break;
                        case (strpos(basename($file), 'storage') !== false):
                            $righ = formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                            $keyq = 2;
                            $valueq = 'Folder';
                            break;
                        case (strpos(basename($file), 'addons') !== false):
                            $righ = formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                            $keyq = 4;
                            $valueq = 'Addons';
                            break;
                        case (strpos(basename($file), 'website') !== false):
                            $righ = formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                            $keyq = 8;
                            $valueq = 'Website';
                            break;
                        default:
                    }
                    $valuekey = $keyq;
                    $valueq .= ' ' . $righ;

                    $data['backup_id'] = $keyq;
                    $data['backup_size'] = $righ;
                    array_push($backup_details, $data);
                    //    <option value="{{$valuekey}}" {{$issharedcheck}}
                }
                    //                dd($backup_details);
                    //error_log("BACKUP DETAIL IS:=> ".json_encode($backup_details));
                    return json_encode($backup_details);
            }
}

//        if (!function_exists('getRows')) {
//            function getRows()
//            {
//                $share_path = base_path('databasebackups/share');
//                $jsonFile = $share_path . DIRECTORY_SEPARATOR . 'shares.json';
//
//                if (file_exists($jsonFile)) {
//                    $jsonData = file_get_contents($jsonFile);
//                    $data = json_decode($jsonData, true);
//
//                    return !empty($data) ? $data : false;
//                }
//                return false;
//            }
//        }

//        if (!function_exists('checkpassword')) {
//            function checkpassword(Request $request, $param)
//            {
//
//                $postpass = filter_input(INPUT_POST, "dwnldpwdxx", FILTER_SANITIZE_SPECIAL_CHARS);
//                if ($postpass) {
//                    $postpass = preg_replace('/\s+/', '', $postname);
//                    $passa = false;
//                    $passpass = false;
//                    if (md5($postpass) === $param) {
//                        $passa = true;
//                        $passpass = true;
//                    }
//                }
//            }
//        }

//        if (!function_exists('mytemplatefunction')) {
//            function mytemplatefunction($param)
//            {
//                return $param . " World";
//            }
//        }

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

//        if (!function_exists('checkTimeBetween')) {
//            function checkTimeBetween($time, $lifedays = false)
//            {
//                $lifedays = $lifedays ? (int)$lifedays : 1;
//                $lifetime = 86400 * $lifedays;
//                if (time() >= $time && time() <= $time + $lifetime) {
//                    return true;
//                }
//                return false;
//            }
//        }

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

        if (!function_exists('secondsToWords')) {
            function secondsToWords($seconds)
            {
                $days = intval(intval($seconds) / (3600 * 24));
                $hours = (intval($seconds) / 3600) % 24;
                $minutes = (intval($seconds) / 60) % 60;
                $seconds = intval($seconds) % 60;

        //    $days = $days ? $days . ' days' : '';
        //    $hours = $hours ? $hours . ' hours' : '';
        //    $minutes = $minutes ? $minutes . ' minutes' : '';
        //    $seconds = $seconds ? $seconds . ' seconds' : '';

        //    return $days . $hours . $minutes . $seconds;
                return $days . ' days & ' . $hours . ':' . $minutes . ':' . $seconds;
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
                return round($size, 2) . ' ' . $syz;
            }
        }

//        if (!function_exists('formatSize2')) {
//            /**
//             * Format file size
//             *
//             * @param string $size new format
//             *
//             * @return formatted size
//             */
//            function formatSize2($size)
//            {
//                $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
//                $syz = $sizes[0];
//                for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
//                    $size = $size / 1024;
//                    $syz = $sizes[$i];
//                }
//                return round($size, 2);
//            }
//        }

//        if (!function_exists('getCronJobBackupTypeList')) {
//            function getCronJobBackupTypeList()
//            {
//                $file = base_path('databasebackups/cronjob.json');
//                if (file_exists($file)) {
//                    return getFileData($file);
//                }
//                return [];
//            }
//        }
//
//        if (!function_exists('getCronJobBackupTypeList2')) {
//            function getCronJobBackupTypeList2()
//            {
//                $file = base_path('databasebackups/cronjob/cronjob.json');
//                if (file_exists($file)) {
//                    return getFileData($file);
//                }
//                return [];
//            }
//        }

        if (!function_exists('getFileData')) {
            function getFileData($file, $convert_to_array = true)
            {
                $file = File::get($file);
                if (!empty($file)) {
                    if ($convert_to_array) {
                        return json_decode($file, true);
                    } else {
                        return $file;
                    }
                }
                return false;
            }
        }

        if (!function_exists('scanFolder')) {
            function scanFolder($path, $ignore_files = [])
            {
                try {
                    if (is_dir($path)) {
                        $data = array_diff(scandir($path), array_merge(['.', '..'], $ignore_files));
                        natsort($data);
                        return $data;
                    }
                    return [];
                } catch (Exception $ex) {
                    return [];
                }
            }
        }

        if (!function_exists('getClientIP')) {
            function getClientIP()
            {
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // check ip from share internet
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check ip is pass from proxy
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = 'UNKNOWN';
                }
                return $ip;
            }
        }

    @endphp

    <div class="position-absolute" id="particles-js"></div>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <div class="alert alert-info" role="alert">
                    <b style="font-size:17px">{{ translate('The backup and restore (DataBase, Folder:public/uploads, Addons And WebSite) and ability to share and send download links is made for you with love by Kia from Aryaclub.com') }}
                        &#128151</b>
                </div>

                <div class="alert alert-warning" role="alert">
                    <p style="font-size:14px">{{ translate('This is a complete backup and restore feature, it is a solution for you if your site and database has < 1GB data, can be used for quickly backup and restore your site.')}}</p>
                    <p style="font-size:14px">{{ translate('If you have more than 1GB files/database/addons, you should use backup feature of your hosting or VPS.')}}</p>
                    <p style="font-size:14px">{{ translate('It is a full backup, it is back up files/website/addons and your database.')}}</p>
                </div>

            </div>
        </div>
    </div>

    @php
        session_start();
        $logspath = base_path('databasebackups/log');
        //   $logspath = '_content/log/';
        $loglist = glob($logspath . '*.json');
        // set most recenton top
        $loglist = array_reverse($loglist);
        $available_days = array();

        foreach ($loglist as $day) {
            $path_parts = pathinfo($day);

            $filenamearr = explode("-", $path_parts['filename']);
            $cleanname = array();
            foreach ($filenamearr as $filenamepart) {
                $cleanname[] = ltrim($filenamepart, '0');
            }
            $available_days[] = $path_parts['filename'];
        }
        $load_datepicker_lang = false;
        $regional_picker = 'en';
    @endphp

    {{--     //table selection for exclude from backup--}}
    <div class="card">
        @php
            $nr = count($tables);
            $cr = $nr / 10;
            $nr % $cr > 0 ? $cr++ : $cr;
            $db = "Tables_in_" . env('DB_DATABASE');
            $i = 0;
            $msg_select_all = "Select All (%s tables)";
            $msg_select_allx = "%s %s %s %s %s";

            $insert4 = "Please insert at least 4 chars, or leave blank to get a random password";
            $time = time();
            $datetime = date('d-m-Y H:i:s', strtotime(Carbon\Carbon::now()));
            $salt = '57a3e3fc49b81ba5856dc89dcf389b08';
            $hash = md5($salt . $time);
            $pulito = env('APP_URL');

            $sharelinkatts = array(
                'insert4' => $insert4,
                'time' => $time,
                'datetime' => $datetime,
                'hash' => $hash,
                'pulito' => $pulito,
            );
            $share_lifetime = array(
                // "days" => "menu value"
                "1" => "24 h",
                "2" => "48 h",
                "3" => "72 h",
                "5" => "5 days",
                "7" => "7 days",
                "10" => "10 days",
                "30" => "30 days",
                "365" => "1 year",
                "36500" => "Unlimited",
            );
            $backup_type = array(
                "1" => "DataBase",
                "2" => "Folder",
                "4" => "Addons",
                "8" => "WebSite",
            );
            $backup_type2 = array(
                "DataBase" => "1",
                "Folder" => "2",
                "Addons" => "4",
                "WebSite" => "8",
            );
            $lifetime = 1;
            $one_time_download = 0;
            $advance_download = 0;
            $AECmodals['share'] = $sharelinkatts;
        @endphp

        <div class="card-header">
            <label for="checkboxkia"
                   id="advcheck">{{ translate('Advanced selection for Tables, to exclude from Backup') }}
                <input onchange="show_hide_selection(this)" type="checkbox" name="checkboxkia"
                       {{--                       style="margin-left:-10px; margin-right:-10px; text-align:left;height:18px;">--}}
                       style="margin-left:5px; margin-right:5px; text-align:left;height:15px;">
            </label>
        </div>

        {{--    //table selection section--}}
        <div class="card-body" id="table_selection">

            <div class="card-header">
                <h6 class="fw-600 mb-0">{{translate('Select the Tables to explode from Backup')}}</h6>

                <label id="ch_all">
                    <input onchange="update_all_status({{$nr}})" type="checkbox"
                           style="margin-left:5px; margin-right:5px; text-align:left;height:20px; overflow:hidden; float:left; ">{{ sprintf($msg_select_all, $nr) }}
                </label>

                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6"></h5>
                    </div>
                </div>
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6"></h5>
                    </div>
                </div>
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6"></h5>
                    </div>
                </div>
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6"></h5>
                    </div>
                </div>

            </div>


            <div class="row gutters-10">
                <div class="col-lg-auto">
                    <div class="card shadow-none bg-light">
                        <div id="persian"></div>
                        <div class="card-body">
                            <form id="frm_cht">
                                @csrf

                                <div class="ch_tables row gutters-5">

                                @foreach($tableNames as $tableName)
                                        @if($i >0 && ($i %$cr) == 0)
                                </div>
                                <div class="ch_tables row gutters-5">
                                    @endif
                                    <div class="col-4 checkbox-wrapper-kia">
                                        <label class="containerxx" id="{{$tableName}}">
                                            <input onchange="update_status(this,{{$nr}})" type="checkbox"
                                                   name="tables[]"
                                                   value="{{ $tableName }}">{{ $tableName }}
                                            <span class="checkmarkxx"></span>
                                        </label>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                    @endforeach
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--    //(Create Backup) and (Check Permission) and (shre link Settings) and (Create Addons Backup) and (Create Cron Job Setting) buttons section--}}
        <div class="loader-container">
            <div style="display:none;" id="PersianGulf_HeadButtonSection" class="loader"></div>
        </div>

        <div class="row gutters-5">
            <div class="card-header row gutters-5">
                <div class="col">

                @can('create_backup')
                        <button data-toggle="modal" data-target="#createBackupxx"
                                onclick="show_animation_createBackup();"
                                style="margin-left:1px; margin-right:10px;"
                                class="btn btn-info btn-sm shadow-primary">{{ translate('Create Backup') }}
                            <i class="las la-archive la-2x"></i>
                        </button>
                    @endcan
                    @cannot('create_backup')
                        <a href="#!" data-toggle="modal"
                           onclick="show_cannot_modal()"
                           style="margin-left:1px; margin-right:10px;"
                           class="btn-primary btn-sm shadow-primary">{{ translate('Create Backup') }}
                            <i class="las la-archive la-2x"></i>
                        </a>
                    @endcan

                    <button data-toggle="modal" data-target="#checkPermission"
                            style="margin-left:10px; margin-right:10px;"
                            class="btn btn-info btn-sm shadow-primary">{{ translate('Check Permission') }}
                        <i class="las la-check la-2x"></i>
                    </button>

{{--                ftp setting--}}
                    @if(1>2)
                        <button data-toggle="modal" data-target="#ftpsettings"
                                style="margin-left:10px; margin-right:10px;"
                                class="btn btn-info btn-sm shadow-primary">{{ translate('Ftp Settings') }}
                            <i class="las la-cloud la-2x"></i>
                        </button>
                    @endif
{{--                ftp setting--}}

                    @php
                        $logdir = base_path('databasebackups/log');
                        //check any json log file exist in log directory, if yes continue else show to user not any log file exist
                        $logcount = 0;
                        foreach (glob($logdir."/*.json") as $logfilename) {
                            if (is_file($logfilename)) {
                                $logcount = $logcount + 1;
                            }
                        }
                    @endphp

                    @if($logcount>0)
                        <button data-toggle="modal" data-target="#logsettings"
                                style="margin-left:10px; margin-right:10px;"
                                class="btn btn-info btn-sm shadow-primary">{{ translate('Show log Activity') }}
                            <i class="las la-file-alt la-2x"></i>
                        </button>
                    @else
                        <button
                            style="margin-left:1px; margin-right:10px;"
                            class="disabled btn btn-info btn-sm shadow-primary">{{ translate('Show log Activity') }}
                            <i class="las la-chart-area la-2x"></i>
                        </button>
                    @endif

                    @php
                        $sharedir = base_path('databasebackups/share');
                        //check any json log file exist in share directory, if yes continue else show to user not any share file exist
                        $sharecount = 0;
                        foreach (glob($sharedir."/*.json") as $sharefilename) {
                            if (is_file($sharefilename)) {
                                $sharecount = $sharecount + 1;
                            }
                        }
                    @endphp

                    @if($sharecount>0)
                        <button data-toggle="modal" data-target="#checksharedlinks"
                                {{--                            onclick="show_check_share_modal();"--}}
                                style="margin-left:10px; margin-right:10px;"
                                class="btn btn-info btn-sm shadow-primary">{{ translate('Check Shared Links') }}
                            <i class="las la-link la-2x"></i>
                        </button>
                    @else
                        <button
                            style="margin-left:10px; margin-right:10px;"
                            class="disabled btn btn-info btn-sm shadow-primary">{{ translate('Check Shared Links') }}
                            <i class="las la-link la-2x"></i>
                        </button>
                    @endif

                    @can('alltasks_cronjob')
                        <button data-toggle="modal" data-target="#createCronjob2"
                                style="margin-left:10px; margin-right:10px;"
                                class="btn btn-info btn-sm shadow-primary">{{ translate('Config Cron Job') }}
                            <i class="las la-clock la-2x"></i>
                        </button>
                    @endcan
                    @cannot('alltasks_cronjob')
                        <a href="#!" data-toggle="modal"
                           onclick="show_cannot_modal()"
                           style="margin-left:1px; margin-right:10px;"
                           class="btn btn-primary btn-sm shadow-primary">{{ translate('Config Cron Job') }}
                            <i class="las la-archive la-2x"></i>
                        </a>
                    @endcan


                    <a href="{{ route('backups.generatejson') }}" onclick="ShowProgressBar();">
                        <button style="margin-left:10px; margin-right:10px;"
                                class="btn btn-info mt-0 btn-sm shadow-primary">{{ translate('Rebuild Structure')}}<i
                                class="las la-recycle la-2x"></i></button>
                    </a>

                    @php
                        $local = ($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1' || $_SERVER['REMOTE_ADDR']=='localhost') ? 1 : 0;
                    @endphp


                    @can('alltasks_cronjob')
                        @php
                            $cronjobdir = base_path('databasebackups/cronjob');
                            //check any json log file exist in cronjob directory, if yes continue else show to user not any cronjob file exist
                            $cronjobdircount = 0;
                            foreach (glob($cronjobdir."/*.json") as $cronjobfilename) {
                                if (is_file($cronjobfilename)) {
                                    $cronjobdircount = $cronjobdircount + 1;
                                }
                            }
                        @endphp

                        @if($cronjobdircount>0)
                            <button data-toggle="modal" data-target="#checkcronjobs2"
                                    {{--                            onclick="show_check_share_modal();"--}}
                                    style="margin-left:10px; margin-right:10px;"
                                    class="btn btn-info btn-sm shadow-primary">{{ translate('Check Cronjob') }}
                                <i class="las la-clock la-2x"></i>
                            </button>
                        @else
                            <button
                                style="margin-left:10px; margin-right:10px;"
                                class="disabled btn btn-info btn-sm shadow-primary">{{ translate('Check Cronjob') }}
                                <i class="las la-clock la-2x"></i>
                            </button>
                        @endif
                    @endcan
                    @cannot('alltasks_cronjob')
                        <a href="#!" data-toggle="modal"
                           onclick="show_cannot_modal()"
                           style="margin-left:1px; margin-right:10px;"
                           class="btn btn-primary btn-sm shadow-primary">{{ translate('Check Cronjob') }}
                            <i class="las la-archive la-2x"></i>
                        </a>
                    @endcan

                </div>
            </div>
        </div>


        {{--    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}
        {{--    //show table of backuped content section--}}
        <form id="sort_backupsxx" action="" method="GET">
{{--bulk action *************--}}
            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal">{{translate('Delete selection')}}</a>
                </div>
            </div>
{{--*************--}}


            <div class="card-body">
                {{--                <table id="BackUpTable" class="display table table-bordered table-striped">--}}
                <table id="BackUpTablexx" class="display table table-bordered StandardTable">
                    <thead>
                    <tr>
                        <th scope="ro"></th>



                        {{--bulk action *************--}}
                        <th>
{{--                            <div class="form-group">--}}
{{--                                <div class="aiz-checkbox-inline">--}}
{{--                                    <label class="aiz-checkbox">--}}
{{--                                        <input type="checkbox" class="check-all">--}}

{{--                                        @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&--}}
{{--         Session::get('locale', Config::get('app.locale')) == 'ir')--}}
{{--                                            <span class="aiz-square-check" style="margin-left:15px;margin-top:-5px; text-align:left;height:18px;"></span>--}}
{{--                                        @else--}}
{{--                                            <span class="aiz-square-check" style="margin-left:15px; margin-top:-5px; text-align:left;height:18px;"></span>--}}
{{--                                        @endif--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}



                            <div class="checkbox-container">
                                <label class="checkbox-label">
                                    <input type="checkbox" class="check-all">
                                    <span class="checkbox-custom"></span>
                                </label>
                            </div>

                        </th>
                        {{--*************--}}



                        <th scope="col">{{ translate('Name') }}</th>
                        <th scope="col">{{ translate('Size') }}</th>
                        <th scope="col">{{ translate('Date') }}</th>
                        <th scope="col">{{ translate('Backup Type') }}</th>
                        <th scope="col">{{ translate('Actions') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php
                        $rownumberxx = 0;
//                    $rownumber = ($pageNum > 1) ? ($pageNum * $rowsPerPage) - $rowsPerPage : 0;
                        ?>

                    @foreach ($backupsxx as $keyxx => $backupxx)
                            <?php
                            $rownumberxx++;
                            ?>
                        <tr>

                            <td class="text-dark">{{ $rownumberxx }}</td>



                            {{--bulk action *************--}}
                            <td>
{{--                                <div class="form-group">--}}
{{--                                    <div class="aiz-checkbox-inline">--}}
{{--                                        <label class="aiz-checkbox">--}}
{{--                                            <input type="checkbox" role="switch" class="check-one" id="backupklid" name="backupklid[]" value="{{$backupxx['klid']}}">--}}
{{--                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&--}}
{{--                                                Session::get('locale', Config::get('app.locale')) == 'ir')--}}
{{--                                                <span class="aiz-square-check" style="margin-left:35px; margin-right:15px; margin-top:15px; text-align:left;height:18px;"></span>--}}
{{--                                            @else--}}
{{--                                                <span class="aiz-square-check" style="margin-left:15px; margin-right:35px; margin-top:15px; text-align:left;height:18px;"></span>--}}
{{--                                            @endif--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                    <div class="checkbox-container">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="check-one" name="backupklid[]" value="{{$backupxx['klid']}}">
                                            <span class="checkbox-custom" style="margin-top:10px;"></span>
                                        </label>
                                    </div>



                            </td>
                            {{--*************--}}




                            <td width="12%;" class="text-dark" style="font-size: 0.88rem; font-weight:normal;">{{ $backupxx['name'] }}</td>
                            <td style="font-size: 0.88rem; font-weight:normal;" data-title="{{translate('backup size') }} :">
                                {{ $backupxx['size'] }}
                            </td>
                            <td width="13%;" style="font-size: 0.88rem; font-weight:normal;"
                                data-title="{{ translate('backup created at') }} :">{{ $backupxx['date'] }}</td>
                            <td width="18%;" data-title="{{ translate('Backup Type') }} :">

                                @foreach($backup_type as $keyq => $valueq)
                                    @foreach( $backupxx['type'] as $keyqxx )
                                        @if((int)$keyqxx===(int)$keyq)
                                            <span class="badge badge-inline badge-md bg-secondary">{{ $valueq }}</span>
                                        @endif
                                    @endforeach
                                @endforeach

                            </td>

                            <td>
                                {{--                                <table width="100%" class="display table table-bordered StandardTable" style="margin: -10px">--}}
                                <table width="100%" style="margin: -10px;border-collapse: collapse; border: none;">

                                    <tr style="border: none;">
                                        {{--                                    new method download--}}
                                        <td style="border: none;">
                                            @can('download_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_download_modal('{{$keyxx}}', '{{json_encode($backupxx['type'])}}', {{json_encode(getBackupIdsAndSize($backupxx['klid']))}})"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                    class="btn btn-primary btn-sm shadow-primary">{{ translate('Download') }}
                                                    <i class="las la-file-download la-2x"></i>
                                                </a>
                                            @endcan
                                            @cannot('download_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_cannot_modal()"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-primary btn-sm shadow-primary">{{ translate('Download') }}
                                                    <i class="las la-file-download la-2x"></i>
                                                </a>
                                            @endcan
                                        </td>

                                        {{--                                    new method delete--}}
                                        <td style="border: none;">
                                            @can('delete_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_delete_modal('{{$keyxx}}', '{{json_encode($backupxx['type'])}}', {{json_encode(getBackupIdsAndSize($backupxx['klid']))}})"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-danger btn-sm shadow-primary">{{ translate('Delete') }}
                                                    <i class="las la-trash-alt la-2x"></i>
                                                </a>
                                            @endcan
                                            @cannot('delete_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_cannot_modal()"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-danger btn-sm shadow-primary">{{ translate('Delete') }}
                                                    <i class="las la-trash-alt la-2x"></i>
                                                </a>
                                            @endcan
                                        </td>

                                        <td style="border: none;">
                                            @can('share_backup')
                                                <a href="#!" id="shareId_{{$backupxx['klid']}}" data-toggle="modal"
                                                   data-id="{{ json_encode($backupxx['type']) }}"
                                                   onclick="show_share_modal('{{$backupxx['klid']}}', '{{json_encode($backupxx['type'])}}', {{json_encode(getBackupIdsAndSize($backupxx['klid']))}})"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-info btn-sm shadow-primary">{{ translate('Share') }}
                                                    <i class="las la-share-alt-square la-2x"></i>
                                                </a>
                                            @endcan
                                            @cannot('share_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_cannot_modal()"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-info btn-sm shadow-primary">{{ translate('Share') }}
                                                    <i class="las la-share-alt-square la-2x"></i>
                                                </a>
                                            @endcan
                                        </td>

{{--                                    upload and download via ftp--}}
                                        @if(1>2)
                                        <td style="border: none;">
{{--                                            @can('delete_backup')--}}
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_ftp_modal('{{$keyxx}}', '{{json_encode($backupxx['type'])}}', {{json_encode(getBackupIdsAndSize($backupxx['klid']))}})"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-success btn-sm shadow-primary">{{ translate('Ftp') }}
                                                    <i class="las la-file-export la-2x"></i>
                                                </a>
{{--                                            @endcan--}}
{{--                                            @cannot('delete_backup')--}}
{{--                                                <a href="#!" data-toggle="modal"--}}
{{--                                                   onclick="show_cannot_modal()"--}}
{{--                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"--}}
{{--                                                   class="btn btn-danger btn-sm shadow-primary">{{ translate('Delete') }}--}}
{{--                                                    <i class="las la-trash-alt la-2x"></i>--}}
{{--                                                </a>--}}
{{--                                            @endcan--}}
                                        </td>
                                        @endif
{{--                                    upload and download via ftp--}}



                                        @php
                                            $sum_restoretype = 0;
                                            foreach ($backupxx['type'] as $restoretype) {
                                            $sum_restoretype = $sum_restoretype + $restoretype;
                                            }
                                        @endphp


                                        <td style="border: none;">
                                            @can('restore_backup')
                                                {{--                                        if backup type id addon or website or both addon and website then restore is diabled and backup must restored manually--}}
                                                @if ($sum_restoretype === 4 || $sum_restoretype === 8 || $sum_restoretype === 12 )
                                                    <a href="#!" data-toggle="modal"
                                                       data-target="#restoreBackupxx_{{ $keyxx }}"
                                                       style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                       class="disabled btn btn-warning btn-sm shadow-primary">{{ translate('Manually') }}
                                                        <i class="las la-user-ninja la-2x"></i>
                                                    </a>
                                                @else
                                                    {{--                                                new method restore--}}
                                                    <a href="#!" data-toggle="modal"
                                                       {{--                                                   data-target="#restoreBackupxx_{{ $keyxx }}"--}}
                                                       onclick="show_restore_modal('{{$keyxx}}', '{{json_encode($backupxx['type'])}}', {{json_encode(getBackupIdsAndSize($backupxx['klid']))}})"
                                                       style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                       class="btn btn-warning btn-sm shadow-primary">{{ translate('Restore') }}
                                                        <i class="las la-trash-restore la-2x"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                            @cannot('restore_backup')
                                                <a href="#!" data-toggle="modal"
                                                   onclick="show_cannot_modal()"
                                                   style="margin-left:1px; padding: 0.2rem 0.6rem;"
                                                   class="btn btn-warning btn-sm shadow-primary">{{ translate('Restore') }}
                                                    <i class="las la-trash-restore la-2x"></i>
                                                </a>
                                            @endcan

                                        </td>


                                    </tr>
                                </table>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </form>
        {{--    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}

    </div>

    {{--    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}

    {{--    //create backup section--}}
    <div class="modal fade" id="createBackupxx" tabindex="-1" role="dialog" aria-labelledby="createBackupxx"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Generate New Backup') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{--get bits function--}}
                {{--@php--}}
                {{--$arr= [64,32,16,8,4,2,1];--}}
                {{--$number = 97;--}}
                {{--$bin = str_split(str_pad(decbin($number),count($arr),"0",STR_PAD_LEFT));--}}

                {{--$reza1 = implode(",", array_intersect_key($arr,array_intersect($bin, ["1"])));--}}



                {{--//If the order is important then you need to sort it prior to output.--}}
                {{--$nums = array_intersect_key($arr,array_intersect($bin, ["1"]));--}}
                {{--sort($nums);--}}
                {{--$reza2 = implode(",", $nums);--}}


                {{--@endphp--}}
                {{--<label class="form-label"--}}
                {{--style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ $reza1 }}--}}
                {{--:</label>--}}

                {{--<label class="form-label"--}}
                {{--style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ $reza2 }}--}}
                {{--:</label>--}}

                {{--<label class="form-label"--}}
                {{--style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ str_pad(decbin($number),count($arr),"0",STR_PAD_LEFT) }}--}}
                {{--:</label>--}}

                {{--                <form id="frm_backup" action="{{ route('backups.store') }}" method="POST">--}}
                <form id="frm_createBackupxx" action="{{ route('backups.storexx') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group required">
                            <label for="name">{{ translate('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder={{ translate('Optional Auto Generate') }}>
                            {{--                                   placeholder={{ translate('Name') }}: {{ translate('Optional Auto Generate') }}>--}}
                            {{--                                   placeholder={{ translate('Name') }}: {{ translate('Optional') }}>--}}
                        </div>

                        @php
                            $addonsdir = public_path('addons');
                            //check any zip file exist in addons directory, if yes continue else show to user not any zip file exist
                            $addons_installed_count = 0;
                            foreach (glob($addonsdir."/*.zip") as $filename) {
                                if (is_file($filename)) {
                                    $addons_installed_count = $addons_installed_count + 1;
                                }
                            }
//                            $addons_installed_count = 0;
                        @endphp

                        <div class="backup-choose-list">
                            <div class="backup-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                           for="name">{{translate('Choose Your Backup Type')}}</label>
                                    <div class="col-lg-9">
                                        {{--                                    $backup_type = array(--}}
                                        {{--                                    "1" => "DataBase",--}}
                                        {{--                                    "2" => "Folder",--}}
                                        {{--                                    "4" => "Addons",--}}
                                        {{--                                    "8" => "WebSite",--}}
                                        {{--                                    );--}}
                                        @php
                                            $disabled_msg = '  '. translate('(Disabled, because you have not installed any plugins)') ;
                                            $enabled_msg = '  '. translate('Create Backup From Installed Addons') . '(' . $addons_installed_count . translate('addons') . ')';
                                            $enabled_msg = '  '. translate('Installed Addons') . '(' . $addons_installed_count . translate('addons') . ')';

                                        @endphp

                                        <select name="backup_ids[]" class="form-control backup_id aiz-selectpicker"
                                                data-live-search="true" data-selected-text-format="count" required
                                                multiple>
                                            @foreach($backup_type as $keyq => $valueq)

                                                @php
                                                    if($keyq===4){
                                                        if($addons_installed_count<1){
                                                            $valueq .= $disabled_msg;
                                                        }
                                                        else {
                                                            $valueq .= $enabled_msg;
                                                        }
                                                    }
                                                @endphp

                                                <option value="{{$keyq}}"
                                                        @if ($keyq===4 && $addons_installed_count<1) disabled
                                                        style="color: #ff7e00;font-size: .99rem;font-weight: bold;"
                                                        @elseif ($keyq===4 && $addons_installed_count>0) style="color: #206b07;font-size: .99rem;font-weight: bold;"
                                                    {{--                                                    @elseif ($keyq===4 && $addons_installed_count>0)--}}
                                                    @endif
                                                >{{ $valueq }}
                                                </option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="checkboxeslistzz" name="checkboxeslist" value="[]"/>
                        <input type="hidden" id="tablecount" name="tablecount" value="{{ count($tables) }}"/>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="submit"
                                    class="btn btn-success">{{ translate('Generate') }}</button>
                        </div>

                    </div>
                </form>

                <div style="display:none;" id="PersianGulf_CreateBackup" class="currently-loading">
                </div>

            </div>
        </div>
    </div>


    {{--    //create cron job backup section222222222222--}}
    <div class="modal fade" id="createCronjob2" tabindex="-1" role="dialog" aria-labelledby="createCronjob"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Generate New Cron Job Backup') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    @php
                        $addonsdir = public_path('addons');
                        $cronlink =  'wget -O - ' . env('APP_URL') . '/backups/cronjobbackup' . '/dde1d3a9a64106e2cbaa0bd35e654224';

                        //check any zip file exist in addons directory, if yes continue else show to user not any zip file exist
                        $addons_installed_count = 0;
                        foreach (glob($addonsdir."/*.zip") as $filename) {
                            if (is_file($filename)) {
                                $addons_installed_count = $addons_installed_count + 1;
                            }
                        }

                        $disabled_msg = '  '. translate('(Disabled, because you have not installed any plugins)') ;
                        $enabled_msg = '  '. translate('Create Backup From Installed Addons') . '(' . $addons_installed_count . translate('addons') . ')';
                        $enabled_msg = '  '. translate('Installed Addons') . '(' . $addons_installed_count . translate('addons') . ')';
                    @endphp

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label"
                               style="font-size:large; color: #ff6e00;margin-top:13px;margin-right:-1px;display:block;font-weight: bold;"
                               for="cron_backup_idsxxx">{{translate('Choose Your Backup Type')}}</label>
                        <div class="col-lg-9">
                            <select name="cron_backup_idsxxx[]" id="cron_backup_idsxxx"
                                    class="form-control aiz-selectpicker"
                                    data-live-search="true" data-selected-text-format="count"
                                    multiple>

                                @foreach($backup_type as $keyq => $valueq)

                                    @php
                                        if($keyq===4){
                                            if($addons_installed_count<1){
                                                $valueq .= $disabled_msg;
                                            }
                                            else {
                                                $valueq .= $enabled_msg;
                                            }
                                        }
                                    @endphp

                                    <option value="{{$keyq}}"
                                            @if ($keyq===4 && $addons_installed_count<1) disabled
                                            style="color: #ff7e00;font-size: .99rem;font-weight: bold;"
                                            @elseif ($keyq===4 && $addons_installed_count>0) style="color: #206b07;font-size: .99rem;font-weight: bold;"
                                        {{--                                                    @elseif ($keyq===4 && $addons_installed_count>0)--}}
                                        @endif
                                    >{{ $valueq }}
                                    </option>

                                @endforeach


                            </select>
                        </div>
                    </div>

                    <div class="form-group row createcronjobtask-wrap mb-3">
                        <label class="col-lg-3 col-from-label"
                               for="name"></label>
                        <div class="col-lg-9">
                            <button id="createcronjobtask" class="btn btn-info">
                                <i class="las la-check-circle"></i> {{ translate('Generate cron') }}
                            </button>
                        </div>
                    </div>

                    <div class="cron-backup-choose-listxxx">
                        <div class="cron-backup-choose">

                            <div class="form-group row cronjob-div-testlink">
                                <label class="col-lg-3 col-from-label"
                                       for="name">{{translate('Cronjob Link For Test')}}</label>
                                <div class="col-lg-9">

                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a class="btn btn-info cronjobrunbutt" href="#" target="_blank"
                                               style="height:42px;"><i
                                                    class="las la-link"></i></a>
                                        </span>
                                        <input dir="ltr" id="croncopylink" class="cronlink form-control" type="text"
                                               onclick="this.select()"
                                               readonly>
                                        <span class="input-group-btn">
                                        <button onclick="CronLinkcopyToClipBoard();" id="cronclipme"
                                                class="cronclipme btn btn-info"
                                                data-bs-toggle="popover" data-bs-placement="bottom"
                                                data-bs-content=" {{ translate('copied') }}"
                                                data-clipboard-target="#croncopylink" style="height:42px;">
                                            <i class="las la-clipboard-check"></i>
                                        </button>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row cronjob-div-task">
                                <label class="col-lg-3 col-from-label"
                                       for="name">{{translate('Cronjob Link For Task')}}</label>
                                <div class="col-lg-9">

                                    <div class="input-group">
                                        <input dir="ltr" id="croncopytask" class="crontask form-control" type="text"
                                               onclick="this.select()"
                                               readonly>
                                        <span class="input-group-btn">
                                        <button onclick="CronTaskcopyToClipBoard();" id="cronclipme"
                                                class="cronclipme btn btn-info"
                                                data-bs-toggle="popover" data-bs-placement="bottom"
                                                data-bs-content=" {{ translate('copied') }}"
                                                data-clipboard-target="#croncopytask" style="height:42px;">
                                            <i class="las la-clipboard-check"></i>
                                        </button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            {{--                                ✿✿✿✿☊✪☊✪☊✪☊✿✿✿✿--}}

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label"
                                       style="font-size: large; color: #0095f8;margin-top:13px;margin-right:-1px;display:block;"
                                       for="name">{{translate('Important Info')}} *</label>
                                <div class="col-lg-9">

                                    <p>
                                        <a class="btn btn-primary" data-toggle="collapse"
                                           href="#multiCollapseExample1xxx"
                                           role="button" aria-expanded="false" aria-controls="multiCollapseExample1xxx">
                                            {{translate('Important Info For How To Config Cron Job, Click Here To Read')}}
                                            <i class="las la-mouse-pointer la-2x"></i>
                                        </a>
                                    </p>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="collapse multi-collapse" id="multiCollapseExample1xxx">
                        @php
                            $my_ip = getClientIP();
                        @endphp
                        <div class="modal-body">
                            <div class="alert alert-info" role="alert" style="margin-top: -5px;">
                                <b>{{ translate('Your IP') }} : {{$my_ip}}</b>
                            </div>
                            <div class="alert alert-info" role="alert" style="margin-top: -15px;">
                                <b>{{ translate('Cron jobs allow you to automate certain commands or scripts on your site.') }}</b>
                                <b>{{ translate('You can set a command or script to run at a specific time every day, week, etc.') }}</b>
                            </div>
                            <div class="alert alert-info" role="alert" style="margin-top: -28px">
                                <b>● {{ translate('For example, you can set a cron job to backup the database, website, specific folder') }}</b>
                                <b> {{ translate('weekly or monthly or daily or at a certain time to make sure you don`t lose your data.') }}</b>
                            </div>
                            <div class="alert alert-warning" role="alert" style="margin-top: -15px">
                                <b>{{ translate('Note: You can set up a cron job on your server.') }}</b>
                                <b>{{ translate('To set a cron job, login to your cpanel and find the Cron Jobs option.') }}</b>
                            </div>
                            <div class="alert alert-warning" role="alert" style="margin-top: -28px">
                                <b>● {{ translate('Go to Cron Jobs.') }}</b>
                            </div>
                            <div class="alert alert-warning" role="alert" style="margin-top: -28px">
                                <b>● {{ translate('Choose and set your desired time period.') }}</b>
                            </div>
                            <div class="alert alert-warning" role="alert" style="margin-top: -28px">
                                <b>● {{ translate('Add new Cron Job.') }}</b>
                            </div>
                            <div class="alert alert-warning" role="alert" style="margin-top: -28px">
                                <b>● {{ translate('Set command as') }} : {{$cronlink}}
                                    .{{ translate("You can copy it from the link above")}}</b>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <div class="alert alert-info" role="alert" style="margin-top: -15px">
                        <b>{{ translate('You can change your backup type at any time.') }}</b>
                        <b>{{ translate('If you have not selected any type of backup, no error will occur.') }}</b>
                        <b>{{ translate('The cron job will run every time at the set time, but no backup will be made. This is an idea for temporary deactivation.') }}</b>
                    </div>

                    <div class="alert alert-warning" role="alert" style="margin-top: -15px">
                        <b style='color:#d9870b;font-size: 15px;'>● {{ translate('Note:') }}</b>
                        <b>● {{ translate('This script works on cPanel. To make it work on VPS, you need to make the necessary settings yourself.') }}</b>
                        <b> {{ translate('If you run the script on localhost, there will be a button to test the cron job.') }}</b>
                    </div>
                    <div class="alert alert-danger" role="alert" style="margin-top: -15px">
                        <b style='color:red;font-size: 15px;'>● {{ translate('Warning:') }}</b>
                        <b> {{ translate('You need to have a good knowledge of Linux commands before you can use cron jobs effectively.') }}</b>
                        <b> {{ translate('You need to have a good knowledge of Linux commands before you can use cron jobs effectively.') }}</b>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{--   //Check CronJobs--}}
    <div class="modal fade" id="checkcronjobs2" tabindex="-1" role="dialog"
         aria-labelledby="checkcronjobs2" aria-hidden="true">
        <div class="modal-body">

            {{--            <div class="modal-dialog modal-lg">--}}
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Check Cronjob') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="loader-container">
                        <div style="display:none;" id="PersianGulf_runcronjobtask" class="loader"></div>
                    </div>

                    <div class="row">

                        <div class="card-body">
                            <div style="height:630px; overflow-y: scroll;">

                                @if(!empty($cronjobs))

                                    <table id="CronjobsTable"
                                           class="display table table-striped table-bordered StandardTable"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{translate('Cronjob Type')}}</th>
                                            <th>{{translate('Created at')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                        </thead>

                                        <tbody id="cronData">

                                        @if(!empty($cronjobs))
                                            @php
                                                $count = 0;
                                            @endphp

                                            @foreach ($cronjobs as $keycronjob => $cronjob)
                                                @php
                                                    $count++;
                                                @endphp
                                                <tr>
                                                    <td width="3%;">{{ $count }}</td>
                                                    @php
                                                        $count_file_exist = 0;
                                                        $countfiles_x = 0;
                                                    @endphp
                                                    @if (count($cronjob['type'])>0 && !empty($cronjob['type']) && $cronjob['type'] != null)

                                                        <td width="25%;" data-title="{{ translate('Cronjob Type') }} :">
                                                            @foreach($backup_type as $keyq => $valueq)
                                                                @foreach( $cronjob['type'] as $keyqxx )
                                                                    @if((int)$keyqxx === (int)$keyq)
                                                                        @php
                                                                            $countfiles_x++;
                                                                            $count_file_exist++;
                                                                        @endphp
                                                                        <span
                                                                            class="badge badge-inline badge-md bg-secondary">{{ $valueq }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        </td>

                                                        <td width="22%;" data-title="{{ translate('Cronjob time') }} :">
                                                            <span
                                                                class="badge badge-inline badge-md bg-secondary">{{ $cronjob['time'] }}</span>
                                                        </td>

                                                    @else
                                                        <td width="22%;" data-title="{{ translate('Backup Type') }} :">
                                                            <span class="badge badge-inline badge-warning"
                                                                  style="font-size:.95rem;color:#e70060;">{{ translate("deleted") }}</span>
                                                        </td>
                                                        <td width="22%;" data-title="{{ translate('Cronjob time') }} :">
                                                            <span class="badge badge-inline badge-warning"
                                                                  style="font-size:.95rem;color:#e70060;">{{ translate("deleted") }}</span>
                                                        </td>
                                                    @endif


                                                    <td width="50%;">
                                                        @if($count_file_exist>0)
                                                            <a href="#!" data-toggle="modal"
                                                               data-target="#editCronjobTask_{{ $keycronjob }}"
                                                               style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                               class="btn btn-info">{{ translate('Edit Cronjob') }}
                                                                <i class="las la-edit la-2x"></i>
                                                            </a>


                                                            @can('alltasks_cronjob')
                                                                <a href="#!"
                                                                   onclick="confirm_cronjobtask_delete('{{route('backups.deletecronjobtask', encrypt($cronjob['idfilename']))}}');"
                                                                   title="{{ translate('Delete this Cronjob') }}"
                                                                   style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                                   class="btn btn-danger">{{ translate('Delete') }}
                                                                    <i class="las la-trash-alt la-2x"></i>
                                                                </a>
                                                            @endcan
                                                            @cannot('alltasks_cronjob')
                                                                <a href="#!" data-toggle="modal"
                                                                   onclick="show_cannot_modal()"
                                                                   style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                                   class="btn btn-danger">{{ translate('Delete') }}
                                                                    <i class="las la-trash-alt la-2x"></i>
                                                                </a>
                                                            @endcan

                                                            @can('alltasks_cronjob')
                                                                <a href="{{ route('backups.cronjobbackup', ["test" => 1, "linkkey" => $cronjob['idfilename']]) }}"
                                                                   onclick="ShowProgressBarruncronjobtask();">
                                                                    <button style="margin-left:10px; margin-right:10px;"
                                                                            class="btn btn-primary mt-0">{{ translate('execute cron')}}
                                                                        <i class="las la-play la-2x"></i>
                                                                    </button>
                                                                </a>
                                                            @endcan
                                                            @cannot('alltasks_cronjob')
                                                                <a href="#!" data-toggle="modal"
                                                                   onclick="show_cannot_modal()"
                                                                   style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                                   class="btn btn-danger">{{ translate('execute cron') }}
                                                                    <i class="las la-trash-alt la-2x"></i>
                                                                </a>
                                                            @endcan

                                                        @else
                                                            <a href="#!" data-toggle="modal"
                                                               data-target="#editCronjobTask_{{ $keycronjob }}"
                                                               style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                               class="disabled btn btn-info">{{ translate('Edit Cronjob') }}</a>
                                                            <a href="#!"
                                                               title="{{ translate('Delete this Cronjob') }}"
                                                               style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                               class="disabled btn btn-soft-danger">{{ translate('Delete') }}
                                                                <i class="las la-trash-alt la-2x"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">No member(s) found...</td>
                                            </tr>
                                        @endif

                                        </tbody>

                                    </table>

                                @else
                                    <tr>
                                        <td colspan="8">No member(s) found...</td>
                                    </tr>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Ban Seller Modal -->
    <div class="modal fade" id="confirm-cronjobtask-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                    </button>
                </div>

                <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-danger" role="alert">
                    <p>{{translate('Do you really want to delete this cronjob task data?')}}</p>
                </div>



                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <a class="btn btn-primary" id="confirmation">{{translate('Proceed!')}}</a>
                </div>
            </div>
        </div>
    </div>



    {{--    //edit cronjob task info section--}}
    @if($cronjobs)
        @foreach ($cronjobs as $keycronjob => $cronjob)
            <div class="modal fade" id="editCronjobTask_{{ $keycronjob }}" tabindex="-1" role="dialog"
                 aria-labelledby="editCronjobTask_{{ $keycronjob }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ translate('Confirm Edit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        @php
                            $addonsdir = public_path('addons');
                            //check any zip file exist in addons directory, if yes continue else show to user not any zip file exist
                            $addons_installed_count = 0;
                            foreach (glob($addonsdir."/*.zip") as $filename) {
                                if (is_file($filename)) {
                                    $addons_installed_count = $addons_installed_count + 1;
                                }
                            }

                            $crontask =  'wget -O - ' . env('APP_URL') . '/backups/cronjobbackup/0/' . $cronjob['idfilename'];
//                            $cronlink =  env('APP_URL') . '/backups/cronjobbackup/1/' . $cronjob['idfilename'];
                            $cronlink =  env('APP_URL') . '/backups/executecronjobfromlink/3/' . $cronjob['idfilename'];
                            $cron_path = base_path('databasebackups/cronjob');
                            $jsonFile = $cron_path . DIRECTORY_SEPARATOR .  'cronjob.json';
                            $shared_msg = '  '. translate('(Selected for cronjob backup)') ;
                            $noshared_msg = '  '. translate('(Not selected for sharing)') ;
                            $disabled_msg = '  '. translate('(Disabled, because you have not installed any plugins)') ;
                            $enabled_msg = '  '. translate('Installed Addons') . '(' . $addons_installed_count . translate('addons') . ')';

                        @endphp
                        <div class="modal-body">


                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label"
                                       for="name">{{translate('Cronjob Link For Task')}}</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a class="disabled btn btn-info" href="#!" target="_blank"
                                               style="height:42px;"><i
                                                    class="las la-link"></i></a>
                                        </span>


                                        <input dir="ltr" id="croncopytask2_{{$keycronjob}}" class="form-control"
                                               type="text"
                                               onclick="this.select()"
                                               value="{{$crontask}}" readonly>
                                        <span class="input-group-btn">
                                        <button onclick="CronTaskcopyToClipBoardx(this,{{$keycronjob}});"
                                                class="btn btn-info"
                                                data-bs-toggle="popover" data-bs-placement="bottom"
                                                data-bs-content=" {{ translate('copied') }}"
                                                data-clipboard-target="#croncopytask" style="height:42px;">
                                            <i class="las la-clipboard-check"></i>
                                        </button>
                                        </span>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label"
                                       for="name">{{translate('Cronjob Link For Test')}}</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                    <span class="input-group-btn">
                                        <a class="btn btn-info" href="{{$cronlink}}" target="_blank"
                                           style="height:42px;"><i
                                                class="las la-link"></i></a>
                                    </span>
                                        <input dir="ltr" id="croncopylink2_{{$keycronjob}}" class="form-control"
                                               type="text"
                                               onclick="this.select()"
                                               value="{{$cronlink}}" readonly>
                                        <span class="input-group-btn">
                                            <button onclick="CronLinkcopyToClipBoardx(this,{{$keycronjob}});"
                                                    class="btn btn-info"
                                                    data-bs-toggle="popover" data-bs-placement="bottom"
                                                    data-bs-content=" {{ translate('copied') }}"
                                                    data-clipboard-target="#croncopylink" style="height:42px;">
                                                <i class="las la-clipboard-check"></i>
                                            </button>
                                    </span>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <form class="form-horizontal" id="frm_editcronjobtask{{ $cronjob['idfilename'] }}"
                              action="{{ route('backups.editcronjobtask') }}" method="POST">
                            @csrf
                            <input type="hidden" name="idkey" value="{{ $cronjob['idfilename'] }}"/>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
    Session::get('locale', Config::get('app.locale')) == 'ir')
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('edit cronjob files?') }}
                                                    :</label>
                                            @else
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('edit cronjob files?') }}
                                                    :</label>
                                            @endif

                                            @if(file_exists($jsonFile) && (!empty($cronjob['type']) && $cronjob['type'] != NULL && $cronjob['type'] != ""))
                                                <select name="edit_crownjob_ids[]"
                                                        class="form-control product_id aiz-selectpicker"
                                                        data-live-search="true" data-selected-text-format="count"
                                                        required
                                                        multiple>
                                                    @foreach($backup_type as $keyq => $valueq)

                                                        @php
                                                            if($keyq == 4){
                                                                if($addons_installed_count<1){
                                                                    $valueq .= $disabled_msg;
                                                                }
                                                                else {
                                                                    $valueq .= $enabled_msg;
                                                                }
                                                            }
                                                        @endphp

                                                        @foreach( $cronjob['type'] as $keyqxx )
                                                            @php
                                                                $selected='0';
                                                                if((int)$keyqxx == (int)$keyq){
                                                                    $selected='1';
                                                                    break;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                        <option value="{{$keyq}}"
                                                                @if ($keyq===4 && $addons_installed_count<1) disabled
                                                                style="color: #ff7e00;font-size: .90rem;font-weight: bold;"
                                                                @elseif ($keyq===4 && $addons_installed_count>0)
                                                                    @if ($selected == 1)
                                                                        style="color: #206b07;font-size: .90rem;font-weight: bold;"
                                                                @else
                                                                    style="color: #ff7e00;font-size: .90rem;font-weight: bold;"
                                                                @endif
                                                                @endif
                                                                @if($selected == 1) selected @endif

                                                                @if ($selected == 1)
                                                                    @php
                                                                        $valueq .= $shared_msg;
                                                                    @endphp
                                                                    style="color: #206b07;font-size: .90rem;font-weight: bold;"
                                                                @else
                                                                    @php
                                                                        $valueq .= $noshared_msg;
                                                                    @endphp
                                                                    style="color: #ff7e00;font-size: .90rem;font-weight: bold;"
                                                            @endif

                                                        >{{ $valueq }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            @endif


                                        </div>
                                    </div>
                                </div>


                                <div class="alert alert-warning" role="alert">
                                    <p style="font-size:14px">{{ translate('Note: There must be at least one backup type to cronjob workedshare.')}}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="submit"
                                            class="btn btn-primary">{{translate('Save Cronjob Task Configuration')}}</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endif



    {{--   //Check Shared Links section--}}
    <div class="modal fade" id="checksharedlinks" tabindex="-1" role="dialog"
         aria-labelledby="checksharedlinks" aria-hidden="true">
        <div class="modal-body">

            <div class="modal-dialog modal-xls">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Check Shared Links') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="row">

                        <div class="card-body">
                            <div style="height:630px; overflow-y: scroll;">

                                @if(!empty($members))
                                    <table id="ShareTable"
                                           class="display table table-striped table-bordered StandardTable"
                                           width="100%">

                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{translate('Attachments')}}</th>
                                            <th>{{translate('Status')}}</th>
                                            <th>{{translate('Size')}}</th>
                                            <th>{{translate('TIme Check')}}</th>
                                            <th>{{translate('Type')}}</th>
                                            <th>{{translate('Password')}}</th>
                                            <th>{{translate('Created at')}}</th>
                                            <th>{{translate('Lifetime')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="userData">
                                        @if(!empty($members))
                                                <?php
                                                $count = 0;
                                                ?>

                                            @foreach ($members as $keymember => $member)
                                                    <?php
                                                    $count++;
                                                    ?>
                                                <tr>
                                                    <td width="3%;"><?php echo $count; ?></td>
                                                    @php
                                                        $totalsize_x = 0;
                                                        $countfiles_x = 0;
                                                        $myfilesnamex_x = "<ul>";
                                                        $myfilessizex_x = "<ul style='list-style: none;'>";
                                                        $myfilesexpire_x = "<ul style='list-style: none;'>";
                                                        $count_file_exist = 0;
                                                        if (count($member['attachmentsxx'])>0 && !empty($member['attachmentsxx']) && $member['attachmentsxx'] !=null) {
                                                            $pieces_xoldoldold = explode(",", $member['attachments']);
                                                            $pieces_x = $member['attachmentsxx'];
                                                            foreach ($pieces_x as $count_x => $pezzo_x) {
                                                                   $myfile_x = urldecode(base64_decode($pezzo_x)); //==> databasebackups/2024-01-03-13-42-40/database-2024-01-03-13-42-40.zip
                                                                   if (file_exists($myfile_x)) {
                                                                        $filepathinfo_x = mbPathinfo($myfile_x);
                                                                        $filename_x = $filepathinfo_x['basename'];
                                                                        $extension_x = strtolower($filepathinfo_x['extension']);
                                                                        $filesize_x = getFileSize($myfile_x);
                                                                        $totalsize_x += $filesize_x;
                                                                        $countfiles_x++;
                                                                        $expired ='<span class="badge badge-inline badge-success" style="font-size:.95rem;color:#206B07FF;">'. translate("exist")  .'</span>';
                                                                        $myfilesnamex_x .= "<li>".$filename_x."</li>";
                                                                        $myfilessizex_x .= "<li>".formatSize($filesize_x)."</li>";
                                                                        $myfilesexpire_x .= "<li>".$expired."</li>";
                                                                        $count_file_exist++;
                                                                   }
                                                                   else {
                                                                     $expired ='<span class="badge badge-inline badge-warning" style="font-size:.95rem;color:#e70060;">'. translate("deleted")  .'</span>';
                                                                     $filepathinfo_x = mbPathinfo($myfile_x);
                                                                     $filename_x = $filepathinfo_x['basename'];
                                                                     $myfilesnamex_x .= "<li>".$filename_x."</li>";
                                                                     $myfilesexpire_x .= "<li>".$expired."</li>";
                                                                   }
                                                            }
                                                        } else {
                                                            $expired ='<span class="badge badge-inline badge-warning" style="font-size:.95rem;color:#e70060;">'. translate("expired")  .'</span>';
                                                            $myfilesnamex_x .= "<li>".$expired."</li>";
                                                            $myfilesexpire_x .= "<li>".$expired."</li>";
                                                        }
                                                        $myfilesnamex_x .= "</ul>";
                                                        $myfilessizex_x .= "</ul>";
                                                        $myfilesexpire_x .= "</ul>";

                                                        $lifetimevariable = $member['lifetime'];
                                                        $lifetimetimestr  = $member['lifetime'] .' '. translate('days');


                                                    @endphp
                                                    <td width="20%;"><?php echo $myfilesnamex_x; ?></td>
                                                    <td width="5%;"><?php echo $myfilesexpire_x; ?></td>

                                                    <td width="9%"><?php echo $myfilessizex_x; ?></td>
                                                    <td width="13%;">

                                                        @php
                                                            $bigger_smaller = checkTimeBiggerOrSmaler($member['time'], $member['lifetime']);
                                                        @endphp

                                                        @if ($bigger_smaller === 0)
                                                            <span class="badge badge-inline badge-warning"
                                                                  style="font-size: .75rem;font-weight: bold;">{{secondsToWords($member['time'] -  time())}} {{ translate('until activation') }}</span>
                                                        @elseif ($bigger_smaller === 1)
                                                            <span class="badge badge-inline badge-danger"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ translate('Download Time Expired') }}</span>
                                                        @elseif ($bigger_smaller === 10)
                                                            <span class="badge badge-inline badge-success"
                                                                  style="font-size: .75rem;font-weight: bold;">{{secondsToWords(($member['time'] + (86400 * $member['lifetime'])) -  time())}} {{ translate('until expiration') }}</span>
                                                        @endif


                                                    </td>

                                                    <td width="6%;">
                                                        @if ($member['onetime'] == 1)
                                                            <span class="badge badge-inline badge-danger"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ translate('Onetime') }}</span>
                                                        @else
                                                            <span class="badge badge-inline badge-success"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ translate('Lifetime') }}</span>
                                                        @endif
                                                    </td>

                                                    <td width="7%;">
                                                            <?php $haspass = $member['pass'] === 'false' ? false : true; ?>
                                                        @php
                                                            if ($member['pass'] === false || $member['pass'] === 'false' || $member['pass'] === null || strlen($member['pass']) < 8 ){
                                                                $haspass = false;
                                                            } elseif (strlen($member['pass']) > 8 && $member['pass'] != null) {
                                                                $haspass = true;
                                                            }
                                                        @endphp
                                                        @if ($haspass)
                                                            <span class="badge badge-inline badge-success"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ translate('Yes') }}</span>
                                                        @else
                                                            <span class="badge badge-inline badge-warning"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ translate('No') }}</span>
                                                        @endif
                                                    </td>

                                                    <td width="12%;">{{ date('Y/m/d - H:i:s', $member['time'])  }}</td>

                                                    <td width="7%;">
                                                        @if ($member['onetime'] == 1)
                                                            <span class="badge badge-inline badge-danger"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ $lifetimetimestr }}</span>
                                                        @else
                                                            <span class="badge badge-inline badge-success"
                                                                  style="font-size: .75rem;font-weight: bold;">{{ $lifetimetimestr }}</span>
                                                        @endif
                                                    </td>


                                                    <td>
                                                        @if($count_file_exist>0)
                                                            <a href="#!" data-toggle="modal"
                                                               data-target="#editShareLink_{{ $keymember }}"
                                                               style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                               class="btn btn-info">{{ translate('Edit Link') }}</a>
                                                        @else
                                                            <a href="#!" data-toggle="modal"
                                                               data-target="#editShareLink_{{ $keymember }}"
                                                               style="margin-left:5px; margin-right:5px; font-size: .85rem;font-weight: bold;"
                                                               class="disabled btn btn-info">{{ translate('Edit Link') }}</a>
                                                        @endif
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">No member(s) found...</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                @else
                                    <tr>
                                        <td colspan="8">No member(s) found...</td>
                                    </tr>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{--    //edit shared link info section--}}
    @if($members)
        @foreach ($members as $keymember => $member)
            <div class="modal fade" id="editShareLink_{{ $keymember }}" tabindex="-1" role="dialog"
                 aria-labelledby="editShareLink_{{ $keymember }}" aria-hidden="true">
                {{--                <div class="modal-dialog modal-dialog-centered modal-mediumlg" role="document">--}}
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ translate('Confirm Edit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        @php
                            $read_link = env('APP_URL') . '/backups/viewfilefromlink/' . $member['idfilename'];
                            $lifetimevariable = $member['lifetime'];
                            $lifetimetimestr  = $member['lifetime'] .' '. translate('days');

                            $path = base_path('databasebackups/') . $member['backupname'];

                            $start_date = $member['time'] ? date('d-m-Y H:i:s', $member['time']) : null;
                            $lifetime = isset($member['lifetime']) ? (int)$member['lifetime'] : 1;
                            $lifedays = 86400 * $lifetime;
                            $end_date   = $member['time'] ? date('d-m-Y H:i:s', $member['time']+$lifedays) : null;
                        @endphp
                        <div class="modal-header">
                            {{--                        <div class="form-group shalink mb-6">--}}
                            <div class="input-group">
                            <span class="input-group-btn">
                                <a class="btn btn-info sharebutt2" href="{{$read_link}}" target="_blank"
                                   style="height:42px;"><i
                                        class="las la-link"></i></a>
                            </span>
                                <input id="copylink2_{{ $keymember }}" class="form-control" type="text"
                                       onclick="this.select()"
                                       value="{{$read_link}}" readonly>
                                <span class="input-group-btn">
                                    <button onclick="copyToClipBoard2(this,{{$keymember}});" class="btn btn-info"
                                            data-bs-toggle="popover" data-bs-placement="bottom"
                                            data-bs-content=" {{ translate('copied') }}"
                                            data-clipboard-target="#copylink2" style="height:42px;">
                                        <i class="las la-clipboard-check"></i>
                                    </button>
                                </span>
                            </div>
                            {{--                        </div>--}}
                        </div>

                        <form class="form-horizontal" id="frm_editsharelink{{ $member['idfilename'] }}"
                              action="{{ route('backups.editsharelink') }}" method="POST">
                            @csrf
                            <input type="hidden" name="keymember" value="{{ $keymember }}"/>
                            <input type="hidden" name="idkey" value="{{ $member['idfilename'] }}"/>
                            <input type="hidden" name="passkey" value="{{ $member['pass'] }}"/>
                            <input type="hidden" name="datetimekey" value="{{ $member['time'] }}"/>
                            <input type="hidden" name="backupname" value="{{ $member['backupname'] }}"/>
                            <div class="modal-body">


                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">

                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
   Session::get('locale', Config::get('app.locale')) == 'ir')
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('One-time download') }}
                                                    :</label>
                                            @else
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:10px;display:block;">{{ translate('One-time download') }}
                                                    :</label>
                                            @endif

                                            <input type="checkbox" name="onetime_link"
                                                   style="background-color: #00bbf2; margin-left:35px; margin-right:5px; margin-top:15px; text-align:left;height:18px;"
                                                   @if($member['onetime'] == 1) checked @endif>

                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
   Session::get('locale', Config::get('app.locale')) == 'ir')
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('Keep links valid for') }}
                                                    :</label>
                                            @else
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('Keep links valid for') }}
                                                    :</label>
                                            @endif
                                            <select class="form-control aiz-selectpicker rounded-0"
                                                    data-live-search="true"
                                                    data-placeholder="{{ translate('Select lifetime type')}}"
                                                    style="width:230px;"
                                                    name="lifetime_link" disabled>
                                                <option value="">{{ translate('Select lifetime type') }}</option>

                                                <option value="{{ $member['lifetime'] }}"
                                                        @if($member['lifetime'] == $lifetimevariable) selected @endif>
                                                    {{ $lifetimetimestr }}
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                                <?php $haspass = $member['pass'] === 'false' ? false : true; ?>
                                            @php
                                                if ($member['pass'] === false || $member['pass'] === 'false' || $member['pass'] === null || strlen($member['pass']) < 8 ){
                                                    $haspass = false;
                                                } elseif (strlen($member['pass']) > 8 && $member['pass'] != null) {
                                                    $haspass = true;
                                                }
                                            @endphp

                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
   Session::get('locale', Config::get('app.locale')) == 'ir')
                                                @if ($haspass)
                                                    <label class="form-label"
                                                           style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('renew password?') }}
                                                        :</label>
                                                @else
                                                    <label class="form-label"
                                                           style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('set password?') }}
                                                        :</label>
                                                @endif
                                            @else
                                                @if ($haspass)
                                                    <label class="form-label"
                                                           style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('renew password?') }}
                                                        :</label>
                                                @else
                                                    <label class="form-label"
                                                           style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('set password?') }}
                                                        :</label>
                                                @endif
                                            @endif

                                            @if ($haspass)
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" style="height:42px;">
                                                        <i class="las la-key la-1x"></i>
                                                    </button>
                                                </span>
                                            @else
                                                <span class="input-group-btn">
                                                    <button class="btn btn-warning" style="height:42px;">
                                                        <i class="las la-lock-open la-1x"></i>
                                                    </button>
                                                </span>

                                            @endif

                                            <input class="form-control" type="hidden" id="oldpasslink_{{ $keymember }}"
                                                   name="oldpasslink_{{ $keymember }}" value="{{$member['pass']}}">

                                            <input class="form-control" type="text" id="updatepasslink_{{ $keymember }}"
                                                   name="updatepasslink_{{ $keymember }}"
                                                   onclick="this.select()"
                                                   placeholder="{{ translate('random password') }}"
                                                   style="width:230px;"
                                                   value="">

                                            <span class="input-group-btn">
                                                  <a href="#" onclick='updatePassword(this, "{{ $keymember }}")'
                                                     class="btn btn-info "
                                                     style="height:42px;">
                                                  <i class="las la-mouse-pointer la-2x"></i></a>
                                            </span>


                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
   Session::get('locale', Config::get('app.locale')) == 'ir')
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('edit shared files?') }}
                                                    :</label>
                                            @else
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('edit shared files?') }}
                                                    :</label>
                                            @endif


                                            <select name="edit_ids[]" class="form-control product_id aiz-selectpicker"
                                                    data-live-search="true" data-selected-text-format="count" required
                                                    multiple>
                                                @foreach (scanFolder($path) as $file)

                                                    @php

                                                        $righ = 0;
                                                        $keyq = 0;
                                                        $valueq ='';

                                                       switch (true) {
                                                           case (strpos(basename($file), 'database') !== false):
                                                                $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                                                $keyq = 1;
                                                                $valueq ='DataBase';
                                                               break;
                                                           case (strpos(basename($file), 'storage') !== false):
                                                                $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                                                $keyq = 2;
                                                                $valueq ='Folder';
                                                               break;
                                                           case (strpos(basename($file), 'addons') !== false):
                                                                $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                                                $keyq = 4;
                                                                $valueq ='Addons';
                                                               break;
                                                           case (strpos(basename($file), 'website') !== false):
                                                                $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                                                $keyq = 8;
                                                                $valueq ='Website';
                                                               break;
                                                           default:
                                                       }
                                                        $issharedcheck = '';
                                                        $shareded_msg = '  '. translate('(Not selected for sharing)') ;
                                                        $valuekey = $keyq;

                                                        foreach ($member['attachmentsxx'] as $count_x => $pezzo_x){
                                                            $myfile = urldecode(base64_decode($pezzo_x)); //==> databasebackups/2024-01-03-13-42-40/database-2024-01-03-13-42-40.zip
                                                            if (strpos(basename($myfile), basename($file)) !== false) {
                                                                $issharedcheck = 'selected';
                                                                $shareded_msg = '  '. translate('(Selected for sharing)') ;
                                                            }
                                                        }
                                                        $valueq .= ' ' .$righ . ' ' . $shareded_msg;
                                                    @endphp

                                                    <option value="{{$valuekey}}" {{$issharedcheck}}
                                                    @if ($issharedcheck==='selected')
                                                        style="color: #206b07;font-size: .75rem;font-weight: bold;"
                                                            @else
                                                                style="color: #ff7e00;font-size: .75rem;font-weight: bold;"
                                                        @endif
                                                    >{{$valueq}}
                                                    </option>
                                                @endforeach
                                            </select>


                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
   Session::get('locale', Config::get('app.locale')) == 'ir')
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('renew time?') }}
                                                </label>
                                            @else
                                                <label class="form-label"
                                                       style="color: #000d80;margin-top:13px;margin-right:-30px;display:block;width:190px;">{{ translate('renew time?') }}
                                                </label>
                                            @endif
                                            <span class="input-group-text"><i class="las la-calendar"
                                                                              style="color:#00bbf2;font-size: 18px;"></i></span>
                                            <input dir="ltr" type="text" class="form-control aiz-date-range"
                                                   name="updatetimeRangelink_{{ $keymember }}"
                                                   style="width:280px;"
                                                   value="{{ $start_date && $end_date ? $start_date . ' to ' . $end_date : '' }}"
                                                   placeholder="{{translate('Select Date')}}" data-time-picker="true"
                                                   data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning" role="alert">
                                    <p style="font-size:14px">{{ translate('Note: There must be at least one link to share. If you want to disable the link for any reason,')}}</p>
                                    <p style="font-size:14px">{{ translate('you can change the date to before today or change the download password')}}</p>
                                    <p style="font-size:14px">{{ translate('so that the files cannot be downloaded by changing the information.')}}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="submit"
                                            class="btn btn-primary">{{translate('Save Shared Link Configuration')}}</button>
                                </div>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{--    //check backup file and folder permission--}}
    <div class="modal fade" id="checkPermission" tabindex="-1" role="dialog" aria-labelledby="checkPermission"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('File And Folder Permission') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ translate('File or Folder') }}</th>
                            <th>{{ translate('Status') }}</th>
                        </tr>
                        </thead>
                        @php
                            $required_paths = ['databasebackups','databasebackups/backup.json', 'databasebackups/log','databasebackups/share','databasebackups/share/shares.json', base_path(), '.env',  'public', 'app/Providers', 'app/Http/Controllers', 'storage', 'resources/views']
                        @endphp
                        <tbody>
                        @foreach ($required_paths as $path)
                            <tr>
                                <td>{{ $path }}</td>
                                <td>
                                    @if(is_writable(base_path($path)))
                                        <i class="las la-check-circle la-2x text-success"></i>
                                    @else
                                        <i class="las la-times-circle la-2x text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ translate('Close') }}</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{--    //log activity section--}}
    <div class="modal fade" id="logsettings" tabindex="-1" role="dialog"
         aria-labelledby="logsettings" aria-hidden="true">
        <div class="modal-body">

            {{--            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">--}}
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-xls">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Statistics') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ translate('actions') }}</strong>
                                </div>
                                <div class="card-body">

                                    <div style="height:500px; overflow-y: scroll;">

                                        <table id="LogTable"
                                               class="display table table-striped table-bordered StandardTable"
                                               width="100%">
                                            <thead { display:block; }>
                                            <tr>
                                                <th scope="ro">#</th>
                                                <th><span class="sorta">{{ translate('day') }}</span></th>
                                                <th><span>hh:mm:ss</span></th>
                                                <th><span class="sorta">{{ translate('user') }}</span></th>
                                                <th><span class="sorta">{{ translate('action') }}</span></th>
                                                <th><span class="sorta">{{ translate('type') }}</span></th>
                                                <th><span class="sorta">{{ translate('item') }}</span></th>
                                            </tr>
                                            </thead>
                                            <tbody { height:300px; overflow-y:scroll; display:block; }>

                                                <?php
                                                $logdir = base_path('databasebackups/log');

                                                $loglist = glob($logdir . "/*.json");
                                                $loglist = $loglist ? $loglist : array();
                                                $loglist = array_reverse(array_values(preg_grep('/^([^.])/', $loglist)));
                                                $range = 1;
                                                $logs = array_slice($loglist, 0, $range);
                                                $result = array();
                                                foreach ($logs as $log) {
                                                    $logfile = base_path('databasebackups/log/') . basename($log);
                                                    if (file_exists($logfile)) {
                                                        $resultnew = json_decode(file_get_contents($logfile), true);
                                                        $result = $resultnew ? array_merge($result, $resultnew) : array();
                                                    }
                                                }
                                                $numdown = 0;

                                                $polardowncount = 0;
                                                $polarplaycount = 0;
                                                $polarupcount = 0;

                                                $labelsarray = array();
                                                $downloaddataset = array();


                                                $time_format = 'Y/m/d - H:i';
                                                $formatdate = substr($time_format, 0, 5);

                                            foreach ($result as $key => $value) {
                                                $listtime = strtotime($key);
                                                $showtime = date($formatdate, $listtime);
                                                $labelsarray[] = $showtime;
                                                $downloads = 0;
                                                $rownumberx = 0;
                                            foreach ($value as $kiave => $day) {
                                                $rownumberx++;
                                                $contextual = "";
                                                $item = str_replace('\\\'', '\'', $day['item']);
                                                if ($day['action'] == 'DOWNLOAD') {
                                                    $downloads++;
                                                    $numdown++;
                                                    $polardowncount++;
                                                }
                                                ?>
                                            <tr class="table stripped">
                                                <td><?php echo $rownumberx; ?></td>
                                                <td><?php echo $showtime; ?></td>
                                                <td><?php echo $day['time']; ?></td>
                                                <td><?php echo $day['user']; ?></td>
                                                <td><?php echo strtolower($day['action']); ?></td>
                                                <td><?php echo $day['type']; ?></td>
                                                <td class="text-nowrap"><?php echo $item; ?></td>
                                                <?php
                                            }
                                                array_push($downloaddataset, $downloads);
                                            }
                                                $downloaddataset = array_reverse($downloaddataset);
                                                $labelsarray = array_reverse($labelsarray);
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{--    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}

    {{--@endif--}}

@endsection


@section('modal')
    {{--        //share section--}}
    <div class="modal fade" id="share_files_modal" tabindex="-1" role="dialog"
         aria-labelledby="sharefilesmodalxx" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Confirm Share') }}</h5>
                    <button type="button" onclick="javascript:window.location.reload()" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-info" role="alert">
                    <b>{{ translate('Do you really want to share this backup?') }}</b>
                </div>

                <div class="modal-body">

                    <div class="createlink-wrap mb-3">
                        <div class="d-grid gap-2">
                            <button id="createlink" class="btn btn-info">
                                <i class="las la-check-circle"></i> {{ translate('Generate link') }}
                            </button>
                        </div>
                    </div>

                    <div class="form-group shalink mb-3">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a class="btn btn-info sharebutt" href="#" target="_blank" style="height:42px;"><i
                                        class="las la-link"></i></a>
                            </span>
                            <input dir="ltr" id="copylink" class="sharelink form-control" type="text"
                                   onclick="this.select()"
                                   readonly>
                            <span class="input-group-btn">
                                    <button onclick="copyToClipBoard();" id="clipme" class="clipme btn btn-info"
                                            data-bs-toggle="popover" data-bs-placement="bottom"
                                            data-bs-content=" {{ translate('copied') }}"
                                            data-clipboard-target="#copylink" style="height:42px;">
                                        <i class="las la-clipboard-check"></i>
                                    </button>
                                </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">

                                @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
    Session::get('locale', Config::get('app.locale')) == 'ir')
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('Password protected') }}
                                        :</label>
                                @else
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:10px;display:block;width:150px;">{{ translate('Password protected') }}
                                        :</label>
                                @endif
                                <input type="checkbox" role="switch" name="use_pass" id="use_pass"
                                       style="background-color: #00bbf2; margin-left:35px; margin-right:5px; margin-top:15px; text-align:left;height:18px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group seclink mb-3">
                        <div class="input-group">
                            <label class="form-label"
                                   style="color: #000d80;margin-top:13px;margin-right:5px;display:block;width:190px;">{{ translate('random password') }}
                                : </label>
                            <a href="#" onclick='copyToClipBoard3();'
                               class="btn btn-info service-btn d-flex align-items-center justify-content-center"><i
                                    style="width:0.65rem; height:1.0rem;"
                                    class="las la-lock-open"></i></a>


                            <input class="form-control passlink setpass" type="text" onclick="this.select()"
                                   placeholder="{{ translate('random password') }}">
                        </div>
                    </div>

                        <?php $advancechecked = $advance_download ? ' checked' : ''; ?>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">

                                @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
    Session::get('locale', Config::get('app.locale')) == 'ir')
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('advance file selection') }}
                                        :</label>
                                @else
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:10px;display:block;width:150px;">{{ translate('advance file selection') }}
                                        :</label>
                                @endif
                                <input type="checkbox" role="switch" name="use_advance" id="use_advance"
                                       onchange="update_advance(this)"
                                       style="background-color: #00bbf2; margin-left:35px; margin-right:5px; margin-top:15px; text-align:left;height:18px;"
                                        <?php echo $advancechecked; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="backup-choose-listxxx" id="backup-choose-listxxx">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label" style="color: #000d80;margin-top:10px;"
                                   for="name">{{translate('Choose Your Backup Type')}}</label>
                            <div class="col-lg-9">

                                <select name="backup_idsxxx[]" id="backup_idsxxx"
                                        class="form-control product_id aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" required multiple>
                                </select>

                            </div>
                        </div>
                    </div>

                    @php
                        $start_date = date('d-m-Y H:i:s', strtotime(Carbon\Carbon::now()));
                        $end_date   = date('d-m-Y H:i:s', strtotime(Carbon\Carbon::now())+86400);
                    @endphp

                    <div class="form-group mb-3">
                        <div class="input-group">
                            <label class="form-label"
                                   style="color: #000d80;margin-top:13px;margin-right:5px;display:block;width:190px;">{{ translate('Keep links valid for') }}
                                : </label>
                            <span class="input-group-text"><i class="las la-calendar"
                                                              style="color:#00bbf2;font-size: 18px;"></i></span>
                            <input dir="ltr" type="text" class="form-control aiz-date-range"
                                   name="createtimeRangelink" id="createtimeRangelink"
                                   value="{{ $start_date && $end_date ? $start_date . ' to ' . $end_date : '' }}"
                                   placeholder="{{translate('Select Date')}}" data-time-picker="true"
                                   data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                   autocomplete="off">

                        </div>
                    </div>

                        <?php $formchecked = $one_time_download ? ' checked' : ''; ?>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">

                                @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 &&
    Session::get('locale', Config::get('app.locale')) == 'ir')
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:-1px;display:block;width:190px;">{{ translate('One-time download') }}
                                        :</label>
                                @else
                                    <label class="form-label"
                                           style="color: #000d80;margin-top:13px;margin-right:10px;display:block;width:150px;">{{ translate('One-time download') }}
                                        :</label>
                                @endif

                                <input type="checkbox" name="onetime" id="onetime" onchange="update_onetime(this)"
                                       style="background-color: #00bbf2; margin-left:35px; margin-right:5px; margin-top:15px; text-align:left;height:18px;"
                                        <?php echo $formchecked; ?>>

                            </div>
                        </div>
                    </div>

                    <div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>

                    <div class="text-center">

                        @if (env('MAIL_USERNAME') != null)

                            <a class="openmail fs-1 btn btn-info my-3" data-toggle="modal"
                               data-target="#frm_sendlinktoemail"
                               href="#!">
                                <i class="las la-envelope-open-text la-3x"></i>
                            </a>

                        @else

                            <div
                                style="color: #ff4d00;background-color:#ffc405;border-color:#f6a662;margin-left: 1rem; margin-right: 1rem;"
                                class="alert alert-warning" role="alert">
                                <b>{{ translate('Please configure SMTP first') }}</b>
                            </div>

                            <a class="disabled openmail fs-1 btn btn-warning my-3" data-toggle="modal"
                               data-target="#frm_sendlinktoemail"
                               href="#!">
                                <i class="las la-envelope-open-text la-3x"></i>
                            </a>

                        @endif
                    </div>

                    <div style="display:none;" id="PersianGulf_ShareBackup" class="currently-loading">
                    </div>

                    <form role="form" id="frm_sendlinktoemail" action="{{ route('backups.sendfilestoemail') }}"
                          method="POST" class="collapse">
                        @csrf
                        {{--                        <input type="hidden" name="key" value="{{ $key }}"/>--}}
                        {{--                        <input type="hidden" name="backuptype" value="{{ $backup['type'] }}"/>--}}
                        <input type="hidden" name="backupnames" value=""/>
                        <input type="hidden" name="timeupto" value=""/>

                        <div class="mailresponse"></div>

                        {{-- language --}}
                        @php
                            if(Session::has('locale')){
                                $locale = Session::get('locale', Config::get('app.locale'));
                            }
                            else{
                                $locale = env('DEFAULT_LANGUAGE');
                            }
                        @endphp

                        <input name="thislang" type="hidden" value="{{ $locale }}">

                        <div class="input-group mb-3">
                            <label class="form-label" for="mitt"
                                   style="color: #000d80;margin-top:13px;margin-right:5px;display:block;width:100px;">{{ translate('from') }}
                                : </label>
                            <span class="input-group-text"><i class="las la-user"></i></span>
                            {{--                                    <input name="mitt" type="email" class="form-control" id="mitt" value="<?php echo $gateKeeper->getUserInfo('email'); ?>" placeholder="{{ translate('Your E-mail') }}" required>--}}
                            <input dir="ltr" name="mitt" type="email" class="form-control" id="mitt"
                                   value="{{ Auth::user()->email ?? '' }}"
                                   placeholder="{{ translate('Your E-mail') }}" required>
                        </div>

                        <div class="wrap-dest">
                            <div class="input-group mb-3">
                                <label class="form-label" for="dest"
                                       style="color: #000d80;margin-top:13px;margin-right:5px;display:block;width:100px;">{{ translate('Send to') }}
                                    : </label>
                                <span class="input-group-text"><i class="las la-envelope"></i></span>
                                {{--                                    <input name="mitt" type="email" class="form-control" id="mitt" value="<?php echo $gateKeeper->getUserInfo('email'); ?>" placeholder="{{ translate('Your E-mail') }}" required>--}}
                                <input dir="ltr" name="dest" type="email" class="form-control addest" id="dest"
                                       {{--                                       value="{{ Auth::user()->email ?? '' }}"--}}
                                       value="kia1349@gmail.com"
                                       placeholder="{{ translate('Destination E-mail') }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="btn btn-primary btn-xs shownext hidden">
                                <i class="las la-user-plus la-2x"></i>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <textarea class="form-control" name="message" id="mess" rows="3"
                                      placeholder="{{ translate('message') }}"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <div class="d-grid gap-2">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-info"><i class="las la-envelope la-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input name="lifetimelink" id="lifetimelink" class="form-control lifetimelink" type="hidden"/>
                        <input name="onetimecheck" id="onetimecheck" class="form-control onetimecheck" type="hidden"/>
                        <input name="passlink" class="form-control passlink" type="hidden">
                        <input name="sharelink" class="sharelink" type="hidden">
                        <input name="secretlink" class="secretlink" type="hidden">
                    </form>

                </div>

            </div>
        </div>
    </div>

    {{--        //download section--}}
    <div class="modal fade" id="download_files_modal" tabindex="-1" role="dialog"
         aria-labelledby="downloadfilesmodalxx" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('confirm download') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div
                    style="color: #ff7e00;background-color:#ffc405;border-color:#f6a662;margin-left: 1rem; margin-right: 1rem;"
                    class="alert alert-warning" role="alert">
                    <b>{{ translate('Do you really want to download this backups?') }}</b>
                </div>

                <form id="frm_downloadBackupxx"
                      action="{{ route('backups.download.backups') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="download_key" id="download_key"/>
                        <input type="hidden" name="download_backuptype" id="download_backuptype"/>

                        <div class="download-choose-list">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label"
                                       for="name">{{translate('download')}}</label>
                                <div class="col-lg-9">
                                    <select name="download_ids[]" id="download_ids"
                                            class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required
                                            multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <b>{{ translate('Note: You can only restore the database and folder. To restore the plugins or the website, proceed manually.') }}</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ translate('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary">{{ translate('Download') }}</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>

    {{--        //restore section--}}
    <div class="modal fade" id="restore_files_modal" tabindex="-1" role="dialog"
         aria-labelledby="restorefilesmodalxx" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('confirm restore') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-warning" role="alert">
                    <b>{{ translate('Do you really want to restore this backups?') }}</b>
                </div>

                <form id="frm_restoreBackupxx"
                      action="{{ route('backups.download.restore') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="restore_key" id="restore_key"/>
                        <input type="hidden" name="restore_backuptype" id="restore_backuptype"/>

                        <div class="restore-choose-list" id="restore-choose-list">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label"
                                       for="name">{{translate('restore')}}</label>
                                <div class="col-lg-9">
                                    <select name="restore_ids[]" id="restore_ids"
                                            class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required
                                            multiple>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <b>{{ translate('Note: You can only restore the database and folder. To restore the plugins or the website, proceed manually.') }}</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ translate('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-warning">{{ translate('Restore') }}</button>
                        </div>
                    </div>

                </form>
                <div style="display:none;" id="PersianGulf_RestoreBackup" class="currently-loading">
                </div>

            </div>
        </div>
    </div>





    {{--    //set ftp settings--}}
    @if(1>0)
        <div class="modal fade" id="ftpsettings" tabindex="-2" role="dialog" aria-labelledby="ftpsettings"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Ftp Settings') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                            @csrf
                            <div id="smtp">
                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="FTP_HOST">
                                    <div class="col-md-3">
                                        <label class="col-from-label">{{translate('FTP HOST')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="FTP_HOST"
                                               value="{{  env('FTP_HOST') }}"
                                               placeholder="{{ translate('ftp.example.com') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="FTP_USERNAME">
                                    <div class="col-md-3">
                                        <label class="col-from-label">{{translate('FTP USERNAME')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="FTP_USERNAME"
                                               value="{{  env('FTP_USERNAME') }}"
                                               placeholder="{{ translate('FTP USERNAME') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="FTP_PASSWORD">
                                    <div class="col-md-3">
                                        <label class="col-from-label">{{translate('FTP PASSWORD')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="FTP_PASSWORD"
                                               value="{{  env('FTP_PASSWORD') }}"
                                               placeholder="{{ translate('FTP PASSWORD') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="FTP_PORT">
                                    <div class="col-md-3">
                                        <label class="col-from-label">{{translate('FTP PORT')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="FTP_PORT"
                                               value="{{  env('FTP_PORT') }}"
                                               placeholder="{{ translate('Default Port 21') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="FTP_DEBUG">
                                    <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker mb-2 mb-md-0" name="FTP_DEBUG">
                                            <option value="1"
                                                    @if (env('FTP_DEBUG') == "1") selected @endif>{{ translate('true') }}</option>
                                            <option value="0"
                                                    @if (env('FTP_DEBUG') == "0") selected @endif>{{ translate('false') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <input type="hidden" name="types[]" value="PROJECT_ROOT">
                                    <div class="col-md-3">
                                        <label class="col-from-label">{{translate('PROJECT ROOT')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="PROJECT_ROOT"
                                               value="{{  env('PROJECT_ROOT') }}"
                                               placeholder="{{ translate('./public_html/your_project_name/') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-primary">{{translate('Save Configuration')}}</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

        </div>
    @endif


    {{--        //delete section--}}
    <div class="modal fade" id="delete_files_modal" tabindex="-1" role="dialog"
         aria-labelledby="deletefilesmodalxx" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('confirm delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-danger" role="alert">
                    <b>{{ translate('Do you really want to delete this backup?') }}</b>
                </div>

                <form id="frm_deleteBackupxx"
                      action="{{ route('backups.download.delete') }}" method="POST">
                    @csrf
                    {!! method_field('DELETE') !!}
                    <div class="modal-body">

                        <input type="hidden" name="delete_key" id="delete_key"/>
                        <input type="hidden" name="delete_backuptype" id="delete_backuptype"/>

                        <div class="delete-choose-list" id="delete-choose-list">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label" for="name">{{translate('delete')}}</label>
                                <div class="col-lg-9">
                                    <select name="delete_ids[]" id="delete_ids"
                                            class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required
                                            multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <b>{{ translate('Note: You can only restore the database and folder. To restore the plugins or the website, proceed manually.') }}</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ translate('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger">{{ translate('Delete') }}</button>
                        </div>
                    </div>

                </form>
                <div id="PersianGulf_delete_files"></div>

            </div>
        </div>
    </div>

    {{--        //ftp upload section--}}
    <div class="modal fade" id="ftp_files_modal" tabindex="-1" role="dialog"
         aria-labelledby="ftpfilesmodalxx" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('confirm ftp upload') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-success" role="alert">
                    <b>{{ translate('Do you really want to upload this backup via FTP?') }}</b>
                </div>

                <form id="frm_ftpBackupxx"
                      action="{{ route('backups.download.uploadviaftp') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">

                        <input type="hidden" name="ftp_key" id="ftp_key"/>
                        <input type="hidden" name="ftp_backuptype" id="ftp_backuptype"/>

                        <div class="ftp-choose-list" id="ftp-choose-list">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label" for="name">{{translate('upload via FTP')}}</label>
                                <div class="col-lg-9">
                                    <select name="ftp_ids[]" id="ftp_ids"
                                            class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required
                                            multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <b>{{ translate('Note: You can only restore the database and folder. To restore the plugins or the website, proceed manually.') }}</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ translate('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger">{{ translate('upload') }}</button>
                        </div>
                    </div>

                </form>
                <div id="PersianGulf_ftp_files"></div>

            </div>
        </div>
    </div>

    {{--        //cannot section--}}
    <div class="modal fade" id="cannot_modal" tabindex="-1" role="dialog"
         aria-labelledby="deletefilesmodalxx" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Notice') }}:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-danger" role="alert">
                    <b>{{ translate('You do not have permission to do this') }}</b>
                </div>
            </div>
        </div>
    </div>



    <div id="bulk-delete-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
{{--                    <p class="mt-1">{{ translate('Are you sure to delete those files?') }}</p>--}}
                    <div style="margin-left: 1rem; margin-right: 1rem;" class="alert alert-danger" role="alert">
                        <b>{{ translate('Are you sure to delete those files?') }}</b>
                    </div>
                    <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a href="javascript:void(0)" onclick="bulk_backup_delete()" class="btn btn-primary mt-2">{{ translate('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    {{--    <script src="//cdn.datatables.net/plug-ins/2.0.0/i18n/fa.json"></script>--}}
    <script>
        AIZ.plugins.particles();
        AIZ.plugins.bootstrapSelect('refresh');

        var frm_restoreBackup_key = null;
        var div_restoreBackup_key = null;
        var div_restoreBackup_element = null;

        let excuteshorten = 0;
        let excuteshortencron = 0;


// check or uncheck all backup for bulk action
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function bulk_backup_delete() {
            // console.debug('88888888888888888888888888888888888888888')
            var data = new FormData($('#sort_backupsxx')[0]);
            // console.debug(data)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-backup-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    //console.debug('========================> '+response)
                    if(response == 1) {
                        location.reload();
                        AIZ.plugins.notify('success', "{{ translate('selected backup deleted successfully') }}");
                    } else {
                        location.reload();
                        AIZ.plugins.notify('danger', "{{ translate('error on delete backup files') }}");

                    }
                }
            });
        }



        function confirm_cronjobtask_delete(url) {
            $('#confirm-cronjobtask-delete').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href', url);
        }


        function show_share_modal(id, backuptypes, backupdetails) {
            show_animation_shareBackup();
            show_share_frmxx(id);
            excuteshorten = 0;

            $('#share_files_modal').modal('show', {backdrop: 'static'});

            const selectxx = document.getElementById("backup_idsxxx");
            const backupdetailsxx = JSON.parse(backupdetails);

            for (var i = 0; i < backupdetailsxx.length; i++) {
                const option = document.createElement("option");
                switch (backupdetailsxx[i].backup_id) {
                    case 1:
                        option.value = backupdetailsxx[i].backup_id;
                        option.selected = true;
                        option.innerHTML = `DataBase` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 2:
                        option.value = backupdetailsxx[i].backup_id;
                        option.selected = true;
                        option.innerHTML = `Folder` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 4:
                        option.value = backupdetailsxx[i].backup_id;
                        option.selected = true;
                        option.innerHTML = `Addons` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 8:
                        option.value = backupdetailsxx[i].backup_id;
                        option.selected = true;
                        option.innerHTML = `Website` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    default:
                        option.value = 0;
                        option.innerHTML = `Unknown`;
                        selectxx.appendChild(option);
                        break;
                }


            }

            AIZ.plugins.bootstrapSelect('refresh');
        }

        function show_download_modal(id, backuptypes, backupdetails) {

            const selectxx = document.getElementById("download_ids");
            $("#download_ids").empty();
            const backupdetailsxx = JSON.parse(backupdetails);

            for (var i = 0; i < backupdetailsxx.length; i++) {
                const option = document.createElement("option");
                switch (backupdetailsxx[i].backup_id) {
                    case 1:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `DataBase` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 2:
                        option.value = backupdetailsxx[i].backup_id;
                       option.innerHTML = `Folder` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 4:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Addons` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 8:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Website` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    default:
                        option.value = 0;
                        option.innerHTML = `Unknown`;
                        selectxx.appendChild(option);
                        break;
                }

            }

            $('#download_key').val(id);
            $('#download_backuptype').val(backuptypes);

            $('#download_files_modal').modal('show', {backdrop: 'static'});

            AIZ.plugins.bootstrapSelect('refresh');
        }

        function show_restore_modal(id, backuptypes, backupdetails) {
            show_animation_restoreBackup();
            const selectxx = document.getElementById("restore_ids");
            $("#restore_ids").empty();
            const backupdetailsxx = JSON.parse(backupdetails);

            for (var i = 0; i < backupdetailsxx.length; i++) {
                const option = document.createElement("option");
                switch (backupdetailsxx[i].backup_id) {
                    case 1:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `DataBase` + ' : ' + backupdetailsxx[i].backup_size;
                        option.disabled = false;
                        option.style.color = '#206b07';
                        option.style.fontWeight = 'bold';
                        selectxx.appendChild(option);
                        break;
                    case 2:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Folder` + ' : ' + backupdetailsxx[i].backup_size;
                        option.disabled = false;
                        option.style.color = '#206b07';
                        option.style.fontWeight = 'bold';
                        selectxx.appendChild(option);
                        break;
                    case 4:
                    @php
                        $addonsdir = public_path('addons');
                        //check any zip file exist in addons directory, if yes continue else show to user not any zip file exist
                        $addons_installed_count = 0;
                        foreach (glob($addonsdir."/*.zip") as $filename) {
                            if (is_file($filename)) {
                                $addons_installed_count = $addons_installed_count + 1;
                            }
                        }
                    @endphp
                        var addons_installed_count = '<?php echo json_encode($addons_installed_count); ?>';
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Addons` + ' : ' + backupdetailsxx[i].backup_size + ' (' + '{{translate('Please Restore Manually, Installed Addons')}}' + ') (' + addons_installed_count + '{{translate('addons')}}' + ')';
                        option.disabled = true;
                        option.style.color = '#ff7e00';
                        option.style.fontSize = 'small';
                        option.style.fontWeight = 'bold';
                        selectxx.appendChild(option);
                        break;
                    case 8:

                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Website` + ' : ' + backupdetailsxx[i].backup_size + ' (' + '{{translate('Please Restore Manually')}}' + ')';
                        option.disabled = true;
                        option.style.color = '#ff7e00';
                        option.style.fontSize = 'small';
                        option.style.fontWeight = 'bold';
                        selectxx.appendChild(option);
                        break;
                    default:
                        option.value = 0;
                        option.innerHTML = `Unknown`;
                        selectxx.appendChild(option);
                        break;
                }


            }

            $('#restore_key').val(id);
            $('#restore_backuptype').val(backuptypes);


            $('#restore_files_modal').modal('show', {backdrop: 'static'});

            AIZ.plugins.bootstrapSelect('refresh');
        }

        function show_cannot_modal(id, backuptypes, backupdetails) {
            $('#cannot_modal').modal('show', {backdrop: 'static'});
        }

        function show_delete_modal(id, backuptypes, backupdetails) {

            const selectxx = document.getElementById("delete_ids");
            $("#delete_ids").empty();
            const backupdetailsxx = JSON.parse(backupdetails);

            for (var i = 0; i < backupdetailsxx.length; i++) {
                const option = document.createElement("option");
                switch (backupdetailsxx[i].backup_id) {
                    case 1:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `DataBase` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 2:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Folder` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 4:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Addons` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 8:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Website` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    default:
                        option.value = 0;
                        option.innerHTML = `Unknown`;
                        selectxx.appendChild(option);
                        break;
                }


            }

            $('#delete_key').val(id);
            $('#delete_backuptype').val(backuptypes);
            $('#delete_files_modal').modal('show', {backdrop: 'static'});
            $('#delete_key').val(id);
            $('#delete_backuptype').val(backuptypes);

            AIZ.plugins.bootstrapSelect('refresh');
        }


        function show_ftp_modal(id, backuptypes, backupdetails) {

            const selectxx = document.getElementById("ftp_ids");
            $("#ftp_ids").empty();
            const backupdetailsxx = JSON.parse(backupdetails);

            for (var i = 0; i < backupdetailsxx.length; i++) {
                const option = document.createElement("option");
                switch (backupdetailsxx[i].backup_id) {
                    case 1:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `DataBase` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 2:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Folder` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 4:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Addons` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    case 8:
                        option.value = backupdetailsxx[i].backup_id;
                        option.innerHTML = `Website` + ' : ' + backupdetailsxx[i].backup_size;
                        selectxx.appendChild(option);
                        break;
                    default:
                        option.value = 0;
                        option.innerHTML = `Unknown`;
                        selectxx.appendChild(option);
                        break;
                }


            }

            $('#ftp_key').val(id);
            $('#ftp_backuptype').val(backuptypes);
            $('#ftp_files_modal').modal('show', {backdrop: 'static'});
            $('#ftp_key').val(id);
            $('#ftp_backuptype').val(backuptypes);

            AIZ.plugins.bootstrapSelect('refresh');
        }

        //close side bar menu when form loaded
        $(document).ready(function () {
            $('body').addClass('side-menu-closed');

            // Activate tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            //for cronjobs
            $('.cronjob-div-testlink').hide();
            $('.cronjob-div-task').hide();
            $('.cronjobrunbutt').attr('href', '#');


            baba = '<h3 style="color: #04a111">all tables have been selected for backup</h3>';
            $("#persian").html(baba);




        //PAGINATE SHARED LINK
            //use with default settings
            // https://datatables.net/plug-ins/i18n/Spanish
            //https://cdn.datatables.net/plug-ins/2.0.0/i18n/
            //https://datatables.net/plug-ins/i18n/

            //use this with custom settings for show entries
            if ($("html:lang(ir)").length > 0 && $("html").attr("lang") === 'ir') {
                new DataTable('#ShareTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/fa.json',
                    },
                });
            } else {
                new DataTable('#ShareTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers'
                });
            }

            //use this with custom settings for show activity logs
            if ($("html:lang(ir)").length > 0 && $("html").attr("lang") === 'ir') {
                new DataTable('#LogTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/fa.json',
                    },
                });
            } else {
                new DataTable('#LogTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers'
                });
            }

            //use this with custom settings for backup data
            if ($("html:lang(ir)").length > 0 && $("html").attr("lang") === 'ir') {
                new DataTable('#BackUpTablexx', {
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 1 ] }], //disable sortable indicator on index 1 column

                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/fa.json',
                    },

                });
            } else {
                new DataTable('#BackUpTablexx', {
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 1 ] }], //disable sortable indicator on index 1 column

                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',

                });
            }


            $('#reportgrid').dataTable( {
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ]
            } );


            // $('#BackUpTablexx').dataTable( {
            //     "columnDefs": [
            //         { "orderable": false, "targets": 0 }
            //     ]
            // } );

            // $('#BackUpTablexx').DataTable( {
            //     "order": [ 0, 'desc' ],
            //     "aoColumnDefs": [
            //         { "bSortable": false, "aTargets": [ 2 ] }
            //     ]
            // } );


            // var table = $('#BackUpTablexx').DataTable({
            //     order: [0],
            //     ordering: false
            // });



            //use this with custom settings for show cronjobs tasks
            if ($("html:lang(ir)").length > 0 && $("html").attr("lang") === 'ir') {
                new DataTable('#CronjobsTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/fa.json',
                    },
                });
            } else {
                new DataTable('#CronjobsTable', {
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, 'All']
                    ],
                    pagingType: 'full_numbers',

                });
            }

            //reset advance table selection checkbox to uncheck when form refreshed
            var adv_check = document.getElementById('advcheck');
            var ch_btnx = adv_check.querySelector('input');
            ch_btnx.checked = false;

            $("#sharefilesmodal").on('hide.bs.modal', function () {
                $('#onetime').prop('checked', false);
                $('#use_pass').prop('checked', false);
                $('#lifetime').removeAttr('disabled');

                //reza new
                $('#use_advance').prop('checked', false);
                //reza new

                $('#lifetime').val("1");
                $('#lifetimelink').val("1");
                $('#onetime').val("0");
                $('#onetimecheck').val("0");
                // $('#onetime').prop('checked', false);
            });
        });

        //////////////////// show animation progressbar for create backup,download backup,restore backup,restructure backup json file and sending share link to email
        function ShowProgressBarruncronjobtask() {
            var progressbardiv = document.getElementById("PersianGulf_runcronjobtask");
            progressbardiv.style.display = (progressbardiv.style.display !== "none") ? "none" : "block";
        }

        function ShowProgressBar() {
            var progressbardiv = document.getElementById("PersianGulf_HeadButtonSection");
            progressbardiv.style.display = (progressbardiv.style.display !== "none") ? "none" : "block";
        }

        function show_animation_createBackup() {
            var frm_createBackupxx = document.getElementById('frm_createBackupxx');
            frm_createBackupxx.addEventListener('submit', bmdLoading1);
        }

        function bmdLoading1() {
            var mydiv = document.getElementById("PersianGulf_CreateBackup");
            mydiv.style.display = (mydiv.style.display !== "none") ? "none" : "block";
        }

        function show_animation_restoreBackup() {
            var frm_restoreBackup22 = document.getElementById('frm_restoreBackupxx');
            frm_restoreBackup22.addEventListener('submit', bmdLoading2);

        }

        function bmdLoading2() {
            var mydiv = document.getElementById("PersianGulf_RestoreBackup");
            mydiv.style.display = (mydiv.style.display !== "none") ? "none" : "block";
        }

        function show_animation_shareBackup() {
            var frm_sendlinktoemail33 = document.getElementById('frm_sendlinktoemail');
            frm_sendlinktoemail33.addEventListener('submit', bmdLoading3);
            console.log('frm_sendlinktoemail==> ')
        }

        function bmdLoading3() {
            var mydiv = document.getElementById("PersianGulf_ShareBackup");
            mydiv.style.display = (mydiv.style.display !== "none") ? "none" : "block";
            console.log('PersianGulf_ShareBackup==> ')
        }

        //if clicked on #ch_all, check all tables in .ch_tables
        var ch_all = document.getElementById('ch_all');
        if (ch_all) {
            var ch_btn = ch_all.querySelector('input');
            var tables = document.querySelectorAll('#frm_cht .ch_tables input');
            ch_all.addEventListener('click', function () {
                if (tables) {
                    for (var i = 0; i < tables.length; i++) tables[i].checked = ch_btn.checked;
                    // update_all_status();
                }
            });
        }

        function update_status(el, tablecount) {
            var arrayx = [];
            var tables = document.querySelectorAll('#frm_cht .ch_tables input');
            var textVar;
            for (var n = 0; n < tables.length; n++) {
                if (tables[n].checked) {
                    textVar = tables[n].value;
                    // document.getElementById(textVar).style.color = '#b52121';
                    arrayx.push(tables[n].value)
                    // console.debug('tables[n].value' + ' ======> ' + tables[n].value + ' ======> ' + tables[n].style.color);
                } else {
                    textVar = tables[n].value;
                    // document.getElementById(textVar).style.color = '#2fae05';
                    }
                el.parentNode.style.color = (el.checked) ? '#b52121' : '#2fae05';

            }

            // console.debug('checkboxeslist' + ' ======> ' + JSON.stringify(arrayx));

            $('input[type=hidden][name="checkboxeslist"]').val(JSON.stringify(arrayx));
            var baba = '';
            if (tablecount === arrayx.length) {
                baba = '<h3 style="color: #cc2e2e">There are no tables to backup</h3>';
            } else {
                if (arrayx.length > 0) {
                    baba = '<h3 style="color: #e7a042">A number of tables have been selected for exclude from backup</h3>';
                } else {
                    baba = '<h3 style="color: #04a111">all tables have been selected for backup</h3>';
                }
            }
            $("#persian").html(baba);

        }

        function update_all_status(tablecount) {
            var arrayx = [];
            var tables = document.querySelectorAll('#frm_cht .ch_tables input');
            var textVar;
            for (var n = 0; n < tables.length; n++) {
                if (tables[n].checked) {
                    textVar = tables[n].value;
                    document.getElementById(textVar).style.color = '#b52121';
                    arrayx.push(tables[n].value)
                } else {
                    textVar = tables[n].value;
                    document.getElementById(textVar).style.color = '#2fae05';
                }            }
            $('input[type=hidden][name="checkboxeslist"]').val(JSON.stringify(arrayx));

            // console.log(arrayx);
            // console.log(tablecount + ' <> ' + arrayx.length);

            var baba = '';
            if (tablecount === arrayx.length) {
                baba = '<h3 style="color: #cc2e2e">There are no tables to backup</h3>';
            } else {
                if (arrayx.length > 0) {
                    baba = '<h3 style="color: #e7a042">A number of tables have been selected for exclude from backup</h3>';
                } else {
                    baba = '<h3 style="color: #04a111">all tables have been selected for backup</h3>';
                }
            }
            $("#persian").html(baba);


            // console.log($("input[id=checkboxeslistzz]").val());
        }

        function show_hide_selection(el) {
            if (el.checked) {
                $("#table_selection").show();
            } else {
                $("#table_selection").hide();
            }
        }


        //share section *************************************

        /**
         * Send link via e-mail
         */
        var AECmodals = '<?php echo json_encode($AECmodals); ?>';
        var AECmodals = JSON.parse(AECmodals);

        var thisIsGlobalFormShare = null;
        var selectedfiles = [];
        var backupnames = null;
        var thisIsGlobalFormShareLink = null;


        function show_share_frm(e, ke, many, msg) {

            var farnaz_id = '';
            farnaz_id = 'shareId_' + e;
            console.log('farnaz_id=>' + farnaz_id + '    ' + e)

            ccccc = document.getElementById(farnaz_id);
            console.log('ccccc=>' + ccccc)
            // let rowid = '0';
            var rowid = '';
            rowid = $(ccccc).attr('data-id');
            console.log('rowid=>' + rowid)
            $('#dataid').text($(this).data('id'));
            $('#dataid').text(rowid);
            thisIsGlobalSelect = rowid;
            $('input[name="backupnames"]').val(e);
            var wichform = 'sendfiles_' + e;
            var wichform2 = 'sharefilesmodal_' + e;
            backupnames = e;
            console.log('backupnames=>' + backupnames)
            thisIsGlobalFormShare = document.getElementById(wichform);

            $('#onetime').prop('checked', false);
            $('#use_pass').prop('checked', false);
            $('#lifetime').removeAttr('disabled');
            $('#lifetime').val("1");
            $('#lifetimelink').val("1");
            $('#onetime').val("0");
            $('#onetimecheck').val("0");

            var numfiles = 2;
            console.log(numfiles);
            if (numfiles > 0) {

                // reset form
                $('.shownext').addClass('hidden');
                $('.bcc-address').remove();
                $('.seclink, .shalink, .mailresponse, .openmail').hide();
                $('.seclink').hide();

                $('.backup-choose-listxxx').hide();
                $('.backup_idsxxx').val('');
                $('#xsendfiles').removeClass('in');
                $('.sharelink, .passlink, .secretlink').val('');
                $('.sharebutt').attr('href', '#');
                $('.createlink-wrap').fadeIn();
                //
                passwidget();
            } else {
            }
            $('#sharefilesmodalxx').modal('show', {backdrop: 'static'});
            AIZ.plugins.bootstrapSelect('refresh');

        }

        function show_share_frmxx(e) {

            var farnaz_id = '';
            farnaz_id = 'shareId_' + e;
            console.log('farnaz_id=>' + farnaz_id + '    ' + e)

            ccccc = document.getElementById(farnaz_id);
            console.log('ccccc=>' + ccccc)
            var rowid = '';
            rowid = $(ccccc).attr('data-id');
            console.log('rowid=>' + rowid)
            $('#dataid').text($(this).data('id'));
            $('#dataid').text(rowid);
            thisIsGlobalSelect = rowid;
            $('input[name="backupnames"]').val(e);
            var wichform = 'sendfiles_' + e;
            var wichform2 = 'sharefilesmodal_' + e;
            backupnames = e;
            console.log('backupnames=>' + backupnames)
            thisIsGlobalFormShare = document.getElementById(wichform);

            $('#onetime').prop('checked', false);
            $('#use_pass').prop('checked', false);
            $('#lifetime').removeAttr('disabled');
            $('#lifetime').val("1");
            $('#lifetimelink').val("1");
            $('#onetime').val("0");
            $('#onetimecheck').val("0");
            $('#use_advance').prop('checked', false);
            var numfiles = 2;
            console.log(numfiles);
            if (numfiles > 0) {

                // reset form
                $('.shownext').addClass('hidden');
                $('.bcc-address').remove();
                //
                $('.seclink, .shalink, .mailresponse, .openmail').hide();
                $('.seclink').hide();

                $('.backup-choose-listxxx').hide();
                $('.backup_idsxxx').val('');
                $('#xsendfiles').removeClass('in');
                $('.sharelink, .passlink, .secretlink').val('');
                $('.sharebutt').attr('href', '#');
                $('.createlink-wrap').fadeIn();
                //
                passwidget();
            }
        }

        /**
         * file sharing password widget
         */
        function passwidget() {
            console.log("in the passwidget");
            if ($('#use_pass').prop('checked')) {
                $('.seclink').show();
            } else {
                $('.seclink').hide();
            }
            if ($('#use_advance').prop('checked')) {
                $('.backup-choose-listxxx').show();
            } else {
                $('.backup-choose-listxxx').hide();
            }
            $('.sharelink, .passlink, .secretlink').val('');
            $('.shalink, .openmail').hide();

            $('#sendfiles').removeClass('in');
            $('#wichform').removeClass('in');
            $('.passlink').prop('readonly', false);
            $('.createlink-wrap').fadeIn();
            $('.backup_idsxxx').prop('readonly', false);
        }

        $(document).on('change', '#use_pass', function () {
            $('.alert').alert('close');
            passwidget();
        });

        $(document).on('change', '#onetime', function () {
            $('.alert').alert('close');
            passwidget();
        });

        $(document).on('change', '#use_advance', function () {
            $('.alert').alert('close');
            passwidget();
        });

        /**
         * create a random string
         */
        function randomstring() {
            console.log("in the randomstring");
            var text = '';
            var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            for (var i = 0; i < 8; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        }

        $(document).on('change', '.setlifetime', function () {
            lifetime = $('.setlifetime').val();
            console.log(lifetime);

            $('.shalink, .openmail').fadeOut('fast', function () {
                $('.createlink-wrap').fadeIn();
            })

        });

        /**
         * Create cronjob task
         */
        $(document).on('click', '#createcronjobtask', function () {

            //for cronjobs
            $('.cronjob-div-testlink').hide();
            $('.cronjob-div-task').hide();
            $('.cronjobrunbutt').attr('href', '#');

            var cron_backup_idsxxx = $('#cron_backup_idsxxx').val();
            console.log('cron_backup_idsxxx===> : ' + cron_backup_idsxxx);
            console.debug('cron_backup_idsxxx===> : ' + cron_backup_idsxxx);
            console.debug(cron_backup_idsxxx);
            if (AECmodals.hasOwnProperty('share')) {
                var $insert4 = AECmodals.share.insert4,
                    $time = AECmodals.share.time,
                    $datetime = AECmodals.share.datetime,
                    $hash = AECmodals.share.hash,
                    $pulito = AECmodals.share.pulito;
            }
            $('.cronlink').val('');
            $('.crontask').val('');
            excuteshortencron = 0;

            if (isNullOrUndefined(cron_backup_idsxxx)) {
                console.log("The value is either undefined or null");
                AIZ.plugins.notify('danger', "{{ translate('At least one backup type must be selected') }}");

            } else {
                console.debug(cron_backup_idsxxx);
                $.post('{{ route('backups.shortencron') }}', {
                    _token: '{{ csrf_token() }}',
                    time: $datetime,
                    hash: $hash,
                    cronfilesidx: cron_backup_idsxxx,
                }, function (data) {
                    excuteshortencron =1;
                    console.log('create link work done');
                    // cronlink = $pulito + '/backups/cronjobbackup/1/' + data;
                    cronlink = $pulito + '/backups/executecronjobfromlink/3/' + data;
                    crontask = 'wget -O - ' + $pulito + '/backups/cronjobbackup/0/' + data;
                    $('.cronjob-div-testlink').show();
                    $('.cronjob-div-task').show();
                    $('.cronlink').val(cronlink);
                    $('.crontask').val(crontask);
                    $('.cronjobrunbutt').attr('href', cronlink);
                });

                console.log("The value is neither undefined nor null");
                AIZ.plugins.notify('success', "{{ translate('Cronjob Data Generated') }}");
            }

        });


        /**
         * Create sharable link
         */
        $(document).on('click', '#createlink', function () {
            // console.log("in the createlink1  backupnames=>"+backupnames);
            if (AECmodals.hasOwnProperty('share')) {
                var $insert4 = AECmodals.share.insert4,
                    $time = AECmodals.share.time,
                    $hash = AECmodals.share.hash,
                    $pulito = AECmodals.share.pulito;
            }
            $('.alert').alert('close');
            var alertmess = '<div class="alert alert-warning alert-dismissible" role="alert">' + $insert4 + '</div>';
            var shortlink, passw, xlifetimelink, xonetimecheck, xbackupnames, xuseadvance;

            xlifetimelink = 1;
            xonetimecheck = '';
            xonetimecheck = $('#onetimecheck').val();

            createtimeRangelink = new Date().getTime();
            createtimeRangelink = $('#createtimeRangelink').val();

            $('input[name="timeupto"]').val($('#lifetime').val());

            // check if wants a password
            if ($('#use_pass').prop('checked')) {
                if (!$('.setpass').val()) {
                    passw = randomstring();
                } else {
                    if ($('.setpass').val().length < 4) {
                        $('.setpass').focus();
                        $('.seclink').after(alertmess);
                        return;
                    } else {
                        passw = $('.setpass').val();
                    }
                }
            }

            // check if wants selectting backup files to share
            xuseadvance = '0';
            var backup_idsx = $('#backup_idsxxx').val();
            console.log('backup_idsx===> : ' + backup_idsx + ' length=> ' + backup_idsx.length);
            console.debug('backup_idsx===> : ' + backup_idsx + ' length=> ' + backup_idsx.length);
            if ($('#use_advance').prop('checked')) {
                $("#use_advance").val("1");
                xuseadvance = $('#use_advance').val();
                console.log('xuseadvance : ' + xuseadvance);
            } else {
                $("#use_advance").val("0");
                xuseadvance = $('#use_advance').val();
                console.log('xuseadvance : ' + xuseadvance);
            }
            if (isNullOrUndefined(backup_idsx)) {
                console.log("The value is either undefined or null");
                AIZ.plugins.notify('danger', "{{ translate('No files selected') }}");
            } else {
                console.debug(backup_idsx);
                $.post('{{ route('backups.shorten') }}', {
                    _token: '{{ csrf_token() }}',
                    createtimeRangelink: createtimeRangelink,
                    time: $time,
                    hash: $hash,
                    pass: passw,
                    backupnames: backupnames,
                    lifetime: xlifetimelink,
                    onetime: xonetimecheck,
                    backupfilesidx: backup_idsx,
                    useadvance: xuseadvance
                }, function (data) {
                    console.log('create link work done');
                    shortlink = $pulito + '/backups/viewfilefromlink/' + data;
                    $('.secretlink').val(data);
                    $('.sharelink').val(shortlink);
                    $('.sharebutt').attr('href', shortlink);
                    $('.passlink').val(passw);
                    $('.passlink').prop('readonly', true);
                    $("#lifetimelink").val(xlifetimelink);
                    // $("#onetimecheck").val(xonetimecheck);
                    $("#onetimecheck").val(xonetimecheck).change();
                    excuteshorten = 1;
                    if ($('#onetime').is(":checked")) {
                        $("#onetimecheck").val("1");
                    } else {
                        $("#onetimecheck").val("0");
                    }

                    $('.createlink-wrap').fadeOut('fast', function () {
                        $('.shalink, .openmail').fadeIn();
                    });
                });

                console.log("The value is neither undefined nor null");
                AIZ.plugins.notify('success', "{{ translate('Download Link Generated') }}");
            }

        });


        // // prevent form submitting with enter
        $(document).on("keyup keypress", "#wichform :input:not(textarea)", function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });

        function isNullOrUndefined(value) {
            return value === undefined || value === null || value === '' || value.length === 0;
        }

        function copyToClipBoard() {
            var linktext = document.getElementById('copylink').value
            navigator.clipboard.writeText(linktext);
            AIZ.plugins.notify('success', "{{ translate('Download Link Copied') }}");
        }

        function CronLinkcopyToClipBoard() {
            var linktext = document.getElementById('croncopylink').value
            navigator.clipboard.writeText(linktext);
            AIZ.plugins.notify('success', "{{ translate('Cronjob Link Copied') }}");
        }

        function CronTaskcopyToClipBoard() {
            var linktext = document.getElementById('croncopytask').value
            navigator.clipboard.writeText(linktext);
            AIZ.plugins.notify('success', "{{ translate('Cronjob Task Link Copied') }}");
        }

        function CronTaskcopyToClipBoardx(el, whichmember) {
            var linktexttask = document.getElementById('croncopytask2_' + whichmember).value
            navigator.clipboard.writeText(linktexttask);
            AIZ.plugins.notify('success', "{{ translate('Cronjob Task Link Copied') }}");
        }

        function CronLinkcopyToClipBoardx(el, whichmember) {
            var linktextlink = document.getElementById('croncopylink2_' + whichmember).value
            navigator.clipboard.writeText(linktextlink);
            AIZ.plugins.notify('success', "{{ translate('Cronjob test Link Copied') }}");
        }

        function copyToClipBoard2(el, whichmember) {
            var linktext2 = document.getElementById('copylink2_' + whichmember).value
            // console.log("copy to clipboard 2  "+whichmember+"    "+linktext2)
            navigator.clipboard.writeText(linktext2);
            AIZ.plugins.notify('success', "{{ translate('Download Link Copied') }} ");
        }

        function copylinkToClipBoard2(el, whichmember) {
            var linktext2 = document.getElementById('copylink2_' + whichmember).value
            // console.log("copy to clipboard 2  "+whichmember+"    "+linktext2)
            navigator.clipboard.writeText(linktext2);
            AIZ.plugins.notify('success', "{{ translate('Download Link Copied') }} ");
        }

        function copytaskToClipBoard2(el, whichmember) {
            var linktext2 = document.getElementById('copylink2_' + whichmember).value
            // console.log("copy to clipboard 2  "+whichmember+"    "+linktext2)
            navigator.clipboard.writeText(linktext2);
            AIZ.plugins.notify('success', "{{ translate('Download Link Copied') }} ");
        }

        function copyToClipBoard3() {

            var linktext3 = $('.setpass').val();
            if (linktext3 === null || linktext3 === undefined || linktext3 === '') {
                AIZ.plugins.notify('warning', "{{ translate('Password Is Empty,You must enter a new password first') }}");
            } else {
                navigator.clipboard.writeText(linktext3);
                AIZ.plugins.notify('success', "{{ translate('Password Copied') }}");
            }
        }

        /**
         * Add mail recipients (file sharing)
         */
        $(document).on('click', '.shownext', function () {
            // var $lastinput = $(this).parent().prev().find('.form-group:last-child .addest');
            var $lastinput = $(this).parent().prev().find('.input-group:last-child .addest');

            if ($lastinput.val().length < 5) {
                $lastinput.focus();
            } else {
                var $newdest, $inputgroup, $addon1, $addon2, $input;

                $input = $('<input dir="ltr" name="send_cc[]" type="email" class="form-control addest">');
                $addon1 = $('<label class="form-label" style="color: #000d80;margin-top:13px;margin-right:5px;display:block;width:100px;"></label>');
                $addon2 = $('<span class="input-group-text"><i class="las la-envelope"></i></span>');
                $inputgroup = $('<div class="input-group"></div>').append($addon1).append($addon2).append($input);
                $newdest = $('<div class="form-group bcc-address mb-3"></div>').append($inputgroup);

                $('.wrap-dest').append($newdest);
            }
        });

        /**
         * Show additional recipients
         */
        $(document).on('input', '#dest', function () {
            if ($(this).val().length > 5) {
                $('.shownext').removeClass('hidden');
            } else {
                $('.shownext').addClass('hidden');
            }
        });

        function update_onetime(el) {
            if (el.checked) {
                $("#onetimecheck").val("1");
                $('#lifetime').attr('disabled', 'disabled');
            } else {
                $("#onetimecheck").val("0");
                $('#lifetime').removeAttr('disabled');
                console.log('unchecked unchecked aah aah aah aah aah aah1');
            }
        }

        function update_advance(el) {
            if (el.checked) {
                $("#use_advance").val("1");
            } else {
                $("#use_advance").val("0");
            }
        }


        //*Works with Bootstrap v3 - v3.3.7 ===> i have 3.3.5
        function setModalMaxHeight(element) {
            this.$element = $(element);
            this.$content = this.$element.find('.modal-content');
            var borderWidth = this.$content.outerHeight() - this.$content.innerHeight();
            var dialogMargin = $(window).width() > 767 ? 60 : 20;
            var contentHeight = $(window).height() - (dialogMargin + borderWidth);
            var headerHeight = this.$element.find('.modal-header').outerHeight() || 0;
            var footerHeight = this.$element.find('.modal-footer').outerHeight() || 0;
            var maxHeight = contentHeight - (headerHeight + footerHeight);
            this.$content.css({
                'overflow': 'hidden'
            });

            this.$element
                .find('.modal-body').css({
                'max-height': maxHeight,
                'overflow-y': 'auto'
            });
        }

        $('.modal').on('show.bs.modal', function () {
            $(this).show();
            setModalMaxHeight(this);
        });

        $(window).resize(function () {
            if ($('.modal.in').length != 0) {
                setModalMaxHeight($('.modal.in'));
            }
        });

        function updatePassword(el, passid) {
            passw = randomstring();
            document.getElementById('updatepasslink_' + passid).value = passw
            AIZ.plugins.notify('success', '{{ translate('New Password Generated successfully') }}');
        }

        $('#share_files_modal').on('hidden.bs.modal', function () {
            if (excuteshorten === 1) {
                location.reload();
            }
        })


        $('#createCronjob2').on('hidden.bs.modal', function () {
            if ( excuteshortencron === 1) {
                location.reload();
            }
        })

    </script>

@endsection

