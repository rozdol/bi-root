<?php
if (!$access['main_admin']) {
    $this->html->error('Honey pot');
}
