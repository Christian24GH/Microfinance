// Employee Performance Data
const performanceData = [];

// Add Employee Performance
function addEmployeePerformance() {
    const name = document.getElementById('employeeName').value.trim();
    const jobTitle = document.getElementById('jobTitle').value.trim();
    const score = document.getElementById('performanceScore').value.trim();
    const reviewDate = document.getElementById('reviewDate').value.trim();
    const qualityOfWork = document.getElementById('qualityOfWork').value;
    const attendance = document.getElementById('attendance').value;
    const reliability = document.getElementById('reliability').value;
    const decisionMaking = document.getElementById('decisionMaking').value;

    // Validate basic fields
    if (!name || !jobTitle || !score || !reviewDate || !qualityOfWork || !attendance || !reliability || !decisionMaking) {
        alert('Please fill out all fields.');
        return;
    }

    const numericScore = parseInt(score);
    if (isNaN(numericScore) || numericScore < 0 || numericScore > 100) {
        alert("Performance score must be a number between 0 and 100.");
        return;
    }

    // Add to performance data
    performanceData.push({ 
        name, 
        jobTitle, 
        score: numericScore, 
        reviewDate,
        qualityOfWork,
        attendance,
        reliability,
        decisionMaking
    });

    updatePerformanceTable();

    // Clear input fields
    document.getElementById('employeeName').value = '';
    document.getElementById('jobTitle').value = '';
    document.getElementById('performanceScore').value = '';
    document.getElementById('reviewDate').value = '';
    document.getElementById('qualityOfWork').value = '';
    document.getElementById('attendance').value = '';
    document.getElementById('reliability').value = '';
    document.getElementById('decisionMaking').value = '';
}

// Update Performance Table
function updatePerformanceTable() {
    const tbody = document.getElementById('performanceTableBody');
    tbody.innerHTML = '';

    performanceData.forEach((entry, index) => {
        const row = `<tr>
            <td>${entry.name}</td>
            <td>${entry.jobTitle}</td>
            <td>${entry.score}</td>
            <td>${entry.reviewDate}</td>
            <td>${entry.qualityOfWork}</td>
            <td>${entry.attendance}</td>
            <td>${entry.reliability}</td>
            <td>${entry.decisionMaking}</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeEntry(${index})">Remove</button></td>
        </tr>`;
        tbody.innerHTML += row;
    });

    updatePerformanceChart();
}

// Remove an entry
function removeEntry(index) {
    const confirmed = confirm("Are you sure you want to remove this entry?");
    if (confirmed) {
        performanceData.splice(index, 1);
        updatePerformanceTable();
    }
}

// Update Team Performance Chart
function updatePerformanceChart() {
    const ctx = document.getElementById('teamPerformanceChart').getContext('2d');
    const labels = performanceData.map(entry => entry.name);
    const scores = performanceData.map(entry => entry.score);

    if (window.teamChart) {
        window.teamChart.destroy();
    }

    window.teamChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Performance Score',
                data: scores,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}
