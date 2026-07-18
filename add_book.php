<?php
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $isbn = trim($_POST["isbn"]);
    $category = trim($_POST["category"]);
    $published_year = trim($_POST["published_year"]);

    if ($title !== "" && $author !== "") {
        $stmt = $conn->prepare(
            "INSERT INTO books (title, author, isbn, category, published_year)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $title,
            $author,
            $isbn,
            $category,
            $published_year
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $message = "Unable to add book. ISBN may already exist.";
        }
    } else {
        $message = "Title and author are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book | Book Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container small-container">
    <a class="back-link" href="index.php">← Back to library</a>

    <div class="form-card">
        <h1>Add a New Book</h1>
        <p>Enter the book details below.</p>

        <?php if ($message !== ""): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Book Title *</label>
            <input type="text" name="title" required>

            <label>Author *</label>
            <input type="text" name="author" required>

            <label>ISBN</label>
            <input type="text" name="isbn">

            <label>Category</label>
            <input type="text" name="category">

            <label>Published Year</label>
            <input type="number" name="published_year" min="1000" max="2099">

            <button class="submit-button" type="submit">Save Book</button>
        </form>
    </div>
</div>

</body>
</html>