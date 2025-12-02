<?php
require_once "dbConnect.php";

if (isset($_POST['saveButton'])) {

    $title = $_POST['title'];
    $episodesWatched = $_POST['episodes_watched'];
    $totalEpisodes = $_POST['total_episodes'];
    $status = $_POST['status'];
    $startDate = $_POST['start_date'];

    $insertSql = "INSERT INTO anime (title, episodes_watched, total_episodes, status, start_date)
                  VALUES ('$title', $episodesWatched, $totalEpisodes, '$status', '$startDate')";

    if ($conn->query($insertSql) === TRUE) {
        $message = "Anime saved!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$listSql = "SELECT * FROM anime ORDER BY id DESC";
$result = $conn->query($listSql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Anime Watchlist - Version 1</title>
</head>
<body>

<h1>Anime Watchlist (Version 1)</h1>

<?php
if (isset($message)) {
    echo "<p><strong>" . $message . "</strong></p>";
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
        <input type="text" name="status" />
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
        echo "<td>" . $row['start_date'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No anime added yet</td></tr>";
}

if ($result) {
    $result->free();
}

$conn->close();
?>
</table>

</body>
</html>
