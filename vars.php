<?php
$debug=0;
$GLOBALS['app_version']="8.0.0";
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'].'/';
$siteURL= $protocol.$domainName;

define('SITE_URL', $siteURL);
$GLOBALS['URL']=SITE_URL;
$domainName=str_ireplace("/", "", $domainName);
define('FW_DIR', ROOT . DS . 'bi');
define('PROJECT_DIR', ROOT . DS . 'src');
define('APP_DIR', PROJECT_DIR);
define('VENDOR_DIR', ROOT.DS.'vendor');
define('CLASSES_DIR', FW_DIR.DS.'classes');


$vendorDirPath = VENDOR_DIR;//ROOT.DS.'vendor';
if (file_exists($vendorDirPath . '/autoload.php')) {
    require $vendorDirPath . '/autoload.php';
} else {
    throw new Exception(
        sprintf(
            'Could not find file \'%s\'. It is generated by Composer. Use \'install --prefer-source\' or \'update --prefer-source\' Composer commands to move forward.',
            $vendorDirPath . '/autoload.php'
        )
    );
}

if (file_exists(APP_DIR.DS.'.env')) {
    $dotenv = new Dotenv\Dotenv(APP_DIR);
    $dotenv->overload();
    //$dotenv->load();
} else {
    if ($debug==1) {
        echo APP_DIR.DS.'.env'." not found <br>";
    }
}
if ($debug==1) {
    echo 'USER DEFINED<br>';
    echo '===============<br>';
    echo "<pre>";
    print_r($_ENV);
    echo "</pre>";
}

//define('CONFIG', ROOT . DS . 'config');
define('WWW_DIR', ROOT . DS . 'public');

//if(($domainName=='')||($domainName=='localhost')||(ip2long($domainName) !== true))$domainName='is.lan';

//$name=explode('.',$domainName);
$domain=$domainName;

function get_app_db($domain)
{
    if (($domain=='')||($domain=='localhost')) {
        $domain='is.lan';
    }
    //$domain='db.app.default.com';
    //$first_level_domains=['.com.cy','.com','.ru','.co.uk','.lan','.org','.gov','.io'];
    $parts=explode('.', $domain);
    if ($parts[0]=='www') {
        array_shift($parts);
    }
    if (($parts[count($parts)-2]=='com')||($parts[count($parts)-2]=='co')) {
        array_pop($parts);
        array_pop($parts);
    } else {
        array_pop($parts);
    }

    $count=count($parts);
    //echo "c:$count<br>";
    //var_dump($parts);
    if ($count<=2) {
        $app_name=$parts[0];
        $db_name=$parts[0];
    }
    if ($count>2) {
        $app_name=$parts[1];
        $db_name=$parts[0];
    }


    if ($app_name=='app') {
        $app_name=$parts[count($parts)-1];
    }
    if ($db_name=='app') {
        $db_name=$parts[count($parts)-1];
    }
    //echo "$db_name@$app_name<br>";

    if ($app_name=='szcmail') {
        $app_name='is';
        $db_name='szc';
    }
    if ($db_name=='is') {
        $app_name='is';
        $db_name='szc';
    }
    if ($app_name=='app') {
        $app_name='is';
        $db_name='szc';
    }
    if (!(file_exists(PROJECT_DIR.$app_name))) {
        $app_name='is';
    }
    if (!(file_exists(PROJECT_DIR.$app_name))) {
        $dirs = array_filter(glob(PROJECT_DIR."*"), 'is_dir');
        $app_name=$dirs[0];
        $app_name = basename($dirs[0]);
    }

    if ($_ENV['DB_NAME']!='') {
        $db_name=$_ENV['DB_NAME'];
    }
    if ($_ENV['APP_NAME']!='') {
        $app_name=$_ENV['APP_NAME'];
    }
    if (($app_name=='')&&($db_name!='')) {
        $app_name=$db_name;
    }
    //echo "$db_name@$app_name<br>";
    return [$db_name,$app_name];
}
$db_app=get_app_db($domain);

$app_name=$db_app[1];
$db_name=$db_app[0];
//echo '<pre>';print_r($db_app);echo '</pre>';exit;

$GLOBALS['project']=$app_name;


define('APP_NAME', $app_name);
define('DB_NAME', $db_name);



$GLOBALS['db']['name']=$db_name;
//define('APP_DIR', PROJECT_DIR . $app_name);


if ($_ENV['DATA_DIR']!='') {
    define('DATA_DIR', $_ENV['DATA_DIR'].DS);
}

$data_dir_tmp=''.ROOT . DS . 'storage';
if (file_exists($data_dir_tmp)) {
    define('DATA_DIR', $data_dir_tmp. DS);
}

define('DOCS_DIR', DATA_DIR .'docs'. DS);
define('LH_DIR', DATA_DIR .'lh'. DS);
define('TMPLTS_DIR', DATA_DIR .'templates'. DS);
define('SACANS_DIR', DATA_DIR .'scans'. DS);
define('TRASH_DIR', DATA_DIR .'trash'. DS);
define('LOGS_DIR', DATA_DIR .'logs'. DS);
define('TK_DIR', DATA_DIR .'tk'. DS);
define('SIGNS_DIR', DATA_DIR .'signatures'. DS);

$app_uri=str_replace('/index.php', '', $_SERVER['PHP_SELF']);
//if($app_uri=='')$app_uri='/';
define(APP_URI, $app_uri);
$formdata='';
foreach ($_POST as $key => $value) {
    $formdata="$formdata&$key=$value";
}
$GLOBALS['orgqry']=$_SERVER['QUERY_STRING'].$formdata;
//require_once(APP_DIR.'/settings.php');

$GLOBALS['db']['server']=getenv('DB_SERVER');
$GLOBALS['db']['port']=getenv('DB_PORT');
if ($GLOBALS['db']['name']=="") {
    $GLOBALS['db']['name']=getenv('DB_NAME');
}
if ($GLOBALS['db']['name2']=="") {
    $GLOBALS['db']['name2']=getenv('DB_SERVER2');
}
$GLOBALS['db']['user']=getenv('DB_USER');
$GLOBALS['db']['pass']=getenv('DB_PASS');
//Vars
$GLOBALS['settings']['dev_mode']=getenv('DEV_MODE');


if ($debug==1) {
    echo 'SYSTEM CONSTANTS<br>';
    echo '===============<br>'.
    'app_name='.$app_name.'<br>'.
    'db_name='.$db_name.'<br>';
    echo "<pre>";
    print_r(get_defined_constants(true)[user]);
    echo "</pre>";
    echo 'SERVER VARS<br>';
    echo '===============<br>';
    echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";
    exit;
}
// composer requires usmanhalalit/pixie
// https://github.com/usmanhalalit/pixie
new \Pixie\Connection(
    'pgsql',
    array(
    'driver'   => 'pgsql',
    'host'     => $_ENV['DB_SERVER'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset'  => 'utf8',
    'prefix'   => '',
    'schema'   => 'public',
    ),
    'QB'
);
