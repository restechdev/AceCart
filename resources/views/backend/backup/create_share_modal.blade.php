@php
    $backup_type = array(
        "1" => "DataBase",
        "2" => "Folder",
        "4" => "Addons",
        "8" => "WebSite",
        );
//    $lifetime = 1;
    $one_time_download = 0;
    $advance_download = 0;
                    if ( !function_exists( 'getRows' ) ) {
                        function getRows(){
                    $share_path = base_path('databasebackups/share');
                    $jsonFile = $share_path . DIRECTORY_SEPARATOR .  'shares.json';

                            if(file_exists($jsonFile)){
                                $jsonData = file_get_contents($jsonFile);
                                $data = json_decode($jsonData, true);

                                return !empty($data) ? $data : false;
                            }
                            return false;
                        }
                        }



                    if ( !function_exists( 'checkpassword' ) ) {
                    function checkpassword(Request $request, $param ) {

                    $postpass = filter_input(INPUT_POST, "dwnldpwdxx", FILTER_SANITIZE_SPECIAL_CHARS);
                    if ($postpass) {
                    $postpass = preg_replace('/\s+/', '', $postname);
                    $passa = false;
                    $passpass = false;
                    if (md5($postpass) === $param) {
                    $passa = true;
                    $passpass = true;
                    }
                    }
                    }
                    }

                    if ( !function_exists( 'mytemplatefunction' ) ) {
                    function mytemplatefunction( $param ) {
                    return $param . " World";
                    }
                    }

                    if ( !function_exists( 'checkTime' ) ) {
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

                    if ( !function_exists( 'mbPathinfo' ) ) {

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

                    if ( !function_exists( 'getFileSize' ) ) {
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


                    if ( !function_exists( 'formatSize' ) ) {
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
                    $syz  = $sizes[$i];
                    }
                    return round($size, 2).' '.$syz;
                    }
                    }

                    if ( !function_exists( 'formatSize2' ) ) {
                    /**
                    * Format file size
                    *
                    * @param string $size new format
                    *
                    * @return formatted size
                    */
                    function formatSize2($size)
                    {
                    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
                    $syz = $sizes[0];
                    for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
                    $size = $size / 1024;
                    $syz  = $sizes[$i];
                    }
                    return round($size, 2);
                    }
                    }

                    if ( !function_exists( 'scanFolder' ) ) {
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
@endphp

<div class="form-group row">
    <label class="col-lg-3 col-from-label" style="color: #000d80;margin-top:10px;"
           for="name">{{translate('Choose Your Backup Type')}}</label>
    <div class="col-lg-9">

{{--        <select name="backup_idsxx[]" id="advance_backup_idsxx"--}}
        <select name="backup_idsxx[]" id="backup_idsxx"
                class="form-control product_id aiz-selectpicker" data-live-search="true"
                data-selected-text-format="count" required multiple>

            {{--                    $backup_type = array(--}}
            {{--                    "1" => "DataBase",--}}
            {{--                    "2" => "Folder",--}}
            {{--                    "4" => "Addons",--}}
            {{--                    "8" => "WebSite",--}}
            {{--                    );--}}
            @foreach($backup_type as $keyq => $valueq)
                @foreach( $backupxxnew['type'] as $keyqxx )
                    @if((int)$keyqxx===(int)$keyq)
                        @switch((int)$keyqxx)

                            @case(1)
                                @php
                                    //"1" => "DataBase"
                                        $righ = '0';
                                            $path = base_path('databasebackups/') . $klid;
                                            foreach (scanFolder($path) as $file) {
                                                if (strpos(basename($file), 'database') !== false) {
                                           $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                                }
                                            }
                                @endphp
                                @break

                            @case(2)
                                @php
                                    //"2" => "Folder"
                                        $righ = '0';
                                         $path = base_path('databasebackups/') . $klid;
                                         foreach (scanFolder($path) as $file) {
                                             if (strpos(basename($file), 'storage') !== false) {
                                        $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                             }
                                         }
                                @endphp
                                @break

                            @case(4)
                                @php
                                    //"4" => "Addons"
                                        $righ = '0';
                                         $path = base_path('databasebackups/') . $klid;
                                         foreach (scanFolder($path) as $file) {
                                             if (strpos(basename($file), 'addons') !== false) {
                                        $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                             }
                                         }
                                @endphp
                                @break

                            @case(8)
                                @php
                                    //"8" => "WebSite"
                                        $righ = '0';
                                         $path = base_path('databasebackups/') . $klid;
                                         foreach (scanFolder($path) as $file) {
                                             if (strpos(basename($file), 'website') !== false) {
                                        $righ=formatsize(getFileSize($path . DIRECTORY_SEPARATOR . $file));
                                             }
                                         }
                                @endphp
                                @break
                            @default
                        @endswitch
                        @php
                            $valueq .= '   : '.$righ;
                        @endphp
                        <option selected='true' value="{{$keyq}}"

                        >{{$valueq}}</option>

                    @endif
                @endforeach
            @endforeach

        </select>

    </div>
</div>

