<?php
include '../config.php';

// ✅ Validate ID safely
$id = (int) $_GET['id'];

$homestayDeleteSql = "DELETE FROM homestay WHERE id = $id";
$result = mysqli_query($conn, $homestayDeleteSql);

header("Location: room.php");
exit();
?>
