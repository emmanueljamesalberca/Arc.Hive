<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	<!-- This file is "laundrydbee/index.php" -->
    

<!-- Customer CRUD -->
<div class="box1">
    <h2>Customer List</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">Add Customer</button>
    <!-- New Search -->
    <button class="btn btn-info" onclick="toggleCustomerSearch()"style="margin-right: 10px;">SEARCH</button>
</div>

<div class="table-container2">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>CustomerID
                    <button class="sort-button" onclick="sortCustomerTable(0, true)">↑</button>
                    <button class="sort-button" onclick="sortCustomerTable(0, false)">↓</button>
                </th>
                <th>Name
                    <button class="sort-button" onclick="sortCustomerTable(1, true)">↑</button>
                    <button class="sort-button" onclick="sortCustomerTable(1, false)">↓</button>
                </th>
                <th>Email
                    <button class="sort-button" onclick="sortCustomerTable(2, true)">↑</button>
                    <button class="sort-button" onclick="sortCustomerTable(2, false)">↓</button>
                </th>
                <th>Contact
                    <button class="sort-button" onclick="sortCustomerTable(3, true)">↑</button>
                    <button class="sort-button" onclick="sortCustomerTable(3, false)">↓</button>
                </th>
                <th>Address</th>
                <th>Gender</th>
                <th>Age
                    <button class="sort-button" onclick="sortCustomerTable(6, true)">↑</button>
                    <button class="sort-button" onclick="sortCustomerTable(6, false)">↓</button>
                </th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch customer data sorted by Name in ascending order
            $query = "SELECT * FROM `customer` ORDER BY Name ASC";
            $result = mysqli_query($connection, $query);

            if (!$result) {
                die("Query Failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo $row['Name']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Contact']; ?></td>
                        <td><?php echo $row['Address']; ?></td>
                        <td><?php echo $row['Gender']; ?></td>
                        <td><?php echo $row['Age']; ?></td>
                        <td><a href="customer-update-data.php?CustomerID=<?php echo $row['CustomerID']; ?>" class="btn btn-success">Update</a></td>
                        <td><a href="customer-delete-data.php?CustomerID=<?php echo $row['CustomerID']; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal for Adding Customer -->
<form action="customer-insert-data.php" method="post">
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="text" name="Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" name="Email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Contact">Contact</label>
                        <input type="text" name="Contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" name="Address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Gender">Gender</label>
                        <select name="Gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Age">Age</label>
                        <input type="number" name="Age" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_customer" value="Add">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let customerSearchRowAdded = false;

    function toggleCustomerSearch() {
        const table = document.querySelector(".table thead");

        if (!customerSearchRowAdded) {
            const searchRow = document.createElement("tr");
            searchRow.classList.add("customer-search-row");

            searchRow.innerHTML = `
                <th><input type="text" class="form-control" placeholder="Search CustomerID" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Name" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Email" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Contact" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Address" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Gender" oninput="filterCustomerTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Age" oninput="filterCustomerTable()"></th>
                <th></th>
                <th></th>
            `;

            table.appendChild(searchRow);
            customerSearchRowAdded = true;
        } else {
            const searchRow = document.querySelector(".customer-search-row");
            searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
        }
    }

    function filterCustomerTable() {
        const searchInputs = document.querySelectorAll(".customer-search-row input");
        const tableRows = document.querySelectorAll(".table tbody tr");

        tableRows.forEach(row => {
            let showRow = true;
            searchInputs.forEach((input, index) => {
                const cell = row.cells[index];
                if (cell && input.value) {
                    const cellText = cell.textContent.toLowerCase();
                    const searchText = input.value.toLowerCase();
                    if (!cellText.includes(searchText)) {
                        showRow = false;
                    }
                }
            });
            row.style.display = showRow ? "" : "none";
        });
    }

    function sortCustomerTable(columnIndex, ascending) {
        const table = document.querySelector(".table tbody");
        const rows = Array.from(table.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim();
            const cellB = b.cells[columnIndex].textContent.trim();

            const valueA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
            const valueB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

            if (ascending) {
                return valueA > valueB ? 1 : -1;
            } else {
                return valueA < valueB ? 1 : -1;
            }
        });

        rows.forEach(row => table.appendChild(row));
    }
</script>

<!-- NEW -->
<!-- Fetch available ItemID and ItemName before Modal ITEM -->
<?php
$items = []; // Initialize an array for storing fetched items

$query = "SELECT `ItemID`, `ItemName` FROM `item`"; // Query to fetch ItemID and ItemName
$result = mysqli_query($connection, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($connection));
} else {
    while($row = mysqli_fetch_assoc($result)){
        $items[] = $row; // Store each row in the $items array
    }
}
?>


<script src="script.js"></script>
<?php include('footer.php'); ?>
