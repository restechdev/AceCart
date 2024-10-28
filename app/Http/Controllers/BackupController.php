<?php
/**
 * Active Ecommerce Backup And Restore manager: ajax/BackupController.php
 *
 * Generate short sharing link
 *
 * PHP version >= 5.3
 *
 * @category  PHP
 * @package   Active Ecommerce Backup And Restore manager Addon
 * @author    Reza Kia <kia@aryaclub.com>
 * @copyright 2023 Reza Kia
 * @license   Apache 2.0
 * @link      http://aryaclub.com/
 */

namespace App\Http\Controllers;

//use DB;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailManager;
use Artisan;
use Auth;
use Faker\Provider\Uuid;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Backup;
use Exception;
use Illuminate\Support\Str;
use Log;
use net\authorize\api\contract\v1\PermissionType;
use Redirect;


use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

//use App\Services as IMysharedownloader;
use Mail;

/*////////////////////////
//	Author: reza kia
//	Created: 7/10/14
//	website: aryaclub.com
//	website: codeadik.com
///////////////////////*/

class BackupController extends Controller
{
    protected $backup;
    protected $file;

    public function __construct(Backup $backup)
    {
        $this->backup = $backup;
        $this->file = new Filesystem();

    }

    /**
     * Simple helper to debug to the console
     *
     * @param $data object, array, string $data
     * @param $context string  Optional a description.
     *
     * @return string
     */
    function debug_to_console($data, $context = 'Debug in Console')
    {
        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();
        $output = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . json_encode($data) . ');';
        $output = sprintf('<script>%s</script>', $output);

        echo $output;
    }

    public function captcha(Request $request, $tmp)
    {
        $code = rand(10000, 99999);

        $im = imagecreatetruecolor(90, 42);

        $bg = imagecolorallocate($im, 223, 207, 229); //background color blue

        $red = mt_rand(100, 255);
        $green = mt_rand(100, 255);
        $blue = mt_rand(100, 255);
        $fg = imagecolorallocate($im, $red, $green, $blue);


        imagefill($im, 0, 0, $bg);
        imagestring($im, 25, 5, 5, $code, $fg);


        $font = public_path('assets/fonts/Kanit-Regular.ttf');
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        $randm = imagecolorallocate($im, $red, $green, $blue);


// Add some shadow to the text
        imagettftext($im, 20, 10, 15, 25, $grey, $font, $code);
        imagettftext($im, 20, -10, 5, 35, $grey, $font, $code);

// Add the text
        imagettftext($im, 20, 0, 10, 30, $black, $font, $code);

        if (Session::has($request->captcha_session_id)) {
            Session::forget($request->captcha_session_id);
        }
        Session::put($request->captcha_session_id, $code);

        header("Cache-Control: no-cache, must-revalidate");
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }


    /**
     * Display backups.
     */
    public function index(Request $request)
    {

        //get backuped files info
        $backups = $this->backup->getBackupList();

        //get backupedxx files info
        $backupsxx = $this->backup->getBackupListxx();

        //get shared link info
        $members = $this->backup->getsharedLinkRows();
        krsort($members);

        //get generated cronjob info
        $cronjobs = $this->backup->getcronJobsRows();
        krsort($cronjobs);


//        $tables = null;
//        $tables = [];
//        $tables = DB::select('SHOW TABLES');
//        $dbname = "Tables_in_".env('DB_DATABASE');

////////////////////////////////////////////////////////
//        $tables_in_db = DB::select('SHOW TABLES');
//        $db = "Tables_in_".env('DB_DATABASE');
//        $tables = [];
//        foreach($tables_in_db as $table){
//            $tables[] = $table->{$db};
//        }
//        dd($tables);
////////////////////////////////////////////////////////
        $tableNames = [];
        foreach (DB::select('SHOW DATABASES') as $database) {
            config('database.connections.mysql.database', $database->Database); //======>aec88
            $tables = DB::select('SHOW TABLES');

            $dbname = env('DB_DATABASE');
            if (config('database.connections.mysql.database') == $dbname) {
                foreach ($tables as $table) { //tables=115
                    foreach ($table as $item) {
                        $tableNames[] = $item;
                    }
                }
                break;
            }


        }
////////////////////////////////////////////////////////







        foreach ($backups as $key => $backup) {
            $backups[$key]['size'] = $this->backup->sizeFormat2(Backup::folderSize(base_path('databasebackups') . DIRECTORY_SEPARATOR . $key));
        }

        foreach ($backupsxx as $keyxx => $backupxx) {
            $backupsxx[$keyxx]['size'] = $this->backup->sizeFormat2(Backup::folderSize(base_path('databasebackups') . DIRECTORY_SEPARATOR . $keyxx));
        }

        return view('backend.backup.backups')->with(compact('backups', 'backupsxx', 'members', 'cronjobs', 'tables', 'tableNames'));
//        return view('backend.backup.backups')->with(compact('backups', 'backupsxx', 'members', 'cronjobs', 'tables'));
    }


