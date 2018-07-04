<?php
// composer require aura/sqlquery
// // https://tproger.ru/translations/how-to-configure-and-use-pdo/
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
->where('username = :username_var')
->groupBy(['id'])
->groupBy(['email'])
->bindValues([
    'username_var' => 'alex']);
// a PDO connection

echo $this->html->pre_display($select->getStatement(), "sql");
echo $this->html->pre_display($select->getBindValues(), "sql");


$pdo = new PDO("pgsql:dbname=".$_ENV['DB_NAME'].";host=".$_ENV['DB_SERVER'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


echo $this->html->pre_display($pdo, "pdo");
// prepare the statment
$sth = $pdo->prepare($select->getStatement());
echo $this->html->pre_display($sth, "sth");

// bind the values and execute
$sth->execute($select->getBindValues());
echo $this->html->pre_display($sth, "sth");

// get the results back as an associative array
$result = $sth->fetch(PDO::FETCH_ASSOC);

echo $this->html->pre_display($result, "result");
