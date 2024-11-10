<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	

<!-- Supplier CRUD -->
<div class="box1">
    <h2>SUPPLIER LIST</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplierModal">ADD SUPPLIER</button>
    <button class="btn btn-info" onclick="toggleSupplierSearch()"style="margin-right: 10px;">SEARCH</button>
</div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>
                SupplierID
                <button class="sort-button" onclick="sortSupplierTable(0, true)">↑</button>
                <button class="sort-button" onclick="sortSupplierTable(0, false)">↓</button>
            </th>
            <th>
               Supplier Name
                <button class="sort-button" onclick="sortSupplierTable(1, true)">↑</button>
                <button class="sort-button" onclick="sortSupplierTable(1, false)">↓</button>
            </th>
            <th>
                Contact
                <button class="sort-button" onclick="sortSupplierTable(2, true)">↑</button>
                <button class="sort-button" onclick="sortSupplierTable(2, false)">↓</button>
            </th>
            <th>
                Address
                <button class="sort-button" onclick="sortSupplierTable(3, true)">↑</button>
                <button class="sort-button" onclick="sortSupplierTable(3, false)">↓</button>
            </th>
			<th>Update</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch supplier data
        $query = "SELECT * FROM `supplier`";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['SupplierID']; ?></td>
                    <td><?php echo $row['SupplierName']; ?></td>
                    <td><?php echo $row['Contact']; ?></td>
                    <td><?php echo $row['Address']; ?></td>
					<td><a href="supplier-update-data.php?SupplierID=<?php echo $row['SupplierID']; ?>" class="btn btn-success">Update</a></td>
                    <td><a href="supplier-delete-data.php?SupplierID=<?php echo $row['SupplierID']; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<!-- Modal FOR SUPPLIER LIST-->
<!-- supplier-insert-data.php -->
<form action="supplier-insert-data.php" method="post">
    <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">ADD SUPPLIER</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Supplier Name with Placeholder -->
                    <div class="form-group">
                        <label for="SupplierName">Supplier Name</label>
                        <input type="text" name="SupplierName" class="form-control" placeholder="Enter Supplier Name" required>
                    </div>
                    <!-- Contact with Placeholder -->
                    <div class="form-group">
                        <label for="Contact">Contact</label>
                        <input type="text" name="Contact" class="form-control" placeholder="Enter Contact" required>
                    </div>
                    <!-- Address with Placeholder -->
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" name="Address" class="form-control" placeholder="Enter Address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_supplier" value="ADD">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let supplierSearchRowAdded = false;

function toggleSupplierSearch() {
    const table = document.querySelector(".table thead");

    // Check if the search row has already been added
    if (!supplierSearchRowAdded) {
        const searchRow = document.createElement("tr");
        searchRow.classList.add("supplier-search-row");

        // Define search input fields for each column
        searchRow.innerHTML = `
            <th><input type="text" class="form-control" placeholder="Search SupplierID" oninput="filterSupplierTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Supplier Name" oninput="filterSupplierTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Contact" oninput="filterSupplierTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Address" oninput="filterSupplierTable()"></th>
            <th></th>
            <th></th>
        `;

        // Insert the search row into the table
        table.appendChild(searchRow);
        supplierSearchRowAdded = true;
    } else {
        // Toggle the visibility of the search row if it already exists
        const searchRow = document.querySelector(".supplier-search-row");
        searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
    }
}

// Function to filter the table based on search inputs
function filterSupplierTable() {
    const searchInputs = document.querySelectorAll(".supplier-search-row input");
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

function sortTable(columnIndex, ascending) {
    const table = document.querySelector(".table tbody");
    const rows = Array.from(table.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].textContent.trim();
        const cellB = b.cells[columnIndex].textContent.trim();

        // Determine if we are dealing with numbers or strings
        const valueA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
        const valueB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

        if (ascending) {
            return valueA > valueB ? 1 : -1;
        } else {
            return valueA < valueB ? 1 : -1;
        }
    });

    // Append the sorted rows back into the table body
    rows.forEach(row => table.appendChild(row));
}
</script>

<script>
    function sortSupplierTable(columnIndex, ascending) {
    const table = document.querySelector(".table tbody");
    const rows = Array.from(table.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].textContent.trim();
        const cellB = b.cells[columnIndex].textContent.trim();

        // Determine if we are dealing with numbers or strings
        const valueA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
        const valueB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

        if (ascending) {
            return valueA > valueB ? 1 : -1;
        } else {
            return valueA < valueB ? 1 : -1;
        }
    });

    // Append the sorted rows back into the table body
    rows.forEach(row => table.appendChild(row));
}

</script>



<script src="script.js"></script>
<?php include('footer.php'); ?>
