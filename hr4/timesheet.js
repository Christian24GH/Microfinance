// Sample data for demonstration
const timesheetData = [
    { name: 'Gon', date: '2025-05-01', status: 'Present', timeIn: '08:00', timeOut: '17:00' },
    { name: 'Killua', date: '2025-05-01', status: 'Present', timeIn: '09:00', timeOut: '18:00' },
    { name: 'Kurapika', date: '2025-05-01', status: 'Absent', timeIn: '-', timeOut: '-' },
    { name: 'Leorio', date: '2025-05-01', status: 'Present', timeIn: '08:30', timeOut: '17:30' },
    { name: 'Hisoka', date: '2025-05-01', status: 'Present', timeIn: '07:45', timeOut: '16:45' },
];

// Populate the timesheet table
const tbody = document.querySelector("#timesheetTable tbody");
timesheetData.forEach(row => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td>${row.name}</td>
        <td>${row.date}</td>
        <td>${row.status}</td>
        <td>${row.timeIn}</td>
        <td>${row.timeOut}</td>
    `;
    tbody.appendChild(tr);
});

// Chart Configuration
const ctx = document.getElementById("timesheetChart").getContext("2d");
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: timesheetData.map(row => row.name),
        datasets: [{
            label: 'Hours Worked',
            data: timesheetData.map(row => {
                if (row.timeIn !== '-' && row.timeOut !== '-') {
                    const [inH, inM] = row.timeIn.split(":").map(Number);
                    const [outH, outM] = row.timeOut.split(":").map(Number);
                    return (outH + outM / 60) - (inH + inM / 60);
                }
                return 0;
            }),
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Hours Worked per Employee' }
        }
    }
});
