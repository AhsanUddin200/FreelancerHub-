<?php
include('db.php');
session_start();

// Check if client is logged in
if ($_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

// Fetch the client's jobs
$client_id = $_SESSION['user_id'];
$query = "SELECT * FROM upwork_jobs WHERE client_id = '$client_id'";
$jobs_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="header-buttons">
        <a href="post_job.php">Post Jobs</a>
        <a href="view_proposals.php">View Proposals</a>
        <a href="hire_freelancers.php">Hire Freelancers</a>
    </div>
    <title>Client Dashboard</title>
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
        h2 {
            color: #34495E; /* Subheading color */
            margin-bottom: 20px;
            font-size: 1.8em; /* Increased font size */
        }
        .job {
            background-color: white; /* White background for job cards */
            padding: 20px;
            margin: 10px 0;
            border: 1px solid #ddd; /* Light border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        h3 {
            margin: 0 0 10px;
            color: #2C3E50; /* Job title color */
        }
        p {
            margin: 5px 0;
            color: #555; /* Text color for job details */
        }
        a {
            display: inline-block;
            margin-top: 10px;
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
        .header-buttons {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            margin-bottom: 20px; /* Space below buttons */
        }
        .header-buttons a {
            margin-left: 10px; /* Space between buttons */
            padding: 10px 15px;
            background-color: #2C3E50; /* Button color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .header-buttons a:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <h1>Client Dashboard</h1>
    
    <h2>Your Job Postings</h2>
    <?php while ($job = mysqli_fetch_assoc($jobs_result)): ?>
        <div class="job">
            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
            <p><?php echo htmlspecialchars($job['description']); ?></p>
            <p>Budget: $<?php echo htmlspecialchars($job['budget']); ?> | Deadline: <?php echo htmlspecialchars($job['deadline']); ?></p>
            <a href="view_proposals.php?job_id=<?php echo $job['id']; ?>">View Proposals</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
