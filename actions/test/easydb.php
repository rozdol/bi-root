<?php
//echo $this->html->pre_display($this, "this");
// https://github.com/paragonie/easydb
// composer require paragonie/easydb:^2
$edb = \ParagonIE\EasyDB\Factory::create(
    'pgsql:host='.$_ENV['DB_SERVER'].';dbname='.$_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);
//$rows = $edb->run('SELECT ?', [1]);
echo $this->html->pre_display($rows, "rows2");

//$edb->safeQuery('SELECT ?', [1]);

$rows = $edb->run('SELECT * FROM users WHERE active = ? ORDER BY id ASC', 1);
echo $this->html->pre_display($rows, "rows");

use Aura\SqlQuery\QueryFactory;

$queryFactory = new QueryFactory('pgsql');
$select = $queryFactory->newSelect();
$select->cols([
        'id',                       // column name
        'username AS namecol',          // one way of aliasing
        'email' => 'col_alias',  // another way of aliasing
        'COUNT(*) AS foo_count'   // embed calculations directly
    ])
->from('users')
//->where('username = :username', ['username' => 'alex']);
->where('username = ?', 'alex')
//->where('surname = ?', 'Titov')
->groupBy(['id'])
->groupBy(['email']);
echo $this->html->pre_display($select->getStatement(), "getStatement");
$bind_values=$select->getBindValues();
echo $this->html->pre_display($bind_values, "getBindValues");
$redused = array_reduce($bind_values, 'array_merge', array());
echo $this->html->pre_display($redused, "redused");
$rows = $edb->safeQuery($select->getStatement(), $bind_values);
//$rows = $edb->safeQuery('SELECT ?', [1]);
echo $this->html->pre_display($rows, "rows2");
