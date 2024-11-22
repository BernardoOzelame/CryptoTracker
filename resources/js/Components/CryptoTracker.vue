<template>
    <div>
        <select v-model="selectedCoin" @change="fetchData">
            <option v-for="coin in coins" :key="coin.id" :value="coin.id">
                {{ coin.name }}
            </option>
        </select>
        <div v-if="chartData">
            <canvas id="crypto-chart"></canvas>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export default {
    data() {
        return {
            coins: [],
            selectedCoin: '',
            chartData: null,
        };
    },
    methods: {
        async fetchCoins() {
            const response = await axios.get('https://api.coingecko.com/api/v3/coins/list');
            this.coins = response.data;
        },
        async fetchData() {
            if (!this.selectedCoin) return;
            const response = await axios.get(
                `https://api.coingecko.com/api/v3/coins/${this.selectedCoin}/market_chart`,
                { params: { vs_currency: 'usd', days: 7 } }
            );
            this.renderChart(response.data.prices);
        },
        renderChart(prices) {
            const ctx = document.getElementById('crypto-chart').getContext('2d');
            if (this.chart) this.chart.destroy();

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: prices.map(price => new Date(price[0]).toLocaleDateString()),
                    datasets: [
                        {
                            label: 'Price (USD)',
                            data: prices.map(price => price[1]),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        },
                    ],
                },
            });
        },
    },
    async mounted() {
        await this.fetchCoins();
    },
};
</script>
