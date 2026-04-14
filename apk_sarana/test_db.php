<?php
$p = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '');
$dbs = $p->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $dbs) . "\n";

// Check for sarana-related db
echo "\n== Looking for sarana tables ==\n";
foreach ($dbs as $db) {
    if (stripos($db, 'sarana') !== false || stripos($db, 'pengaduan') !== false || stripos($db, 'smk') !== false || stripos($db, 'aspirasi') !== false) {
        echo "FOUND: $db\n";
        $p2 = new PDO("mysql:host=127.0.0.1;dbname=$db;charset=utf8mb4", 'root', '');
        $ts = $p2->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "  Tables: " . implode(', ', $ts) . "\n";
    }
}

// Check all dbs for aspirasis table
echo "\n== Searching all dbs for aspirasis table ==\n";
foreach ($dbs as $db) {
    if (in_array($db, ['information_schema','performance_schema','mysql','sys'])) continue;
    try {
        $p3 = new PDO("mysql:host=127.0.0.1;dbname=$db;charset=utf8mb4", 'root', '');
        $ts = $p3->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (in_array('aspirasis', $ts) || in_array('input_aspirasis', $ts)) {
            echo "FOUND in $db: " . implode(', ', $ts) . "\n";
        }
    } catch(Exception $e) {}
}
