<?php
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$port = '5432';                                    
$dbname = 'postgres';                              
$username = 'postgres.ttoqwpjxkzsfunumarne';      
$password = 'MLClub@@023';                        

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>