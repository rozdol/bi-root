<?php
// $form_opt['well_class']="span11 columns form-wrap";
// $form_opt['title']="Api Test";

// $out.=$this->html->form_start($what, $id, '', $form_opt);
// $out.="<hr>";

// $out.=$this->html->form_text('URL', '/?act=api', 'URL', '', 0, 'span12');
// $out.=$this->html->form_textarea('data', '', 'Data', '', 0, 'rows=7', 'span12');
// $out.=$this->html->form_textarea('results', '', 'results', '', 0, 'rows=7', 'span12');
// #$out.=$this->html->form_chekbox('active', $res[active], 'Active', '', 0, 'span12');

// $out.=$this->html->form_submit('Save');
// $out.=$this->html->form_end();

// $body.=$out;
//
$wait="<img src=".ASSETS_URI."/assets/img/ajax-loader-bar.gif>";
$arr=[
    'user'=>'admin',
    'pass'=>'Pass1234',
    'api_key' => '',
    'func' => 'get_rate',
    'help' => '0',
];
$vals=[
    'surname'=>'Test'
];
$json=json_encode($arr, JSON_PRETTY_PRINT);
$json_vals=json_encode($vals, JSON_PRETTY_PRINT);

$u='{
        "user": "admin",
        "api_key": "dff1d9-1ef5e0-f37396-701ffc-6deff8",
        "func": "update",
        "table": "users",
        "id": "4",
     "help": "0"
}';

$form="
<div id='stylized' class='well'>
    <h2>Test API connection</h2>
    <dt><label>URL</label><input name='base_url' id='base_url' value='".SITE_URL."?act=api'  class='span12' type='text' placeholder='/?act=api'/></dt>

    <div class='row' style='margin-left:0px;'>
        <div class='span6'>
            <dt><label>Data</label><textarea name='json_data' id='json_data' value=''  class='span12' type='text' rows=10 placeholder='json'/>$json</textarea></dt>
        </div>
        <div class='span6'>
            <dt><label>Values</label><textarea name='json_values' id='json_values' value=''  class='span12' type='text' rows=10 placeholder='json'/>$json_vals</textarea></dt>
        </div>
    </div>






    <dt><label>Result</label><textarea name='result_json_data' id='result_json_data' value=''  class='span12' type='text' rows=10 placeholder=''/></textarea></dt>

    <div class='spacer'></div>
    <span name='result' id='result' value='' class=''>|</span>
    <div class='spacer'></div>
    <button type='button' class='btn btn-info' name='act' value='Calc' id='button' onClick='
    var json_data=document.getElementById(\"json_data\").value;
    var json_values=document.getElementById(\"json_values\").value;
    var base_url=document.getElementById(\"base_url\").value;


	document.getElementById(\"result_json_data\").value=\"$wait Quering...\";

    ajaxFunctionValue(\"result_json_data\",\"?csrf=$GLOBALS[csrf]&act=append&what=rest_api&debug=1&source=reuters&json_data=\"+json_data+\"&base_url=\"+base_url+\"&json_values=\"+json_values);
    '

    language='javascript'>Post request</button><br>
    <div class='spacer'></div>

</div>
";
$body.=$form;

//document.getElementById(\"result\").innerHTML=\"$wait Quering...\";
//
//ajaxFunctionValue(\"result_json_data\",\"?csrf=$GLOBALS[csrf]&act=append&what=rest_api&debug=1&source=reuters&json_data=\"+json_data+\"&base_url=\"+base_url);
//
//
//
//ajaxFunction(\"result\",\"?csrf=$GLOBALS[csrf]&act=append&what=rest_api&debug=1&source=reuters&json_data=\"+json_data+\"&base_url=\"+base_url);
