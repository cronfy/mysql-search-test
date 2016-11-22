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
                $set = "$name = $value";
                break;
            case 'float':
                $value = $product_attrs[$attr_id]['float_value'];
                $set = "$name = $value";
                break;
            case 'bool':
                $value = $product_attrs[$attr_id]['bool_value'];
                $set = "$name = $value";
                break;
            case 'words':
                $value = $product_attrs[$attr_id]['string_value'];
                $set = "$name = '$value'";
                break;
            default:
                throw new \Exception("Unknown attribute type {$desc['type']}");
        }

        $insert[] = $set;
    }

    $answer = implode(", ", $insert);

    return $answer;
}

$res = mysql_query("SELECT id from Product");
while ($product = mysql_fetch_assoc($res)) {
    $set_values = attributesToInsert($product['id'], $attributes);
    $sql = "INSERT INTO AttributeIndex SET product_id = {$product['id']}, $set_values;";
    mysql_query($sql);
    if (mysql_errno()) {
        echo mysql_error() . "\n";
        die();
    }
    echo ".";
}

echo "ok\n";