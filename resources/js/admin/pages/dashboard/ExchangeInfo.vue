<template>
    <div class="card d-inline-flex">
        <div class="card-header py-1 font-weight-bold text-gray">
            Exchange info
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table">            
                <tbody>
                    <tr class="text-gray">
                        <td>Balance</td>
                        <td class="font-weight-bold">${{ info.balance_usd }}</td>                                                
                    </tr>                    
                    <tr class="text-gray">
                        <td class="pr-3">Users balance</td>
                        <td>${{ info.users_balance_usd }}</td>                                                
                    </tr>                    
                    <tr class="text-gray">
                        <td>Commissions</td>
                        <td>${{ info.commissions_usd }}</td>                                                
                    </tr>                    
                    <tr class="text-gray">
                        <td>Deposits</td>
                        <td>${{ info.deposits_usd }}</td>                                                
                    </tr>                    
                    <tr class="text-gray">
                        <td>Witdraws</td>
                        <td>${{ info.withdraws_usd }}</td>                                                
                    </tr>                    
                </tbody>
            </table>
            <!-- <div>
                <a href="{{ route('admin.dashboard.exchange_info') }}">Exchange info</a>
            </div> -->
        </div>        
    </div>
    <!-- /.card -->
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchInfo } from "../../api/ExchangeInfo";

let info = ref([]);

const updateList = async () => {
    info.value = await fetchInfo();            
    info.value.balance_usd = Math.floor(info.value.balance_usd*100)/100;
    info.value.users_balance_usd = Math.floor(info.value.users_balance_usd*100)/100;
    info.value.commissions_usd = Math.floor(info.value.commissions_usd*100)/100;
    info.value.deposits_usd = Math.floor(info.value.deposits_usd*100)/100;
    info.value.withdraws_usd = Math.floor(info.value.withdraws_usd*100)/100;
}

onMounted(async () => {        
    updateList();            
});
</script>

<style scoped>
</style>