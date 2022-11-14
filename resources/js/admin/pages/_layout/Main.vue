<template>
    123343
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchPoolsList } from "../../api/AssetPools";


let poolsList = ref([]);

const updateList = async () => {    
    const pools = await fetchPoolsList({withItems:false,isActive:1});
}

onMounted(() => {        
    updateList();        
});
</script>

<style scoped>
</style>