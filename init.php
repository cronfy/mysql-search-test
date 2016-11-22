<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:27
 */

function array_rand_value(&$array, $qty = 1) {
    $answer = [];
    for ($i = 1; $i <= $qty; $i++) {
        $rand = rand(0, count($array) - 1);
        $answer[] = $array[$rand];
    }
    return count($answer) == 1 ? $answer[0] : $answer;
}

mysql_connect('localhost', 'search', 'search');
mysql_select_db('search');

$data = [
    'attributes' => [
        'color' => ['type' => 'id', 'qty' => 17],
        'size' => ['type' => 'id', 'qty' => 29],
        'brand' => ['type' => 'id', 'qty' => 87],
        'material' => ['type' => 'id', 'qty' => 55],

        'length' => ['type' => 'float', 'min' => 1, 'max' => 50],
        'height' => ['type' => 'float', 'min' => 1, 'max' => 200],
        'weight' => ['type' => 'float', 'min' => 1, 'max' => 100],

        'waterproof' => ['type' => 'bool'],

        'tags'  => ['type' => 'words', 'min' => 2, 'max' => 5]
    ],
    'words' => explode(" ",
            "parcel talk end wobble doubt please handy pan deafening harmony burly fold gray cave"
            . " bewildered cars dinner frequent tempt alluring nerve high ready juvenile annoy low"
            . " taboo liquid screw interesting confuse wistful corn cast shaggy rot shut absent spark"
            . " maddening reject account test request clean improve eggs outstanding bat trees")

];

srand(22);
$counter = 0;
foreach ($data['attributes'] as $name => &$desc) {
    $counter++;
    $desc['id'] = $counter * 17;
    switch ($desc['type']) {
        case 'id':
            for ($i = 1; $i <= $desc['qty']; $i++) {
                $desc['values'][] = $counter * 100 + rand(0,99);
            }
            break;
    }
}
unset($desc);
srand(time());

return $data;
