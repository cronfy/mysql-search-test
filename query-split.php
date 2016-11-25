<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:46
 */

function getProductAttributes($id) {
    $answer = [];
    $res = mysql_query("SELECT * from ProductAttribute WHERE product_id = " . $id);
    while ($row = mysql_fetch_assoc($res)) {
        $answer[$row['attribute_id']] = $row;
    }
    return $answer;
}

$data = require ("init.php");
$attributes = $data['attributes'];
$words = $data['words'];

$query = "SELECT P.id FROM Product P\n";

srand(42);

$rand_product_1 = getProductAttributes(rand(1000,10000));
$rand_product_2 = getProductAttributes(rand(1000,10000));

foreach ($attributes as $name => $desc) {
    $alias = $name;
    switch ($desc['type']) {
        case 'id':
            $table = "ProductAttribute_integer";
            $values = array_rand_value($desc['values'], 4);
            $values[] = $rand_product_1[$desc['id']]['int_value'];
            $values[] = $rand_product_2[$desc['id']]['int_value'];
            $value = implode(',', $values);
            $condition = " AND $alias.value IN ($value)";
            break;
        case 'bool':
            $table = "ProductAttribute_boolean";
            $value = rand(0,1);
            $condition = " AND $alias.value = $value";
            break;
        case 'words':
            $table = "ProductAttribute_varchar";
            $p_words = explode(",", $rand_product_1[$desc['id']]['string_value']);
            $value = array_rand_value($p_words);
            $condition = " AND $alias.value LIKE '%$value%'";
            break;
        case 'float':
            $table = "ProductAttribute_float";
            $interval = [
                $rand_product_1[$desc['id']]['float_value'],
                $rand_product_2[$desc['id']]['float_value'],
            ];
            sort($interval);
            $condition = " AND $alias.value > " . floor($interval[0]);
            $condition .= " AND $alias.value < " . ceil($interval[1]);
            break;
        default:
            throw new \Exception("Unknown attribute type {$desc['type']}");
    }
    $condition = "P.id = $alias.product_id AND $alias.attribute_id = {$desc['id']} " . $condition;

    $query .= "JOIN $table $alias ON ($condition)\n";
}

$query .= ";\n";
echo $query;
