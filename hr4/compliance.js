function addCompliance() {
    const type = document.getElementById('complianceType').value;
    const description = document.getElementById('description').value;
    const dueDate = document.getElementById('dueDate').value;
    const status = document.getElementById('status').value;
    const tableBody = document.getElementById('complianceTableBody');

    if (!type || !description || !dueDate || !status) {
        alert('Please fill in all fields.');
        return;
    }

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${type}</td>
        <td>${description}</td>
        <td>${dueDate}</td>
        <td>${status}</td>
        <td>
            <button class="btn btn-warning btn-sm" onclick="editCompliance(this)">Edit</button>
            <button class="btn btn-danger btn-sm" onclick="deleteCompliance(this)">Delete</button>
        </td>
    `;
    tableBody.appendChild(row);

    // Clear the form inputs
    document.getElementById('complianceType').value = '';
    document.getElementById('description').value = '';
    document.getElementById('dueDate').value = '';
    document.getElementById('status').value = 'Pending';
}

function deleteCompliance(button) {
    if (confirm('Are you sure you want to delete this compliance entry?')) {
        const row = button.parentNode.parentNode;
        row.remove();
    }
}

function editCompliance(button) {
    const row = button.parentNode.parentNode;
    const cells = row.getElementsByTagName('td');

    document.getElementById('complianceType').value = cells[0].innerText;
    document.getElementById('description').value = cells[1].innerText;
    document.getElementById('dueDate').value = cells[2].innerText;
    document.getElementById('status').value = cells[3].innerText;

    // Remove the row after editing
    row.remove();
}
