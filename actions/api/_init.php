<?php
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

// echo json_encode($_POST);
// exit;

unset($_POST[data]);
foreach ($_POST as $key => $value) {
    $inputs[$key]=$this->html->filter($key, $value);
    //$inputs[$key]=filter_var($value, $filters[$key], $options[$key]);
}



if ($inputs[api_key]=='') {
    $username=$inputs[user];
    $password=$inputs[pass];

    $sql = "SELECT * FROM users WHERE username='$username' order by id asc limit 1";
    $user=$this->db->GetRow($sql);
    $good_hash=$user[password_hash];

    $ok=$this->crypt->validate_password($password, $good_hash)*1;
    if ($ok > 0) {
        $sql="SELECT * from apis where user_id='$user[id]' and active='t'";
        $api=$this->db->getrow($sql);
        if ($api[id]>0) {
            $funcs=explode(',', $api[functions]);
            echo json_encode(['api_key'=>$api[key],'funcs'=>$funcs]);
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
$sql="SELECT * from apis where key='$inputs[api_key]' and active='t'";
$api=$this->db->getrow($sql);

//?act=api&user=username&pass=pass
//?act=api
// user=username
// api_key=key
// func=get_rate
// date=01.01.2018

// func=view
// table=partners

//?act=api&what=test&data={"api_key":"; delete from apis;---'","user":"admin","func":"get_consent_status","param":"it@example.com"}

if (!$api[id]) {
    echo json_encode(['error'=>'No api']);
    exit;
}
$user=$this->db->getrow("SELECT * from users where id='$api[user_id]' and active='1'");
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
    echo json_encode(['error'=>$inputs[func].' not definded']);
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
