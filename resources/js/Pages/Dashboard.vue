<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold leading-tight text-gray-800 dark:text-gray-200" style="font-size: xx-large;"
            >
                Crypto Tracker
            </h2>
        </template>

        <div class="py-2">
            <div class="m-5">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100" style="width: 2000px; max-width: 100%; min-height: 600px">    
                        <CryptoTracker />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import CryptoTracker from '../Components/CryptoTracker.vue';

export default {
    components: { CryptoTracker },
};
</script>
