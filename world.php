<?php
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : 'countries';

    if ($lookup === 'cities') {
        $stmt = $conn->prepare(
            "SELECT cities.name, cities.district, cities.population 
             FROM cities
             JOIN countries ON cities.country_code = countries.code
             WHERE countries.name LIKE :country"
        );
        $stmt->execute(['country' => '%' . $country . '%']);
    } else {
        $stmt = $conn->prepare(
            "SELECT name, continent, independence_year, head_of_state 
             FROM countries 
             WHERE name LIKE :country"
        );
        $stmt->execute(['country' => '%' . $country . '%']);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

if ($results) {
    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
    if ($lookup === 'cities') {
        echo '<thead><tr><th>Name</th><th>District</th><th>Population</th></tr></thead><tbody>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['district']) . '</td>';
            echo '<td>' . htmlspecialchars($row['population']) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<thead><tr><th>Country Name</th><th>Continent</th><th>Independence Year</th><th>Head of State</th></tr></thead><tbody>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
            echo '<td>' . htmlspecialchars($row['independence_year'] ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($row['head_of_state'] ?? 'N/A') . '</td>';
            echo '</tr>';
        }
    }
    echo '</tbody></table>';
} else {
    echo '<p>No results found.</p>';
}
?>
