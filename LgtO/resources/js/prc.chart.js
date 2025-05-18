import Chart from 'chart.js/auto';

const ctx = document.getElementById('dailySpendingChart');

if (ctx && window.dailyAmounts) {
    const labels = window.dailyAmounts.map(item => item.date);
    const dataValues = window.dailyAmounts.map(item => item.total);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Procurement Spending (₱)',
                data: dataValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.15)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true
        }
    });
}
