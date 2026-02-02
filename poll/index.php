<?php
/**
 * Sloppy poll
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 April 2020
 * @version 1.0.1 December 2024 Code convention updated
 */

$conn = new mysqli('db', 'root', 'pepe', 'movies'); 
    
if ($conn->connect_errno) {
    echo 'Connection unsuccessful';
    die('Connection unsuccessful: ' . $conn->connect_errno);
}

if (isset($_GET['vote'])) {
    $sql = 'UPDATE votes SET nNumVotes = nNumVotes + 1 WHERE nVotesID = 1';
    if (!$conn->query($sql)) {
        die('Insert unsuccessful: ' . $conn->error . '; query: ' . $sql); 
    }
}

$sql = 'SELECT nNumVotes FROM votes WHERE nVotesID = 1';
if (!$res = $conn->query($sql)) {
    die('Insert unsuccessful: ' . $conn->error . '; query: ' . $sql); 
} else {
    $votes = $res->fetch_assoc()['nNumVotes'];
    echo 'Number of votes: ' . $votes;
}