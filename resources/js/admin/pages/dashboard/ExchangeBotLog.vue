<template>
    <div class="card d-inline-flex">
        <div class="card-header py-1 text-gray text-right">
            <div class="d-inline-block text-left" style="width:105px;">
                <input class="align-middle" v-model="isUpdated" type="checkbox" value="1" id="updateCb">            
                <label class="align-middle" :class="{'font-weight-normal':!isUpdated}" for="updateCb" style="text-indent:3px;margin-bottom: 2px;">
                    Autoupdate
                </label>
            </div>            
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
        <table class="table table-sm" style="max-width:900px;">
            <thead>
                <tr class="text-gray">
                    <th>ID</th>
                    <th>api_request</th>   
                    <th>oper_type</th>                                                                                                                                           
                    <th>value_fiat</th>                                                
                    <th>value_crypto</th>                                                
                    <th>pool</th>   
                    <th>user</th>                                               
                    <th>initiator</th>                                                                                                                 
                    <th>created_at</th>
                    <!-- <th></th>
                    <th></th>                             -->
                </tr>
            </thead>
            <tbody>        
                <template v-for="(data) in ExchangeBotLogList" :key="data.id">                
                <tr> 
                    <td>{{ data.id }}</td>                            
                    <td class="text-left">{{ data.api_request }}</td>                    
                    <td>
                        {{ data.operation_type }}
                    </td>                    
                    <td class="text-left">${{ data.value_fiat }}</td>                    
                    <td class="text-left">{{ data.value_crypto }}</td>                    
                    <td class="text-left" style="white-space: nowrap;">{{ data.asset_pool_name_short }}</td>                    
                    <td class="text-left">
                        <a :href="`/admin/users/${data.user_id}/edit`" target="_blanc">{{ data.user_name }}</a>
                    </td>
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
        <div class="card-footer clearfix d-flex justify-content-center pt-2">    
            <v-pagination
                v-model="page"
                :pages="pagesCount"
                :range-size="1"
                active-color="#DCEDFF"
                @update:modelValue="pageUpdateHandler"
            />                        
        </div>              
    </div>
    <!-- /.card -->
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchExchangeBotLogList, countExchangeBotLog } from "../../api/ExchangeBotLog";

import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

let ExchangeBotLogList = ref([]);

let isUpdated = ref(false);
let page = ref(1);
let pagesCount = ref(1);
let itemsCount = 0;

let onPageItems = 10;

const pageUpdateHandler = async (newPage) => {      
    updateList();
}

const updateListCount = async () => {
    const data = await countExchangeBotLog();        
    itemsCount = data.count;
}

const updateList = async () => {

    const tList = await fetchExchangeBotLogList({withItems:true, offset:onPageItems*(page.value-1), count:onPageItems});        
    ExchangeBotLogList.value = tList.map(item => {
        item.value_fiat = Math.floor(1000*item.value_fiat)/1000;        
        item.value_crypto = item.value_crypto.substr(0, 10);
        return item;
    });

    pagesCount.value = Math.ceil(itemsCount/onPageItems);
}

onMounted(async () => {    
    await updateListCount();
    updateList();          
    setInterval(async () => {
        if (isUpdated.value) {
            page.value = 1;
            await updateListCount();
            updateList();          
        }
    }, 1000);  
});
</script>

<style scoped>
</style>