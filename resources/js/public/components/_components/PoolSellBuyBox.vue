<template>
    
    <div class="home_user_buyandsell_box">
        <div class="home_user_buyandsell_box_header">
            Balabce - <span class="home_user_buyandsell_balance" v-html="userBuyandsellBalance"></span> USDT
        </div>
        <div class="home_user_buyandsell_box_body">
            <div class="home_user_buyandsell_box_form">
                <div class="home_user_buyandsell_box_form-header">
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isBuyTabSelected}" @click="selectTab('buy')">Buy</div>
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isSellTabSelected}" @click="selectTab('sell')">Sell</div>
                </div>
                <div v-show="isBuyTabSelected">
                    <div class="home_user_buyandsell_box_form-body">
                        <div>
                            <label>Asset pools:</label>   
                            <br>                                                                  
                            <select v-if="userBuyAssetPools.length" v-model="selectAssetPoolBuyId" autocomplete="off">                                                    
                                <option value="0">---Select pool---</option>
                                <option 
                                    v-for="pool in userBuyAssetPools" 
                                    :value="pool.pool_id"
                                    :key="pool.pool_id"
                                >
                                    {{ pool.pool_name }}{{ pool.price_usd ? ' - $' + pool.price_usd_print : '' }}
                                </option>
                                <!-- <img src="/{{ $pool->asset_pool->logo }}" alt="">                                                                                                                           -->
                                <!-- {{ $pool->pool_balance }} -->                                                                                                                                    
                            </select>   
                            <br>       
                            <label>Rate pools:</label>  
                            <br>  
                            <div v-if="userBuyRatePools.length">
                                <select v-model="selectRatePoolBuyId" autocomplete="off">                                                    
                                    <option value="0">---Select pool---</option>
                                    <option 
                                        v-for="pool in userBuyRatePools" 
                                        :value="pool.pool_id"
                                        :key="pool.pool_id"
                                    >
                                        {{ pool.pool_name }}{{ pool.price_usd ? ' - $' + pool.price_usd_print : '' }}
                                    </option>                                                                                                                                                             
                                </select>
                                <br>
                                <input v-model="dateFromRatePoolBuy" style="width:80px;"> - <input v-model="dateToRatePoolBuy" style="width:80px;">
                            </div>                                                                                                                                                                         
                            <label v-else>No rate pools</label>                                                                                             
                        </div>
                        <div>
                            <label>Sum:</label>
                            <input v-model="valuePoolBuy" autocomplete="off">
                        </div>
                    </div>
                    <div class="validation_box" v-show="validationErrorMsg">
                        {{validationErrorMsg}}
                    </div>
                    <div class="home_user_buyandsell_box_form-bottom">
                        <button @click="hendelPoolBuy">BUY</button>
                    </div>
                </div>
                <div v-show="isSellTabSelected">
                    <div class="home_user_buyandsell_box_form-body">
                        <div>
                            <label>Asset pools:</label>   
                            <br>                                                                  
                            <select v-if="userSellAssetPools.length" v-model="selectAssetPoolSellId" autocomplete="off">                                                    
                                <option value="0">---Select pool---</option>
                                <option 
                                    v-for="pool in userSellAssetPools" 
                                    :value="pool.pool_id"
                                    :key="pool.pool_id"
                                >{{ pool.pool_name }} - ${{ pool.price_usd_print }}</option>
                                <!-- <img src="/{{ $pool->asset_pool->logo }}" alt="">                                                                                                                           -->
                                <!-- {{ $pool->pool_balance }} -->                                                                                                                                    
                            </select>   
                            <br>       
                            <label>Rate pools:</label>  
                            <br>                                                                                  
                            <select v-if="userSellRatePools.length" v-model="selectRatePoolSellId" autocomplete="off">                                                    
                                <option value="0">---Select pool---</option>
                                <option 
                                    v-for="pool in userSellRatePools" 
                                    :value="pool.pool_id"
                                    :key="pool.pool_id"
                                >{{ pool.pool_name }} - ${{ pool.price_usd_print }}</option>
                                <!-- <img src="/{{ $pool->asset_pool->logo }}" alt="">                                                                                                                           -->
                                <!-- {{ $pool->pool_balance }} -->                                                                                                                                    
                            </select>                                 
                            <label v-else>No rate pools</label>                                                                                             
                        </div>
                        <div>
                            <label>Sum:</label>
                            <input v-model="valuePoolSell" autocomplete="off">
                        </div>
                    </div>
                    <div class="validation_box" v-show="validationErrorMsg">
                        {{validationErrorMsg}}
                    </div>
                    <div class="home_user_buyandsell_box_form-bottom">
                        <button @click="hendelPoolSell">SELL</button>
                    </div>
                </div>
            </div>                      
        </div>
    </div>
</template>

