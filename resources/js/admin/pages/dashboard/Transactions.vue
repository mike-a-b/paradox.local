<template>
    <div class="card d-inline-flex">
        <div v-if="isDashboardMode" class="card-header py-1 font-weight-bold text-gray">
            Transactions
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
        <table class="table table-sm" style="max-width:900px;">
            <thead>
                <tr class="text-gray">
                    <th>ID</th>
                    <th>type</th>                            
                    <th>from_user</th>                                                                                                                 
                    <th>value</th>                                                
                    <th>commission</th>                                                
                    <th>pool</th>                                                
                    <th>initiator</th>                                                
                    <th>created_at</th>
                    <!-- <th></th>
                    <th></th>                             -->
                </tr>
            </thead>
            <tbody>        
                <template v-for="(data) in transactionsList" :key="data.id">                
                <tr> 
                    <td>{{ data.id }}</td>                            
                    <td>
                        {{ data.type_name_short }}
                    </td>
                    <td class="text-left">
                        <a :href="`/admin/users/${data.from_user_id}/edit`" target="_blanc">{{ data.from_user_name }}</a>
                    </td>
                    <td class="text-left">${{ data.value }}</td>                    
                    <td class="text-left">${{ data.commission_value }}</td>                    
                    <td class="text-left">{{ data.asset_pool_name_short }}</td>                    
                    <td class="text-left">{{ data.creator_user_name }}</td>                    
                    <td>{{ data.created_at }}</td>
                    <!-- <td class="text-center">                        
                        <i @click="editModal(data.id)" class="fa fa-edit table-row-icon pointer"></i>                        
                    </td>                            
                    <td>                        
                        <i @click="deleteModal(data.id)" class="fa fa-trash text-danger table-row-icon pointer"></i>                        
                    </td> -->
                </tr>
                </template>
            </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        <div v-if="!isDashboardMode" class="card-footer clearfix d-flex justify-content-center pt-2">    
            <v-pagination
                v-model="page"
                :pages="pagesCount"
                :range-size="1"
                active-color="#DCEDFF"
                @update:modelValue="pageUpdateHandler"
            />                        
        </div>
        <div v-else class="card-footer clearfix d-flex justify-content-center pt-2">
            <a href="/admin/dashboard/transactions">See all</a>
        </div>        
    </div>
    <!-- /.card -->
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchTransactionsList, countTransactions } from "../../api/Transactions";

import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

const props = defineProps({
  mode: String
})

let transactionsList = ref([]);

let isDashboardMode = ref(false);
let page = ref(1);
let pagesCount = ref(1);
let itemsCount = 0;

let onPageItems = 10;

const pageUpdateHandler = async (newPage) => {      
    updateList();
}

const updateListCount = async () => {
    const data = await countTransactions();        
    itemsCount = data.count;
}

const updateList = async () => {
    isDashboardMode.value = props.mode == 'dashboard';
    onPageItems = isDashboardMode.value ? 5 : onPageItems;

    const tList = await fetchTransactionsList({withItems:true, offset:onPageItems*(page.value-1), count:onPageItems});        
    transactionsList.value = tList.map(item => {
        item.value = Math.floor(1000*item.value)/1000;
        item.commission_value = Math.floor(1000*item.commission_value)/1000;
        return item;
    });

    pagesCount.value = Math.ceil(itemsCount/onPageItems);
}

onMounted(async () => {    
    await updateListCount();
    updateList();            
});
</script>

<style scoped>
</style>