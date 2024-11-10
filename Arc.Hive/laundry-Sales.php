<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>

<style>
    .job-order-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
    }

    .job-order-input {
        flex: 1; /* Makes the input take up the available space */
    }

    .job-order-btn {
        white-space: nowrap; /* Ensures buttons don't wrap */
    }
</style>

<div class="job-order-actions">
    <input type="text" id="jobOrderSearch" class="form-control job-order-input" placeholder="Enter Job Order ID">
    <button class="btn btn-info job-order-btn" onclick="searchJobOrder()">Search Job Order ID</button>
    <button class="btn btn-danger job-order-btn" onclick="deleteJobOrder()">Delete Job Order</button>
</div>
<br>
<script>
    function searchJobOrder() {
        const jobOrderID = document.getElementById('jobOrderSearch').value.toLowerCase();
        
        // Get salesTable and itemsTable each time the function is called
        const salesTable = document.querySelector('#salesTable tbody');
        const itemsTable = document.querySelector('#itemsTable tbody');
        
        const salesRows = salesTable.querySelectorAll('tr');
        const itemsRows = itemsTable.querySelectorAll('tr');

        // Show all rows if input is empty
        if (jobOrderID === "") {
            salesRows.forEach(row => row.style.display = '');
            itemsRows.forEach(row => row.style.display = '');
            return;
        }

        // Filter salesTable rows by JobOrderID (assume JobOrderID is the first column)
        salesRows.forEach(row => {
            const cellText = row.cells[0].textContent.toLowerCase();
            row.style.display = cellText.includes(jobOrderID) ? '' : 'none';
        });

        // Filter itemsTable rows by JobOrderID (assume JobOrderID is the first column)
        itemsRows.forEach(row => {
            const cellText = row.cells[0].textContent.toLowerCase();
            row.style.display = cellText.includes(jobOrderID) ? '' : 'none';
        });
    }

    function deleteJobOrder() {
        const jobOrderID = document.getElementById("jobOrderSearch").value;
        if (!jobOrderID) {
            alert("Please enter a Job Order ID to delete.");
            return;
        }

        if (confirm(`Are you sure you want to delete Job Order #${jobOrderID} and its items?`)) {
            fetch(`delete-job-order.php?JobOrderID=${jobOrderID}`, {
                method: "GET"
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Reload page to refresh data
            })
            .catch(error => console.error("Error:", error));
        }
    }
</script>


<!-- Job Order List -->
<div class="box1">
    <h2>Job Order List</h2>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobOrderModal">Add Job Order</button> -->
    <button class="btn btn-info" onclick="toggleSearchRow('salesTable')">SEARCH</button>
</div>

