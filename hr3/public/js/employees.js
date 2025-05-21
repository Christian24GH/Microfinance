document.addEventListener('DOMContentLoaded', () => {
    fetch('/api/employees', {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token') // or from cookie/session
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('#employeeTable tbody');
        data.forEach(emp => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${emp.name}</td>
                <td>${emp.position}</td>
                <td>${emp.department}</td>
                <td>${emp.status}</td>
                <td><button onclick="viewDetails(${emp.id})">View Details</button></td>
            `;
            tbody.appendChild(row);
        });
    });
});

function viewDetails(empId) {
    fetch(`/api/employees/${empId}/time-entries`)
        .then(res => res.json())
        .then(data => {
            const content = document.getElementById('timeEntriesContent');
            content.innerHTML = data.map(entry => `
                <div>${entry.date} - ${entry.hours_worked} hrs</div>
            `).join('');
            document.getElementById('timeEntriesModal').style.display = 'block';
        });
}

function closeModal() {
    document.getElementById('timeEntriesModal').style.display = 'none';
}
