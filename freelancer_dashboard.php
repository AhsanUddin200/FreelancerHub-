<?php
include('db.php');
session_start();

// Ensure the freelancer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'freelancer') {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['user_id'];

// Fetch the jobs where the freelancer is hired
$query = "
    SELECT upwork_jobs.title, upwork_jobs.description, upwork_jobs.budget, upwork_jobs.deadline
    FROM upwork_jobs
    INNER JOIN upwork_proposals ON upwork_jobs.id = upwork_proposals.job_id
    WHERE upwork_proposals.freelancer_id = '$freelancer_id' AND upwork_proposals.status = 'hired'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9; /* Light background for contrast */
            padding: 20px;
            margin: 0;
        }
        h1 {
            color: #2C3E50; /* Main heading color */
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em; /* Increased font size */
        }
        h2 {
            color: #34495E; /* Subheading color */
            margin-bottom: 20px;
            font-size: 1.8em; /* Increased font size */
        }
        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .button {
            background-color: #2C3E50; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none; /* Remove underline */
        }
        .button i {
            margin-right: 5px; /* Space between icon and text */
        }
        .job {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #ddd; /* Light border */
            border-radius: 10px; /* More rounded corners */
            background-color: white; /* White background for job cards */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Deeper shadow */
            transition: transform 0.2s, box-shadow 0.2s; /* Smooth hover effect */
        }
        .job:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }
        h3 {
            margin: 0 0 10px;
            color: #2C3E50; /* Job title color */
            font-size: 1.5em; /* Increased font size */
        }
        p {
            margin: 5px 0;
            color: #555; /* Text color for job details */
            line-height: 1.6; /* Improved line height for readability */
        }
        strong {
            color: #2C3E50; /* Strong text color */
        }
        .no-jobs {
            text-align: center;
            color: #888; /* Color for no jobs message */
            font-size: 1.2em; /* Increased font size */
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Freelancer Dashboard</h1>

<div class="button-container">
    <a href="browse_jobs.php" class="button">
        <i class="fas fa-search"></i> Browse Jobs
    </a>
    <a href="submit_proposal.php" class="button">
        <i class="fas fa-paper-plane"></i> Submit Proposals
    </a>
</div>

<h2>Projects You've Been Hired For</h2>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($job = mysqli_fetch_assoc($result)) {
        echo "<div class='job'>";
        echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
        echo "<p>" . htmlspecialchars($job['description']) . "</p>";
        echo "<p><strong>Budget:</strong> $" . htmlspecialchars($job['budget']) . "</p>";
        echo "<p><strong>Deadline:</strong> " . htmlspecialchars($job['deadline']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p class='no-jobs'>You have not been hired for any projects yet.</p>";
}
?>

<!-- Include Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
