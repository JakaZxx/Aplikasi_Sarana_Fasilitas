<?php
require 'src/helpers/db.php';
$stmt = db()->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$schema = [];
foreach($tables as $table) {
    $stmt = db()->query("DESCRIBE `$table`");
    $schema[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
echo json_encode($schema, JSON_PRETTY_PRINT);
