<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:31
 */

$data = require ("init.php");
$attributes = $data['attributes'];
$words = $data['words'];

srand(22);
for ($i = 1; $i <= 200000; $i++) {
	mysql_query("INSERT INTO Product VALUES (null, 'Product n $i')");
    $id = mysql_insert_id();
    foreach ($attributes as $name => $desc) {
        $sql = "
            INSERT INTO ProductAttribute
            SET product_id = $id, attribute_id = {$desc['id']} 
        ";
        switch ($desc['type']) {
            case 'id':
                $attr_value = array_rand_value($desc['values']);
                $sql .= ", int_value = $attr_value";
                break;
            case 'float':
                $attr_value = rand($desc['min'] * 100, $desc['max'] * 100) / 100;
                $sql .= ", float_value = $attr_value";
                break;
            case 'bool':
                $attr_value = rand(0,1);
                $sql .= ", bool_value = $attr_value";
                break;
            case 'words':
                $attr_value = implode(",", array_rand_value($words, rand($desc['min'], $desc['max'])));
                $sql .= ", string_value = '$attr_value'";
                break;
        }
        mysql_query($sql);
        if (mysql_errno()) {
            echo mysql_error() . "\n";
            die();
        }
        $sql .= ';';
    }
	echo ".";
}
