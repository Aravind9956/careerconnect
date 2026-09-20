/**
 * CareerConnect - Admin Analytics & Chart.js Engine
 */

function renderAdminCharts(analyticsData) {
    if (typeof Chart === 'undefined') return;

    // 1. User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart');
    if (userGrowthCtx && analyticsData.userGrowth) {
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: analyticsData.userGrowth.labels,
                datasets: [{
                    label: 'New Registrations',
                    data: analyticsData.userGrowth.data,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // 2. Application Status Doughnut Chart
    const appStatusCtx = document.getElementById('appStatusChart');
    if (appStatusCtx && analyticsData.appStatus) {
        new Chart(appStatusCtx, {
            type: 'doughnut',
            data: {
                labels: analyticsData.appStatus.labels,
                datasets: [{
                    data: analyticsData.appStatus.data,
                    backgroundColor: ['#2563eb', '#06b6d4', '#f59e0b', '#ef4444', '#10b981']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // 3. Jobs by Category Bar Chart
    const jobsCategoryCtx = document.getElementById('jobsCategoryChart');
    if (jobsCategoryCtx && analyticsData.jobsCategory) {
        new Chart(jobsCategoryCtx, {
            type: 'bar',
            data: {
                labels: analyticsData.jobsCategory.labels,
                datasets: [{
                    label: 'Jobs Posted',
                    data: analyticsData.jobsCategory.data,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
}
