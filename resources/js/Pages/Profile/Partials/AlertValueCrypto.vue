<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Configurar Alertas
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Selecione uma das moedas abaixo para configurar um alerta de valor.
            </p>
        </header>

        <div class="listagemMoedasSelecionadas">
            <span class="moedas" v-for="coin in selectedCoins" :key="coin.id" @click="openModal(coin)">
                <span class="nomeMoedas text-gray-900 dark:text-gray-200">- {{ coin.name }}</span>
            </span>
            <span class="text-gray-900 dark:text-gray-200" v-if="selectedCoins.length == 0">
                Nenhuma moeda selecionada.
            </span>
        </div>

        <Modal :show="isModalVisible" @close="closeModal">
            <div class="p-6">
                <span class="text-xl font-medium text-gray-900 dark:text-gray-100">Definir alerta para: </span><span class="text-lg">{{ selectedCoinName }}</span>

                <div class="mt-6">
                    <label for="alertValue" class="text-gray-900 dark:text-gray-100">Valor para disparar o alerta</label>
                    <input type="number" id="alertValue" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                </div>

                <div class="mt-12 flex justify-end">
                    <DangerButton @click="closeModal">Cancelar</DangerButton>
                    <SecondaryButton class="ms-3" @click="saveAlert">Salvar Alerta</SecondaryButton>
                </div>
            </div>
        </Modal>

    </section>
</template>

<style>
    .listagemMoedasSelecionadas {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        width: 350px;
    }

    .moedas {
        border: solid 1px #ccc;
        padding: 10px;
        min-height: 50px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .nomeMoedas {
        margin-left: 10px;
        font-size: 18px;
    }
</style>

<script>
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Toast from 'primevue/toast';

export default {
    components: {
        Modal,
        DangerButton,
        SecondaryButton,
    },
    data() {
        return {
            selectedCoins: [],
            isModalVisible: false,
            selectedCoinName: '',
        };
    },

    methods: {
        openModal(coin) {
            this.selectedCoinName = coin.name;
            this.isModalVisible = true;
        },

        closeModal() {
            this.isModalVisible = false;
        },

        saveAlert() {
            const alertValue = document.getElementById('alertValue').value;
            console.log('Enviando dados para o servidor:', {
                coin_name: this.selectedCoinName,
                alert_value: alertValue,
            });

            axios.post('/alerts', {
                coin_name: this.selectedCoinName,
                alert_value: alertValue,
            }).then(response => {
                alert('Alerta configurado com sucesso!');
                this.closeModal();
            }).catch(error => {
                console.error('Erro completo:', error);
                if (error.response) {
                    console.error('Erro da API:', error.response.data);
                    console.error('Status HTTP:', error.response.status);
                    console.error('Headers:', error.response.headers);
                } else if (error.request) {
                    console.error('Nenhuma resposta recebida:', error.request);
                } else {
                    console.error('Erro ao configurar requisição:', error.message);
                }
                alert('Erro ao salvar alerta.');
            });
        }

    },

    mounted() {
        const userId = window.userId;
        const coinsKey = `selectedCoins_${userId}`;
        const savedCoins = localStorage.getItem(coinsKey);
        if (savedCoins) {
            this.selectedCoins = JSON.parse(savedCoins);
        }
    }
};
</script>
