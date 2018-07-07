<?php
//Edit apis
if ($act=='edit') {
    $sql="select * from $what WHERE id=$id";
    $res=$this->db->GetRow($sql);
} else {
    $sql="select * from $what WHERE id=$refid";
    $res2=$this->db->GetRow($sql);
    $res[active]='t';
    if (($reference=='users')&&($refid!=0)) {
        $res=$this->db->getrow("SELECT * from apis where user_id=$refid limit 1");
        if ($res[id]>0) {
        } else {
            $filenames[]='get_rate';
            $dir = APP_DIR.DS.'actions'.DS.'api';
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($this->utils->contains('.php', $file)) {
                    $filename=explode('.', $file);
                    if ($filename[0]!='test') {
                        $filenames[]=$filename[0];
                    }
                }
            }

            $funcs=implode(',', $filenames);
            // if ($GLOBALS[app_name]=='fastconsent') {
            //     $funcs='get_rate,get_consent_status,get_balance,get_recepients,get_companies,run_session';
            // }
            
            $key=$this->data->api($refid, $funcs);
            $res=$this->db->getrow("SELECT * from apis where user_id=$refid and key='$key'");
        }
    }
}


//echo $this->html->pre_display($refid,"$reference");
$form_opt['well_class']="span11 columns form-wrap";

$out.=$this->html->form_start($what, $id, '', $form_opt);
$out.="<hr>";
$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

$out.=$this->html->form_hidden('reflink', $reflink);
$out.=$this->html->form_hidden('id', $id);
$out.=$this->html->form_hidden('reference', $reference);
$out.=$this->html->form_hidden('refid', $refid);

$out.=$this->html->form_textarea('functions', $res[functions], 'Functions', 'get_rate,import_lists,chk_inegrity', 0, '', 'span12');
$out.=$this->html->form_textarea('key', $res[key], 'Key', '', 0, '', 'span12');
$out.=$this->html->form_date('exp_date', $res[exp_date], 'Exp date', '', 0, 'span12');
$out.=$this->html->form_chekbox('active', $res[active], 'Active', '', 0, 'span12');


$out.=$this->html->form_confirmations();
$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$body.=$out;
