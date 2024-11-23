<template>
    <div style="margin-left: 30px;">
        <!-- <h2>Escolha a moeda e o intervalo de datas:</h2> -->
        <h1>Crypto Tracker</h1>
        <div class="containerListagemMoedasEscolhidas">
            <div>
                <label>Data inicial:</label>
                <input type="date" v-model="startDate" @change="fetchData" style="color: black;" />
                <br>
                <label>Data final:</label>
                <input type="date" v-model="endDate" @change="fetchData" style="color: black;" />
            </div>
            <!-- Seleção de moeda -->
            <div class="listagemMoedasEscolhidas">
                <span class="moedaEscolhida" v-for="coin in selectedCoins" :key="coin.id">
                    <input type="checkbox" :name="coin.name" :id="coin.id">
                    <span class="nomeMoedaEscolhida">{{ coin.name }}</span>
                    <span title="Remover Criptomoeda" @click="removeCoin(coin.id)" class="removerMoedaEscolhida"><i
                            class="fa fa-times"></i></span>
                </span>
            </div>

            <select v-if="showSelect" v-model="selectedCoin" @change="fetchData" v-on:change="addCryptoToList"
                style="color: black;">
                <option v-for="coin in coins" :key="coin.id" :value="coin.id">
                    {{ coin.name }}
                </option>
            </select>
        </div>
        <button class="btnAddCrypto" type="button" v-on:click="toggleSelectVisibility">Adicionar Criptomoeda</button>

        <!-- Seleção de datas -->


        <!-- Seleção de moedas de conversão -->
        <!-- <div>
            <label>
                <input type="checkbox" value="brl" v-model="selectedCurrencies" @change="fetchData" />
                BRL
            </label>
            <label>
                <input type="checkbox" value="usd" v-model="selectedCurrencies" @change="fetchData" />
                USD
            </label>
        </div> -->

        <!-- Gráfico -->
        <!-- <div v-if="chartData" style="margin-top: 100px;">
            <canvas style="max-width: 90%; max-height: 500px;" id="crypto-chart" ref="chartCanvas"></canvas>
        </div> -->
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

.containerListagemMoedasEscolhidas {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
    width: 350px;
    max-height: 70vh;
    overflow-y: auto;
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
            coins: [], // Lista de moedas
            selectedCoin: "", // Moeda selecionada pelo usuário
            startDate: "", // Data inicial escolhida pelo usuário
            endDate: "", // Data final escolhida pelo usuário
            selectedCurrencies: [], // Moedas inicialmente selecionadas
            chartData: null, // Dados para renderizar o gráfico
            chart: null, // Referência para o gráfico
            showSelect: false, // Mostra ou esconde o select
            selectedCoins: [], // Lista de moedas selecionadas
        };
    },
    methods: {
        toggleSelectVisibility() {
            this.showSelect = !this.showSelect;
        },

        // Adiciona a criptomoeda selecionada à lista
        addCryptoToList() {
            const coin = this.coins.find(c => c.id === this.selectedCoin);
            if (coin && !this.selectedCoins.some(c => c.id === coin.id)) {
                this.selectedCoins.push(coin);
                this.saveToLocalStorage(); // Salvar no localStorage
            }
            this.showSelect = false;
        },

        // Remove a criptomoeda da lista
        removeCoin(coinId) {
            this.selectedCoins = this.selectedCoins.filter(coin => coin.id !== coinId);
            this.saveToLocalStorage(); // Salvar no localStorage
        },

        // Salva os dados no localStorage
        saveToLocalStorage() {
            localStorage.setItem('selectedCoins', JSON.stringify(this.selectedCoins));
            localStorage.setItem('startDate', this.startDate);
            localStorage.setItem('endDate', this.endDate);
        },

        // Carrega os dados do localStorage
        loadFromLocalStorage() {
            const savedCoins = localStorage.getItem('selectedCoins');
            const savedStartDate = localStorage.getItem('startDate');
            const savedEndDate = localStorage.getItem('endDate');

            if (savedCoins) {
                this.selectedCoins = JSON.parse(savedCoins);
            }
            if (savedStartDate) {
                this.startDate = savedStartDate;
            }
            if (savedEndDate) {
                this.endDate = savedEndDate;
            }
        },

        // Busca a lista de moedas disponíveis na API
        async fetchCoins() {
            try {
                const response = await axios.get("https://api.coingecko.com/api/v3/coins/list");
                this.coins = response.data;
            } catch (error) {
                console.error("Erro ao buscar moedas:", error);
            }
        },

        // Busca os dados do gráfico com base na moeda, datas e moedas selecionadas
        async fetchData() {
            if (!this.selectedCoin || !this.startDate || !this.endDate || this.selectedCurrencies.length === 0) {
                console.warn("Preencha todas as opções antes de buscar dados.");
                return;
            }

            const start = new Date(this.startDate).getTime(); // Timestamp em milissegundos
            const end = new Date(this.endDate).getTime(); // Timestamp em milissegundos

            const datasets = [];

            for (const currency of this.selectedCurrencies) {
                try {
                    const response = await axios.get(
                        `https://api.coingecko.com/api/v3/coins/${this.selectedCoin}/market_chart/range`,
                        {
                            params: {
                                vs_currency: currency,
                                from: start / 1000, // Timestamps em segundos
                                to: end / 1000, // Timestamps em segundos
                            },
                        }
                    );

                    // Filtra os dados para garantir que estão dentro do intervalo
                    const filteredData = response.data.prices.filter((price) => {
                        const timestamp = price[0]; // O timestamp vem no índice 0
                        return timestamp >= start && timestamp <= end;
                    });

                    // Adiciona os dados filtrados ao dataset
                    datasets.push({
                        label: `Preço (${currency.toUpperCase()})`,
                        data: filteredData.map((price) => ({
                            x: price[0],
                            y: price[1],
                        })),
                        borderColor: currency === "brl" ? "rgba(75, 192, 192, 1)" : "rgba(255, 99, 132, 1)",
                        backgroundColor: currency === "brl" ? "rgba(75, 192, 192, 0.2)" : "rgba(255, 99, 132, 0.2)",
                    });
                } catch (error) {
                    console.error(`Erro ao buscar dados para ${currency}:`, error);
                }
            }

            this.chartData = datasets;

            this.$nextTick(() => this.renderChart());
        },

        // Renderiza o gráfico com base nos dados buscados
        renderChart() {
            const canvas = this.$refs.chartCanvas;

            if (!canvas) {
                console.error("Canvas não encontrado!");
                return;
            }

            const ctx = canvas.getContext("2d");

            if (this.chart) {
                this.chart.destroy();
            }

            if (this.chartData.length === 0) {
                console.warn("Sem dados para exibir no gráfico.");
                return;
            }

            this.chart = new Chart(ctx, {
                type: "line", // Tipo do gráfico: line ou bar
                data: {
                    labels: this.chartData[0].data.map((point) => point.x),
                    datasets: this.chartData,
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            mode: "index",
                            intersect: false,
                        },
                    },
                    scales: {
                        x: {
                            type: "time",
                            time: {
                                unit: "day", // Ajusta a granularidade para dias
                            },
                            min: new Date(this.startDate).getTime(), // Define o limite mínimo no eixo
                            max: new Date(this.endDate).getTime(), // Define o limite máximo no eixo
                        },
                        y: {
                            beginAtZero: false,
                        },
                    },
                },
            });
        },
    },
    async mounted() {
        await this.fetchCoins();
    },
    created() {
        this.loadFromLocalStorage();
    }
};
</script>
