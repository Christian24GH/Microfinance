function addBenefitDeduction() {
    const employeeId = document.getElementById('employeeId').value.trim();
    const EmployeeName = document.getElementById('EmployeeName').value.trim();
    const benefitType = document.getElementById('benefitType').value;
    const benefitAmountRaw = document.getElementById('benefitAmount').value.trim();
    const deductionType = document.getElementById('deductionType').value;
    const deductionAmountRaw = document.getElementById('deductionAmount').value.trim();

    // Validate form
    const form = document.getElementById('benefitsForm');
    if (!form.checkValidity()) {
        alert('Please fill in all the required fields.');
        return;
    }

    // Validate and format amounts
    const benefitAmount = parseFloat(benefitAmountRaw);
    const deductionAmount = parseFloat(deductionAmountRaw);

    if (isNaN(benefitAmount) || isNaN(deductionAmount)) {
        alert('Please enter valid numbers for Benefit Amount and Deduction Amount.');
        return;
    }

    const benefitAmountFormatted = benefitAmount.toFixed(2);
    const deductionAmountFormatted = deductionAmount.toFixed(2);

    // Create a new row
    const tableBody = document.querySelector('#benefitsTable tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${employeeId}</td>
        <td>${EmployeeName}</td>
        <td>${benefitType}</td>
        <td>₱${benefitAmountFormatted}</td>
        <td>${deductionType}</td>
        <td>₱${deductionAmountFormatted}</td>
        <td>
            <button class="btn btn-warning btn-sm" onclick="editRow(this)">Edit</button>
            <button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button>
        </td>
    `;
    tableBody.appendChild(row);

    // Clear the form and refocus
    form.reset();
    document.getElementById('employeeId').focus();
}

function deleteRow(button) {
    if (confirm('Are you sure you want to delete this entry?')) {
        const row = button.closest('tr');
        row.remove();
    }
}

function editRow(button) {
    if (!confirm('Are you sure you want to edit this entry?')) return;
    
    const row = button.closest('tr');
    const cells = row.getElementsByTagName('td');
    
    document.getElementById('employeeId').value = cells[0].innerText;
    document.getElementById('EmployeeName').value = cells[1].innerText;
    document.getElementById('benefitType').value = cells[2].innerText;
    document.getElementById('benefitAmount').value = cells[3].innerText.replace('₱', '').trim();
    document.getElementById('deductionType').value = cells[4].innerText;
    document.getElementById('deductionAmount').value = cells[5].innerText.replace('₱', '').trim();

    row.remove();
}