    public function paginate($items, $perPage = 7, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
//        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    public function downloadDatabase2($key)
    {
        $path = base_path('databasebackups/') . $key;
        foreach ($this->backup->scanFolder($path) as $file) {
            if (strpos(basename($file), 'database') !== false) {
                return response()->download($path . DIRECTORY_SEPARATOR . $file);
            }
        }
        return true;
    }

//////////////////////////////////////////////////////////////////////////////////////////


    public function storexx(Request $request)
    {

        try {
            $nowdatetime = Carbon::now();

            $nowdatetime1 = $nowdatetime->format('Y-m-d-H-i-s');
            $nowdatetime2 = $nowdatetime->toDateTimeString();

            $data = $this->backup->createBackupFolderxx($request, $nowdatetime1, $nowdatetime2, 1);

            if ($request->backup_ids) {
                foreach ($request->backup_ids as $backuptype) {

                    switch ($backuptype) {
                        case 1:
                            $this->backup->backupDb($nowdatetime1, $request['checkboxeslist'], 1);
                            break;
                        case 2:
                            $this->backup->backupStorage(public_path('uploads'), $nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/uploads"
                            break;
                        case 4:
                            $this->backup->backupAddonsxxx($nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/addons"
                            break;
                        case 8:
                            $this->backup->backupStorage(base_path(), $nowdatetime1, $backuptype); //base_path() ==> "/home/aryaclub/aec.aryaclub.com"
                            break;
                        default:
                    }

                }
            }


            return redirect()->route('backups');
        } catch (Exception $error) {
            return Redirect::back()
                ->withError($error->getMessage());
        }
    }


    public function storecronbackuptype(Request $request)
    {

        try {
            $data = $this->backup->createCronJobFile($request);
            return redirect()->route('backups');
        } catch (Exception $error) {
            return Redirect::back()
                ->withError($error->getMessage());
        }
    }

    public function generatejson(Request $request)
    {

        $directoryToScan = base_path('databasebackups');
        $json_array = array();

// Open a known directory, and proceed to read its contents
        foreach(glob($directoryToScan . '/*' , GLOB_ONLYDIR) as $foldersx)
        {
//            dd($foldersx);
//            "/opt/lampp/htdocs/aec82/databasebackups/2024-03-11-10-13-48"

            $json_arraytype = array();
            $json_Array_count = 0;

            foreach(glob($foldersx, GLOB_ONLYDIR) as $folders)
        {
            $json_arraytype = array();
            //get total number of jpg files in each folder
            $num_files = count(glob("$folders/*.zip"));
            $totalFiles = (string)$num_files;
//dd($num_files);
            if ($num_files < 1)  {
                break;
            }

            $animals = [
                'aardvark', 'albatross', 'alligator', 'alpaca', 'ant', 'anteater', 'antelope', 'ape', 'armadillo', 'donkey',
                'baboon', 'badger', 'barracuda', 'bat', 'bear', 'beaver', 'bee', 'bison', 'boar', 'buffalo', 'galago', 'butterfly',
                'camel', 'caribou', 'cat', 'caterpillar', 'cattle', 'chamois', 'cheetah', 'chicken', 'chimpanzee', 'chinchilla',
                'chough', 'clam', 'cobra', 'cockroach', 'cod', 'cormorant', 'coyote', 'crab', 'crane', 'crocodile', 'crow', 'curlew',
                'deer', 'dinosaur', 'dog', 'dogfish', 'dolphin', 'donkey', 'dotterel', 'dove', 'dragonfly', 'duck', 'dugong', 'dunlin',
                'eagle', 'echidna', 'eel', 'eland', 'elephant', 'elephant-seal', 'elk', 'emu', 'falcon', 'ferret', 'finch', 'fish',
                'flamingo', 'fly', 'fox', 'frog', 'gaur', 'gazelle', 'gerbil', 'giant-panda', 'giraffe', 'gnat', 'gnu', 'goat', 'goose',
                'goldfinch', 'goldfish', 'gorilla', 'goshawk', 'grasshopper', 'grouse', 'guanaco', 'guinea-fowl', 'guinea-pig', 'gull',
                'hamster', 'hare', 'hawk', 'hedgehog', 'heron', 'herring', 'hippopotamus', 'hornet', 'horse', 'human', 'hummingbird', 'hyena',
                'jackal', 'jaguar', 'jay', 'jay, blue', 'jellyfish', 'kangaroo', 'koala', 'komodo-dragon', 'kouprey', 'kudu', 'lapwing',
                'lark', 'lemur', 'leopard', 'lion', 'llama', 'lobster', 'locust', 'loris', 'louse', 'lyrebird', 'magpie', 'mallard', 'manatee',
                'marten', 'meerkat', 'mink', 'mole', 'monkey', 'moose', 'mouse', 'mosquito', 'mule', 'narwhal', 'newt', 'nightingale',
                'octopus', 'okapi', 'opossum', 'oryx', 'ostrich', 'otter', 'owl', 'ox', 'oyster', 'panther', 'parrot', 'partridge', 'peafowl',
                'pelican', 'penguin', 'pheasant', 'pig', 'pigeon', 'pony', 'porcupine', 'porpoise', 'prairie-dog', 'quail', 'quelea', 'rabbit',
                'raccoon', 'rail', 'ram', 'rat', 'raven', 'red-deer', 'red-panda', 'reindeer', 'rhinoceros', 'rook', 'ruff', 'salamander',
                'salmon', 'sand-dollar', 'sandpiper', 'sardine', 'scorpion', 'sea-lion', 'sea-urchin', 'seahorse', 'seal', 'shark', 'sheep',
                'shrew', 'shrimp', 'skunk', 'snail', 'snake', 'spider', 'squid', 'squirrel', 'starling', 'stingray', 'stinkbug', 'stork',
                'swallow', 'swan', 'tapir', 'tarsier', 'termite', 'tiger', 'toad', 'trout', 'turkey', 'turtle', 'vicuna', 'viper', 'vulture',
                'wallaby', 'walrus', 'wasp', 'water-buffalo', 'weasel', 'whale', 'wolf', 'wolverine', 'wombat', 'woodcock', 'woodpecker', 'worm', 'wren', 'yak', 'zebra'
            ];

            $random_key = array_rand($animals);
            $picked_name = $animals[$random_key];
            $random_number = rand(1000, 9999);


            $dateString = basename($foldersx);

            $carbonDate = Carbon::createFromFormat('Y-m-d-H-i-s',$dateString);

            $json_key = basename($foldersx);
            $json_name = $picked_name . '-' . $random_number;
            $json_date = (string)$carbonDate;

            foreach (glob("$folders/*.zip") as $filename) {

//            dd($filename);
//            "/opt/lampp/htdocs/aec82/databasebackups/2024-03-11-10-13-48/website-2024-03-11-10-13-48.zip"

//            dd(basename($filename));
//            "website-2024-03-11-10-13-48.zip"

                switch (true) {
                    case (strpos(basename($filename), 'database') !== false):
                        $keyq = "1";
                        break;
                    case (strpos(basename($filename), 'storage') !== false):
                        $keyq = "2";
                        break;
                    case (strpos(basename($filename), 'addons') !== false):
                        $keyq = "4";
                        break;
                    case (strpos(basename($filename), 'website') !== false):
                        $keyq = "8";
                        break;
                    default:
                }
                array_push($json_arraytype, $keyq);

//                //get date on which each folder was created.
//                $fileDate = date("mdY", filectime($folders));

            }
            $json_Array[$json_key] = array(
                'name' => $json_name,
                'date' => $json_date,
                'klid' => $json_key,
                'type' => $json_arraytype
            );

        }
        }


        $json_Array_count = count($json_Array);
//dd($json_Array);
//dd($json_Array_count);
        $file = base_path('databasebackups/backup.json');

       try {
            //first delete 'databasebackups/backup.json'
            $this->file->delete($file);

            //second put data in 'databasebackups/backup.json'
           if ($json_Array_count>0) {
               $this->backup->saveFileData($file, $json_Array);
           }
           if (file_exists($file)) {
               chmod($file, 0777);
           }
            return redirect()->route('backups');
        } catch (Exception $error) {
            return Redirect::back()
                ->withError($error->getMessage());
        }
    }

    public function getClientIP($nothing = false)
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
    /**
     * Determine the size of a file
     *
     * @param string $path file to calculate
     *
     * @return sizeInBytes
     * @since  3.0.3
     */
    public function getFileSize($path)
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


    /**
     * Get pathinfo in UTF-8
     *
     * @param string $filepath to search
     *
     * @return array $ret
     */
    public function mbPathinfo($filepath)
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

    public function cronjobbackup($test = 0, $linkkey)
    {
        $share_path = base_path('databasebackups/cronjob');

        $share_json = $share_path . DIRECTORY_SEPARATOR . 'cronjob.json';
        if ($linkkey && file_exists($share_json)) {
            $arr = json_decode(file_get_contents($share_json), true);

            $find_val = $linkkey;

            $linkkeyvalidate = false;
            $datarray = [];


            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $find_val) {
                    $datarray = $value;
                    $time = $datarray['time'];

                    if ((isset($datarray['type']) && !empty($datarray['type']))) {
                        // value is not null
                        $linkkeyvalidate = true;
                    } else {
                        // value is null or empty
                        $linkkeyvalidate = false;
                    }

                    break;
                } else {
                    $datarray = [];
                    $linkkeyvalidate = false;
                }
            }

            if ($linkkeyvalidate) { //all ok
                $time = $datarray['time'];
                $piecesxx = $datarray['type'];

                ////////////////////////////////////////////////////////
                $local = ($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1' || $_SERVER['REMOTE_ADDR']=='localhost') ? 1 : 0;
                try {
                    $data['backup_ids'] = array();
                    $data['name'] = null;
                    $data['checkboxeslist'] = array();

                    $cronjobxx = $datarray;

                    if (!empty($cronjobxx['type'] && $cronjobxx['type'] != NULL && $cronjobxx['type'] != "")) {

                        if ($cronjobxx) {
                            $data['backup_ids'] = $cronjobxx['type'];
                        }

                        $nowdatetime = Carbon::now();

                        $nowdatetime1 = $nowdatetime->format('Y-m-d-H-i-s');
                        $nowdatetime2 = $nowdatetime->toDateTimeString();

                        $DataBackupFolder = $this->backup->createBackupFolderxx($data, $nowdatetime1, $nowdatetime2, 0);

                        if ($data['backup_ids']) {
                            foreach ($data['backup_ids'] as $backuptype) {
                                switch ($backuptype) {
                                    case 1:
                                        $this->backup->backupDb($nowdatetime1, $data['checkboxeslist'], 0);
                                        break;
                                    case 2:
                                        $this->backup->backupStorage(public_path('uploads'), $nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/uploads"
                                        break;
                                    case 4:
                                        $this->backup->backupAddonsxxx($nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/addons"
                                        break;
                                    case 8:
                                        $this->backup->backupStorage(base_path(), $nowdatetime1, $backuptype); //base_path() ==> "/home/aryaclub/aec.aryaclub.com"
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }


                        Artisan::call('cache:clear');

                    }
                    if($local || $test == 1){
                        return redirect()->route('backups');
                    }

                } catch (Exception $error) {
                }
                ////////////////////////////////////////////////////////
            } // END all ok
        } // END if ($linkkey && file_exists($share_json))
    }



    public function cronjobbackup2(Request $request)
    {
//        dd($request);

        $linkkey = $request->linkkey;
        $test = $request->test;
//        dd("====linkkey=>".$linkkey."         ====test=>".$test);
        $kia = 0;

//        error_log("====linkkey=>".$linkkey."         ====test=>".$test);


        $cron_path = base_path('databasebackups/cronjob');

        $cron_json = $cron_path . DIRECTORY_SEPARATOR . 'cronjob.json';
        if ($linkkey && file_exists($cron_json)) {
            $arr = json_decode(file_get_contents($cron_json), true);

            $find_val = $linkkey;

            $linkkeyvalidate = false;
            $datarray = [];


            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $find_val) {
                    $datarray = $value;
                    $time = $datarray['time'];

                    if ((isset($datarray['type']) && !empty($datarray['type']))) {
                        // value is not null
                        $linkkeyvalidate = true;
                    } else {
                        // value is null or empty
                        $linkkeyvalidate = false;
                    }

                    break;
                } else {
                    $datarray = [];
                    $linkkeyvalidate = false;
                }
            }

            if ($linkkeyvalidate) { //all ok
                $time = $datarray['time'];
                $piecesxx = $datarray['type'];

                ////////////////////////////////////////////////////////
                $local = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == 'localhost') ? 1 : 0;
                try {
                    $data['backup_ids'] = array();
                    $data['name'] = null;
                    $data['checkboxeslist'] = array();

                    $cronjobxx = $datarray;

                    if (!empty($cronjobxx['type'] && $cronjobxx['type'] != NULL && $cronjobxx['type'] != "")) {

                        if ($cronjobxx) {
                            $data['backup_ids'] = $cronjobxx['type'];
                        }

                        $nowdatetime = Carbon::now();

                        $nowdatetime1 = $nowdatetime->format('Y-m-d-H-i-s');
                        $nowdatetime2 = $nowdatetime->toDateTimeString();

                        $DataBackupInfo = $this->backup->createBackupFolderxx($data, $nowdatetime1, $nowdatetime2, 0);

                        if ($data['backup_ids']) {
                            foreach ($data['backup_ids'] as $backuptype) {
                                switch ($backuptype) {
                                    case 1:
                                        $this->backup->backupDb($nowdatetime1, $data['checkboxeslist'], 0);
                                        break;
                                    case 2:
                                        $this->backup->backupStorage(public_path('uploads'), $nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/uploads"
                                        break;
                                    case 4:
                                        $this->backup->backupAddonsxxx($nowdatetime1, $backuptype); //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/addons"
                                        break;
                                    case 8:
                                        $this->backup->backupStorage(base_path(), $nowdatetime1, $backuptype); //base_path() ==> "/home/aryaclub/aec.aryaclub.com"
                                        break;
                                    default:
                                        break;
                                }
                            }
                            $kia = 1;
                        }
                        $kia = 1;


//                        Artisan::call('cache:clear');

                    }
                } catch (Exception $error) {
                    flash(translate("failed on execute cronjob and creating backup!"))->error();
//                    return back();
                    $kia = 0;
                }
                ////////////////////////////////////////////////////////
            } // END all ok
        } // END if ($linkkey && file_exists($share_json))

//        $DataBackupInfo=>
//                    $data[$now1] = [
//                        'name' => $name ?? $now1,
//                        'date' => $now2,
//                        'klid' => $now1,
//                        'type' => $backupids
//                    ];

//        error_log("backup info =====>".$DataBackupInfo);
        //backup info =====>{"name":"dunlin-4830-cronjob","date":"2024-05-05 13:44:41","klid":"2024-05-05-13-44-41","type":["1"]}


        $phpObject = json_decode($DataBackupInfo);
//        error_log("backup info2 =====>".$phpObject->name);




        $span = '';
        $backup_type = array(
            "1" => "DataBase",
            "2" => "Folder",
            "4" => "Addons",
            "8" => "WebSite",
        );
        foreach ($backup_type as $keyq => $valueq) {
            foreach ($phpObject->type as $keyqxx) {
                if ((int)$keyqxx === (int)$keyq) {
                    $span .= '<span class="badge badge-inline badge-md bg-secondary" style="margin-left:5px;margin-right:5px;" >' . $valueq . '</span>';
                }
            }
        }


        $size = $this->backup->sizeFormat2(Backup::folderSize(base_path('databasebackups') . DIRECTORY_SEPARATOR . $phpObject->klid));

        $html = '';
        $html .= '<div class="card shadow-none border rounded-0">
                        <div class="card-body">
                            <div>
                                <!-- <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">'.translate("Backup Detail").'</h5> -->
                                <h5 class="fw-600 mb-3 fs-16 pb-2">'.translate("Backup Detail").'</h5>
                                <!-- Product Details -->
                                <div>
                                    <!-- <table class="table table-responsive-md fs-14"> -->
                                    <table class="display table table-bordered StandardTable">
                                        <thead>
                                        <tr>

                                            <th scope="ro">#</th>
                                            <th scope="col">'.translate("Name").'</th>
                                            <th scope="col">'.translate("Size").'</th>
                                            <th scope="col">'.translate("Date").'</th>
                                            <th scope="col">'.translate("Backup Type").'</th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td  width="5%;" class="border-top-0 border-bottom pl-0">#</td>
                                            <td width="20%;" class="border-top-0 border-bottom">' . $phpObject->name . '</td>
                                            <td  width="15%;" class="border-top-0 border-bottom">' . $size . '</td>
                                            <td  width="15%;" class="border-top-0 border-bottom">' . $phpObject->date . '</td>
                                            <td width="45%;" class="border-top-0 border-bottom">' . $span . '</td>
                                        </tr>
                                         </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>';


        $response['status'] = $kia;
        $response['html'] = $html;
        echo json_encode($response);
//        return $kia;

    }


//    public function downloadBackups(Request $request, $key, $backuptype)
    public function downloadBackups(Request $request)
    {

//        dd($request);
//        "key" => "2024-02-08-22-06-39"
//      "backuptype" => "["2","4"]"
//      "backup_ids" => array:2 [▼
//        0 => "2"
//        1 => "4"

//        $backup_type = array(
//            "1" => "DataBase",
//            "2" => "Folder",
//            "4" => "Addons",
//            "8" => "WebSite",
//        );


        $zip = new \ZipArchive();
        $fileName = 'zipFile.zip';
        $path = base_path('databasebackups/') . $request->download_key;


        if ($request->download_ids) {
            if ($zip->open(public_path($fileName), \ZipArchive::CREATE) == TRUE) {

                foreach ($request->download_ids as $backuptype) {

                    switch ($backuptype) {
                        case 1:
                            foreach ($this->backup->scanFolder($path) as $file) {
                                if (strpos(basename($file), 'database') !== false) {
                                    $zip->addFile($path . DIRECTORY_SEPARATOR . $file, basename($path . DIRECTORY_SEPARATOR . $file));
                                }
                            }
                            break;
                        case 2:
                            foreach ($this->backup->scanFolder($path) as $file) {
                                if (strpos(basename($file), 'storage') !== false) {
                                    $zip->addFile($path . DIRECTORY_SEPARATOR . $file, basename($path . DIRECTORY_SEPARATOR . $file));
                                }
                            }
                            break;
                        case 4:
                            foreach ($this->backup->scanFolder($path) as $file) {
                                if (strpos(basename($file), 'addons') !== false) {
                                    $zip->addFile($path . DIRECTORY_SEPARATOR . $file, basename($path . DIRECTORY_SEPARATOR . $file));
                                }
                            }
                            break;
                        case 8:
                            foreach ($this->backup->scanFolder($path) as $file) {
                                if (strpos(basename($file), 'website') !== false) {
                                    $zip->addFile($path . DIRECTORY_SEPARATOR . $file, basename($path . DIRECTORY_SEPARATOR . $file));
                                }
                            }
                            break;
                        default:
                    }
                }
                $zip->close();

            }

            return response()->download(public_path($fileName))->deleteFileAfterSend(true);

        }

        return true;
    }


    public function restoreBackups(Request $request)
    {

//        $backup_type = array(
//            "1" => "DataBase",
//            "2" => "Folder",
//            "4" => "Addons",
//            "8" => "WebSite",
//        );

        if ($request->restore_ids) {
            $path = base_path('databasebackups/') . $request->restore_key;
            $sum_restoretype = 0;
            foreach ($request->restore_ids as $restoretype) {
                $sum_restoretype = $sum_restoretype + $restoretype;
            }

            switch ($sum_restoretype) {
                case 1:
                    try {
                        foreach ($this->backup->scanFolder($path) as $file) {
                            if (strpos(basename($file), 'database') !== false) {
                                $this->backup->restoreDb($path . DIRECTORY_SEPARATOR . $file, $path);
                            }
                        }
                        return redirect()->route('backups');
                    } catch (Exception $ex) {
                        return Redirect::back()
                            ->withError($ex->getMessage());
                    }
                    break;


                case 2:
                    try {
                        foreach ($this->backup->scanFolder($path) as $file) {
                            if (strpos(basename($file), 'storage') !== false) {
                                $this->backup->restoreStorage($path . DIRECTORY_SEPARATOR . $file, public_path('uploads'));  //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/uploads"
                            }
                        }
                        return redirect()->route('backups');
                    } catch (Exception $ex) {
                        return Redirect::back()
                            ->withError($ex->getMessage());
                    }
                    break;

                case 3:
                    try {
                        foreach ($this->backup->scanFolder($path) as $file) {
                            if (strpos(basename($file), 'database') !== false) {
                                $this->backup->restoreDb($path . DIRECTORY_SEPARATOR . $file, $path);
                            }

                            if (strpos(basename($file), 'storage') !== false) {
                                $this->backup->restoreStorage($path . DIRECTORY_SEPARATOR . $file, public_path('uploads'));  //public_path('uploads') ==> "/home/aryaclub/aec.aryaclub.com/public/uploads"
                            }
                        }
                        return redirect()->route('backups');
                    } catch (Exception $ex) {
                        return Redirect::back()
                            ->withError($ex->getMessage());
                    }
                    break;
                default:
            }
        }
    }


    public function deleteBackups(Request $request)
    {
//dd($request);

//first:
//        #parameters: array:5 [▼
//        "_token" => "EK5Ki3qiAF6lmVkKDMO4XjhQFrQWZISHWLmgmbq5"
//      "_method" => "DELETE"
//      "key" => "2024-03-11-16-31-06"
//      "backuptype" => "["1","2"]"
//      "delete_ids" => array:1 [▼
//        0 => "1"
//      ]
//    ]

//second:
//        #parameters: array:5 [▼
//        "_token" => "EK5Ki3qiAF6lmVkKDMO4XjhQFrQWZISHWLmgmbq5"
//      "_method" => "DELETE"
//      "key" => "2024-03-11-16-31-06"
//      "backuptype" => "["1","2"]"
//      "delete_ids" => array:1 [▼
//        0 => "1"
//      ]
//    ]




//   error_log("DELETE DETAIL(1) IS:=> key: " . $request->key . " ids: " . $request->delete_ids . " type: " . json_decode($request->backuptype));



//        $master_arr = json_decode($request->backuptype);
//        $delete_arr = $request->delete_ids;
//        $arr_1 = array_diff($master_arr, $delete_arr);
//        $arr_2 = array_diff($delete_arr, $master_arr);
//

//                dd($request->delete_key);
//                dd($request->delete_ids);
//                dd(json_decode($request->delete_backuptype));


        if (isset($request->delete_ids) && isset($request->delete_backuptype)) {
            try {
                $this->backup->deleteBackupxx($request->delete_key, $request->delete_ids, json_decode($request->delete_backuptype));
                return redirect()->route('backups');
            } catch (Exception $error) {
                return Redirect::back()
                    ->withError($error->getMessage());
            }
        }
    }

    public function uploadviaftpBackups(Request $request)
    {
//dd($request);

//first:
//        #parameters: array:5 [▼
//        "_token" => "EK5Ki3qiAF6lmVkKDMO4XjhQFrQWZISHWLmgmbq5"
//      "_method" => "DELETE"
//      "key" => "2024-03-11-16-31-06"
//      "backuptype" => "["1","2"]"
//      "delete_ids" => array:1 [▼
//        0 => "1"
//      ]
//    ]

//second:
//        #parameters: array:5 [▼
//        "_token" => "EK5Ki3qiAF6lmVkKDMO4XjhQFrQWZISHWLmgmbq5"
//      "_method" => "DELETE"
//      "key" => "2024-03-11-16-31-06"
//      "backuptype" => "["1","2"]"
//      "delete_ids" => array:1 [▼
//        0 => "1"
//      ]
//    ]




//   error_log("DELETE DETAIL(1) IS:=> key: " . $request->key . " ids: " . $request->delete_ids . " type: " . json_decode($request->backuptype));



//        $master_arr = json_decode($request->backuptype);
//        $delete_arr = $request->delete_ids;
//        $arr_1 = array_diff($master_arr, $delete_arr);
//        $arr_2 = array_diff($delete_arr, $master_arr);
//

//                dd($request->delete_key);
//                dd($request->delete_ids);
//                dd(json_decode($request->delete_backuptype));


        if (isset($request->ftp_ids) && isset($request->ftp_backuptype)) {
            try {
                $this->backup->uploadviaftpBackupsxx($request->ftp_key, $request->ftp_ids, json_decode($request->ftp_backuptype));
                return redirect()->route('backups');
            } catch (Exception $error) {
                return Redirect::back()
                    ->withError($error->getMessage());
            }
        }
    }









    public function bulk_backup_delete_old(Request $request) {
        $deleted = false;
        $rebuilddata = false;
        $returnvalue = 0;
        $countbackupid = count($request->backupklid);
        $countbackupdelete = 0;

        if ($request->backupklid && count($request->backupklid) > 0) {

            foreach ($request->backupklid as $foldername) {
                $path = base_path('databasebackups/') . $foldername;
                $deleted = $this->file->deleteDirectory($path);
                if ($deleted) {
                    $countbackupdelete ++;
                }
            }
            $file = base_path('databasebackups/backup.json');
            $data = $this->backup->getBackupListxx();
            if (!empty($data)) {
                $tmp = explode('/', $path);
                unset($data[end($tmp)]);
                $rebuilddata = $this->backup->saveFileData($file, $data);
            }

//            if ($deleted && $rebuilddata) {
            if ($countbackupid == $countbackupdelete && $rebuilddata) {
                $returnvalue = 1;
                flash($countbackupdelete . ' ' . translate('selected backup deleted successfully').json_encode($request->backupklid))->success();

            } else {
                $returnvalue = 0;
                flash(translate('error on delete seleted backup'))->error();
            }

        } else {
            $returnvalue = 0;
            flash(translate('error on delete seleted backup'))->error();
        }
        return $returnvalue;
    }

    public function bulk_backup_delete(Request $request) {
        $returnvalue = 0;
        $countbackupid = count($request->backupklid);
        $countbackupdelete = 0;
        $json_Array_count = 0;

        if ($request->backupklid && count($request->backupklid) > 0) {

            foreach ($request->backupklid as $foldername) {
                $path = base_path('databasebackups/') . $foldername;
                $deleted = $this->file->deleteDirectory($path);
                if ($deleted) {
                    $countbackupdelete ++;
                }
            }

            if ($countbackupid == $countbackupdelete) {

//              rejenerate and restructured backup.json file
                $directoryToScan = base_path('databasebackups');
                $json_array = array();

                // Open a known directory, and proceed to read its contents
                foreach(glob($directoryToScan . '/*' , GLOB_ONLYDIR) as $foldersx)
                {
                    $json_arraytype = array();
                    foreach(glob($foldersx, GLOB_ONLYDIR) as $folders)
                    {
                        $json_arraytype = array();
                        //get total number of jpg files in each folder
                        $num_files = count(glob("$folders/*.zip"));
                        $totalFiles = (string)$num_files;

                        if ($num_files < 1)  {
                            break;
                        }

                        $animals = [
                            'aardvark', 'albatross', 'alligator', 'alpaca', 'ant', 'anteater', 'antelope', 'ape', 'armadillo', 'donkey',
                            'baboon', 'badger', 'barracuda', 'bat', 'bear', 'beaver', 'bee', 'bison', 'boar', 'buffalo', 'galago', 'butterfly',
                            'camel', 'caribou', 'cat', 'caterpillar', 'cattle', 'chamois', 'cheetah', 'chicken', 'chimpanzee', 'chinchilla',
                            'chough', 'clam', 'cobra', 'cockroach', 'cod', 'cormorant', 'coyote', 'crab', 'crane', 'crocodile', 'crow', 'curlew',
                            'deer', 'dinosaur', 'dog', 'dogfish', 'dolphin', 'donkey', 'dotterel', 'dove', 'dragonfly', 'duck', 'dugong', 'dunlin',
                            'eagle', 'echidna', 'eel', 'eland', 'elephant', 'elephant-seal', 'elk', 'emu', 'falcon', 'ferret', 'finch', 'fish',
                            'flamingo', 'fly', 'fox', 'frog', 'gaur', 'gazelle', 'gerbil', 'giant-panda', 'giraffe', 'gnat', 'gnu', 'goat', 'goose',
                            'goldfinch', 'goldfish', 'gorilla', 'goshawk', 'grasshopper', 'grouse', 'guanaco', 'guinea-fowl', 'guinea-pig', 'gull',
                            'hamster', 'hare', 'hawk', 'hedgehog', 'heron', 'herring', 'hippopotamus', 'hornet', 'horse', 'human', 'hummingbird', 'hyena',
                            'jackal', 'jaguar', 'jay', 'jay, blue', 'jellyfish', 'kangaroo', 'koala', 'komodo-dragon', 'kouprey', 'kudu', 'lapwing',
                            'lark', 'lemur', 'leopard', 'lion', 'llama', 'lobster', 'locust', 'loris', 'louse', 'lyrebird', 'magpie', 'mallard', 'manatee',
                            'marten', 'meerkat', 'mink', 'mole', 'monkey', 'moose', 'mouse', 'mosquito', 'mule', 'narwhal', 'newt', 'nightingale',
                            'octopus', 'okapi', 'opossum', 'oryx', 'ostrich', 'otter', 'owl', 'ox', 'oyster', 'panther', 'parrot', 'partridge', 'peafowl',
                            'pelican', 'penguin', 'pheasant', 'pig', 'pigeon', 'pony', 'porcupine', 'porpoise', 'prairie-dog', 'quail', 'quelea', 'rabbit',
                            'raccoon', 'rail', 'ram', 'rat', 'raven', 'red-deer', 'red-panda', 'reindeer', 'rhinoceros', 'rook', 'ruff', 'salamander',
                            'salmon', 'sand-dollar', 'sandpiper', 'sardine', 'scorpion', 'sea-lion', 'sea-urchin', 'seahorse', 'seal', 'shark', 'sheep',
                            'shrew', 'shrimp', 'skunk', 'snail', 'snake', 'spider', 'squid', 'squirrel', 'starling', 'stingray', 'stinkbug', 'stork',
                            'swallow', 'swan', 'tapir', 'tarsier', 'termite', 'tiger', 'toad', 'trout', 'turkey', 'turtle', 'vicuna', 'viper', 'vulture',
                            'wallaby', 'walrus', 'wasp', 'water-buffalo', 'weasel', 'whale', 'wolf', 'wolverine', 'wombat', 'woodcock', 'woodpecker', 'worm', 'wren', 'yak', 'zebra'
                        ];

                        $random_key = array_rand($animals);
                        $picked_name = $animals[$random_key];
                        $random_number = rand(1000, 9999);
                        $dateString = basename($foldersx);
                        $carbonDate = Carbon::createFromFormat('Y-m-d-H-i-s',$dateString);
                        $json_key = basename($foldersx);
                        $json_name = $picked_name . '-' . $random_number;
                        $json_date = (string)$carbonDate;

                        foreach (glob("$folders/*.zip") as $filename) {
          //            $filename===> "/opt/lampp/htdocs/aec82/databasebackups/2024-03-11-10-13-48/website-2024-03-11-10-13-48.zip"

                            switch (true) {
                                case (strpos(basename($filename), 'database') !== false):
                                    $keyq = "1";
                                    break;
                                case (strpos(basename($filename), 'storage') !== false):
                                    $keyq = "2";
                                    break;
                                case (strpos(basename($filename), 'addons') !== false):
                                    $keyq = "4";
                                    break;
                                case (strpos(basename($filename), 'website') !== false):
                                    $keyq = "8";
                                    break;
                                default:
                            }
                            array_push($json_arraytype, $keyq);

                        }
                        $json_Array[$json_key] = array(
                            'name' => $json_name,
                            'date' => $json_date,
                            'klid' => $json_key,
                            'type' => $json_arraytype
                        );
                        $json_Array_count = count($json_Array);
                    }
                }

                $file = base_path('databasebackups/backup.json');

                try {
                    //first delete 'databasebackups/backup.json'
                    $this->file->delete($file);

                    //second put data in 'databasebackups/backup.json'
                    if ($json_Array_count>0) {
                        $this->backup->saveFileData($file, $json_Array);
                    }
                    if (file_exists($file)) {
                        chmod($file, 0777);
                    }

                        $returnvalue = 1;
//                    flash(translate('selected backup deleted successfully').json_encode($request->backupklid).'  ==> ' . $countbackupdelete)->success();
                } catch (Exception $error) {
                    $returnvalue = 0;
//                    $returnvalue = $error->getMessage();

//                    flash(translate('error on delete seleted backup'))->error();
                }

            } else {
                $returnvalue = 0;
//                flash(translate('error on delete seleted backup'))->error();
            }

        } else {
            $returnvalue = 0;
//            flash(translate('no file selected for delete'))->error();
        }

        return ($returnvalue == 1) ? true : false;
        //or
        //return $returnvalue == 1;

    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function deleteCronjobTaskLinkInfo($id_idfilename) {


        $idfilename = decrypt($id_idfilename);
//        dd($idfilename);
        $cron_path = base_path('databasebackups/cronjob');
        $cron_json = $cron_path . DIRECTORY_SEPARATOR . 'cronjob.json';
        if (file_exists($cron_json)) {
            $arr = json_decode(file_get_contents($cron_json), true);
            $result = [];

            foreach ($arr as $key => $value) {
                if ($value['idfilename'] != $idfilename) {
                    $result[] = $value;
                }
            }
            $update = file_put_contents($cron_json, json_encode($result));

//            dd(json_encode($result));
//            print_r(json_encode($result));
            flash(translate('Cronjob Task Data Deleted Successfully'))->success();
        }

        return back();

    }

    public function editCronjobTaskLinkInfo(Request $request)
    {
//dd($request);


        $cron_path = base_path('databasebackups/cronjob');
        $cron_json = $cron_path . DIRECTORY_SEPARATOR . 'cronjob.json';
        if (file_exists($cron_json)) {
            $arr = json_decode(file_get_contents($cron_json), true);

            $khorkhorx = json_encode($request->edit_crownjob_ids);
//            dd($khorkhorx);

            $khorkhorxx = json_decode($khorkhorx);
//            dd($khorkhorxx);




            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $request->idkey) {
                      $arr[$key]['type'] = $khorkhorxx;
                }
            }
            $update = file_put_contents($cron_json, json_encode($arr));
        }

        return redirect()->route('backups');
    }





    public function editBackupsShareLinkInfo(Request $request)
    {
//dd($request);
        if (isset($request->lifetime_link)) {
            $lifetime_update = $request->lifetime_link;
        } else {
            $lifetime_update = 1;
        }

        if (isset($request->onetime_link)) {
            $onetime_update = $request->onetime_link == 'on' ? 1 : 0;
        } else {
            $onetime_update = 0;
        }

        $old_password_data = 'oldpasslink_' . $request->keymember;
        $password_data = 'updatepasslink_' . $request->keymember;
        if (isset($request->$password_data) && $request->$password_data != null && $request->$password_data !== $old_password_data) {
            $password_update = md5($request->$password_data);
        } else {
            $password_update = $request->passkey;
        }

        $datetime_data = 'updatetimelink_' . $request->keymember;

        if (isset($request->$datetime_data)) {

            $datetime_update = strtotime($request->$datetime_data) == $request->datetimekey ? $request->datetimekey : strtotime($request->$datetime_data);
        } else {
            $datetime_update = strtotime(date('Y-m-d H:m:s'));
        }


        $datetimerange_data = 'updatetimeRangelink_' . $request->keymember;
        if (isset($request->$datetimerange_data)) {
            $date_var = explode(" to ", $request->$datetimerange_data);
            $datetime_data_start_date = strtotime($date_var[0]);
            $datetime_data_end_date = strtotime($date_var[1]);
            $datetimerange_update = $datetime_data_start_date == $request->datetimekey ? $request->datetimekey : $datetime_data_start_date;
            $lifetime_update = floor(($datetime_data_end_date - $datetime_data_start_date) / 86400);
        } else {
            $datetime_data_start_date = strtotime(date('Y-m-d H:m:s'));
            $datetime_data_end_date = strtotime(date('Y-m-d H:m:s'));
            $datetimerange_update = $datetime_data_start_date;
            $lifetime_update = floor(($datetime_data_end_date - $datetime_data_start_date) / 86400);
        }
        $datetime_update = $datetimerange_update;
        $lifetime_update = $lifetime_update > 1 ? $lifetime_update : 1;


        $share_path = base_path('databasebackups/share');
        $share_json = $share_path . DIRECTORY_SEPARATOR . 'shares.json';
        if (file_exists($share_json)) {
            $arr = json_decode(file_get_contents($share_json), true);
//            foreach ($arr as $key => $value) {
//                if ($value['idfilename'] == $request->idkey) {
//                    $master_arr = $value['attachmentsxx']; //array of all files shared
//                }
//            }
            $khorkhorx = json_encode($request->edit_ids);
//            dd($khorkhorx);

            $khorkhorxx = json_decode($khorkhorx);
//            dd($khorkhorxx);


            $attachments = array();
            $attachmentsxx = array();
            $backupnames = $request->backupname;
            $path = 'databasebackups/' . $backupnames;
            if (count($khorkhorxx) > 0) {
                foreach ($this->backup->scanFolder($path) as $backupfiles) {
                    foreach ($khorkhorxx as $backuptype) {

                        switch ($backuptype) {
                            case 1:
                                if (strpos(basename($backupfiles), 'database') !== false) {
                                    array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                    array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                }
                                break;
                            case 2:
                                if (strpos(basename($backupfiles), 'storage') !== false) {
                                    array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                    array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                }
                                break;
                            case 4:
                                if (strpos(basename($backupfiles), 'addons') !== false) {
                                    array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                    array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                }
                                break;
                            case 8:
                                if (strpos(basename($backupfiles), 'website') !== false) {
                                    array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                    array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                }
                                break;
                            default:
                        }
                    }

                }
            }
            $atts = join(',', $attachments);


//dd($attachmentsxx);



            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $request->idkey) {
                    $arr[$key]['lifetime'] = $lifetime_update;
                    $arr[$key]['onetime'] = $onetime_update;
                    $arr[$key]['pass'] = $password_update;
                    $arr[$key]['time'] = $datetime_update;
                    $arr[$key]['attachments'] = $atts;
                    $arr[$key]['attachmentsxx'] = $attachmentsxx;

                }
            }
            $update = file_put_contents($share_json, json_encode($arr));
        }

        return redirect()->route('backups');
    }

    public function downloadfilefromlink1($countfiles_sh_share_myfile)
    {

        $babareza = explode(",", $countfiles_sh_share_myfile);

        $script_url = env('APP_URL') . "/";

        $supah = $babareza[1]; //$sh;                                    // filter_input(INPUT_GET, 'sh', FILTER_SANITIZE_SPECIAL_CHARS);
        $json_file = $babareza[2]; //$share;                             // filter_input(INPUT_GET, 'share', FILTER_SANITIZE_SPECIAL_CHARS);
        $android = false;
        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (stripos($useragent, 'android') !== false) {
            $android = true;
        }

        $myfile = urldecode(base64_decode($babareza[3])); //$myfile = urldecode(base64_decode($myfile));==>it is  attachmentxx array

        $share_path = base_path('databasebackups/share');
        $share_json = $share_path . DIRECTORY_SEPARATOR . 'shares.json';

        if ($json_file && file_exists($share_json)) {
            $arr = json_decode(file_get_contents($share_json), true);
            $find_val = $json_file;
            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $find_val) {
                    $datarray = $value;
                }
            }

            $time = $datarray['time'];
            $hash = $datarray['hash'];
            $one_time_download_config = 0;
            $lifetime_config = 1;
            $lifetime = isset($datarray['lifetime']) ? (int)$datarray['lifetime'] : $lifetime_config;
            $onetime_download = isset($datarray['onetime']) ? (int)$datarray['onetime'] : $one_time_download_config;
            if (md5($time . $hash) !== $supah) {
                $this->setError('<i class="las la-slash"></i> ' . 'access denied'); //Utils::setError('<i class="bi bi-slash-circle"></i> '."access denied");
                header('Location:' . $script_url);
                exit;
            }

            // Check expiration time.
            if ($this->checkTime($time, $lifetime)) {
                if (file_exists($myfile)) {
                    $master_arr = $datarray['attachmentsxx']; //array of all files shared
                    $subdwn_arr = array();
                    array_push($subdwn_arr, $babareza[3]);

                    $headers = $this->getHeaders($myfile);  //getHeaders must be edited and corrected lateer

                    if ($this->download(
                            $headers['file'],
                            $headers['filename'],
                            $headers['file_size'],
                            $headers['content_type'],
                            $headers['disposition'],
                            $android
                        ) === true
                    ) {

                        $this->logDownload($headers['trackfile']);
                        if ($onetime_download == 1) {

                            $arr_1 = array_diff($master_arr, $subdwn_arr);
                            $arr_2 = array_diff($subdwn_arr, $master_arr);
                            $final_output = array_merge($arr_1, $arr_2);
                            $final_output = $arr_1;
                            $jsonData = file_get_contents($share_json);
                            $datanew = json_decode($jsonData, true);
                            foreach ($datanew as $key => $value) {
                                if ($value['idfilename'] == $find_val) {
                                    $datanew[$key]['attachmentsxx'] = $final_output;
                                }
                            }
                            $file_put = file_put_contents($share_json, json_encode($datanew));

                            if ($file_put == false) {
                                return redirect()->back()->with('error', translate('There was a problem downloading the file') . ' ' . $myfile);
                            } else {
                                return redirect()->back()->with('success', translate('The file was downloaded successfully') . ' ' . $myfile);
                            }
                        }

                    } else {
                        return redirect()->route('backups.viewfilefromlink', $find_val)->with('error', translate('There was a problem downloading the file') . ' ' . $myfile);
                    }

                }
            }

        }

