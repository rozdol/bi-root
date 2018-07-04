<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
echo $this->html->QRscan();
exit;