<script setup>    
    import { onMounted, ref } from "vue"; 
    import { fetchUserAssetPools, fetchUserRatePools, postBuyandsellPool, fetchUserProfile, fetchAssetPools, fetchRatePools } from './PoolSellBuyBox.js';  
    import { updateUserProfile } from '../helpers/redrawer';  
    import { formatDateYMD } from '../lib/date';  

    const valuePoolSell = ref(0);
    const valuePoolBuy = ref(0);
    const userSellAssetPools = ref([]);    
    const userBuyAssetPools = ref([]);    
    const selectAssetPoolSellId = ref(0);
    const selectAssetPoolBuyId = ref(0);
    const userSellRatePools = ref([]);    
    const userBuyRatePools = ref([]);    
    const selectRatePoolSellId = ref(0);
    const selectRatePoolBuyId = ref(0);
    const userBuyandsellBalance = ref(0);    
    const isBuyTabSelected = ref(true);    
    const isSellTabSelected = ref(false);    
    const dateFromRatePoolBuy = ref(formatDateYMD(new Date()));
    const dateToRatePoolBuy = ref(formatDateYMD(new Date((new Date()).setDate((new Date()).getDate() + 30))));    
    const validationErrorMsg = ref('');

    const selectTab = async (tab) => {
        isBuyTabSelected.value = tab == 'buy';        
        isSellTabSelected.value = tab == 'sell';        
        if (isSellTabSelected.value) {
            updateSellAssetPools();    
            updateSellRatePools();         
        } else if (isBuyTabSelected.value) {
            updateBuyAssetPools();    
            updateBuyRatePools();
        }
    } 

    const updateBuyAssetPools = async () => {
        userBuyAssetPools.value = await fetchAssetPools();
    } 

    const updateBuyRatePools = async () => {
        userBuyRatePools.value = await fetchRatePools();
    } 
    
    const updateSellAssetPools = async () => {
        userSellAssetPools.value = await fetchUserAssetPools();
    } 

    const updateSellRatePools = async () => {
        userSellRatePools.value = await fetchUserRatePools();
         //console.log(userSellAssetPools.value);
    } 

    const hendelPoolSell = async () => 
    {
        validationErrorMsg.value = '';
        const sum = parseFloat(valuePoolSell.value);
        let updatesData = null;
        if (parseInt(selectAssetPoolSellId.value) && sum > 0) {
            const poolId = selectAssetPoolSellId.value;        
            updatesData = await postBuyandsellPool({ 
                                    poolType:'assets-pool',
                                    operationType:'sell',
                                    poolId,
                                    sum
                                });   
            if (!validatePoolSellBuy(updatesData)) {                
                return false;
            }            
            updateSellAssetPools();
        }
        if (parseInt(selectRatePoolSellId.value) && sum > 0) {
            const poolId = selectRatePoolSellId.value;        
            updatesData = await postBuyandsellPool({ 
                                    poolType:'rate-pool',
                                    operationType:'sell',
                                    poolId,
                                    sum
                                });   
            if (!validatePoolSellBuy(updatesData)) {                
                return false;
            }                                
            updateSellRatePools();
        }
        if (updatesData && updatesData.balance_usd_print != '') {
            updateUserProfile('$' + updatesData.balance_usd_print);
            userBuyandsellBalance.value = updatesData.balance_usdt_print;            
            updatesData = null;
        }
        
        // console.log(updatesData);            
        // const select = document.querySelector('#select-pool-sell');            
        // while (select.length > 1) {
        //     select.remove(1);
        // }
        // const assetPools = await fetchUserAssetPools();
        // assetPools.forEach(item => {
        //     let opt = document.createElement('option');
        //     opt.value = item.pool_id;
        //     opt.innerHTML = item.pool_name + ' - $' + item.price_usd_print;
        //     if (poolId == opt.value) {
        //         opt.selected = true;
        //     }
        //     select.appendChild(opt);                
        // });            
        //console.log(assetPools);
    
    }

    const hendelPoolBuy = async () => 
    {
        validationErrorMsg.value = '';
        const sum = parseFloat(valuePoolBuy.value);
        let updatesData = null;
        if (parseInt(selectAssetPoolBuyId.value) && sum > 0) {
            const poolId = selectAssetPoolBuyId.value;        
            updatesData = await postBuyandsellPool({ 
                                    poolType: 'assets-pool',
                                    operationType: 'buy',
                                    poolId,
                                    sum
                                }); 
            if (!validatePoolSellBuy(updatesData)) {                
                return false;
            }           
            updateBuyAssetPools();
        }
        if (parseInt(selectRatePoolBuyId.value) && sum > 0) {
            const poolId = selectRatePoolBuyId.value;        
            updatesData = await postBuyandsellPool({ 
                                    poolType: 'rate-pool',
                                    operationType: 'buy',
                                    poolId,
                                    sum,
                                    dateStart: dateFromRatePoolBuy.value,
                                    dateEnd: dateToRatePoolBuy.value,
                                }); 
            if (!validatePoolSellBuy(updatesData)) {                
                return false;
            }  
            updateBuyRatePools();
        }
        if (updatesData && updatesData.balance_usd_print != '') {
            updateUserProfile('$' + updatesData.balance_usd_print);
            userBuyandsellBalance.value = updatesData.balance_usdt_print;            
            updatesData = null;
        }
    }

    const validatePoolSellBuy = response => {
        if (response.errors) {            
            validationErrorMsg.value = response.errors['sum'][0];
            //console.log(msg);
            return false;
        }               
        return true;        
    }

    onMounted(async () => {
        const userProfile = await fetchUserProfile();        
        userBuyandsellBalance.value = userProfile.balance_usdt_print;
        updateBuyAssetPools();      
        updateBuyRatePools();   
    })

</script>

<style scoped>

</style>