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

srand(42);

$rand_product_1 = getProductAttributes(rand(1000,10000));
$rand_product_2 = getProductAttributes(rand(1000,10000));

$alias = "AI";
$conditions = [];
foreach ($attributes as $name => $desc) {
    switch ($desc['type']) {
        case 'id':
            $values = array_rand_value($desc['values'], 4);
            $values[] = $rand_product_1[$desc['id']]['int_value'];
            $values[] = $rand_product_2[$desc['id']]['int_value'];
            $value = implode(',', $values);
            $conditions[]= "$alias.$name IN ($value)";
            break;
        case 'bool':
//            continue;
            $value = rand(0,1);
            $conditions[] = "$alias.$name = $value";
            break;
        case 'words':
            $p_words = explode(",", $rand_product_1[$desc['id']]['string_value']);
            $value = array_rand_value($p_words);
            $conditions[] = "$alias.$name LIKE '%$value%'";
            break;
        case 'float':
            $interval = [
                $rand_product_1[$desc['id']]['float_value'],
                $rand_product_2[$desc['id']]['float_value'],
            ];
            sort($interval);
            $conditions[] = "$alias.$name > " . floor($interval[0]);
            $conditions[] = "$alias.$name < " . ceil($interval[1]);
            break;
        default:
            throw new \Exception("Unknown attribute type {$desc['type']}");
    }
}

$query = "SELECT P.id FROM Product P
JOIN AttributeIndex AI ON (P.id = AI.product_id)
WHERE " . implode(" AND ", $conditions);

$query .= ";\n";
echo $query;
