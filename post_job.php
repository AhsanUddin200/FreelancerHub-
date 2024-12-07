<?php
session_start();
include 'db.php';

// Check if the user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert the job into the database
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $budget = mysqli_real_escape_string($conn, $_POST['budget']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $client_id = $_SESSION['user_id'];

    $query = "INSERT INTO upwork_jobs (title, description, budget, deadline, client_id) VALUES ('$title', '$description', '$budget', '$deadline', '$client_id')";
    
    if (mysqli_query($conn, $query)) {
        // Redirect to client dashboard after successful job posting
        header('Location: client_dashboard.php');
        exit();
    } else {
        echo "<p>Error posting job: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
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
        form {
            background-color: white; /* White background for form */
            padding: 20px;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            max-width: 600px; /* Max width for form */
            margin: 0 auto; /* Center the form */
        }
        label {
            font-weight: bold; /* Bold labels */
            margin-top: 10px;
            display: block; /* Block display for labels */
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Padding for inputs */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 15px; /* Space below inputs */
        }
        textarea {
            resize: vertical; /* Allow vertical resizing */
        }
        input[type="submit"] {
            background-color: #2C3E50; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px; /* Padding for button */
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; /* Full width for button */
            font-weight: bold; /* Bold button text */
        }
        input[type="submit"]:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
        p {
            text-align: center; /* Center text for feedback messages */
            color: #2C3E50; /* Color for feedback messages */
        }
    </style>
</head>
<body>
    <h1>Post a Job</h1>

    <form action="post_job.php" method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Budget:</label>
        <input type="number" name="budget" required>

        <label>Deadline:</label>
        <input type="date" name="deadline" required>

        <input type="submit" value="Post Job">
    </form>
</body>
</html>
