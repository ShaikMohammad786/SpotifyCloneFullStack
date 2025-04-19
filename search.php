<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "spotify_clone");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Function to clean strings for search
function cleanString($string) {
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string); // Transliterate accented characters
    $string = preg_replace('/\d{1,2}[-. ]*/', '', $string); // Remove leading numbers like 01-
    $string = preg_replace('/[\[\(].*?[\]\)]/', '', $string); // Remove anything in [] or ()
    $string = preg_replace('/[^a-zA-Z0-9]/', '', $string); // Remove all non-alphanumeric
    return trim($string);
}

// Function to calculate Levenshtein distance
function calculateLevenshtein($str1, $str2) {
    return levenshtein(strtolower($str1), strtolower($str2));
}

$searchTerm = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if ($searchTerm === '') {
    echo json_encode([]);
    exit;
}

// Clean and lowercase the search term
$cleanedSearchTerm = cleanString(strtolower($searchTerm));

// Use a relaxed LIKE pattern to fetch potential matches (first 3+ characters)
$shortSearchTerm = substr($cleanedSearchTerm, 0, 3); // Use first 3 chars for broad matching
if (strlen($shortSearchTerm) < 3) {
    $shortSearchTerm = $cleanedSearchTerm; // Fallback for short terms
}
$likeSearchTerm = '%' . $shortSearchTerm . '%';

// SQL query to fetch potential matches
$sql = "
    SELECT id, title, artist, filename, cover_url
    FROM songs
    WHERE LOWER(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(title, '\\d{1,2}[-. ]*', ''), '[\\[\\(].*?[\\]\\)]', ''), '[^a-zA-Z0-9]', '')) LIKE ?
       OR LOWER(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(artist, '\\d{1,2}[-. ]*', ''), '[\\[\\(].*?[\\]\\)]', ''), '[^a-zA-Z0-9]', '')) LIKE ?
       OR title LIKE ? OR artist LIKE ?
";

// Prepare the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "SQL preparation failed: " . $conn->error]);
    exit;
}

// Bind parameters
$rawLikeSearchTerm = '%' . $searchTerm . '%'; // For raw title/artist matching
$stmt->bind_param("ssss", $likeSearchTerm, $likeSearchTerm, $rawLikeSearchTerm, $rawLikeSearchTerm);

// Execute and fetch results
$potentialMatches = [];
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $potentialMatches[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "artist" => $row['artist'],
            "filename" => $row['filename'],
            "cover_url" => $row['cover_url'],
            "cleaned_title" => cleanString(strtolower($row['title'])),
            "cleaned_artist" => cleanString(strtolower($row['artist']))
        ];
    }
} else {
    echo json_encode(["error" => "Query execution failed: " . $stmt->error]);
    exit;
}

// Filter and rank matches using Levenshtein distance
$songs = [];
$maxDistance = max(5, strlen($cleanedSearchTerm) / 5); 
foreach ($potentialMatches as $match) {
    $titleDistance = calculateLevenshtein($cleanedSearchTerm, $match['cleaned_title']);
    $artistDistance = calculateLevenshtein($cleanedSearchTerm, $match['cleaned_artist']);
    
    // Include if close enough or exact substring match
    if ($titleDistance <= $maxDistance || $artistDistance <= $maxDistance ||
        strpos($match['cleaned_title'], $cleanedSearchTerm) !== false ||
        strpos($match['cleaned_artist'], $cleanedSearchTerm) !== false) {
        $songs[] = [
            "id" => $match['id'],
            "title" => $match['title'],
            "artist" => $match['artist'],
            "filename" => $match['filename'],
            "cover_url" => $match['cover_url'],
            "distance" => min($titleDistance, $artistDistance) // For sorting
        ];
    }
}

// Sort by distance (closest matches first)
usort($songs, function($a, $b) {
    return $a['distance'] - $b['distance'];
});

// Remove distance field from output
$songs = array_map(function($song) {
    unset($song['distance']);
    return $song;
}, $songs);

// Limit results (optional, e.g., top 50)
$songs = array_slice($songs, 0, 50);

echo json_encode($songs);

$stmt->close();
$conn->close();
?>