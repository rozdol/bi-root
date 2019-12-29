<?php
$help=$this->html->readRQn('help');
$func=$this->html->readRQs('func');
if ($help) {

    $arr=[
        "user"=> "admin",
        "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
        "func"=> $func,
        "what"=>"rate",
        "pair"=>"eur/usd",
    ];
    $new_vals=['Help'=>$func,'sample'=>$arr];
    $JSONData=$new_vals;
    return $JSONData;

    //$_POST[filters]='id = 1,id = 2 or id = 3 or id = 4, id = 5, id > 9';
}

$what=$this->html->readRQs('what');

if (!$GLOBALS["access"]["report_$what"]) {
    echo json_encode([
        'error'=>"No access to report '$what' for user '$GLOBALS[username]'",
      //  'access'=>$GLOBALS[access]
    ]
    );
    exit;
}
$function=$func;
$procedure_file=APP_DIR.DS.'actions'.DS.$function.DS.strtolower(str_replace("\\", "/", $what)). '.php';
if (file_exists($procedure_file)) {
    require $procedure_file;
} else {
    $procedure_file=FW_DIR.DS.'actions'.DS.$function.DS.strtolower(str_replace("\\", "/", $what)). '.php';
    if (file_exists($procedure_file)) {
        require $procedure_file;
    } else {
    	echo json_encode([
    	    'error'=>"No report '$what' implemented",
    	]
    	);
    	exit;
    }
}
$tmp=$JSONData;
unset($JSONData);
$JSONData[report]=$tmp;
$GLOBALS['endtime']=microtime(true);
$runtime=round($GLOBALS['endtime']-$GLOBALS['starttime'], 2);
$JSONData[runtime]=$runtime;

//echo $body;