<?php
use \Firebase\JWT\JWT;

error_reporting(E_ERROR);
$JSONData=null;
//Set mode for BI
$GLOBALS[offline_mode]=1;
sendheaders();
$inputs=getinput($this);

//$this->utils->log("in:".print_r($inputs, true));
//$this->utils->log($this->html->pre_display($_SERVER,'_SERVER').$this->html->pre_display($_POST,'POST')); exit;

//echo json_encode(['inputs'=>$inputs]); exit;

//Check for signup/verify routine
if ($inputs[api_key]=='') {
    if ($inputs[func]=='signup') {
        signup($inputs,$this);
    }
    if ($inputs[func]=='verify') {
        // TBD
        //verify($inputs,$this);
    }
    authenticate($inputs,$this);
}

find_api_by_key($inputs,$this);

$this->data->getDefVals();
$this->data->getAccess();
$this->data->click();

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

//$this->data->api(2,'get_consent_status,get_balance,get_recepients,get_companies,run_session');

function find_api_by_key($inputs=[],$app){
    $query = QB::table('apis')
        ->where('key', $inputs[api_key])
        ->where('active', 't');
    $api = get_object_vars($query->first());

    //$app->html->dd($api);

    if (!$api[id]) {
        echo json_encode(['error'=>'No api']);
        exit;
    }

    $http_authorization_arr=explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
    if ($http_authorization_arr[0]=="Bearer") {
        $http_authorization=$http_authorization_arr[1];
    }

    if ($http_authorization=='') {
        $http_authorization=$inputs['Authorization'];
        if ($http_authorization=='') {
            echo json_encode(['error'=>'No authorization string supplied']);
            exit;
        }
    }
    try {
        $decoded = JWT::decode($http_authorization, getenv('APP_SALT'), array('HS256'));
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
    $GLOBALS[username]=$user[username];
    $functions = array_map('trim', explode(',', $api[functions]));
    $functions[]='update';
    $functions[]='insert';
    $functions[]='delete';
    $functions[]='view';
    $functions[]='show';
    $functions[]='help';
    $functions[]='report';
    $GLOBALS[api][functions]=$functions;
    //$app->html->dd(['functions'=>$functions, 'inputs'=>$inputs[func]],1);

    // $procedure_file=APP_DIR.DS.'actions'.DS.'api'.DS.strtolower(str_replace("\\", "/", $inputs[func])). '.php';
    // if (!file_exists($procedure_file)) {
    //     echo json_encode(['error'=>'Function '.$inputs[func].' is not defined']);
    //     exit;
    // }

    if (!in_array($inputs[func], $functions)) {
        echo json_encode(['error'=>'No access to '.$inputs[func]]);
        exit;
    }
    //$functions[]=$inputs[func];
}



// API init functions =========================================================
function sendheaders(){
    // respond to preflights
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      // return only the headers and not the content
      // only allow CORS if we're doing a GET - i.e. no saving for now.
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
      }
      exit;
    }

    header('Access-Control-Allow-Origin:  *');
    header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-File-Name, X-File-Type, X-File-Size');



    header('Pragma: no-cache');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Content-Disposition: inline; filename="files.json"');
    header('X-Content-Type-Options: nosniff');
    header('Content-type: application/json');
}

function getinput($app){
    $inputs=[];
    $php_input = json_decode(file_get_contents('php://input'), true);

    //Merge console input into POST request to sanitize values by readRQ() function
    if ($php_input) {
        $_POST = array_merge($php_input, $_POST);
    }

    //sanitize input ecxept 'data'
    $data_bkp=$_POST[data];
    unset($_POST[data]);
    foreach ($_POST as $key => $value) {
        $inputs[$key]=$app->html->filter($key, $value);
        //$inputs[$key]=filter_var($value, $filters[$key], $options[$key]);
    }
    $_POST[data]=$data_bkp;
    return $inputs;
}

function signup ($inputs=[], $app) {
    //echo json_encode(['inputs2'=>$inputs]);

    $password=$inputs[pass];
    if($password=='')$password=$inputs[password];

    if($password==''){
        echo json_encode(['error'=>"No password supplied"]);
        exit;
    }

    if($inputs[email]==''){
        echo json_encode(['error'=>"No email supplied"]);
        exit;
    }
    $_POST[name]=$inputs[name];
    $_POST[surname]=$inputs[surname];
    $_POST[email]=$inputs[email];
    $_POST[password]=$password;
    $_POST[password_confirm]=$inputs[password_confirm];
    $app->save('signups');
    exit;
}

function authenticate ($inputs=[], $app) {
    $validity_period=60*60*24*7;//604800; //week
    $username=$inputs[user];
    if($username=='')$username=$inputs[username];
    if($username==''){
        // echo json_encode($inputs);
        echo json_encode(['error'=>"No username supplied"]);
        exit;
    }
    $password=$inputs[pass];
    if($password=='')$password=$inputs[password];

    if($password==''){
        echo json_encode(['error'=>"No password supplied"]);
        exit;
    }

    $query = QB::table('users')
        ->where('username', $username)
        ->where('active', '1')
        ->orderBy('id', 'ASC');
    $user = get_object_vars($query->first());

    $good_hash=$user[password_hash];

    $ok=$app->crypt->validate_password($password, $good_hash)*1;
    if ($ok > 0) {

        $token =
        [
            'sub' => $user[id],
            'unm' => $user[username],
            'exp' => time() + $validity_period //1 week
        ];
        $jwt = JWT::encode($token, getenv('APP_SALT'));

        $query = QB::table('apis')
            ->where('user_id', $user[id])
            ->where('active', 't');
        $api = get_object_vars($query->first());

        if ($api[id]>0) {
            $funcs=explode(',', $api[functions]);
            echo json_encode([
                'api_key'=>$api[key],
                'access_token'=>"$jwt",
                'token_type'=>'bearer',
                'expires_in'=>$validity_period,
                'expires_on'=>time() + $validity_period,
                'funcs'=>$funcs]);
            exit;
        } else {
            echo json_encode(['error'=>"No api key for user $username"]);
            exit;
        }
    } else {
        $descr="API:$username:$password";
        $app->data->chk_fails($descr);
        echo json_encode(['error'=>'auth_fail','user'=>$inputs[user]]);
        exit;
    }
    echo json_encode(['error'=>'never_happen']);
    exit;
}

// $request=[
// 'api_key'=>'ca560231d9b1bce209dd313833495f8e',
// 'user'=>'admin',
// 'func'=>'get_consent_status',
// 'param'=>'it@example.com',
// ];

//?act=api&what=test&data={"api_key":"ca560231d9b1bce209dd313833495f8e","user":"admin","func":"get_consent_status","param":"it@example.com"}
