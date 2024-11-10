<?php
include('header.php');
include('dbcon.php'); 

date_default_timezone_set('Asia/Manila');

function getJobOrderNumber() {
    global $connection;
    $today = date('Y-F-d'); 
    $query = "SELECT COUNT(*) as orderCount FROM `job-order` WHERE `Date` LIKE '$today%'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['orderCount'] + 1;
}
?>

<div class="container" style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: Orange; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Create a Job Order</h2>
    
    <form action="job-order-submit.php" method="post">
        <!-- Service Selection Dropdown -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="service" style="font-weight: bold;">Choose Service:</label>
            <select name="service" id="service" class="form-control" required onchange="enableAdditionalItemsCheckbox(); calculateTotal();" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                <option value="" disabled selected>Select a Service</option>
                <?php
                $serviceQuery = "SELECT ServiceID, ServiceType, Price FROM service";
                $serviceResult = mysqli_query($connection, $serviceQuery);

                while ($serviceRow = mysqli_fetch_assoc($serviceResult)) {
                    echo "<option value='{$serviceRow['ServiceID']}' data-price='{$serviceRow['Price']}'>
                            {$serviceRow['ServiceID']} - {$serviceRow['ServiceType']} (₱{$serviceRow['Price']})
                        </option>";
                }
                ?>
            </select>
        </div>

        <!-- Date (Current Date and Time) -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="date" style="font-weight: bold;">Date:</label>
            <input type="text" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
        </div>

        <!-- Customer Selection -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="customer" style="font-weight: bold;">Customer Name:</label>
            <select name="customer" id="customer" class="form-control">
                <?php
                // First option defaults to "Not Available"
                $customerQuery = "SELECT CustomerID, Name FROM customer";
                $customerResult = mysqli_query($connection, $customerQuery);

                while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                    $selected = ($customerRow['CustomerID'] == 1) ? 'selected' : ''; // Default to "1 - Not Available"
                    echo "<option value='{$customerRow['CustomerID']}' $selected>
                            {$customerRow['CustomerID']} - {$customerRow['Name']}
                        </option>";
                }
                ?>
            </select>
        </div>

    <!-- Weight Input -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="weight" style="font-weight: bold;">Weight:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" name="weight" id="weight" class="form-control" min="0" max="8" required onchange="calculateTotal()" placeholder="8 KG Max" style="flex: 1;">
                <span style="margin-left: 10px;">KG</span>
            </div>
        </div>

        <!-- Additional Items Section -->
        <div class="form-group" style="margin-bottom: 15px;">
            <input type="checkbox" id="addItemsCheckbox" onclick="toggleAdditionalItems()" style="margin-right: 10px;" disabled> Any Additional Item? (Note: Choose a Service before you can check this)
        </div>

        <!-- Additional Items Section -->
        <div id="additionalItems" style="display: none; margin-bottom: 15px;">
            <h4 style="font-weight: bold; margin-bottom: 10px;">Select Additional Items:</h4>
            <?php
            $itemQuery = "SELECT ItemID, ItemName, Price, ItemQuantity FROM item";
            $itemResult = mysqli_query($connection, $itemQuery);

            while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                echo "<div class='form-check' style='margin-bottom: 10px;'>
                        <input type='checkbox' name='items[]' value='{$itemRow['ItemID']}' class='form-check-input item-checkbox' data-price='{$itemRow['Price']}' data-id='{$itemRow['ItemID']}' onclick='calculateTotal()'>
                        <label class='form-check-label' style='margin-left: 5px;'>
                            {$itemRow['ItemName']} (₱{$itemRow['Price']})
                        </label>
                        <input type='number' id='quantity{$itemRow['ItemID']}' name='quantity{$itemRow['ItemID']}' min='0' max='{$itemRow['ItemQuantity']}' value='0' onchange='calculateTotal()' style='width: 50px; text-align: center; margin-left: 10px;'>
                        <span style='color: gray; margin-left: 10px;'>Available: {$itemRow['ItemQuantity']}</span>
                    </div>";
            }
            ?>
        </div>

        <!-- Final Amount Display -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="finalAmount" style="font-weight: bold;">Final Amount:</label>
            <input type="text" name="finalAmount" id="finalAmount" class="form-control" readonly>
        </div>

        <!-- Confirm Button -->
        <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 16px; border-radius: 5px;">Confirm</button>
    </form>
</div>

<script>
function enableAdditionalItemsCheckbox() {
    // Enable the additional items checkbox when a service is selected
    const serviceDropdown = document.getElementById('service');
    const addItemsCheckbox = document.getElementById('addItemsCheckbox');
    
    if (serviceDropdown.value !== "") {
        addItemsCheckbox.disabled = false;
    } else {
        addItemsCheckbox.disabled = true;
    }
}

function toggleAdditionalItems() {
    const additionalItemsDiv = document.getElementById('additionalItems');
    additionalItemsDiv.style.display = additionalItemsDiv.style.display === 'none' ? 'block' : 'none';
    calculateTotal();
}

function calculateTotal() {
    const serviceDropdown = document.getElementById('service');
    const finalAmountField = document.getElementById('finalAmount');
    let total = 0;

    // Get service price
    if (serviceDropdown.selectedIndex > 0) {
        const selectedOption = serviceDropdown.options[serviceDropdown.selectedIndex];
        total += parseFloat(selectedOption.getAttribute('data-price')) || 0;
    }

    // Get additional items price
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        const itemId = checkbox.getAttribute('data-id');
        const itemPrice = parseFloat(checkbox.getAttribute('data-price')) || 0;
        const quantity = parseInt(document.getElementById('quantity' + itemId).value) || 0;

        if (checkbox.checked && quantity > 0) {
            total += itemPrice * quantity;
        }
    });

    finalAmountField.value = '₱' + total.toFixed(2);
}
</script>

<?php include('footer.php'); ?>