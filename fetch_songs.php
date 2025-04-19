<?php
set_time_limit(0); 

$clientId = "9431fe8f1258468b990704001e2150e8";
$clientSecret = "1d1b3a2ec3154a128d064cb3e35eb1bc";
$accessToken = null;


function getSpotifyAccessToken($clientId, $clientSecret) {
    $url = "https://accounts.spotify.com/api/token";
    $headers = [
        "Authorization: Basic " . base64_encode("$clientId:$clientSecret"),
        "Content-Type: application/x-www-form-urlencoded"
    ];
    $data = "grant_type=client_credentials";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $response = json_decode($result, true);
    curl_close($ch);

    return $response['access_token'] ?? null;
}

// 2. Search Spotify
function searchSpotifyTrack($token, $query) {
    $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=1";
    $headers = ["Authorization: Bearer $token"];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function cleanFileName($filename) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = preg_replace('/\d{1,2}[-. ]*/', '', $name); // remove leading numbers like 01-
    $name = preg_replace('/[\[\(].*?[\]\)]/', '', $name); // remove anything in [] or ()
    $name = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $name); // remove weird symbols
    $name = preg_replace('/\s+/', ' ', $name); // normalize spaces
    return trim($name);
}

// 3. Connect to MySQL
$conn = new mysqli("localhost", "root", "", "spotify_clone");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// 4. Get .mp3 files from /songs folder
$songsFolder = "../songs";
$files = array_diff(scandir($songsFolder), array('.', '..'));

$accessToken = getSpotifyAccessToken($clientId, $clientSecret);

foreach ($files as $file) {
    if (str_ends_with($file, ".mp3")) {
        $filename = $file;
        $check = $conn->prepare("SELECT id FROM songs WHERE filename = ?");
$check->bind_param("s", $filename);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "⏩ Skipped (already exists): $filename<br>";
    continue;
}

       
        $searchName = cleanFileName($file);

        

        $result = searchSpotifyTrack($accessToken, $searchName);
        $track = $result['tracks']['items'][0] ?? null;

        if ($track) {
            $title = $track['name'];
            $artist = $track['artists'][0]['name'];
            $album = $track['album']['name'];
            $cover = $track['album']['images'][0]['url'];
            $duration = round($track['duration_ms'] / 1000);
            $spotifyId = $track['id'];


$check = $conn->prepare("SELECT id FROM songs WHERE title = ? AND artist = ?");
$check->bind_param("ss", $title, $artist);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ Duplicate: $title by $artist<br>";
    continue; 
}

            // 5. Store in database
            $stmt = $conn->prepare("INSERT INTO songs (filename, title, artist, album, cover_url, duration, spotify_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssis", $filename, $title, $artist, $album, $cover, $duration, $spotifyId);
            $stmt->execute();
            echo "✅ Inserted: $title by $artist<br>";
        } else {
            echo "❌ Not found: $searchName<br>";
        }
    }
}

$conn->close();
?>

