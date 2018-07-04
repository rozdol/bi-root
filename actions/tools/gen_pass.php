<?php
if ($access['main_admin']) {
    $sql="select * from users where active=1 order by id";
    $body.="<div class='green'>";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    while ($row = pg_fetch_array($cur)) {
        $user=$this->data->username($row[id]);
        $pass=$this->utils->gen_Password();
        $txt.= "$user\t$row[username]\t$pass\n";
    }
    $out.="<textarea>$txt</textarea>";
}
$body.=$out;
