<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	
<!-- Supply CRUD -->
<?php
// Fetch supply data
$supplyQuery = "SELECT supply.SupplierID, supply.InvoiceID, supply.Date, supply.UserID, supply.Total, supplier.SupplierName 
                FROM supply 
                LEFT JOIN supplier ON supply.SupplierID = supplier.SupplierID";


$supplyResult = mysqli_query($connection, $supplyQuery);

if (!$supplyResult) {
    die("Query Failed: " . mysqli_error($connection));
}
?>


<style>
    .invoice-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
    }

    .invoice-input {
        flex: 1; /* Makes the input take up the available space */
    }

    .invoice-btn {
        white-space: nowrap; /* Ensures buttons don't wrap */
    }
</style>
    <div class="invoice-actions">
        <input type="text" id="invoiceID" class="form-control invoice-input" placeholder="Enter Invoice ID">
        <button class="btn btn-info invoice-btn" onclick="searchInvoice()">Search Invoice ID</button>
        <button class="btn btn-danger invoice-btn" onclick="deleteInvoice()">Delete Invoice</button>
    </div>
    <script>
    function searchInvoice() {
    const invoiceID = document.getElementById('invoiceID').value.toLowerCase();
    const supplyRows = document.querySelectorAll('#supplyListTable tbody tr');
    const supplyDetailsRows = document.querySelectorAll('#supplyDetailsTable tbody tr');

    supplyRows.forEach(row => {
        const cellText = row.cells[1].textContent.toLowerCase(); // Assuming InvoiceID is the second column
        row.style.display = cellText.includes(invoiceID) ? '' : 'none';
    });

    supplyDetailsRows.forEach(row => {
        const cellText = row.cells[0].textContent.toLowerCase(); // Assuming InvoiceID is the first column
        row.style.display = cellText.includes(invoiceID) ? '' : 'none';
    });
}

function deleteInvoice() {
    const invoiceID = document.getElementById('invoiceID').value;
    if (!invoiceID) {
        alert('Please enter an Invoice ID to delete.');
        return;
    }

    if (confirm(`Are you sure you want to delete Invoice ID ${invoiceID} from both tables?`)) {
        window.location.href = `delete-invoice.php?InvoiceID=${invoiceID}`;
    }
}
</script>

