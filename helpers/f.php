<?php
$func_file=APP_DIR.DS.'functions'.DS.$f.'.php';
if (file_exists($func_file)) {
    return include($func_file);
}
