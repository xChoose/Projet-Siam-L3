<?php
    $dbname = 'ma_base_de_donnees.db';
    if (!class_exists('SQLite3')) {
        die("SQLite 3 NOT supported.");
    }

    // Removed $flags since it's not defined and not necessary if you don't have specific flags
    $base = new SQLite3($dbname);

    if (!$base) {
        die("Error opening database: " . $base->lastErrorMsg());
    }

    echo "SQLite 3 supported.";
?>
