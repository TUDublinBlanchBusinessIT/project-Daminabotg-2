<?php
require_once "dbConnect.php";

$message = "";

if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    $conn->query("DELETE FROM anime WHERE id = $deleteID");
    header("Location: index.php?message=Anime+deleted!");
    exit();
}

$editData = null;

if (isset($_GET['edit'])) {
    $editID = $_GET['edit'];
    $editResult = $conn->query("SELECT * FROM anime WHERE id = $editID");
    $editData = $editResult->fetch_assoc();
}

if (isset($_POST['updateButton'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $episodesWatched = $_POST['episodes_watched'];
    $totalEpisodes = $_POST['total_episodes'];
    $status = $_POST['status'];
    $dubbed = $_POST['dubbed'];
    $genre = $_POST['genre_id'];
    $startDate = $_POST['start_date'];

    $conn->query("UPDATE anime SET 
                  title='$title',
                  episodes_watched=$episodesWatched,
                  total_episodes=$totalEpisodes,
                  status='$status',
                  dubbed='$dubbed',
                  genre_id=$genre,
                  start_date='$startDate'
                  WHERE id=$id");

    header("Location: index.php?message=Anime+updated!");
    exit();
}

if (isset($_POST['saveButton'])) {
    $title = $_POST['title'];
    $episodesWatched = $_POST['episodes_watched'];
    $totalEpisodes = $_POST['total_episodes'];
    $status = $_POST['status'];
    $dubbed = $_POST['dubbed'];
    $genre = $_POST['genre_id'];
    $startDate = $_POST['start_date'];

    $checkResult = $conn->query("SELECT * FROM anime WHERE title = '$title'");

    if ($checkResult->num_rows > 0) {
        header("Location: index.php?message=Anime+already+exists!");
        exit();
    }

    $conn->query("INSERT INTO anime 
                  (title, episodes_watched, total_episodes, status, start_date, dubbed, genre_id)
                  VALUES 
                  ('$title', $episodesWatched, $totalEpisodes, '$status', '$startDate', '$dubbed', $genre)");

    header("Location: index.php?message=Anime+saved!");
    exit();
}

$listSql = "SELECT anime.id, anime.title, anime.episodes_watched, anime.total_episodes,
                   anime.status, anime.dubbed, genres.genre_name, anime.start_date, anime.genre_id
            FROM anime
            LEFT JOIN genres ON anime.genre_id = genres.id
            ORDER BY anime.id ASC";

$result = $conn->query($listSql);

$genreResult = $conn->query("SELECT * FROM genres ORDER BY genre_name ASC");
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

<h2><?php echo ($editData ? "Edit Anime" : "Add Anime"); ?></h2>

<form method="POST" action="index.php">

    <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>" />
    <?php endif; ?>

    <p>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo ($editData ? $editData['title'] : '') ?>" />
    </p>

    <p>
        <label>Episodes Watched:</label><br>
        <input type="number" name="episodes_watched" value="<?php echo ($editData ? $editData['episodes_watched'] : '') ?>" />
    </p>

    <p>
        <label>Total Episodes:</label><br>
        <input type="number" name="total_episodes" value="<?php echo ($editData ? $editData['total_episodes'] : '') ?>" />
    </p>

    <p>
        <label>Status:</label><br>
        <select name="status">
            <?php
            $statusList = ["Watching", "Completed", "On Hold", "Dropped", "Plan to Watch"];
            foreach ($statusList as $s) {
                $sel = ($editData && $editData['status'] == $s) ? "selected" : "";
                echo "<option value='$s' $sel>$s</option>";
            }
            ?>
        </select>
    </p>

    <p>
        <label>Dubbed or Subbed:</label><br>
        <input type="radio" name="dubbed" value="Dubbed" <?php echo ($editData && $editData['dubbed'] == 'Dubbed') ? 'checked' : ''; ?> /> Dubbed
        <input type="radio" name="dubbed" value="Subbed" <?php echo ($editData && $editData['dubbed'] == 'Subbed') ? 'checked' : ''; ?> /> Subbed
    </p>

    <p>
        <label>Genre:</label><br>
        <select name="genre_id">
            <?php
            $genreResult->data_seek(0);
            while ($g = $genreResult->fetch_assoc()) {
                $sel = ($editData && $editData['genre_id'] == $g['id']) ? "selected" : "";
                echo "<option value='" . $g['id'] . "' $sel>" . $g['genre_name'] . "</option>";
            }
            ?>
        </select>
    </p>

    <p>
        <label>Start Date:</label><br>
        <input type="date" name="start_date" value="<?php echo ($editData ? $editData['start_date'] : '') ?>" />
    </p>

    <p>
        <?php if ($editData): ?>
            <input type="submit" name="updateButton" value="Update Anime" />
        <?php else: ?>
            <input type="submit" name="saveButton" value="Save Anime" />
        <?php endif; ?>
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
        <th>Dubbed</th>
        <th>Genre</th>
        <th>Start Date</th>
        <th>Edit</th>
        <th>Delete</th>
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
        echo "<td><a href='index.php?edit=" . $row['id'] . "'>Edit</a></td>";
        echo "<td><a href='index.php?delete=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No anime added yet</td></tr>";
}

$conn->close();
?>
</table>

</body>
</html>
