<?php
/**
 * Created by PhpStorm.
 * User: cronfy
 * Date: 22.11.16
 * Time: 16:28
 */

$data = require ("init.php");

echo "Product\n";
mysql_query("
DROP TABLE IF EXISTS Product;
");

mysql_query("
CREATE TABLE `Product` (
  `id` int(11) NOT NULL primary key auto_increment,
  `name` varchar(150)
)
");

echo mysql_error() . "\n";

echo "Attribute\n";
mysql_query("
DROP TABLE  IF EXISTS ProductAttribute;
");

mysql_query("
CREATE TABLE ProductAttribute (
  product_id integer NOT NULL,
  attribute_id smallint, 

  int_value integer,
  float_value float,
  bool_value boolean,
  string_value varchar(255)
)
");

echo mysql_error() . "\n";

echo "Index\n";
mysql_query(
"CREATE INDEX PA
ON ProductAttribute (product_id, attribute_id)"
);

echo mysql_error() . "\n";
