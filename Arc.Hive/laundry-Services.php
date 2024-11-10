<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	

<!-- Service CRUD -->
<div class="box1">
    <h2>Service List</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal">Add Service</button>
    <!-- New Search -->
    <button class="btn btn-info" onclick="toggleServiceSearch()"style="margin-right: 10px;">SEARCH</button>
</div>
<div class="table-container2">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>ServiceID
                    <button class="sort-button" onclick="sortServiceTable(0, true)">↑</button>
                    <button class="sort-button" onclick="sortServiceTable(0, false)">↓</button>
                </th>
                <th>Service Type
                    <button class="sort-button" onclick="sortServiceTable(1, true)">↑</button>
                    <button class="sort-button" onclick="sortServiceTable(1, false)">↓</button>
                </th>
                <th>Price
                    <button class="sort-button" onclick="sortServiceTable(2, true)">↑</button>
                    <button class="sort-button" onclick="sortServiceTable(2, false)">↓</button>
                </th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch service data
            $query = "SELECT * FROM `service`";
            $result = mysqli_query($connection, $query);
            
            if (!$result) {
                die("Query Failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['ServiceID']; ?></td>
                        <td><?php echo $row['ServiceType']; ?></td>
                        <td><?php echo number_format($row['Price'], 2) . ' ₱'; ?></td>
                        <td><a href="service-update-data.php?ServiceID=<?php echo $row['ServiceID']; ?>" class="btn btn-success">Update</a></td>
                        <td><a href="service-delete-data.php?ServiceID=<?php echo $row['ServiceID']; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<!-- Modal for Adding Service -->
<form action="service-insert-data.php" method="post">
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalLabel">Add Service</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ServiceType">Service Type</label>
                        <input type="text" name="ServiceType" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Price">Price</label>
                        <input type="text" name="Price" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_service" value="Add Service">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let serviceSearchRowAdded = false;

    function toggleServiceSearch() {
        const table = document.querySelector(".table thead");

        if (!serviceSearchRowAdded) {
            const searchRow = document.createElement("tr");
            searchRow.classList.add("service-search-row");

            searchRow.innerHTML = `
                <th><input type="text" class="form-control" placeholder="Search ServiceID" oninput="filterServiceTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Service Type" oninput="filterServiceTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Price" oninput="filterServiceTable()"></th>
                <th></th>
                <th></th>
            `;

            table.appendChild(searchRow);
            serviceSearchRowAdded = true;
        } else {
            const searchRow = document.querySelector(".service-search-row");
            searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
        }
    }

    function filterServiceTable() {
        const searchInputs = document.querySelectorAll(".service-search-row input");
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

    function sortServiceTable(columnIndex, ascending) {
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
