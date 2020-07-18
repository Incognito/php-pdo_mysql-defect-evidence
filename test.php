<?php
sleep(1);

$connection = new PDO('mysql:host=db;port=3306;dbname=mysql', 'root', 'example', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

$connection->prepare('DROP TABLE IF EXISTS pdo_bug_test;')->execute();
$connection->prepare('CREATE TABLE pdo_bug_test (value1 VARCHAR(10), value2 INT);')->execute();

$connection->beginTransaction();

$test = $argv[1];
switch ($test) {
  case "a": // Always true
    $sql = 'INSERT INTO pdo_bug_test (value1, value2) VALUES (1, 2);';
    $statement = $connection->prepare($sql); $statement->execute();
    break;
  case "b": // Always true
    $sql = 'INSERT INTO pdo_bug_test (value1, value2) VALUES (1, 2);';
    $statement = $connection->prepare($sql)->execute();
    break;
  case "c": // commit() returns true in 7.0.22, false in 7.0.23
    $sql = 'INSERT INTO pdo_bug_test (value1, value2) VALUES (1, 2);';
    $sql .= 'INSERT INTO pdo_bug_test (value1, value2) VALUES (3, 4);';
    $statement = $connection->prepare($sql); $statement->execute();
    break;
  case "d": // Always true
    $sql = 'INSERT INTO pdo_bug_test (value1, value2) VALUES (1, 2);';
    $sql .= 'INSERT INTO pdo_bug_test (value1, value2) VALUES (3, 4);';
    $statement = $connection->prepare($sql)->execute();
    break;
}

$result = $connection->commit();

echo "Test case $test ";
var_dump($result);
echo "\n";