        return true;
    }

    public function send(Request $request)
    {

        $path = base_path('databasebackups/') . $request->key;

        try {

            return redirect()->route('backups');
        } catch (Exception $error) {
            return Redirect::back()
                ->withError($error->getMessage());
        }
    }


//    public function check_share_modal(Request $request)
//    {
//
//    }



    public function share_modal(Request $request)
    {

        //get backupedxx files info
        $backupsxxx = $this->backup->getBackupListxx();

        foreach ($backupsxxx as $key => $value) {
            if ($value['klid'] == $request->id) {
                $backupxxnew = $value;
            }
        }

        $klid = $request->id;

        foreach ($backupsxxx as $keyxx => $backupxxx) {
            $backupsxxx[$keyxx]['size'] = $this->backup->sizeFormat2(Backup::folderSize(base_path('databasebackups') . DIRECTORY_SEPARATOR . $keyxx));
        }
        return view('backend.backup.create_share_modal')->with(compact('backupsxxx', 'backupxxnew', 'klid'));
    }

    public function shortencron(Request $request)
    {

        $khorkhory = json_encode($request->cronfilesidx);
        $khorkhoryy = json_decode($khorkhory);

        $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_SPECIAL_CHARS);
//        $formatedDate = $date->format('Y-m-d H:i:s');

        $pass = false;

        $type = array();
        if (count($khorkhoryy) > 0) {
            foreach ($khorkhoryy as $backuptype) {
                array_push($type, $backuptype);

            }

        }

        $atts = join(',', $type);

        $saveData = array();
        $saveData['time'] = $time;
        $saveData['type'] = $type;

        $json_name = md5($time . $atts . $pass);
        $saveData['idfilename'] = $json_name;

        $baseFolder = $this->backup->createFolder(base_path('databasebackups/cronjob'));
        $userData = $saveData;

        $filex = base_path('databasebackups/cronjob/cronjob.json');
        if (file_exists($filex)) {
            $data = array();
            $jsonData = file_get_contents($filex);
            $data = json_decode($jsonData, true);
            $data = array_filter($data);
            array_push($data, $userData);
        } else {
            $data[] = $userData;
        }
        $insert = file_put_contents($filex, json_encode($data));
        echo $json_name;

    }

    public function shorten(Request $request)
    {

//        $khorkhorx = json_encode($request->backupfilesid);
//        $khorkhorxx = json_decode($khorkhorx);

        $khorkhory = json_encode($request->backupfilesidx);
        $khorkhoryy = json_decode($khorkhory);


//        error_log("BACKUP DETAIL(1) IS:=> ".json_encode($khorkhorxx));
//        error_log("BACKUP DETAIL(2) IS:=> ".json_encode($khorkhoryy));


        $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_SPECIAL_CHARS);
        $hash = filter_input(INPUT_POST, "hash", FILTER_SANITIZE_SPECIAL_CHARS);
        $backupnames = filter_input(INPUT_POST, "backupnames", FILTER_SANITIZE_SPECIAL_CHARS);

        $onetime = filter_input(INPUT_POST, "onetime", FILTER_SANITIZE_SPECIAL_CHARS);
        $pass = isset($_POST['pass']) ? $_POST['pass'] : false;

        $lifetime = filter_input(INPUT_POST, "createtimeRangelink", FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($request->createtimeRangelink)) {
            $date_var = explode(" to ", $request->createtimeRangelink);
            $datetime_data_start_date = strtotime($date_var[0]);
            $datetime_data_end_date = strtotime($date_var[1]);
        } else {
            $datetime_data_start_date = strtotime(date('Y-m-d H:m:s'));
            $datetime_data_end_date = strtotime(date('Y-m-d H:m:s'));
        }
        $datetimerange_update = $datetime_data_start_date;
        $lifetime_update = floor(($datetime_data_end_date - $datetime_data_start_date) / 86400);
        $datetime_create = $datetimerange_update;
        $lifetime_create = $lifetime_update > 1 ? $lifetime_update : 1;


        $hpass = false;
        if ($pass) {
            if (strlen($pass) > 0) {
                $hpass = md5($pass);
            }
        }

        $attachments = array();
        $attachmentsxx = array();
        $path = base_path('databasebackups/') . $backupnames;
        $path = 'databasebackups/' . $backupnames;
