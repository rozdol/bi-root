<?php
use \Firebase\JWT\JWT;

error_reporting(E_ERROR);

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Disposition: inline; filename="files.json"');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
header('Content-type: application/json');
//$JSONinput=$this->html->readRQ('data');
$filtered=[];
//$filtered=$this->html->readRQj('data');
$JSONData=null;
$inputs=$filtered;
$php_input = json_decode(file_get_contents('php://input'), true);

if ($php_input) {
    $_POST = array_merge($php_input, $_POST);
}


unset($_POST[data]);
foreach ($_POST as $key => $value) {
    $inputs[$key]=$this->html->filter($key, $value);
    //$inputs[$key]=filter_var($value, $filters[$key], $options[$key]);
}

if ($inputs[api_key]=='') {
    $username=$inputs[user];
    $password=$inputs[pass];

    $query = QB::table('users')
        ->where('username', $username)
        ->where('active', '1')
        ->orderBy('id', 'ASC');
    $user = get_object_vars($query->first());

    $good_hash=$user[password_hash];

    $ok=$this->crypt->validate_password($password, $good_hash)*1;
    if ($ok > 0) {
        $token =
        [
            'sub' => $user[id],
            'unm' => $user[username],
            'exp' => time() + 604800 //1 week
        ];
        $jwt = JWT::encode($token, $_ENV[APP_SALT]);

        $query = QB::table('apis')
            ->where('user_id', $user[id])
            ->where('active', 't');
        $api = get_object_vars($query->first());

        if ($api[id]>0) {
            $funcs=explode(',', $api[functions]);
            echo json_encode(['api_key'=>$api[key], 'authorization'=>"Bearer $jwt",'funcs'=>$funcs]);
            exit;
        } else {
            echo json_encode(['error'=>"No api key for user $username"]);
            exit;
        }
    } else {
        $descr="API:$username:$password";
        $this->data->chk_fails($descr);
        echo json_encode(['error'=>'auth_fail','user'=>$inputs[user]]);
        exit;
    }
    echo json_encode(['error'=>'never_happen']);
    exit;
}
//$this->data->api(2,'get_consent_status,get_balance,get_recepients,get_companies,run_session');


$query = QB::table('apis')
    ->where('key', $inputs[api_key])
    ->where('active', 't');
$api = get_object_vars($query->first());


if (!$api[id]) {
    echo json_encode(['error'=>'No api']);
    exit;
}

$http_authorization_arr=explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
if ($http_authorization_arr[0]=="Bearer") {
    $http_authorization=$http_authorization_arr[1];
}

if ($http_authorization=='') {
    echo json_encode(['error'=>'No authorization string supplied']);
    exit;
}
try {
    $decoded = JWT::decode($http_authorization, $_ENV[APP_SALT], array('HS256'));
} catch (Exception $e) {
    echo json_encode(['error'=>'No Auths']);
    exit;
}

if ($decoded->sub!=$api[user_id]) {
    echo json_encode(['error'=>'Auth not matched with api']);
    exit;
}

if ($decoded->exp<time()) {
    echo json_encode(['error'=>'Auth expired']);
    exit;
}

$query = QB::table('users')
    ->where('id', $decoded->sub)
    ->where('username', $decoded->unm)
    ->where('active', '1');
$user = get_object_vars($query->first());

if (!$user[id]) {
    echo json_encode(['error'=>'No user']);
    exit;
}
if ($user[username]!=$inputs[user]) {
    echo json_encode(['error'=>'Not allowed']);
    exit;
}

$GLOBALS[uid]=$user[id];
$functions=explode(',', $api[functions]);
$functions[]='update';
$functions[]='insert';
$functions[]='delete';
$functions[]='view';
$functions[]='show';

$procedure_file=APP_DIR.DS.'actions'.DS.'api'.DS.strtolower(str_replace("\\", "/", $inputs[func])). '.php';
if (file_exists($procedure_file)) {
    $functions[]=$inputs[func];
}

if (!in_array($inputs[func], $functions)) {
    echo json_encode(['error'=>$inputs[func].' not defined']);
    exit;
}
$this->data->getAccess();
$inputs[func]=$this->html->filename($inputs[func]);
$procedure_file=APP_DIR.DS.'actions'.DS.'api'.DS.strtolower(str_replace("\\", "/", $inputs[func])). '.php';
if (file_exists($procedure_file)) {
    require $procedure_file;
} else {
    $procedure_file=FW_DIR.DS.'actions'.DS.'api'.DS.strtolower(str_replace("\\", "/", $inputs[func])). '.php';
    if (file_exists($procedure_file)) {
        require $procedure_file;
    } else {
        echo json_encode(['error'=>$inputs[func].' not implemented.'.$procedure_file]);
        exit;
    }
}


// $request=[
// 'api_key'=>'ca560231d9b1bce209dd313833495f8e',
// 'user'=>'admin',
// 'func'=>'get_consent_status',
// 'param'=>'it@example.com',
// ];

//?act=api&what=test&data={"api_key":"ca560231d9b1bce209dd313833495f8e","user":"admin","func":"get_consent_status","param":"it@example.com"}
