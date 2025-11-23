<?php
$conn = new mysqli("localhost", "root", "", "bap_system");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];  // Get the entry ID from the form
    $action = $_POST['action'];  // Get the action (approve/deny)

    // Determine the status based on the action
    if ($action === 'approve') {
        $status = 'Approved';
    } else if ($action === 'deny') {
        $status = 'Denied';
    }

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE bap_entries SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");  // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
