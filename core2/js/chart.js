document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('loanGrowthChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: loanDates,
            datasets: [{
                label: 'Loan Disbursement Amount (₱)',
                data: loanAmounts,
                borderColor: '#4bc5ec',
                backgroundColor: 'rgba(75, 197, 236, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Monthly Loan Growth'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Disbursement Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount (₱)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    document.getElementById('downloadChart').addEventListener('click', function () {
        const link = document.createElement('a');
        link.href = chart.toBase64Image();
        link.download = 'loan-growth-chart.png';
        link.click();
    });
});
