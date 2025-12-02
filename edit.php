<<?php
error_reporting(E_ALL)
ini_set("display_errors", 1);

include("db.php");

$message = "";

// Make sure an ID is provided
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = $_GET["id"];

// Get current anime title
$stmt = $conn->prepare("SELECT title FROM anime WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No anime with this ID, go back
    header("Location: index.php");
    exit;
}

$row = $result->fetch_assoc();
$currentTitle = $row["title"];

$stmt->close();

// Handle form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTitle = trim($_POST["title"]);

    if ($newTitle === "") {
        $message = "Title cannot be empty.";
    } else {
        $update = $conn->prepare("UPDATE anime SET title = ? WHERE id = ?");
        $update->bind_param("si", $newTitle, $id); // <-- FIXED HERE

        if ($update->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $message = "Error updating anime.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Anime</title>
</head>
<body>

<h1>Edit Anime Title</h1>

<form method="POST">
    <label>New Title:</label>
    <input type="text" name="title" value="<?php echo $currentTitle; ?>">
    <button type="submit">Save</button>
</form>

<p style="color:red; font-weight:bold;"><?php echo $message; ?></p>

<p><a href="index.php">Back to list</a></p>

</body>
</html>