<div class="box1">
    <h2>SUPPLY LIST</h2>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplyModal"style="margin-left: 10px;">Add Supply</button> -->
    <button class="btn btn-info" onclick="toggleSupplySearch()" style="margin-left: 10px;">SEARCH</button>



    <table id="supplyListTable" class="table table-hover table-bordered table-striped">
        <thead>
        <tr>
            <th>InvoiceID 
                <button class="sort-button" onclick="sortSupplyListTable(0, true)">↑</button>
                <button class="sort-button" onclick="sortSupplyListTable(0, false)">↓</button>
            </th>
            <th>ItemID 
                <button class="sort-button" onclick="sortSupplyListTable(1, true)">↑</button>
                <button class="sort-button" onclick="sortSupplyListTable(1, false)">↓</button>
            </th>
            <th>Quantity 
                <button class="sort-button" onclick="sortSupplyListTable(2, true)">↑</button>
                <button class="sort-button" onclick="sortSupplyListTable(2, false)">↓</button>
            </th>
            <th>Base-Price 
                <button class="sort-button" onclick="sortSupplyListTable(3, true)">↑</button>
                <button class="sort-button" onclick="sortSupplyListTable(3, false)">↓</button>
            </th>
            <th>Subtotal 
                <button class="sort-button" onclick="sortSupplyListTable(4, true)">↑</button>
                <button class="sort-button" onclick="sortSupplyListTable(4, false)">↓</button>
            </th>
            <th>Delete</th>
        </tr>
        <tr id="supplyDetailsSearchRow" style="display: none;">
            <th><input type="text" class="form-control" placeholder="Search InvoiceID" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search ItemID" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Quantity" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Base-Price" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Subtotal" oninput="filterSupplyDetailsTable()"></th>
            <th></th>
        </tr>
    </thead>
        <tbody>
            <?php while ($supplyRow = mysqli_fetch_assoc($supplyResult)) { ?>
                <tr>
                    <td><?php echo $supplyRow['SupplierID'] . ' - ' . $supplyRow['SupplierName']; ?></td>
                    <td><?php echo $supplyRow['InvoiceID']; ?></td>
                    <td><?php echo $supplyRow['Date']; ?></td>
                    <td><?php echo $supplyRow['UserID']; ?></td>
                    <td><?php echo number_format($supplyRow['Total'], 2) . ' ₱'; ?></td>
                    <td><a href="supply-delete.php?SupplierID=<?php echo $supplyRow['SupplierID']; ?>&InvoiceID=<?php echo $supplyRow['InvoiceID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supply?')">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Add Supply Modal -->
<div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="supply-insert.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplyModalLabel">Add New Supply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="InvoiceID" class="form-label">Invoice ID</label>
                        <input type="text" class="form-control" id="InvoiceID" name="InvoiceID" required>
                    </div>
                    <div class="mb-3">
                        <label for="Date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="Date" name="Date" required>
                    </div>
                    <div class="mb-3">
                        <label for="UserID" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="UserID" name="UserID" required>
                    </div>
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="number" step="0.01" class="form-control" id="Total" name="Total" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Supply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Edit Supply Modal for each entry -->
    <?php mysqli_data_seek($supplyResult, 0); // Reset result pointer ?>
    <?php while ($supplyRow = mysqli_fetch_assoc($supplyResult)) { ?>
        <div class="modal fade" id="editSupplyModal<?php echo $supplyRow['SupplierID']; ?>" tabindex="-1" aria-labelledby="editSupplyModalLabel<?php echo $supplyRow['SupplierID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="supply-update.php" method="post">
                        <input type="hidden" name="SupplierID" value="<?php echo $supplyRow['SupplierID']; ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSupplyModalLabel<?php echo $supplyRow['SupplierID']; ?>">Edit Supply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="InvoiceID" class="form-label">Invoice ID</label>
                                <input type="text" class="form-control" id="InvoiceID" name="InvoiceID" value="<?php echo $supplyRow['InvoiceID']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="Date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="Date" name="Date" value="<?php echo $supplyRow['Date']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="UserID" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="UserID" name="UserID" value="<?php echo $supplyRow['UserID']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="Total" class="form-label">Total</label>
                                <input type="number" step="0.01" class="form-control" id="Total" name="Total" value="<?php echo $supplyRow['Total']; ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Supply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

<script>
// Toggle search row visibility
function toggleSupplySearch() {
    const searchRow = document.getElementById('supplySearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

// Filter function for supply table
function filterSupplyTable() {
    const inputFields = document.querySelectorAll('#supplySearchRow input');
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        let isVisible = true;
        inputFields.forEach((input, index) => {
            const cellText = row.cells[index].textContent.toLowerCase();
            const searchText = input.value.toLowerCase();
            if (searchText && !cellText.includes(searchText)) {
                isVisible = false;
            }
        });
        row.style.display = isVisible ? '' : 'none';
    });
}
</script>


<!-- Supply Details List Table -->
<div class="box1">
    <h2>SUPPLY DETAILS LIST</h2>
    <button class="btn btn-info" onclick="toggleSupplyDetailsSearch()" style="margin-right: 10px;">SEARCH</button>
    <table id="supplyDetailsTable" class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>InvoiceID 
                    <button class="sort-button" onclick="sortSupplyDetailsTable(0, true)">↑</button>
                    <button class="sort-button" onclick="sortSupplyDetailsTable(0, false)">↓</button>
                </th>
                <th>ItemID 
                    <button class="sort-button" onclick="sortSupplyDetailsTable(1, true)">↑</button>
                    <button class="sort-button" onclick="sortSupplyDetailsTable(1, false)">↓</button>
                </th>
                <th>Quantity 
                    <button class="sort-button" onclick="sortSupplyDetailsTable(2, true)">↑</button>
                    <button class="sort-button" onclick="sortSupplyDetailsTable(2, false)">↓</button>
                </th>
                <th>Base-Price 
                    <button class="sort-button" onclick="sortSupplyDetailsTable(3, true)">↑</button>
                    <button class="sort-button" onclick="sortSupplyDetailsTable(3, false)">↓</button>
                </th>
                <th>Subtotal 
                    <button class="sort-button" onclick="sortSupplyDetailsTable(4, true)">↑</button>
                    <button class="sort-button" onclick="sortSupplyDetailsTable(4, false)">↓</button>
                </th>
                <th>Delete</th>
            </tr>
            <tr id="supplyDetailsSearchRow" style="display: none;">
                <th><input type="text" class="form-control" placeholder="Search InvoiceID" oninput="filterSupplyDetailsTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search ItemID" oninput="filterSupplyDetailsTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Quantity" oninput="filterSupplyDetailsTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Base-Price" oninput="filterSupplyDetailsTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Subtotal" oninput="filterSupplyDetailsTable()"></th>
                <th></th>
            </tr>
        </thead>
    </thead>
    <tbody>
        <?php
        // Fetch supply details data
        $query = "SELECT `supply-details`.InvoiceID, `supply-details`.ItemID, `supply-details`.Quantity, 
                 `supply-details`.`Base-Price`, (`supply-details`.Quantity * `supply-details`.`Base-Price`) AS Subtotal, 
                 item.ItemName 
          FROM `supply-details` 
          JOIN `item` ON `supply-details`.ItemID = item.ItemID";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['InvoiceID']; ?></td>
                    <td><?php echo $row['ItemID'] . ' - ' . $row['ItemName']; ?></td> <!-- Display ItemID - ItemName -->
                    <td><?php echo $row['Quantity']; ?></td>
                    <td><?php echo $row['Base-Price'] . ' ₱'; ?></td> <!-- Use backticks for Base-Price -->
                    <td><?php echo number_format($row['Subtotal'], 2) . ' ₱'; ?></td>
                    <td><a href="supply-details-delete-data.php?InvoiceID=<?php echo $row['InvoiceID']; ?>&ItemID=<?php echo $row['ItemID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item from the invoice?');">Delete</a>
                    </td>
                    
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>


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

<!-- Modal FOR SUPPLY DETAILS -->
<form action="supply-details-insert-data.php" method="post">
    <div class="modal fade" id="supplyDetailsModal" tabindex="-1" role="dialog" aria-labelledby="supplyDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplyDetailsModalLabel">ADD SUPPLY DETAILS</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
					<div class="form-group">
                        <label for="InvoiceID">Invoice ID</label>
                        <input type="number" name="InvoiceID" class="form-control" required>
                    </div>
					<div class="form-group">
                        <label for="ItemID">Item ID</label>
                        <select name="ItemID" class="form-control" required>
                            <option value="">Select an Item</option> <!-- Placeholder option -->
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['ItemID']; ?>">
                                    <?php echo $item['ItemID'] . ' - ' . $item['ItemName']; ?> <!-- Display both ItemID and ItemName -->
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-control" oninput="calculateSubtotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="Base-Price">Base Price</label>
                        <input type="text" name="Base-Price" id="BasePrice" class="form-control" oninput="calculateSubtotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="Subtotal">Subtotal</label>
                        <input type="text" name="Subtotal" id="Subtotal" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_supply_details" value="ADD">
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function sortSupplyListTable(columnIndex, ascending) {
    const table = document.getElementById('supplyListTable');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

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

    rows.forEach(row => table.querySelector('tbody').appendChild(row));
}
// Toggle search row visibility for Supply List
function toggleSupplySearch() {
    const searchRow = document.getElementById('supplySearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

// Filter function for supply list table
function filterSupplyListTable() {
    const inputFields = document.querySelectorAll('#supplySearchRow input');
    const rows = document.querySelectorAll('#supplyListTable tbody tr');

    rows.forEach(row => {
        let isVisible = true;
        inputFields.forEach((input, index) => {
            const cellText = row.cells[index].textContent.toLowerCase();
            const searchText = input.value.toLowerCase();
            if (searchText && !cellText.includes(searchText)) {
                isVisible = false;
            }
        });
        row.style.display = isVisible ? '' : 'none';
    });
}

// Toggle search row visibility for Supply Details List
function toggleSupplyDetailsSearch() {
    const searchRow = document.getElementById('supplyDetailsSearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

// Filter function for supply details table
function filterSupplyDetailsTable() {
    const inputFields = document.querySelectorAll('#supplyDetailsSearchRow input');
    const rows = document.querySelectorAll('#supplyDetailsTable tbody tr');

    rows.forEach(row => {
        let isVisible = true;
        inputFields.forEach((input, index) => {
            const cellText = row.cells[index].textContent.toLowerCase();
            const searchText = input.value.toLowerCase();
            if (searchText && !cellText.includes(searchText)) {
                isVisible = false;
            }
        });
        row.style.display = isVisible ? '' : 'none';
    });
}

// Sort table function for Supply Details List
function sortSupplyDetailsTable(columnIndex, ascending) {
    const table = document.getElementById('supplyDetailsTable');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

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

    rows.forEach(row => table.querySelector('tbody').appendChild(row));
}
</script>


<script src="script.js"></script>
<?php include('footer.php'); ?>
