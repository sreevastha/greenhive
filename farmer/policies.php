<?php
$conn = new mysqli("localhost", "root", "", "greenhive");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM policies";
$result = $conn->query($sql);

$policies = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $policies[] = $row;
    }
}

echo json_encode($policies);
$conn->close();
?>