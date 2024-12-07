<?php
include('db.php');
session_start();

// Fetch job categories from the database
$categories = ['Design', 'Writing', 'Development'];  // You can expand this list if needed

// Get jobs from the database (filtered by category, if selected)
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$query = "SELECT * FROM upwork_jobs" . ($category_filter ? " WHERE title LIKE '%$category_filter%'" : "");
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs - Upwork Clone</title>
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
        .filter {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filter button {
            background-color: #2C3E50; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 2px 12px; /* Adjusted padding for smaller buttons */
            margin: 0 80px; /* Set margin to 10px for spacing */
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9em; /* Slightly smaller font size */
            width: 100px; /* Set a fixed width for buttons */
            text-align: center; /* Center text */
            font-weight: bold; 
        }
        .filter button:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
        .job-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .job {
            background-color: white; /* White background for job cards */
            padding: 20px;
            margin: 10px;
            border: 1px solid #ddd; /* Light border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            width: calc(48% - 20px); /* Responsive width */
            transition: transform 0.2s, box-shadow 0.2s; /* Smooth hover effect */
        }
        .job:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
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
            background-color: #2C3E50; /* Submit Proposal button color */
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
    <h1>Browse Jobs</h1>
    
    <!-- Filter by category -->
    <div class="filter">
        <?php foreach ($categories as $category): ?>
            <a href="browse_jobs.php?category=<?php echo $category; ?>">
                <button><?php echo $category; ?></button>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Display job listings -->
    <div class="job-list">
        <?php while ($job = mysqli_fetch_assoc($result)): ?>
            <div class="job">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <p><?php echo htmlspecialchars($job['description']); ?></p>
                <p>Budget: $<?php echo htmlspecialchars($job['budget']); ?></p>
                <p>Deadline: <?php echo htmlspecialchars($job['deadline']); ?></p>
                <a href="submit_proposal.php?job_id=<?php echo $job['id']; ?>">Submit Proposal</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>