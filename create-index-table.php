<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:31
 */

$data = require ("init.php");
$attributes = $data['attributes'];

$columns = [];
foreach ($attributes as $name => $desc) {
    switch ($desc['type']) {
        case 'id':
            $column = "$name integer";
            break;
        case 'float':
            $column = "$name float";
            break;
        case 'bool':
            $column = "$name boolean";
            break;
        case 'words':
            $column = "$name varchar(255)";
            break;
        default:
            throw new \Exception("Unknown type {$desc['type']}");
    }
    $columns[] = $column;
}

$sql = "DROP TABLE IF EXISTS AttributeIndex";

mysql_query($sql);
if (mysql_errno()) {
    echo mysql_error() . "\n";
    die();
}

$sql = "CREATE TABLE AttributeIndex (product_id integer NOT NULL," . implode(",", $columns) . ')';

mysql_query($sql);
if (mysql_errno()) {
    echo mysql_error() . "\n";
    die();
}

$sql = "CREATE INDEX P ON AttributeIndex (product_id)";

mysql_query($sql);
if (mysql_errno()) {
    echo mysql_error() . "\n";
}

