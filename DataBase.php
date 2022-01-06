<?php
require 'index.php';

$mysqli = new mysqli('localhost', $DB_NAME, $DB_USER, $DB_PASS);

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

$sql = "CREATE TABLE `user` (
 `id` bigint NOT NULL,
 `step` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
 `lang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";
$mysqli->query($sql);

echo 'ok' ;
unlink('table.php');