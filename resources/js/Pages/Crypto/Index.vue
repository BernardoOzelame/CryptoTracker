<template>
    <div id="app">
        <headerComponent />
        <crypto-tracker />
    </div>
</template>

<script>
import CryptoTracker from '../../Components/CryptoTracker.vue';
import headerComponent from '../../Components/Header.vue';

export default {
    components: { CryptoTracker, headerComponent },
};
</script>
