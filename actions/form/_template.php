<?php
//form _template
$out.=$this->html->form_start($what,0,'List of required documents');
$out.=$this->html->form_hidden('reflink',$reflink);
$out.=$this->html->form_hidden('id',$id);
$out.=$this->html->form_hidden('reference',$reference);
$out.=$this->html->form_hidden('refid',$refid);

$out.=$this->html->form_text('Alias','value');
$out.=$this->html->form_date('date','01.01.2015');
$out.=$this->html->form_textarea('descr','text');
$out.=$this->html->form_chekbox('active','TruE');
$out.=$this->html->form_2cols('left','rignt','Test unit',1);
$out.=$this->html->form_1col('rignt','Test unit 1',0);
$out.=$this->html->form_submit('ok');
$out.=$this->html->form_end();




$body.=$out;