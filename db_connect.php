<?php

/**
 * File: db_connect.php
 * Author: Do Vu Khanh Quynh
 * Description: Establishes the shared MySQLi database connection ($conn) using settings.php.
 */

// Load database configuration variables ($host, $user, $pwd, $sql_db)
require_once(__DIR__ . "/settings.php");

// Open connection to MySQL database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

// Verify connection status
if (!$conn) {
    die("<p>Database connection failure: " . mysqli_connect_error() . "</p>");
}

// Ensure proper UTF-8 character encoding
mysqli_set_charset($conn, "utf8mb4");
