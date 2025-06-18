<?php
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BlueBird - Homestay Management</title>
  <!-- fontawesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <!-- bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    crossorigin="anonymous"
  />
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
  ></script>
  <link rel="stylesheet" href="css/room.css" />
</head>

<body>
  <div class="container mt-5">
    <h2 class="mb-4">Add Homestay</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="mb-5">
      <label for="name">Homestay Name:</label>
      <input type="text" name="name" class="form-control mb-3" required />

      <label for="total_rooms">Total Rooms:</label>
      <input type="number" name="total_rooms" class="form-control mb-3" required />

      <label for="price">Price (per night):</label>
      <input type="number" step="0.01" name="price" class="form-control mb-3" required />

      <label for="location">Location:</label>
      <input type="text" name="location" class="form-control mb-3" />

      <label for="description">Description:</label>
      <textarea name="description" class="form-control mb-3" required></textarea>

      <label for="image">Image:</label>
      <input type="file" name="image" class="form-control mb-3" required />

      <button type="submit" class="btn btn-success" name="addhomestay">Add Homestay</button>
    </form>

    <?php
    if (isset($_POST['addhomestay'])) {
      // ✅ Sanitize & prepare inputs
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $total_rooms = (int) $_POST['total_rooms'];
      $price = (float) $_POST['price'];
      $location = mysqli_real_escape_string($conn, $_POST['location']);
      $description = mysqli_real_escape_string($conn, $_POST['description']);

      // ✅ Handle file upload
      $image = $_FILES['image']['name'];
      $tmp = $_FILES['image']['tmp_name'];

      // ✅ Ensure uploads folder exists
      if (!is_dir("../uploads")) {
        mkdir("../uploads", 0777, true);
      }

      // ✅ Move uploaded file
      move_uploaded_file($tmp, "../uploads/" . $image);

      // ✅ Insert ALL data including image
      $sql = "INSERT INTO homestay (name, total_rooms, price, location, description, image)
              VALUES ('$name', '$total_rooms', '$price', '$location', '$description', '$image')";
      $result = mysqli_query($conn, $sql);

      if ($result) {
        header("Location: room.php");
        exit();
      } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
      }
    }
    ?>
  </div>

  <div class="container">
    <h2 class="mb-4">Homestay List</h2>
    <div class="row">
      <?php
      $sql = "SELECT * FROM homestay";
      $result = mysqli_query($conn, $sql);

      while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = !empty($row['image']) ? "../uploads/" . htmlspecialchars($row['image']) : "../uploads/default.jpg";

        echo "<div class='col-md-4 mb-4'>
                <div class='card h-100'>
                  <img src='$imagePath' class='card-img-top' alt='Homestay Image' style='height:200px; object-fit:cover;'>
                  <div class='card-body text-center'>
                    <h4 class='card-title'>" . htmlspecialchars($row['name']) . "</h4>
                    <p class='card-text'>Rooms: " . $row['total_rooms'] . "</p>
                    <p class='card-text'>Price: RM" . number_format($row['price'], 2) . "</p>
                    <p class='card-text'>Location: " . htmlspecialchars($row['location']) . "</p>
                    <p class='card-text'>" . nl2br(htmlspecialchars($row['description'])) . "</p>
                    <a href='roomdelete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                  </div>
                </div>
              </div>";
      }
      ?>
    </div>
  </div>
</body>

</html>
