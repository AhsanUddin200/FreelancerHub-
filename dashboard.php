<?php
include('db.php');
session_start();

// Ensure the client is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

// Fetch the jobs posted by the client
$query = "SELECT * FROM upwork_jobs WHERE client_id = '$client_id'";
$result = mysqli_query($conn, $query);

// Handle hiring freelancer
if (isset($_POST['hire_freelancer_id'])) {
    $freelancer_id = $_POST['hire_freelancer_id'];
    $job_id = $_POST['job_id'];

    // Update the proposal status to 'hired'
    $hire_query = "UPDATE upwork_proposals SET status = 'hired' WHERE job_id = '$job_id' AND freelancer_id = '$freelancer_id'";
    if (mysqli_query($conn, $hire_query)) {
        // Optionally, you can also update the job's status to "hired" if needed.
        $update_job_status = "UPDATE upwork_jobs SET status = 'hired' WHERE id = '$job_id'";
        if (mysqli_query($conn, $update_job_status)) {
            echo "Freelancer successfully hired!";
        }
    } else {
        echo "Error in hiring freelancer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .job-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .job {
            background-color: white;
            padding: 15px;
            width: 45%;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .proposal {
            margin-top: 10px;
            background-color: #f0f0f0;
            padding: 10px;
        }
        .hire-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Your Job Postings</h1>

<div class="job-list">
    <?php while ($job = mysqli_fetch_assoc($result)): ?>
        <div class="job">
            <h3><?php echo $job['title']; ?></h3>
            <p><strong>Description:</strong> <?php echo $job['description']; ?></p>
            <p><strong>Budget:</strong> $<?php echo $job['budget']; ?></p>
            <p><strong>Deadline:</strong> <?php echo $job['deadline']; ?></p>

            <!-- Fetch proposals for this job -->
            <?php
            $job_id = $job['id'];
            $proposal_query = "SELECT * FROM upwork_proposals WHERE job_id = '$job_id' AND status = 'pending'";
            $proposal_result = mysqli_query($conn, $proposal_query);
            ?>
            
            <h4>Proposals:</h4>
            <?php while ($proposal = mysqli_fetch_assoc($proposal_result)): ?>
                <div class="proposal">
                    <p><strong>Freelancer ID:</strong> <?php echo $proposal['freelancer_id']; ?></p>
                    <p><strong>Cover Letter:</strong> <?php echo $proposal['cover_letter']; ?></p>
                    <p><strong>Bid Amount:</strong> $<?php echo $proposal['bid_amount']; ?></p>

                    <!-- Button to hire freelancer -->
                    <form method="POST">
                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                        <input type="hidden" name="hire_freelancer_id" value="<?php echo $proposal['freelancer_id']; ?>">
                        <input type="submit" class="hire-button" value="Hire Freelancer">
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
