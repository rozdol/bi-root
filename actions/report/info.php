<?php
if ($what == 'info') {
    if (PHP_OS<>'WINNT') {
        //$ua=$_SERVER['HTTP_USER_AGENT'];
        //$instr="iPad";
        //$pos = strpos($ua,"iPad");
        //if($pos === false)$us="NotIpad";else $us="iPad";
        $sql="SELECT relname AS \"relation\", pg_size_pretty(pg_relation_size(C.oid)) AS \"size\"
			  FROM pg_class C LEFT JOIN pg_namespace N ON (N.oid = C.relnamespace)
			  WHERE nspname NOT IN ('pg_catalog', 'information_schema')
			  ORDER BY pg_relation_size(C.oid) DESC
			  LIMIT 10;";
        echo $this->data->sql_display($sql, 'Biggest tables');


            

        if ($this->utils->contains('iPad', $_SERVER['HTTP_USER_AGENT'])) {
            $us="IPAD!!!";
        }
        $output .= "SERVER_SOFTWARE: $us\n".$_SERVER['SERVER_SOFTWARE']."\n\n";
        $output .= "USER_AGENT: $us\n".$_SERVER['HTTP_USER_AGENT']."\n\n";
        $output .= "SERVER:\n".shell_exec("uname -a")."\n\n";
        $output .= "UPTIME:\n".shell_exec("uptime")."\n\n";
        $output .= "DISK:\n".shell_exec("df -h")."\n\n";
        //$output .= "USAGE:\n".shell_exec("cat /var/www/html/server/hits.txt")."\n\n";
        //$output .= "CHANGES:\n $changes\n";
        //$output .= "USAGE:\n".shell_exec("/var/www/html/server/hits.sh /var/log/httpd/access_log")."\n";
        $out.= "<pre>$output</pre>";

        $extentions=get_loaded_extensions();
        $out.= $this->html->pre_display($extentions, "PHP extentions");
    } else {
        $exec="dir /B \"$progdir\backups\\\" ";
        $output .= "CHANGES:\n $changes\n";
        $out.= "<pre>No available info for NT based servers.
$output
		    </pre>";
    }
}
    
$body.=$out;