<div class="table-container">
    <table id="salesTable" class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>JobOrderID
                    <button class="sort-button" onclick="sortTable('salesTable', 0, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 0, false)">↓</button>
                </th>
                <th>ServiceID
                    <button class="sort-button" onclick="sortTable('salesTable', 1, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 1, false)">↓</button>
                </th>
                <th>Date
                    <button class="sort-button" onclick="sortTable('salesTable', 2, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 2, false)">↓</button>
                </th>
                <th>CustomerID
                    <button class="sort-button" onclick="sortTable('salesTable', 3, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 3, false)">↓</button>
                </th>
                <th>Weight
                    <button class="sort-button" onclick="sortTable('salesTable', 4, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 4, false)">↓</button>
                </th>
                <th>Total
                    <button class="sort-button" onclick="sortTable('salesTable', 5, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('salesTable', 5, false)">↓</button>
                </th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch job order data sorted by JobOrderID in descending order
            $query = "SELECT `job-order`.JobOrderID, `job-order`.ServiceID, `job-order`.Date, `job-order`.CustomerID, 
                            `job-order`.Weight, `job-order`.Total, 
                            service.ServiceType, customer.Name 
                    FROM `job-order` 
                    JOIN service ON `job-order`.ServiceID = service.ServiceID 
                    JOIN customer ON `job-order`.CustomerID = customer.CustomerID
                    ORDER BY `job-order`.JobOrderID DESC";
            $result = mysqli_query($connection, $query);

            if (!$result) {
                die("Query Failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['JobOrderID']; ?></td>
                        <td><?php echo $row['ServiceID'] . ' - ' . $row['ServiceType']; ?></td>
                        <td><?php echo $row['Date']; ?></td>
                        <td><?php echo $row['CustomerID'] . ' - ' . $row['Name']; ?></td>
                        <td><?php echo $row['Weight']; ?></td>
                        <td><?php echo $row['Total'] . ' ₱'; ?></td>
                        <td><a href="job-order-delete-data.php?JobOrderID=<?php echo $row['JobOrderID']; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<!-- Job Order Items List -->
<div class="box1">
    <h2>Job Order Items</h2>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobOrderItemModal">Add Job Order Item</button> -->
    <button class="btn btn-info" onclick="toggleSearchRow('itemsTable')">SEARCH</button>
</div>

<div class="table-container">
    <table id="itemsTable" class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>JobOrderID
                    <button class="sort-button" onclick="sortTable('itemsTable', 0, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('itemsTable', 0, false)">↓</button>
                </th>
                <th>ItemID
                    <button class="sort-button" onclick="sortTable('itemsTable', 1, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('itemsTable', 1, false)">↓</button>
                </th>
                <th>ItemQuantity
                    <button class="sort-button" onclick="sortTable('itemsTable', 2, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('itemsTable', 2, false)">↓</button>
                </th>
                <th>Subtotal
                    <button class="sort-button" onclick="sortTable('itemsTable', 3, true)">↑</button>
                    <button class="sort-button" onclick="sortTable('itemsTable', 3, false)">↓</button>
                </th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch job order items data sorted by JobOrderID in descending order
            $query = "SELECT `job-order-items`.JobOrderID, `job-order-items`.ItemID, `job-order-items`.ItemQuantity, `job-order-items`.Subtotal, 
                            item.ItemName 
                    FROM `job-order-items` 
                    JOIN item ON `job-order-items`.ItemID = item.ItemID
                    ORDER BY `job-order-items`.JobOrderID DESC";
            $result = mysqli_query($connection, $query);


            if (!$result) {
                die("Query Failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['JobOrderID']; ?></td>
                        <td><?php echo $row['ItemID'] . ' - ' . $row['ItemName']; ?></td>
                        <td><?php echo $row['ItemQuantity']; ?></td>
                        <td><?php echo $row['Subtotal'] . ' ₱'; ?></td>
                        <td><a href="job-order-item-delete-data.php?JobOrderID=<?php echo $row['JobOrderID']; ?>&ItemID=<?php echo $row['ItemID']; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    // Toggle search row for a table by ID
    function toggleSearchRow(tableId) {
        const table = document.getElementById(tableId).querySelector("thead");
        let searchRow = table.querySelector(".search-row");

        if (!searchRow) {
            searchRow = document.createElement("tr");
            searchRow.classList.add("search-row");

            // Generate search inputs for all columns except the last two (Update and Delete)
            const columnCount = table.querySelectorAll("th").length - 2;
            searchRow.innerHTML = Array.from({ length: columnCount }).map(() => 
                `<th><input type="text" class="form-control" oninput="filterTable('${tableId}')" /></th>`
            ).join("");

            // Add empty <th> elements for the Update and Delete columns
            searchRow.innerHTML += "<th></th><th></th>";

            table.appendChild(searchRow);
        } else {
            searchRow.style.display = searchRow.style.display === "none" ? "" : "none";
        }
    }

    // Filter table rows based on search inputs
    function filterTable(tableId) {
        const table = document.getElementById(tableId);
        const searchInputs = table.querySelectorAll(".search-row input");
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let visible = true;
            searchInputs.forEach((input, index) => {
                const cellText = row.cells[index].textContent.toLowerCase();
                const searchText = input.value.toLowerCase();
                if (searchText && !cellText.includes(searchText)) {
                    visible = false;
                }
            });
            row.style.display = visible ? "" : "none";
        });
    }

    // Sort table columns
    function sortTable(tableId, columnIndex, ascending) {
        const table = document.getElementById(tableId);
        const rows = Array.from(table.querySelectorAll("tbody tr"));

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim();
            const cellB = b.cells[columnIndex].textContent.trim();

            const valueA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
            const valueB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

            return ascending ? (valueA > valueB ? 1 : -1) : (valueA < valueB ? 1 : -1);
        });

        rows.forEach(row => table.querySelector("tbody").appendChild(row));
    }
</script>

<script src="script.js"></script>
<?php include('footer.php'); ?>