<template>
    <div>
        <!-- <vue3-simple-typeahead 
            :items="poolsList" 
            placeholder="Type pool name..." 
            @selectItem="selectPool"                            
            :minInputLength="minAcInputLength" 
            :itemProjection="
                (item) => {
                    return `${item.name} (${item.group_name})`;
                }
            "
        /> -->
        <VueMultiselect
            v-model="selectedPool"
            :options="poolsList"
            :custom-label="nameWithMultiselect" 
            placeholder="Select pool" 
            label="id" 
            track-by="id"
            @select="selectPool"
        >
        </VueMultiselect>
        <input v-model="poolId" name="asset_pool_id" type="hidden">
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchPoolsList } from "../../api/AssetPools";
import { fetchPoolsList as fetchUserPoolsList } from "../../api/UserAssetPools";

//import Vue3SimpleTypeahead from 'vue3-simple-typeahead'

import VueMultiselect from 'vue-multiselect'

const props = defineProps({
  user_id: String,
});

let poolsList = ref([]);
let selectedPool = ref(null);

let poolId = ref(0);
 
//const minAcInputLength = 1;
//let selection = ref(null);

const selectPool = (item) => {
    poolId.value = item.id;
    //console.log(item);
}

const nameWithMultiselect = ({ name, group_name }) => {
    return `${name} — [${group_name}]`
};

const updateList = async () => {    
    const pools = await fetchPoolsList({withItems:false,isActive:1});
    //const pools = await fetchUserPoolsList({withItems:false});
    const userPools = await fetchUserPoolsList({userId:props.user_id});
    const userPoolIds = userPools.map(item => item.asset_pool_id);
    poolsList.value = pools.filter(item => {
        return !userPoolIds.includes(item.id);
    });
}

onMounted(() => {        
    updateList();        
});
</script>

<style scoped>
</style>