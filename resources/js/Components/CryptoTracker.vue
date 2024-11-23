<template>

    <div style="margin-left: 30px;">

        <h1>Crypto Tracker</h1>

        <div v-if="showError" style="padding: 20px; font-size: 25px;">Deu merda na API</div>

        <div>
            <label>Data inicial:</label>
            <input type="date" v-model="startDate" @change="saveToLocalStorage" style="color: black;" />
            <br>
            <label>Data final:</label>
            <input type="date" v-model="endDate" @change="saveToLocalStorage" style="color: black;" />
        </div>

        <div class="containerListagemMoedasEscolhidas">

            <div class="listagemMoedasEscolhidas">
                <span class="moedaEscolhida" v-for="coin in selectedCoins" :key="coin.id">
                    <input type="checkbox" v-model="coin.selected" />
                    <span class="nomeMoedaEscolhida">{{ coin.name }}</span>
                    <span title="Remover Criptomoeda" @click="removeCoin(coin.id)" class="removerMoedaEscolhida"><i
                            class="fa fa-times"></i></span>
                </span>
            </div>

            <select v-if="showSelect" v-model="selectedCoin" @change="addCryptoToList" style="color: black;">
                <option v-for="coin in coins" :key="coin.id" :value="coin.id">
                    {{ coin.name }}
                </option>
            </select>

        </div>
        <button class="btnAddCrypto" type="button" v-on:click="toggleSelectVisibility">Adicionar Criptomoeda</button>
        <button class="btnGerarGrafico" type="button" @click="generateChart">Gerar gráfico</button>

        <div v-if="chartData" style="margin-top: 100px;">
            <canvas style="max-width: 90%; max-height: 500px;" id="crypto-chart" ref="chartCanvas"></canvas>
        </div>

    </div>

</template>

<style>
    .btnAddCrypto {
        background-color: #4CAF50;
        /* Green */
        border: none;
        color: white;
        padding: 10px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .btnGerarGrafico {
        background-color: #4c66af;
        border: none;
        color: white;
        padding: 10px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .containerListagemMoedasEscolhidas {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        width: 350px;
        max-height: 70vh;
        overflow-y: auto;
        margin-top: 30px;
    }

    .listagemMoedasEscolhidas {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
    }

    .moedaEscolhida {
        border: solid 1px #ccc;
        padding: 10px;
        min-height: 72px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nomeMoedaEscolhida {
        margin-left: 10px;
        font-size: 18px;
    }

    .removerMoedaEscolhida {
        color: rgb(227, 87, 87);
        cursor: pointer;
        margin-left: auto;
        margin-right: 10px;
        font-size: 20px;
    }

    input[type="checkbox"] {
        margin-left: 10px;
        width: 20px;
        height: 20px;
    }
</style>

<script>
    import axios from "axios";
    import { Chart, registerables } from "chart.js";
    import "chartjs-adapter-date-fns";
    import 'font-awesome/css/font-awesome.css';

    Chart.register(...registerables);

    export default {

        data() {

            return {
                coins: [], // cryptos que vem da API
                selectedCoin: "", // crypto selecionada (adicionar na lista)
                startDate: "", // data inicial
                endDate: "", // data final
                chartData: null, 
                chart: null, 
                showSelect: false,
                showError: false,
                selectedCoins: [], // crypto selecionadas no checkbox
            };

        },
        methods: {

            toggleSelectVisibility() {
                this.showSelect = !this.showSelect;
            },

            addCryptoToList() {
                const coin = this.coins.find(c => c.id === this.selectedCoin);
                if (coin && !this.selectedCoins.some(c => c.id === coin.id)) {
                    this.selectedCoins.push({ ...coin, selected: true });
                    this.saveToLocalStorage();
                }
                this.showSelect = false;
            },

            removeCoin(coinId) {
                this.selectedCoins = this.selectedCoins.filter(coin => coin.id !== coinId);
                this.saveToLocalStorage();
            },

            saveToLocalStorage() {
                localStorage.setItem('selectedCoins', JSON.stringify(this.selectedCoins));
                localStorage.setItem('startDate', this.startDate);
                localStorage.setItem('endDate', this.endDate);
            },

            loadFromLocalStorage() {
                const savedCoins = localStorage.getItem('selectedCoins');
                const savedStartDate = localStorage.getItem('startDate');
                const savedEndDate = localStorage.getItem('endDate');

                if (savedCoins) this.selectedCoins = JSON.parse(savedCoins);
                if (savedStartDate) this.startDate = savedStartDate;
                if (savedEndDate) this.endDate = savedEndDate;
            },

            async fetchCoins() {
                try {
                    const response = await axios.get("https://api.coingecko.com/api/v3/coins/list");
                    this.coins = response.data;
                } catch (error) {
                    console.error("Erro ao buscar moedas:", error);
                }
            },

            getRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },

            generateChart() {
                const selectedCoins = this.selectedCoins.filter(coin => coin.selected);
                if (selectedCoins.length === 0 || !this.startDate || !this.endDate) {
                    console.warn("Preencha todas as opções antes de gerar o gráfico.");
                    return;
                }

                const start = new Date(this.startDate).getTime() / 1000; // Timestamps em segundos
                const end = new Date(this.endDate).getTime() / 1000; // Timestamps em segundos

                const datasets = [];

                const fetchPromises = selectedCoins.map(coin => {
                    return axios.get(`https://api.coingecko.com/api/v3/coins/${coin.id}/market_chart/range`, {
                        params: {
                            vs_currency: "brl",
                            from: start,
                            to: end,
                        },
                    });
                });

                // Unifica todas as promessas da requisição
                Promise.all(fetchPromises)
                    .then(responses => {
                        responses.forEach((response, index) => {
                            const coin = selectedCoins[index];
                            const color = this.getRandomColor();

                            const data = response.data.prices.map(price => ({
                                x: price[0],
                                y: price[1],
                            }));

                            datasets.push({
                                label: `${coin.name}`,
                                data,
                                borderColor: color,
                                backgroundColor: color + "80",
                            });
                        });

                        this.chartData = datasets;
                        this.renderChart();
                    })
                    .catch(error => console.error("Erro ao buscar os dados para o gráfico:", error), this.showError = true);
            },

            renderChart() {
                // Garantir que o DOM esteja atualizado antes de tentar acessar o canvas
                this.$nextTick(() => {
                    const canvas = this.$refs.chartCanvas;
                    if (!canvas) {
                        console.error("Canvas não encontrado!");
                        return;
                    }

                    const ctx = canvas.getContext("2d");

                    if (this.chart) {
                        this.chart.destroy(); // limpa o grafico que existe
                    }

                    if (this.chartData.length === 0) {
                        console.warn("Sem dados para exibir no gráfico.");
                        return;
                    }

                    this.chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: this.chartData[0].data.map((point) => point.x),
                            datasets: this.chartData,
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    type: "time",
                                    time: {
                                        unit: "day",
                                    },
                                    min: new Date(this.startDate).getTime(),
                                    max: new Date(this.endDate).getTime(),
                                },
                                y: {
                                    beginAtZero: false,
                                },
                            },
                        },
                    });
                });
            }

        },

        async mounted() {

            await this.fetchCoins();
            this.loadFromLocalStorage();

        },

    };

</script>
