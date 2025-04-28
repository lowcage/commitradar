<template>
    <div class="info-card">
        <div class="info-header">
            <h3>Commits Over the Last 52 Weeks</h3>
        </div>
        <div class="info-content">
            <canvas ref="canvas"></canvas>
        </div>
    </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export default {
    props: {
        activity: {
            type: Array,
            required: true,
        },
    },
    mounted() {
        const ctx = this.$refs.canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.activity.map(entry => {
                    const date = new Date(entry.week * 1000);
                    return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
                }),
                datasets: [{
                    label: 'Commits per Week',
                    data: this.activity.map(entry => entry.total),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    pointBackgroundColor: '#2563eb',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#374151',
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: {
                            color: '#e5e7eb',
                        }
                    },
                    y: {
                        ticks: { color: '#6b7280' },
                        grid: {
                            color: '#e5e7eb',
                        },
                        beginAtZero: true,
                    }
                }
            }
        });
    },
};
</script>

<style scoped>
.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-top: 2rem;
}

.info-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.info-content {
    padding: 1.5rem;
    height: 400px;
}
</style>
