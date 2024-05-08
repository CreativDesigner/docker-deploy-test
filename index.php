<?php
$host = 'db';
$db   = 'meineDatenbank';
$user = 'root';
$pass = 'pass123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Überprüfen, ob die Tabelle existiert und ggf. erstellen
$query = "CREATE TABLE IF NOT EXISTS visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    count INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$pdo->exec($query);

// Prüfen, ob ein Eintrag existiert, und ggf. einen Eintrag erstellen
$query = "SELECT id FROM visits LIMIT 1";
$stmt = $pdo->query($query);

if ($stmt->fetchColumn() === false) {
    $query = "INSERT INTO visits (count) VALUES (0)";
    $pdo->exec($query);
}

// Zähler inkrementieren
$query = "UPDATE visits SET count = count + 1";
$pdo->exec($query);

// Aktuellen Zähler abrufen
$query = "SELECT count FROM visits";
$stmt = $pdo->query($query);
$counter = $stmt->fetchColumn();

echo "Version: 1<br/>";
echo "Counter: " . $counter;
?>
