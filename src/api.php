<?php
/**
 * Main backend API
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 April 2020
 * @version 2.0.0 September 2021
 *                MySQLi substituted by PDO
 *                SQL statements are now prepared
 *                Data returned from the DB is sanitised to prevent execution of injected JS
 * @version 2.0.1 December 2024 Code convention updated
 * @version 2.0.2 February 2026 Input validation refactored
 */

$server = 'db';
$dbName = 'movies';
$user = 'root';
$pwd = 'pepe';

$dsn = 'mysql:host=' . $server . ';dbname=' . $dbName . ';charset=utf8';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = @new PDO($dsn, $user, $pwd, $options); 
} catch (\PDOException $e) {
    echo 'Connection unsuccessful';
    die('Connection unsuccessful: ' . $e->getMessage());
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $action = trim($_GET['action'] ?? '');
        $action = preg_replace('/\s+/u', ' ', $action);   // Normalise whitespace
        // Reject HTML tags
        if (preg_match('/<\s*\/?\s*[A-Za-z!][^>]*>/u', $action)) {
            http_response_code(400);
            exit;
        }
        if ($action === 'get_movie') {
            $movieID = (int) $_GET['movie_id'] ?? 0;

            $sql = 'SELECT cName FROM movies WHERE nMovieID = ?';                

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$movieID]);
            } catch (PDOException $e) {
                $pdo = null;
                $errorMessage = 'There was an error while trying to retrieve a movie: ' . $e->getMessage();
                echo $errorMessage;
                die($errorMessage);
            }
                
            http_response_code(200);
            if (!($results = $stmt->fetch())) {
                echo json_encode('');
            } else {
                echo json_encode(htmlspecialchars($results['cName'], ENT_QUOTES, 'UTF-8'));
            }
        } else {
            http_response_code(400);
            exit;
        }
        break;
    case 'POST':
        $action = trim($_POST['action'] ?? '');
        $action = preg_replace('/\s+/u', ' ', $action);   // Normalise whitespace
        // Reject HTML tags
        if (preg_match('/<\s*\/?\s*[A-Za-z!][^>]*>/u', $action)) {
            http_response_code(400);
            exit;
        }
        if ($action === 'new_movie') {
            $movieName = trim($_POST['movie_name'] ?? '');
            $movieName = preg_replace('/\s+/u', ' ', $movieName);   // Normalise whitespace
            // Reject HTML tags
            if (preg_match('/<\s*\/?\s*[A-Za-z!][^>]*>/u', $movieName)) {
                http_response_code(400);
                exit;
            }

            $sql = 'INSERT INTO movies (cName) VALUES (?)';

            try {

                $stmt = $pdo->prepare($sql);
                $stmt->execute([$movieName]);
                
                http_response_code(201);
                echo json_encode('Insert successful');
            } catch (PDOException $e) {
                http_response_code(500);
                $pdo = null;
                $errorMessage = 'There was an error while trying to insert a new movie: ' . $e->getMessage();
                echo $errorMessage;
                die($errorMessage);
            }
        } else {
            http_response_code(400);
            exit;
        }
        break;
    default:
        http_response_code(405);    // Method not allowed
        exit;
}
        
$pdo = null;