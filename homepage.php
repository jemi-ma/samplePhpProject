<?php
require("connection.php");

// Handle upload
if (isset($_POST['upload'])) {
    $image = $_FILES['image']['name'];
    $ingredients = $_POST['ingredients'];
    $recipe = $_POST['recipe'];
    $recipename = $_POST['recipename'];  // New field

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO recipedetails (img, ingredients, recipe, recipename) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $target_file, $ingredients, $recipe, $recipename);  // Bind the recipename
        $stmt->execute();
        echo "<script>showAlert('Recipe uploaded successfully!', 'success');</script>";
    } else {
        echo "<script>showAlert('Failed to upload image.', 'error');</script>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Fetch the image path associated with the ID
    $stmt = $conn->prepare("SELECT img FROM recipedetails WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && isset($row['img']) && file_exists($row['img'])) {
        unlink($row['img']); // Delete the image file
    }

    // Delete the record from the database
    $stmt = $conn->prepare("DELETE FROM recipedetails WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>showAlert('Recipe deleted successfully!', 'success');</script>";
}

// Handle update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $ingredients = $_POST['ingredients'];
    $recipe = $_POST['recipe'];

    $stmt = $conn->prepare("UPDATE recipedetails SET ingredients = ?, recipe = ? WHERE id = ?");
    $stmt->bind_param("ssi", $ingredients, $recipe, $id);
    $stmt->execute();
    echo "<script>showAlert('Recipe updated successfully!', 'success');</script>";
}

// Fetch all recipes
$result = $conn->query("SELECT * FROM recipedetails");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Sharing</title>
    <link rel="stylesheet" href="homepage.css"> <!-- Your CSS link -->
</head>
<body>
    <div class="container">
        <h1>Recipe Sharing Platform</h1>
        
        <!-- Upload Form -->
        <h2>Upload Recipe</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="inputbox">
                <input type="file" name="image" required>
            </div>
            <div class="inputbox">
                <textarea name="ingredients" placeholder="Ingredients" required></textarea>
            </div>
            <div class="inputbox">
                <textarea name="recipe" placeholder="Recipe" required></textarea>
            </div>
            <div class="inputbox">
                <input type="text" name="recipename" placeholder="Recipe Name" required>
            </div>
            <div class="button">
                <input type="submit" name="upload" value="Upload Recipe">
            </div>
        </form>

        <!-- Display Recipes -->
        <div class="table-container">
            <h2>All Recipes</h2>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Recipe Name</th>
                    <th>Ingredients</th>
                    <th>Recipe</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= $row['img'] ?>" alt="Recipe Image" width="100"></td>
                    <td><?= $row['recipename'] ?></td>
                    <td><?= $row['ingredients'] ?></td>
                    <td><?= $row['recipe'] ?></td>
                    <td>
                        <!-- Update Button -->
                        <a href="homepage.php?edit=<?= $row['id'] ?>"><button class="update-btn">Update</button></a>
                        <a href="?delete=<?= $row['id'] ?>"><button class="delete-btn">Delete</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Edit Recipe Form -->
        <?php if (isset($_GET['edit'])): ?>
            <?php
            $editId = $_GET['edit'];
            $stmt = $conn->prepare("SELECT * FROM recipedetails WHERE id = ?");
            $stmt->bind_param("i", $editId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            ?>
            <h2>Edit Recipe</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="inputbox">
                    <input type="file" name="image">
                </div>
                <div class="inputbox">
                    <textarea name="ingredients" placeholder="Ingredients" required><?= $row['ingredients'] ?></textarea>
                </div>
                <div class="inputbox">
                    <textarea name="recipe" placeholder="Recipe" required><?= $row['recipe'] ?></textarea>
                </div>
                <div class="inputbox">
                    <input type="text" name="recipename" placeholder="Recipe Name" value="<?= $row['recipename'] ?>" required>
                </div>
                <div class="button">
                    <input type="submit" name="update" value="Update Recipe">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<script>
    // Function to show alert pop-up
    function showAlert(message, type = 'success') {
        let bgColor;
        switch (type) {
            case 'success':
                bgColor = '#4caf50'; // Green for success
                break;
            case 'error':
                bgColor = '#f44336'; // Red for error
                break;
            default:
                bgColor = '#2196f3'; // Blue for info
        }
        // Create the alert element
        const alertBox = document.createElement('div');
        alertBox.style.position = 'fixed';
        alertBox.style.bottom = '20px';
        alertBox.style.right = '20px';
        alertBox.style.padding = '15px 20px';
        alertBox.style.color = '#fff';
        alertBox.style.backgroundColor = bgColor;
        alertBox.style.borderRadius = '5px';
        alertBox.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.3)';
        alertBox.style.zIndex = '1000';
        alertBox.style.fontSize = '1em';
        alertBox.style.fontWeight = 'bold';
        alertBox.textContent = message;

        document.body.appendChild(alertBox);

        // Remove after 3 seconds
        setTimeout(() => {
            alertBox.remove();
        }, 3000);
    }
</script>
