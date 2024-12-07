<?php
include('db.php');
session_start();

// Check if the client is logged in and the job_id is set
if ($_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['job_id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['job_id']); // Sanitize input to prevent SQL injection

    // Fetch proposals for the specific job from the database
    $query = "SELECT * FROM upwork_proposals WHERE job_id = '$job_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Display the proposals
        echo "<h1>Proposals for Job ID: $job_id</h1>";

        while ($proposal = mysqli_fetch_assoc($result)) {
            echo "<div class='proposal'>";
            echo "<p><strong>Freelancer ID:</strong> " . htmlspecialchars($proposal['freelancer_id']) . "</p>";
            echo "<p><strong>Cover Letter:</strong> " . htmlspecialchars($proposal['cover_letter']) . "</p>";
            echo "<p><strong>Bid Amount:</strong> $" . htmlspecialchars($proposal['bid_amount']) . "</p>";

            // Hire button for the client to hire the freelancer
            echo "<form method='POST' action='hire_freelancer.php'>
                    <input type='hidden' name='proposal_id' value='" . htmlspecialchars($proposal['id']) . "'>
                    <input type='hidden' name='job_id' value='$job_id'>
                    <button type='submit' name='hire'>Hire Freelancer</button>
                  </form>";

            echo "</div>";
        }
    } else {
        echo "<p>No proposals found for this job.</p>";
    }
} else {
    echo "<p>No job ID provided.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Proposals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9; /* Light background for contrast */
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #2C3E50; /* Main heading color */
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em; /* Increased font size */
        }
        .proposal {
            background-color: white; /* White background for proposal cards */
            padding: 20px;
            margin: 10px 0;
            border: 1px solid #ddd; /* Light border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        p {
            margin: 5px 0;
            color: #555; /* Text color for proposal details */
        }
        button {
            background-color: #2C3E50; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px; /* Padding for button */
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px; /* Space above button */
        }
        button:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <!-- The PHP code will output the proposals here -->
</body>
</html>
