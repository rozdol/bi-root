<?php
$source=$this->html->readRQ('source');
//$out="No source selected";
if ($source=='reuters') {
    $data=$this->html->readRQj('json_data');
    $values=$this->html->readRQj('json_values');
    $json_values=json_encode($values);
    $data['values']=$json_values;

    $base_url=$this->html->readRQs('base_url');
    $json=json_encode($data, JSON_PRETTY_PRINT);

    //$out=$base_url."\n".$json."\n".$json_values."\n";

    $api = new RestClient;

    $result = $api->post($base_url, $data);

    //var_dump($result);

    $response_array= get_object_vars($result->decode_response());
    $response_json=json_encode($response_array, JSON_PRETTY_PRINT);
    $out.=$response_json;


    // $api = new RestClient([
    //     'base_url' => "https://api.twitter.com/1.1",
    //     'format' => "json",
    //      // https://dev.twitter.com/docs/auth/application-only-auth
    //     'headers' => ['Authorization' => 'Bearer '.OAUTH_BEARER],
    // ]);
    // $result = $api->get("search/tweets", ['q' => "#php"]);
    // // GET http://api.twitter.com/1.1/search/tweets.json?q=%23php
    // if ($result->info->http_code == 200) {
    //     var_dump($result->decode_response());
    // }
}
if ($source=='reutersq') {
    $identifier_id=$this->html->readRQ('identifier_id');
    if ($identifier_id=='') {
        $df=$this->html->readRQd('df', 1);
        $dt=$this->html->readRQd('dt', 1);
        $index=$this->html->readRQ('index');
        $identifier_id=$this->project->ric_encode($index, $df, $dt);
    }

    $out="ok $identifier_id";
    $result=$this->project->reuters_import([$identifier_id]);
    if ($result[info]) {
        echo $this->html->message($result[info]);
    }
    if ($result[error]) {
        $this->html->error($result[error]);
    }
    foreach ($result as $ric => $value) {
        $count=$this->db->getval("SELECT count(*) from quote_values where name='$ric'");
        $value=$this->funcs->cleannumber($value);
        if (($count==0)&&($value>0)) {
            $index_data=$this->project->ric_decode($ric);

            //$this->db->getval("INSERT INTO quote_values (name, ric, index, value, da) values ('$ric','$ric','$index','$value')");
            $vals=array(
                'name'=>$ric,
                'ric'=>$ric,
                'index'=>$index_data[index],
                'value'=>$value,
                'date_from'=>$this->funcs->F_date($index_data[df], 1),
                'date_to'=>$this->funcs->F_date($index_data[dt], 1),
            );
            $this->db->insert_db('quote_values', $vals);
        } else {
            $this->db->GetVal("update quote_values set value='$value', date=now() where name='$ric'");
        }
    }
    $out=$result[$identifier_id]; //$this->html->pre_display($result,"result");
}
$body.=$out;
