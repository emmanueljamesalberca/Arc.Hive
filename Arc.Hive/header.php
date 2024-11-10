<?php
// Start output buffering to handle any header modifications
ob_start();

// Start a PHP session (should be at the very beginning of your application)
session_start();

// Include the database connection file


// Any other PHP logic that needs to run before HTML output goes here

// Check if there are messages to be displayed (success, error, etc.)
// This logic allows message handling while preventing premature output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head Section -->
    <meta charset="UTF-8">
    <title>Home Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles-item.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; font-family: Arial, sans-serif; background-color: #f4f6f9;">
    <!-- Header Section -->
    <header style="background-color: #333; color: #fff; padding: 1em; text-align: center; font-size: 1.5em; font-weight: bold;">
        Laundry Busy Bee
    </header>

    <!-- Main Layout with Sidebar and Top-Aligned Content -->
    <div style="display: flex; flex: 1;">
        <!-- Sidebar for Navigation -->
        <div class="sidebar">
            <h2>Navigation</h2>
            <a href="laundry-items.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-items.php' ? 'active' : ''; ?>">Inventory Management</a>
            <a href="laundry-category.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-category.php' ? 'active' : ''; ?>">View Item Category</a>
            <a href="laundry-supplier.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-supplier.php' ? 'active' : ''; ?>">View Suppliers</a>
            <a href="laundry-record-invoice.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-record-invoice.php' ? 'active' : ''; ?>">Record Invoice Purchases</a>
            <a href="laundry-supply_details.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-supply_details.php' ? 'active' : ''; ?>">View Supply Details</a>
            <a href="laundry-Services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-Services.php' ? 'active' : ''; ?>">View Services</a>
            <a href="laundry-Customer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-Customer.php' ? 'active' : ''; ?>">View Customer</a>
            <a href="laundry-job-order.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-job-order.php' ? 'active' : ''; ?>">Create Job Order and Job Order Items</a>
            <a href="laundry-Sales.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laundry-Sales.php' ? 'active' : ''; ?>">View Sales</a>
        </div>

        <style>
            .sidebar {
                background-color: #333;
                width: 300px;
                padding: 0px;
                color: white;
            }

            .sidebar h2 {
                color: white;
                text-align: center;
                margin-bottom: 30px;
            }

            .sidebar a {
                color: white;
                display: block;
                padding: 10px;
                text-decoration: none;
                border-bottom: 3px solid white; /* White line between each link */
                transition: background-color 0.3s, color 0.3s; /* Smooth transition */
            }

            .sidebar a:hover {
                color: yellow;
                background-color: #444; /* Optional: darkens the background on hover */
            }

            .sidebar a.active {
                background-color: #e9ecef; /* Same gray as the center background */
                color: #333;
            }

            .table-container {
                max-height: 400px; /* Set your preferred height */
                overflow-y: auto;
                margin-top: 50px; /* Space between tables */
                margin-bottom: 20px; /* Space between tables */
                border: 1px solid #ddd; /* Optional: border around the container */
                border-radius: 5px; /* Optional: rounded corners */
            }

            .table-container2 {
                max-height: 1000px; /* Set your preferred height */
                overflow-y: auto;
                margin-top: 50px; /* Space between tables */
                margin-bottom: 20px; /* Space between tables */
                border: 1px solid #ddd; /* Optional: border around the container */
                border-radius: 5px; /* Optional: rounded corners */
            }
        </style>

        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this item?");
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Main Content Area with Top Alignment -->
        <div style="margin-left: 100px; display: flex; flex-direction: column; align-items: center; width: calc(100% - 280px); padding: 0px;">
            <div style="width: 100%; max-width: 1300px; margin-top: 20px;">
                <h2 class="text-center" style="font-weight: bold; color: #333; margin-bottom: 20px;" id="liveDateTime"></h2>

                <script>
                    function updateDateTime() {
                        const options = {
                            timeZone: "Asia/Manila",
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "numeric",
                            minute: "numeric",
                            second: "numeric",
                            hour12: true
                        };
                        const date = new Date().toLocaleString("en-US", options);

                        // Format the date to replace the comma with an empty space for the desired format
                        const formattedDateTime = date.replace(",", "");

                        document.getElementById("liveDateTime").innerText = formattedDateTime;
                    }

                    setInterval(updateDateTime, 1000); // Update every second
                    updateDateTime(); // Initial call to set the time immediately
                </script>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['delete_msg'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_GET['delete_msg']; ?>
                    </div>
                <?php endif; ?>

                <!-- Content Box for Items Table -->
                <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <!-- Main content continues -->