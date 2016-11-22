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

$counter = 0;
foreach ($attributes as $name => $desc) {
    $counter++;
    if ($counter > 10) {
        break;
    }
    $alias = $name;
    $condition = "P.id = $alias.product_id AND $alias.attribute_id = {$desc['id']}";
    switch ($desc['type']) {
        case 'id':
            $values = array_rand_value($desc['values'], 4);
            $values[] = $rand_product_1[$desc['id']]['int_value'];
            $values[] = $rand_product_2[$desc['id']]['int_value'];
            $value = implode(',', $values);
            $condition .= " AND $alias.int_value IN ($value)";
            break;
        case 'bool':
            $value = rand(0,1);
            $condition .= " AND $alias.bool_value = $value";
            break;
        case 'words':
            $p_words = explode(",", $rand_product_1[$desc['id']]['string_value']);
            $value = array_rand_value($p_words);
            $condition .= " AND $alias.string_value LIKE '%$value%'";
            break;
        case 'float':
            $interval = [
                $rand_product_1[$desc['id']]['float_value'],
                $rand_product_2[$desc['id']]['float_value'],
            ];
            sort($interval);
            $condition .= " AND $alias.float_value > " . floor($interval[0]);
            $condition .= " AND $alias.float_value < " . ceil($interval[1]);
            break;
        default:
            throw new \Exception("Unknown attribute type {$desc['type']}");
    }
    $query .= "JOIN ProductAttribute $alias ON ($condition)\n";
}

$query .= ";\n";
echo $query;
