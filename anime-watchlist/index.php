<?php
require_once "dbConnect.php";

if (isset($_POST['saveButton'])) {

    $title = $_POST['title'];
    $episodesWatched = $_POST['episodes_watched'];
    $totalEpisodes = $_POST['total_episodes'];
    $status = $_POST['status'];
    $dubbed = $_POST['dubbed'];
    $genre = $_POST['genre_id'];
    $startDate = $_POST['start_date'];

    
    $checkSql = "SELECT * FROM anime WHERE title = '$title'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        header("Location: index.php?message=Anime+already+exists!");
        exit();
    }

    
    $insertSql = "INSERT INTO anime (title, episodes_watched, total_episodes, status, start_date, dubbed, genre_id)
                  VALUES ('$title', $episodesWatched, $totalEpisodes, '$status', '$startDate', '$dubbed', $genre)";

    if ($conn->query($insertSql) === TRUE) {
        header("Location: index.php?message=Anime+saved!");
        exit();
    } else {
        header("Location: index.php?message=Error");
        exit();
    }
}


$listSql = "SELECT anime.*, genres.genre_name 
            FROM anime
            LEFT JOIN genres ON anime.genre_id = genres.id
            ORDER BY anime.id ASC";
$result = $conn->query($listSql);


$genreSql = "SELECT * FROM genres ORDER BY genre_name ASC";
$genreResult = $conn->query($genreSql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Anime Watchlist</title>
</head>
<body>

<h1>Anime Watchlist</h1>

<?php
if (isset($_GET['message'])) {
    echo "<p><strong>" . $_GET['message'] . "</strong></p>";
}
?>

<h2>Add Anime</h2>

<form method="POST" action="index.php">
    <p>
        <label>Title:</label><br>
        <input type="text" name="title" />
    </p>

    <p>
        <label>Episodes Watched:</label><br>
        <input type="number" name="episodes_watched" />
    </p>

    <p>
        <label>Total Episodes:</label><br>
        <input type="number" name="total_episodes" />
    </p>

    <p>
        <label>Status:</label><br>
        <select name="status">
            <option value="Watching">Watching</option>
            <option value="Completed">Completed</option>
            <option value="On Hold">On Hold</option>
            <option value="Dropped">Dropped</option>
            <option value="Plan to Watch">Plan to Watch</option>
        </select>
    </p>

    <p>
        <label>Dubbed or Subbed:</label><br>
        <input type="radio" name="dubbed" value="Dubbed"> Dubbed
        <input type="radio" name="dubbed" value="Subbed"> Subbed
    </p>

    <p>
        <label>Genre:</label><br>
        <select name="genre_id">
            <?php
            if ($genreResult && $genreResult->num_rows > 0) {
                while ($g = $genreResult->fetch_assoc()) {
                    echo "<option value='" . $g['id'] . "'>" . $g['genre_name'] . "</option>";
                }
            }
            ?>
        </select>
    </p>

    <p>
        <label>Start Date:</label><br>
        <input type="date" name="start_date" />
    </p>

    <p>
        <input type="submit" name="saveButton" value="Save Anime" />
    </p>
</form>

<h2>My Anime List</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Episodes Watched</th>
        <th>Total Episodes</th>
        <th>Status</th>
        <th>Dubbed?</th>
        <th>Genre</th>
        <th>Start Date</th>
    </tr>

<?php
if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['episodes_watched'] . "</td>";
        echo "<td>" . $row['total_episodes'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['dubbed'] . "</td>";
        echo "<td>" . $row['genre_name'] . "</td>";
        echo "<td>" . $row['start_date'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No anime added yet</td></tr>";
}

if ($result) {
    $result->free();
}

$conn->close();
?>
</table>

</body>
</html>
