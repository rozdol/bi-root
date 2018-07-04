<?php
$func_file=FW_DIR.DS.'functions'.DS.$f.'.php';
if (file_exists($func_file)) {
    return include($func_file);
}
