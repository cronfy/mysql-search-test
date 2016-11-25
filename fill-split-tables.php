<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:31
 */

$data = require ("init.php");
$attributes = $data['attributes'];

function attributesToInsert($product_id, $attributes) {
    $res_attr = mysql_query("SELECT * from ProductAttribute WHERE product_id = " . $product_id);
    $product_attrs = [];
    while ($attr = mysql_fetch_assoc($res_attr)) {
        $product_attrs[$attr['attribute_id']] = $attr;
    }

    $insert = [];
    foreach ($attributes as $name => $desc) {
        $attr_id = $desc['id'];
        switch ($desc['type']) {
            case 'id':
                $value = $product_attrs[$attr_id]['int_value'];
                $table = "ProductAttribute_integer";
                break;
            case 'float':
                $value = $product_attrs[$attr_id]['float_value'];
                $table = "ProductAttribute_float";
                break;
            case 'bool':
                $value = $product_attrs[$attr_id]['bool_value'];
                $table = "ProductAttribute_boolean";
                break;
            case 'words':
                $value = $product_attrs[$attr_id]['string_value'];
                $table = "ProductAttribute_varchar";
                break;
            default:
                throw new \Exception("Unknown attribute type {$desc['type']}");
        }

        if ($desc['type'] == 'words') {
            $query = "INSERT INTO $table SET product_id = $product_id, attribute_id = $attr_id, value = '$value';";
        } else {
            $query = "INSERT INTO $table SET product_id = $product_id, attribute_id = $attr_id, value = $value;";
        }

        $insert[] = $query;
    }

    return $insert;
}

$res = mysql_query("SELECT id from Product");
while ($product = mysql_fetch_assoc($res)) {
    $inserts = attributesToInsert($product['id'], $attributes);
    foreach ($inserts as $insert) {
        mysql_query($insert);
        if (mysql_errno()) {
            echo mysql_error() . "\n";
            die();
        }
    }
    echo ".";
}

echo "ok\n";