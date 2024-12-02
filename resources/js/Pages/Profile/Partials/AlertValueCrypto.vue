<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Configurar Alertas de Valor de Criptomoeda
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Selecione uma das moedas abaixo para configurar um alerta de valor.
            </p>
        </header>

        <div class="listagemMoedasSelecionadas">
            <span class="moedas" v-for="coin in selectedCoins" :key="coin.id" @click="openModal(coin)">
                <span class="nomeMoedas text-gray-900">{{ coin.name }}</span>
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
            // lógica para salvar o alerta com base no que o usuário preencheu no input
        },
    },

    mounted() {
        const savedCoins = localStorage.getItem('selectedCoins');
        if (savedCoins) {
            this.selectedCoins = JSON.parse(savedCoins);
        }
    }
};
</script>
