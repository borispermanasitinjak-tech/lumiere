<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-outline" style="margin-bottom: 20px;">&larr; Back to Dashboard</a>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Sales Report & Analytics</h2>
        <a href="<?php echo BASE_URL; ?>/admin/export_csv" class="btn btn-primary"><i class="fas fa-file-csv"></i> Download CSV</a>
    </div>

    <div class="card">
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Gradients
    const gradientRevenue = ctx.createLinearGradient(0, 0, 0, 400);
    gradientRevenue.addColorStop(0, 'rgba(188, 108, 37, 0.6)'); // Accent color high
    gradientRevenue.addColorStop(1, 'rgba(188, 108, 37, 0.05)'); // Accent color low

    const gradientOrders = ctx.createLinearGradient(0, 0, 0, 400);
    gradientOrders.addColorStop(0, 'rgba(42, 157, 143, 0.6)'); 
    gradientOrders.addColorStop(1, 'rgba(42, 157, 143, 0.05)');

    // Font defaults
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#555';

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $data['dates']; ?>,
            datasets: [{
                label: 'Revenue (Rp)',
                data: <?php echo $data['revenues']; ?>,
                borderColor: '#bc6c25', // Accent
                backgroundColor: gradientRevenue,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#bc6c25',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            },
            {
                label: 'Orders',
                data: <?php echo $data['orders']; ?>,
                borderColor: '#2a9d8f', // Secondary cool color
                backgroundColor: gradientOrders,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2a9d8f',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 10,
                    boxPadding: 5,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: '#eee'
                    },
                    title: {
                        display: true,
                        text: 'Revenue (Rp)',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Orders',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
