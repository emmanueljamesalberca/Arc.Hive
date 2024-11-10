<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	<!-- This file is "laundrydbee/index.php" -->



<!-- Category CRUD -->
<div class="box1">
	<h2>Category List</h2>
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">ADD CATEGORY</button> <!-- Changed id -->
    <!-- New Search -->
    <button class="btn btn-info" onclick="toggleCategorySearch()"style="margin-right: 10px;">SEARCH</button>
</div>
<div class="table-container2">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>CategoryID
                    <button class="sort-button" onclick="sortCategoryTable(0, true)">↑</button>
                    <button class="sort-button" onclick="sortCategoryTable(0, false)">↓</button>
                </th>
                <th>Category Name
                    <button class="sort-button" onclick="sortCategoryTable(1, true)">↑</button>
                    <button class="sort-button" onclick="sortCategoryTable(1, false)">↓</button>
                </th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch category data
            $query = "SELECT * FROM `category`";
            $result = mysqli_query($connection, $query);    
            if (!$result) {
                die("Query Failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['CategoryID']; ?></td>
                        <td><?php echo $row['CategoryName']; ?></td>
                        <td><a href="category-update-data.php?CategoryID=<?php echo $row['CategoryID']; ?>" class="btn btn-success">Update</a></td>
                        <td><a href="category-delete-data.php?CategoryID=<?php echo $row['CategoryID']; ?>" class="btn btn-danger" onclick="return confirmDelete()">Delete</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>


<!-- Modal for Adding Category (Only Category Name) -->
<form action="category-insert-data.php" method="post">
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">ADD CATEGORY</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="CategoryName">Category Name</label>
                        <input type="text" name="CategoryName" class="form-control" placeholder="Enter Category Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_category" value="ADD">
                </div>
            </div>
        </div>
    </div>
</form>

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

<script>
    let categorySearchRowAdded = false;

function toggleCategorySearch() {
    const table = document.querySelector(".table thead");

    // Check if the search row has already been added
    if (!categorySearchRowAdded) {
        const searchRow = document.createElement("tr");
        searchRow.classList.add("category-search-row");

        // Define search input fields for each column
        searchRow.innerHTML = `
            <th><input type="text" class="form-control" placeholder="Search CategoryID" oninput="filterCategoryTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Category Name" oninput="filterCategoryTable()"></th>
            <th></th>
            <th></th>
        `;

        // Insert the search row into the table
        table.appendChild(searchRow);
        categorySearchRowAdded = true;
    } else {
        // Toggle the visibility of the search row if it already exists
        const searchRow = document.querySelector(".category-search-row");
        searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
    }
}

// Function to filter the table based on search inputs
function filterCategoryTable() {
    const searchInputs = document.querySelectorAll(".category-search-row input");
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
</script>

<script>
    function sortCategoryTable(columnIndex, ascending) {
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