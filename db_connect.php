<?php

/**
 * File: db_connect.php
 * Author: Do Vu Khanh Quynh
 * Description: Establishes the shared MySQLi database connection ($conn) using settings.php.
 */

// Load database configuration variables ($host, $user, $pwd, $sql_db)
require_once(__DIR__ . "/settings.php");

// PHP 8.1+ throws exceptions on connection errors by default; switch them off
// so a failure is handled below instead of crashing with a stack trace.
mysqli_report(MYSQLI_REPORT_OFF);

// Open connection to MySQL database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

// Verify connection status
if (!$conn) {
    // Technical details go to the server log only, never to the visitor.
    error_log("Database connection failure: " . mysqli_connect_error());
    die("<p>Sorry, this page is temporarily unavailable. Please try again later.</p>");
}

// Ensure proper UTF-8 character encoding
mysqli_set_charset($conn, "utf8mb4");
