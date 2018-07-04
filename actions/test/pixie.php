<?php
// composer requires usmanhalalit/pixie

new \Pixie\Connection('pgsql', array(
                    'driver'   => 'pgsql',
                    'host'     => $_ENV['DB_SERVER'],
                    'database' => $_ENV['DB_NAME'],
                    'username' => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASS'],
                    'charset'  => 'utf8',
                    'prefix'   => '',
                    'schema'   => 'public',
                ), 'QB');

$row = QB::table('users')->find(3);

echo $this->html->pre_display($row, "row");

$query = QB::table('users')->where('active', '=', '1');
$rows=$query->get();
foreach ($rows as $row) {
    echo $this->html->pre_display($row, "row ".$row->username);
}
$json=json_encode($rows, JSON_PRETTY_PRINT);
echo $this->html->pre_display($json, "json");
