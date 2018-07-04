<?php
//--------------------------
if (!$access['main_admin']) {
    $this->html->error('Honey pot');
}
        echo "<h1>Self test</h1>";
        //====
        echo "<h3>PDF, DOC, XLS test</h3>";
        echo "<a href='?act=pdf&what=pdf_appgreek&id=215&date=&grid=&nobg=&signedby=Me'>PDF</a><br>";
        echo "<a href='?act=doc&what=doc_dir_resign_appoint&reftable=partners&doc=1&id=215&date='>DOC</a><br>";
        echo "<a href='?act=append&what=test'>Append</a><br>";
        //docx_tmpl('serv-agr_tmplt1','partners',5831);
        //exit;
        //====
        echo "<h3>AJAX test</h3>";
        echo $this->html->show_hide("Ajax", "?act=show&what=subtypes&plain=1&nomaxid=1");
        
        //====
        echo "<h3>AJAX test append</h3>";
        echo $this->html->show_hide("Append", "?act=append&what=test&plain=1&nomaxid=1");
        
        //====
        echo "<h3>AJAX test append</h3>";
        echo $this->html->show_hide("Append show", "?act=append&what=types&plain=1&nomaxid=1");
        
        //====
        echo "<h3>AJAX test functions</h3>";
        echo $this->html->show_hide("functions", "?act=append&what=app_procedures");
        
        //====
        echo "<h3>Chart test JS</h3>";
        $arr=array('1'=>1,'2'=>5,'3'=>3);
        $res[chartdata]=json_encode($arr);
        $_POST[json]=$res[chartdata];
        $_POST[title]="Testing";
        $_POST[subtitle]="";
        $_POST[xAxisName]="";
        $_POST[yAxisName]="";
        $_POST[numberPrefix]="";
        $_POST[w]=1100;
        $_POST[h]=400;
        echo "<div class='span12'><div class='pull-left'>";
        echo $this->report('linechart_js');
        echo "</div></div>";
        
        echo "<h3>Chart test Flash</h3>";
        echo "<div class='span12'><div class='pull-left'>";
        echo $this->report('linechart');
        echo "</div></div>";
        
        //====
        echo "<h3>Chart test JS 2</h3>";
        $arr=array('1'=>7,'2'=>2,'3'=>10);
        $res[chartdata]=json_encode($arr);
        $_POST[json]=$res[chartdata];
        $_POST[title]="Testing2";
        $_POST[subtitle]="";
        $_POST[xAxisName]="";
        $_POST[yAxisName]="";
        $_POST[numberPrefix]="";
        $_POST[w]=1100;
        $_POST[h]=400;
        echo "<div class='span12'><div class='pull-left'>";
        echo $this->report('linechart_js');
        echo "</div></div>";
        
        echo "<h3>Chart test Flash 2</h3>";
        echo "<div class='span12'><div class='pull-left'>";
        echo $this->report('linechart');
        echo "</div></div>";
        
        
        
        
        /*
        //====
        echo "<h3>Currency test</h3>";
        $usd_amount=1000;
        $eur_amount=1000;
        $date=$this->dates->F_date($this->html->readRQ('date'),1);
        $usd_rate=$this->data->get_rate(600,$date);
        $eur_rate=$this->data->get_rate(601,$date);
        $eur_usd=round($eur_rate/$usd_rate,4);
        $usd_eur=round($usd_rate/$eur_rate,4);
        $usd_eur_amount=round($usd_amount*$usd_eur,2);
        $eur_usd_amount=round($eur_amount*$eur_usd,2);
        echo "USD: $usd_amount, rate: $usd_rate<br>";
        echo "EUR: $eur_amount, rate: $eur_rate<br>";
        echo "USD/EUR: $usd_eur_amount, rate: $usd_eur<br>";
        echo "EUR/USD: $eur_usd_amount, rate: $eur_usd<br>";
        */
        //====
        $rnumber=rand(1000, 9999);
        echo "RANDOM NUMBER: $number";
        echo "<h3>Mail test</h3>";
        $to=$this->html->readRQ('to');
if ($to=='') {
    $to='it@example.com';
}
        $from='email@example.com';
        $subject='Test from BI';
        $text='<b>Test bold</b> not bold.<br>'.$rnumber;
        $mail=$this->utils->sendmail_html($to, $from, $subject, $text);
        echo "Mail sent to $to<pre>$text </pre><br>$mail";
        
        
        //====
        echo "<h3>SMS test</h3>";
        $number=$_ENV['ADMIN_MOBILE'];
        $text='Test from BI.'.$rnumber;
        $this->utils->sendsms($number, $text);
        echo "SMS sent to $number<pre>$text</pre>";
        
        //====
        echo "<h3>Celendar</h3>";
        $this->data->show_cal('');
        
//$GLOBALS[message_time]=10;
//$out.= $this->html->refreshpage($reflink,$GLOBALS[message_time],"<div class='alert alert-info'>Executed $function $what $item.</div>");
$body="$out $nav $export";