//        if (count($khorkhorxx) > 0) {
        if (count($khorkhoryy) > 0) {
            foreach ($this->backup->scanFolder($path) as $backupfiles) {

//                foreach ($khorkhorxx as $backuptype) {
                foreach ($khorkhoryy as $backuptype) {

                    switch ($backuptype) {
                        case 1:
                            if (strpos(basename($backupfiles), 'database') !== false) {
                                array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                            }
                            break;
                        case 2:
                            if (strpos(basename($backupfiles), 'storage') !== false) {
                                array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                            }
                            break;
                        case 4:
                            if (strpos(basename($backupfiles), 'addons') !== false) {
                                array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                            }
                            break;
                        case 8:
                            if (strpos(basename($backupfiles), 'website') !== false) {
                                array_push($attachments, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                                array_push($attachmentsxx, base64_encode($path . DIRECTORY_SEPARATOR . rawurlencode($backupfiles)));
                            }
                            break;
                        default:
                    }
                }

            }
        }

        $atts = join(',', $attachments);

        $saveData = array();

        $saveData['lifetime'] = $lifetime_create;
        $saveData['onetime'] = $onetime;
        $saveData['pass'] = $hpass;

        $saveData['time'] = $datetime_create;
        $saveData['hash'] = $hash;
        $saveData['attachments'] = $atts;
        $saveData['attachmentsxx'] = $attachmentsxx;
        $json_name = md5($time . $atts . $pass);
        $saveData['idfilename'] = $json_name;
        $saveData['backupname'] = $backupnames;

//      create folder if not exist
        $shareFolder = $this->backup->createFolder(base_path('databasebackups/share'));

        $userData = $saveData;
        $filex = base_path('databasebackups/share/shares.json');

        if (file_exists($filex)) {
            $data = array();
            $jsonData = file_get_contents($filex);
            $data = json_decode($jsonData, true);
            $data = array_filter($data);
            array_push($data, $userData);
        } else {
            $data[] = $userData;
        }
        $insert = file_put_contents($filex, json_encode($data));
        echo $json_name;

    }

    public function checkTime($time, $lifedays = false)
    {

        $lifedays = $lifedays ? (int)$lifedays : 1;
        $lifetime = 86400 * $lifedays;
        if (time() <= $time + $lifetime) {
            return true;
        }
        return false;
    }


    public function sendfilestoemail(Request $request)
    {

        $this_backupnames = $request->backupnames;
        $this_lang = $request->thislang;
        $this_from = $request->mitt;
        $this_dest = $request->dest;
        $this_message = $request->message;
        $this_lifetimelink = $request->lifetimelink;
        $this_onetimecheck = $request->onetimecheck;
        $this_passlink = $request->passlink;
        $this_link = $request->sharelink;
        $this_secretlink = $request->secretlink;

        $bcc = filter_input(INPUT_POST, 'send_cc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        //for retrieve data from json file

        $share_path = base_path('databasebackups/share');
        $share_json = $share_path . DIRECTORY_SEPARATOR . 'shares.json';


        if ($request->secretlink && file_exists($share_json)) {
            $arr = json_decode(file_get_contents($share_json), true);

            foreach ($arr as $key => $value) {
                if ($value['idfilename'] == $this_secretlink) {
                    $datarray = $value;

                    $pass = (isset($datarray['pass']) ? $datarray['pass'] : false);
                    $hash = $datarray['hash'];
                    $time = $datarray['time'];
                    $sh = md5($time . $hash);
                    $piecesoldoldold = explode(",", $datarray['attachments']);
                    $pieces = $datarray['attachmentsxx'];
                    $totalsize = 0;
                    $countfiles = 0;
                    $linkkey = $this_secretlink;
                    $myfilesnamex = "<ul>";
                    $myfilessizex = "<ul>";
                    foreach ($pieces as $count => $pezzo) {
                        $myfile = urldecode(base64_decode($pezzo)); //==> databasebackups/2024-01-03-13-42-40/database-2024-01-03-13-42-40.zip
                        if (file_exists($myfile)) {
                            $filepathinfo = $this->mbPathinfo($myfile);
                            $filename = $filepathinfo['basename'];
                            $extension = strtolower($filepathinfo['extension']);
                            $filesize = $this->getFileSize($myfile);
                            $totalsize += $filesize;

                            $parameters = array();

                            array_push($parameters, $countfiles);
                            $paramatts = join(',', $parameters);

                            array_push($parameters, $sh);
                            $paramatts = join(',', $parameters);

                            array_push($parameters, $linkkey);
                            $paramatts = join(',', $parameters);

                            array_push($parameters, $pezzo);
                            $paramatts = join(',', $parameters);
                            $countfiles++;


                            $myfilesnamex .= "<li>" . $filename . "</li>";
                            $myfilessizex .= "<li>" . $this->formatSize($filesize) . "</li>";

                        }
                    }
                    $myfilesnamex .= "</ul>";
                    $myfilessizex .= "</ul>";
                }
            }
        }

        if (env('MAIL_USERNAME') != null) {
            //sends download link to selected destination email
            if (filter_var($request->dest, FILTER_VALIDATE_EMAIL)) {
                if ($request->has('dest') && $request->has('sharelink') && $request->has('secretlink')) {

                    $array['view'] = 'emails.download_link';
                    $array['subject'] = translate('sent you some files');
                    $array['from'] = env('MAIL_FROM_ADDRESS');
                    $array['content'] = $request->sharelink;
                    $array['message'] = $this_message;
                    $array['filename'] = $filename;
                    $array['filesize'] = $this->formatSize($filesize);
                    $array['time'] = $time;
                    $array['password'] = $this_passlink;
                    $array['myfilesnamex'] = $myfilesnamex;
                    $array['myfilessizex'] = $myfilessizex;
                    try {
                        Mail::to($request->dest)->queue(new EmailManager($array));
                    } catch (\Exception $e) {

                    }
                }
            }

            if (isset($bcc) && count($bcc) > 0) {

                foreach ($bcc as $key => $bccemail) {

                    if (filter_var($bccemail, FILTER_VALIDATE_EMAIL)) {
                        if ($request->has('dest') && $request->has('sharelink') && $request->has('secretlink')) {
                            $array['view'] = 'emails.download_link';
                            $array['subject'] = translate('sent you some files');
                            $array['from'] = env('MAIL_FROM_ADDRESS');
                            $array['content'] = $request->sharelink;
                            $array['message'] = $this_message;
                            $array['filename'] = $filename;
                            $array['filesize'] = $this->formatSize($filesize);
                            $array['time'] = $time;
                            $array['password'] = $this_passlink;
                            $array['myfilesnamex'] = $myfilesnamex;
                            $array['myfilessizex'] = $myfilessizex;
                            try {
                                Mail::to($bccemail)->queue(new EmailManager($array));
                            } catch (\Exception $e) {
                            }
                        }
                    }

                }
            }

        } else {
            flash(translate('Please configure SMTP first'))->error();
            return back();
        }

        flash(translate('Dowload link has been send'))->success();
        return redirect()->back();
    }


    public function viewfilefromlink($linkkey)
    {
        return view('backend.backup.download_share')->with(compact('linkkey'));
    }

    public function executecronjobfromlink($test = 0, $linkkey)
    {
        return view('backend.backup.execute_cronjob')->with(compact('linkkey', 'test'));
    }


    /**
     * Format file size
     *
     * @param string $size new format
     *
     * @return formatted size
     */
    public function formatSize($size)
    {
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        $syz = $sizes[0];
        for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
            $size = $size / 1024;
            $syz = $sizes[$i];
        }
        return round($size, 2) . ' ' . $syz;
    }


    /**
     * Output errors
     *
     * @param string $message error message
     *
     * @return output error
     */
    public function setError($message)
    {
        if (isset($_SESSION['error']) && in_array($message, $_SESSION['error'])) {
            return false;
        }
        $_SESSION['error'][] = $message;
    }


    /**
     * Get file info before processing download
     *
     * @param string $getfile file to download
     * @param string $playmp3 check audio
     *
     * @return $headers array
     */
    public function getHeaders($getfile)
    {
        $headers = array();

        $file = $getfile;
        $filepathinfo = $this->mbPathinfo($file);

        $filename = $filepathinfo['basename'];
        $dirname = $filepathinfo['dirname'] . '/';
        $ext = $filepathinfo['extension'];
        $file_size = $this->getFileSize($file);
        //dd($this->getFileSize($file));
        $disposition = 'attachment';
        $content_type = 'application/force-download';

        if (strtolower($ext) == 'zip') {
            $content_type = 'application/zip';
        }
        $headers['file'] = $file;
        $headers['filename'] = $filename;
        $headers['file_size'] = $file_size;
        $headers['content_type'] = $content_type;
        $headers['disposition'] = $disposition;
        $headers['trackfile'] = './' . $file;
        $headers['dirname'] = $dirname;

        return $headers;
    }


    /**
     * Download files
     *
     * @param string $file path to download
     * @param string $filename file name
     * @param string $file_size file size
     * @param string $content_type header content type
     * @param string $disposition header disposition
     * @param bool $android android device
     *
     * @return file served
     */
    public function download(
        $file,
        $filename,
        $file_size,
        $content_type,
        $disposition = 'inline',
        $android = false
    )
    {
        // Gzip enabled may set the wrong file size.
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        if (ini_get('zlib.output_compression')) {
            @ini_set('zlib.output_compression', 'Off');
        }
        @set_time_limit(0);
        session_write_close();
        header("Content-Length: " . $file_size);

        if ($android) {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        } else {
            header("Content-Type: $content_type");
            header("Content-Disposition: $disposition; filename=\"" . $filename . "\"");
            // header("Content-Transfer-Encoding: binary");
            header("Expires: -1");
        }
        if (ob_get_level()) {
            ob_end_clean();
        }
        $handle = fopen($file, 'rb');
        if ($handle !== false && $file_size > 0) {
            @flock($handle, LOCK_SH);
            $start = 0;
            $end = $file_size - 1;
            $chunk = 8 * 1024;
            $requested = (float)$end - (float)$start + 1;
            $error = false;
            while (!$error) {
                if ($chunk >= $requested) {
                    $chunk = (integer)$requested;
                }
                while (!feof($handle) && (connection_status() === 0)) {
                    if (!$buffer = @fread($handle, $chunk)) {
                        $error = true;
                        break 2;
                    }
                    print($buffer);
                    flush();
                }
                @flock($handle, LOCK_UN);
                @fclose($handle);
                break;
            }
            if ($error) {
                // 500 - Internal server error
                exit;
            }
        }
        /* Deprecated method, connection error with very large files */
        // readfile($file);
        return true;

    }


    /**
     * Log download of single files
     *
     * @param string $path the path to set
     * @param bool $folder if is folder
     * @param string $relative relative path to /log/ folder
     *
     * @return $message
     */
    public function logDownload($path, $folder = false)
    {

        if (Auth::check()) {
            $user = Auth::user()->user_type;
        } else {
            $user = 'guest';
        }


        $type = $folder ? 'folder' : 'file';
        if (is_array($path)) {
            foreach ($path as $value) {
                $path = addslashes($value);
                $message = array(
                    'user' => $user,
                    'action' => 'DOWNLOAD',
                    'type' => $type,
                    'item' => ltrim($path, './'),
                );
                $this->logtofile($message);
            }
        } else {
            $path = addslashes($path);
            $message = array(
                'user' => $user,
                'action' => 'DOWNLOAD',
                'type' => $type,
                'item' => ltrim($path, './'),
            );
            $this->logtofile($message);
        }
    }


    /**
     * Print log file
     *
     * @param string $message the message to log
     * @param string $relpath relative path of log file // DROPPED in favor of dirname()
     *
     * @return $message
     */
    public function logtofile($message)
    {
        $log_file = true;
        if ($log_file == true) {

            $logFolder = $this->backup->createFolder(base_path('databasebackups/log'));
            $logjson = base_path('databasebackups/log/log.json');


            if ($this->isFileWritable($logjson)) {
                $message['time'] = date('H:i:s');
                if (file_exists($logjson)) {
                    $oldlog = json_decode(file_get_contents($logjson), true);
                } else {
                    $oldlog = array();
                }

                $daily = date('Y-m-d');
                $oldlog[$daily][] = $message;
                $f = fopen($logjson, 'a');
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    file_put_contents($logjson, json_encode($oldlog, JSON_FORCE_OBJECT));
                } else {
                    if (flock($f, LOCK_EX | LOCK_NB)) {
                        file_put_contents($logjson, json_encode($oldlog, JSON_FORCE_OBJECT));
                        flock($f, LOCK_UN);
                    }
                }
                fclose($f);
            } else {
                flash(translate('The script does not have permissions to write inside databasebackups/log/ folder. check CHMOD ') . $logjson)->error();
                return;
            }
        }
    }

    /**
     * Check if target file is writeable
     *
     * @param string $file path to check
     *
     * @return true/false
     */
    public function isFileWritable($file)
    {
        if (file_exists($file) && is_writable($file)) {
            return true;
        }
        if (is_writable(dirname($file))) {
            return true;
        }
        return false;
    }


}

