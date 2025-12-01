<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("db.php");

$message = "";

// Handle form submission (Add Anime)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);

    if ($title === "") {
        $message = "Please enter an anime title.";
    } else {
        // Check for duplicates
        $check = $conn->prepare("SELECT id FROM anime WHERE title = ?");
        $check->bind_param("s", $title);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "This anime already exists in your list.";
        } else {
            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO anime (title) VALUES (?)");
            $stmt->bind_param("s", $title);

            if ($stmt->execute()) {
                $message = "Anime added successfully!";
            } else {
                $message = "Error adding anime.";
            }

            $stmt->close();
        }
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
        <input type="text" name="title">
        <button type="submit">Add</button>
    </form>

    <p style="color:red; font-weight:bold;"><?php echo $message; ?></p>

    <h2>Current Watchlist</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>#</th>
            <th>Anime Title</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM anime ORDER BY id ASC");
        $counter = 1; // row numbering

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                echo "<tr>
                    <td>" . $counter . "</td>
                    <td>" . $row['title'] . "</td>
                    <td>
                        <a href='edit.php?id=" . $row['id'] . "'>Edit</a>
                        |
                        <a href='delete.php?id=" . $row['id'] . "' 
                           onclick='return confirm(\"Are you sure you want to delete this anime?\");'>
                           Delete
                        </a>
                    </td>
                </tr>";

                $counter++;
            }
        } else {
            echo "<tr><td colspan='3'>No anime added yet.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
