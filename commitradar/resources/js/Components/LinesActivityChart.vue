<template>
    <div class="info-card">
        <div class="info-header">
            <h3>Lines Changed per Week (Loaded Only)</h3>
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
        chartData: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        const ctx = this.$refs.canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: this.chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#374151',
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: { color: '#e5e7eb' },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6b7280' },
                        grid: { color: '#e5e7eb' },
                    },
                },
            },
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
