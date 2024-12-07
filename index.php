<?php
include('db.php');
session_start();

// Handle Search and Category Filter
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Predefined job categories with icons
$categories = [
    'Development' => 'fa-laptop-code', 
    'Design' => 'fa-paint-brush', 
    'Writing' => 'fa-pen-nib'
];

// Construct the query to fetch jobs with optional search and category filter
$query = "SELECT * FROM upwork_jobs WHERE 1=1";

if ($search_term != '') {
    $query .= " AND (title LIKE '%$search_term%' OR description LIKE '%$search_term%')";
}

if ($category_filter != '') {
    $query .= " AND category = '$category_filter'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceHub - Job Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f4f6f7;
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            line-height: 1.6;
            color: var(--text-color);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .header-buttons button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .header-buttons button:hover {
            background-color: #2980b9;
        }

        .search-section {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input, 
        .search-form select {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-form button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .job-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .job-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-5px);
        }

        .job-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .job-category {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .job-actions {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .budget-badge {
            background-color: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            .header-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container header-content">
            <div class="logo">FreelanceHub</div>
            <div class="header-buttons">
                <button onclick="window.location.href='signup.php?type=client'">
                    <i class="fas fa-user-tie"></i> Client Signup
                </button>
                <button onclick="window.location.href='signup.php?type=freelancer'">
                    <i class="fas fa-user-edit"></i> Freelancer Signup
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="search-section">
            <form class="search-form" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search jobs, skills, or keywords" 
                    value="<?php echo htmlspecialchars($search_term); ?>"
                >
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $category => $icon): ?>
                        <option 
                            value="<?php echo $category; ?>" 
                            <?php echo $category_filter == $category ? 'selected' : ''; ?>
                        >
                            <?php echo $category; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

        <div class="job-list">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($job = mysqli_fetch_assoc($result)): ?>
                    <div class="job-card">
                        <div class="job-card-header">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <div class="budget-badge">
                                <?php echo htmlspecialchars($job['budget']); ?>
                            </div>
                        </div>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <div class="job-category">
                          
                        </div>
                        <div class="job-actions">
                            <span>Deadline: <?php echo htmlspecialchars($job['deadline']); ?></span>
                            <button onclick="viewProposals(<?php echo $job['id']; ?>)">
                                View Proposals
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-jobs">
                    <p>No jobs found. Try a different search or category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function viewProposals(jobId) {
            window.location.href = 'view_proposals.php?job_id=' + jobId;
        }

        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', () => {
            const jobCards = document.querySelectorAll('.job-card');
            jobCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.boxShadow = '0 8px 15px rgba(0,0,0,0.2)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                });
            });
        });
    </script>
</body>
</html>