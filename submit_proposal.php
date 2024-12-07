<?php
include('db.php');
session_start();

// Check if freelancer is logged in
if ($_SESSION['user_type'] != 'freelancer') {
    header("Location: login.php");
    exit();
}

// Validate the presence of 'job_id' in the URL
if (!isset($_GET['job_id'])) {
    echo "Error: Job ID is missing.";
    exit();
}

// Fetch the job ID from the URL
$job_id = $_GET['job_id'];

// Handle proposal submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $freelancer_id = $_SESSION['user_id'];
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    $bid_amount = $_POST['bid_amount'];

    // Insert the proposal into the database
    $query = "INSERT INTO upwork_proposals (job_id, freelancer_id, cover_letter, bid_amount, status) 
              VALUES ('$job_id', '$freelancer_id', '$cover_letter', '$bid_amount', 'pending')";
    if (mysqli_query($conn, $query)) {
        echo "<p>Proposal Submitted Successfully!</p>";
        // Redirect or give feedback to user
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch job details
$query = "SELECT * FROM upwork_jobs WHERE id = '$job_id'";
$result = mysqli_query($conn, $query);
$job = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Proposal</title>
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
            font-size: 2em; /* Increased font size */
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
        textarea {
            width: 100%; /* Full width for textarea */
            padding: 10px; /* Padding for textarea */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            resize: vertical; /* Allow vertical resizing */
            margin-bottom: 15px; /* Space below textarea */
        }
        input[type="number"] {
            width: 100%; /* Full width for input */
            padding: 10px; /* Padding for input */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 15px; /* Space below input */
        }
        button {
            background-color: #2C3E50; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px; /* Padding for button */
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px; /* Space above button */
            width: 100%; /* Full width for button */
            font-weight: bold; /* Bold button text */
        }
        button:hover {
            background-color: #1A252F; /* Darker shade on hover */
        }
        p {
            text-align: center; /* Center text for feedback messages */
            color: #2C3E50; /* Color for feedback messages */
        }
    </style>
</head>
<body>
    <h1>Submit Proposal for Job: <?php echo htmlspecialchars($job['title']); ?></h1>
    <form method="POST" action="">
        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
        <label for="cover_letter">Cover Letter:</label>
        <textarea name="cover_letter" rows="4" required></textarea>
        <label for="bid_amount">Bid Amount ($):</label>
        <input type="number" name="bid_amount" required>
        <button type="submit">Submit Proposal</button>
    </form>
</body>
</html>
