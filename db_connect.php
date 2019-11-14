<?php
     define('DB_DSN','mysql:host=sql311.byethost.com;dbname=b18_24742953_project;charset=utf8');
     define('DB_USER','b18_24742953');
     define('DB_PASS','Password01');     
     
     try {
         // Try creating new PDO connection to MySQL.
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
         //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die(); // Force execution to stop on errors.
         // When deploying to production you should handle this
         // situation more gracefully. Â¯\_(ãƒ„)_/Â¯
     }
 ?>