<?php
include('db.php');
session_start();

// Check if client is logged in
if ($_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

// Initialize message variable
$message = '';

if (isset($_POST['proposal_id'])) {
    $proposal_id = mysqli_real_escape_string($conn, $_POST['proposal_id']);

    // Fetch the proposal from the database
    $query = "SELECT * FROM upwork_proposals WHERE id = '$proposal_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $proposal = mysqli_fetch_assoc($result);
        $freelancer_id = $proposal['freelancer_id'];
        $job_id = $proposal['job_id']; // Get the job ID from the proposal

        // 1. Update the job table with the freelancer's ID (hire the freelancer)
        $update_job_query = "UPDATE upwork_jobs SET freelancer_id = '$freelancer_id' WHERE id = '$job_id'";
        if (mysqli_query($conn, $update_job_query)) {
            // 2. Mark the proposal as 'hired' in the proposals table
            $update_proposal_query = "UPDATE upwork_proposals SET status = 'hired' WHERE id = '$proposal_id'";

            if (mysqli_query($conn, $update_proposal_query)) {
                // Success, freelancer hired
                $message = "Freelancer hired successfully!";
            } else {
                // Error while updating proposal status
                $message = "Failed to update proposal status: " . mysqli_error($conn);
            }
        } else {
            // Error while updating job table
            $message = "Failed to update job table: " . mysqli_error($conn);
        }
    } else {
        // If the proposal is not found
        $message = "Proposal not found.";
    }
} else {
    $message = "No proposal ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Freelancer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9; /* Light background for contrast */
            margin: 0;
            padding: 20px;
        }
        .message {
            background-color: #e7f3fe; /* Light blue background for messages */
            color: #31708f; /* Dark blue text */
            border: 1px solid #bce8f1; /* Light blue border */
            border-radius: 5px; /* Rounded corners */
            padding: 15px; /* Padding for message box */
            margin-bottom: 20px; /* Space below message */
            text-align: center; /* Center text */
        }
        h1 {
            color: #2C3E50; /* Main heading color */
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em; /* Increased font size */
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2C3E50; /* Button color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <h1>Hire Freelancer</h1>

    <?php if ($message): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="client_dashboard.php">Back to Dashboard</a>
</body>
</html>
