<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="container" style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
    <h2>Invoice Recorded Successfully!</h2>
    <p>Goodjob!</p>
    <a href="laundry-record-invoice.php" class="btn btn-primary">Record Another Invoice</a>
</div>
<?php else: ?>


<div class="container" style="max-width: 600px; margin: 20px auto; padding: 20px; background-color:#ffff0080; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Record Invoice Purchases</h2>
    
    <form action="invoice-record-submit.php" method="post">
        <!-- Invoice ID Input -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="invoiceID" style="font-weight: bold;">Enter Invoice ID:</label>
            <input type="text" name="invoiceID" id="invoiceID" class="form-control" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>

        <!-- Supplier Selection Dropdown -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="supplier" style="font-weight: bold;">Enter Supplier:</label>
            <select name="supplier" id="supplier" class="form-control" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                <option value="" disabled selected>Select Supplier</option>
                <?php
                $supplierQuery = "SELECT SupplierID, SupplierName FROM supplier";
                $supplierResult = mysqli_query($connection, $supplierQuery);

                while ($supplierRow = mysqli_fetch_assoc($supplierResult)) {
                    echo "<option value='{$supplierRow['SupplierID']}'>{$supplierRow['SupplierID']} - {$supplierRow['SupplierName']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Items Section -->
        <div id="itemsContainer">
            <h4 style="font-weight: bold; margin-bottom: 10px;">Add Items:</h4>
            <div class="item-group" style="border-bottom: 10px solid Orange; padding-bottom: 15px; margin-bottom: 15px;">
                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="item" style="font-weight: bold;">Item:</label>
                    <select name="items[]" class="form-control item-select" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="" disabled selected>Select Item</option>
                        <?php
                        // Fetch items once, store in a variable, and reuse
                        $itemOptions = '';
                        $itemQuery = "SELECT ItemID, ItemName FROM item";
                        $itemResult = mysqli_query($connection, $itemQuery);
                        while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                            $itemOptions .= "<option value='{$itemRow['ItemID']}'>{$itemRow['ItemID']} - {$itemRow['ItemName']}</option>";
                        }
                        echo $itemOptions;
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="itemQty" style="font-weight: bold;">Item Qty:</label>
                    <input type="number" name="itemQty[]" class="form-control item-qty" min="1" value="1" required onchange="calculateSubtotal(this)" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="itemBasePrice" style="font-weight: bold;">Item Base Price:</label>
                    <input type="number" step="0.01" name="itemBasePrice[]" class="form-control item-base-price" required onchange="calculateSubtotal(this)" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                </div>


                <div class="form-group" style="margin-bottom: 10px;">
                    <label for="itemSubtotal" style="font-weight: bold;">Item Subtotal:</label>
                    <input type="text" name="itemSubtotal[]" class="form-control item-subtotal" readonly style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                </div>
            </div>
        </div>

        <!-- Button to Add More Items -->
        <button type="button" class="btn btn-secondary" onclick="addItem()">Add Another Item</button>

        <!-- Total Display -->
        <div class="form-group" style="margin-top: 15px; margin-bottom: 15px;">
            <label for="totalAmount" style="font-weight: bold;">Total:</label>
            <input type="text" name="totalAmount" id="totalAmount" class="form-control" readonly style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>

        <!-- Confirm Button -->
        <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 16px; border-radius: 5px;">Confirm</button>
    </form>
</div>

<script>
function calculateSubtotal(element) {
    const itemGroup = element.closest('.item-group');
    const qty = parseFloat(itemGroup.querySelector('.item-qty').value) || 0;
    const basePrice = parseFloat(itemGroup.querySelector('.item-base-price').value) || 0;
    const subtotal = qty * basePrice;
    
    // Display the formatted subtotal in the readonly field
    itemGroup.querySelector('.item-subtotal').value = `₱${subtotal.toFixed(2)}`;
    
    // Ensure the hidden subtotal input is updated or created
    let hiddenSubtotalInput = itemGroup.querySelector('.hidden-item-subtotal');
    if (!hiddenSubtotalInput) {
        hiddenSubtotalInput = document.createElement('input');
        hiddenSubtotalInput.type = 'hidden';
        hiddenSubtotalInput.name = 'itemSubtotal[]';
        hiddenSubtotalInput.classList.add('hidden-item-subtotal');
        itemGroup.appendChild(hiddenSubtotalInput);
    }
    hiddenSubtotalInput.value = subtotal.toFixed(2); // Set the numeric value for submission
    
    // Calculate the total for all items
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.hidden-item-subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('totalAmount').value = `₱${total.toFixed(2)}`;

    // Ensure there is a hidden total input for submission
    let hiddenTotalInput = document.getElementById('hiddenTotalAmount');
    if (!hiddenTotalInput) {
        hiddenTotalInput = document.createElement('input');
        hiddenTotalInput.type = 'hidden';
        hiddenTotalInput.name = 'totalAmount';
        hiddenTotalInput.id = 'hiddenTotalAmount';
        document.querySelector('form').appendChild(hiddenTotalInput);
    }
    hiddenTotalInput.value = total.toFixed(2); // Set the numeric total for submission
}

// Function to add a new item section dynamically
function addItem() {
    const itemsContainer = document.getElementById('itemsContainer');
    const newItemGroup = document.createElement('div');
    newItemGroup.classList.add('item-group');
    newItemGroup.style.cssText = "border-bottom: 10px solid Orange; padding-bottom: 15px; margin-bottom: 15px;";

    newItemGroup.innerHTML = `
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="item" style="font-weight: bold;">Item:</label>
            <select name="items[]" class="form-control item-select" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                <option value="" disabled selected>Select Item</option>
                <?php echo $itemOptions; // Use pre-fetched item options ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="itemQty" style="font-weight: bold;">Item Qty:</label>
            <input type="number" name="itemQty[]" class="form-control item-qty" min="1" value="1" required onchange="calculateSubtotal(this)" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="itemBasePrice" style="font-weight: bold;">Item Base Price:</label>
            <input type="number" step="0.01" name="itemBasePrice[]" class="form-control item-base-price" required onchange="calculateSubtotal(this)" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="itemSubtotal" style="font-weight: bold;">Item Subtotal:</label>
            <input type="text" name="itemSubtotal[]" class="form-control item-subtotal" readonly style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>
    `;
    itemsContainer.appendChild(newItemGroup);
}
</script>

<?php endif; ?>
<?php include('footer.php'); ?>