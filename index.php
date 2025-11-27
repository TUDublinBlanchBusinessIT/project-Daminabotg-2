<?php 
include("db.php");

// Handle form submission
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO anime (title) VALUES (?)");
        $stmt->bind_param("s", $title);

        if ($stmt->execute()) {
            $message = "Anime added successfully!";
        } else {
            $message = "Error adding anime.";
        }

        $stmt->close();
    } else {
        $message = "Please enter an anime name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anime Watchlist</title>
</head>
<body>
    <h1>My Anime Watchlist</h1>

    <form method="POST">
        <label>Anime Title:</label>
        <input type="text" name="title" required>
        <button type="submit">Add</button>
    </form>

    <p><?php echo $message; ?></p>

    <h2>Current Watchlist</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Anime Title</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM anime ORDER BY id DESC");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No anime added yet.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
