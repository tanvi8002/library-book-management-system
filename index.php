<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);

    $stmt = $conn->prepare(
        "SELECT * FROM books
         WHERE title LIKE ?
         OR author LIKE ?
         OR isbn LIKE ?
         OR category LIKE ?
         ORDER BY id DESC"
    );

    $keyword = "%" . $search . "%";
    $stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query("SELECT * FROM books ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <header>
        <div>
            <h1>Book Library</h1>
            <p>Manage and search book records efficiently.</p>
        </div>

        <a class="add-button" href="add_book.php">+ Add Book</a>
    </header>

    <form class="search-box" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search by title, author, ISBN, or category..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit">Search</button>

        <?php if ($search !== ""): ?>
            <a class="clear-button" href="index.php">Clear</a>
        <?php endif; ?>
    </form>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Category</th>
                    <th>Year</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($books && $books->num_rows > 0): ?>
                    <?php while ($book = $books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book["title"]); ?></td>
                            <td><?php echo htmlspecialchars($book["author"]); ?></td>
                            <td><?php echo htmlspecialchars($book["isbn"]); ?></td>
                            <td><?php echo htmlspecialchars($book["category"]); ?></td>
                            <td><?php echo htmlspecialchars($book["published_year"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-results">No books found. Add your first book.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>