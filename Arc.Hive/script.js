function calculateSubtotal() {
    // Get the values of Quantity and Base Price
    var quantity = document.getElementById('Quantity').value;
    var basePrice = document.getElementById('BasePrice').value;

    // Calculate Subtotal
    var subtotal = quantity * basePrice;

    // Update the Subtotal field
    document.getElementById('Subtotal').value = subtotal.toFixed(2); // Formatting to 2 decimal places
}

// This is for Supply Details
function sortSupplyListTable(columnIndex, ascending) {
    sortTable('supplyListTable', columnIndex, ascending);
}

function sortSupplyDetailsTable(columnIndex, ascending) {
    sortTable('supplyDetailsTable', columnIndex, ascending);
}

function sortTable(tableId, columnIndex, ascending) {
    const table = document.getElementById(tableId);
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
function toggleSupplySearch() {
    const searchRow = document.getElementById('supplySearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

function toggleSupplyDetailsSearch() {
    const searchRow = document.getElementById('supplyDetailsSearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

function filterSupplyListTable() {
    filterTable('supplyListTable', 'supplySearchRow');
}

function filterSupplyDetailsTable() {
    filterTable('supplyDetailsTable', 'supplyDetailsSearchRow');
}

function filterTable(tableId, searchRowId) {
    const inputFields = document.querySelectorAll(`#${searchRowId} input`);
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tbody tr');

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

