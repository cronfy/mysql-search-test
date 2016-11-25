<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:31
 */

$data = require ("init.php");
$attributes = $data['attributes'];

$sql = [];
foreach (['integer', 'boolean', 'float', 'varchar'] as $type) {
    $table = "ProductAttribute_$type";
    $drop = "DROP TABLE IF EXISTS $table";
    $column_type = $type == 'varchar' ? 'varchar(255)' : $type;
    $create = "
    CREATE TABLE $table (
        product_id integer NOT NULL,
        attribute_id smallint, 
        value $column_type
    )
    ";
    $create_index = "CREATE INDEX P ON $table (product_id)";
    $sql[] = $drop;
    $sql[] = $create;
    $sql[] = $create_index;
}

foreach ($sql as $query) {
    mysql_query($query);
    if (mysql_errno()) {
        echo mysql_error() . "\n";
        die();
    }
}
