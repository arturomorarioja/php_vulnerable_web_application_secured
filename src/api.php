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
 */

$server = 'localhost';
$dbName = 'movies';
$user = 'root';
$pwd = '';

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
        $action = htmlspecialchars(trim($_GET['action'] ?? ''));
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
                
            if (!($results = $stmt->fetch())) {
                echo json_encode('');
            } else {
                echo json_encode(htmlspecialchars($results['cName']));
            }
        }
        break;
    case 'POST':
        $action = htmlspecialchars(trim($_POST['action'] ?? ''));
        if ($action === 'new_movie') {
            $movieName = htmlspecialchars(trim($_POST['movie_name'] ?? ''));

            $sql = 'INSERT INTO movies (cName) VALUES (?)';

            try {

                $stmt = $pdo->prepare($sql);
                $stmt->execute([$movieName]);
                
                echo json_encode('Insert successful');
            } catch (PDOException $e) {
                $pdo = null;
                $errorMessage = 'There was an error while trying to insert a new movie: ' . $e->getMessage();
                echo $errorMessage;
                die($errorMessage);
            }
        }
        break;
}
        
$pdo = null;