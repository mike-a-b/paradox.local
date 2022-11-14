<template>
    
    <div class="home_user_buyandsell_box">
        <div class="home_user_buyandsell_box_header">
            BALABCE <span class="home_user_buyandsell_balance" v-html="userBuyandsellBalance"></span> USDT
        </div>
        <div class="home_user_buyandsell_box_body">
            <div class="home_user_buyandsell_box_form">
                <div class="home_user_buyandsell_box_form-header">
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isBuyTabSelected}" @click="selectTab('buy')">Buy</div>
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isSellTabSelected}" @click="selectTab('sell')">Sell</div>
                </div>
                <div>
                    <div class="home_user_buyandsell_box_form-body">
                        <div class="home_user_buyandsell_box_form-balance">                            
                            <span><b>{{ userAssetPool.price_usd ? userAssetPool.price_usd_print : 0 }}</b> USDT</span>
                            <label>Pool balance</label>
                        </div>
                        <div class="home_user_buyandsell_box_form-sum">                                                        
                            <input 
                                v-model="valuePool"
                                class="settings_form_input_field"
                                :class="{'is-invalid': validationErrorMsg}"                                  
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="validation_box" v-show="validationErrorMsg">
                        {{validationErrorMsg}}
                    </div>
                    <div class="home_user_buyandsell_box_form-bottom">
                        <button 
                            class="settings_form_button" 
                            @click="hendelPoolSellBuy(isSellTabSelected ? 'sell' : 'buy')"
                        >
                            {{ isSellTabSelected ? 'SELL' : 'BUY' }}
                        </button>
                    </div>
                </div>
                <!-- <div v-show="isSellTabSelected">
                    <div class="home_user_buyandsell_box_form-body">
                        <div>
                            Pool balance:
                            <br>
                            <b>{{ userAssetPool.price_usd ? userAssetPool.price_usd_print : 0 }}</b> USDT                            
                        </div>
                        <div>
                            <label>Sum:</label>
                            <input v-model="valuePool" autocomplete="off">
                        </div>
                    </div>
                    <div class="validation_box" v-show="validationErrorMsg">
                        {{validationErrorMsg}}
                    </div>
                    <div class="home_user_buyandsell_box_form-bottom">
                        <button @click="hendelPoolSellBuy('sell')">SELL</button>
                    </div>
                </div> -->
            </div>                      
        </div>
    </div>
</template>

<script setup>    
    import { onMounted, ref } from "vue"; 
    import { fetchUserAssetPools, postBuyandsellPool, fetchUserProfile} from './PoolSellBuyBox.js';  
    import { updateUserProfile } from '../helpers/redrawer';      

    const poolId = ref(0);
    const valuePool = ref(0);
    //const valuePoolBuy = ref(0);
    const userAssetPool = ref([]);           
    const userBuyandsellBalance = ref(0);    
    const isBuyTabSelected = ref(true);    
    const isSellTabSelected = ref(false);        
    const validationErrorMsg = ref('');

    const props = defineProps({
        poolId: Number
    })

    const selectTab = async (tab) => {
        isBuyTabSelected.value = tab == 'buy';        
        isSellTabSelected.value = tab == 'sell';        
        updateAssetPools();
        valuePool.value = 0;
    }     
    
    const updateAssetPools = async () => {
        const list = await fetchUserAssetPools({poolId:poolId.value});
        userAssetPool.value = list && list[0] ? list[0] : null;
        //console.log(userAssetPool.value);
    } 

    const hendelPoolSellBuy = async (operationType) => 
    {
        validationErrorMsg.value = '';
        const sum = parseFloat(valuePool.value);
        let updatesData = null;
        if (sum > 0) {                  
            updatesData = await postBuyandsellPool({ 
                                    poolType:'assets-pool',
                                    operationType: operationType,
                                    poolId: poolId.value,
                                    sum
                                });   
            if (!validatePoolSellBuy(updatesData)) {                
                return false;
            }            
            updateAssetPools();
            valuePool.value = 0;
        }
        
        if (updatesData && updatesData.balance_usd_print != '') {
            updateUserProfile('$' + updatesData.balance_usd_print);
            userBuyandsellBalance.value = updatesData.balance_usdt_print;            
            updatesData = null;
        }        
    }

    // const hendelPoolBuy = async () => 
    // {
    //     validationErrorMsg.value = '';
    //     const sum = parseFloat(valuePoolBuy.value);
    //     let updatesData = null;
    //     if (sum > 0) {            
    //         updatesData = await postBuyandsellPool({ 
    //                                 poolType: 'assets-pool',
    //                                 operationType: 'buy',
    //                                 poolId: poolId.value,
    //                                 sum
    //                             }); 
    //         if (!validatePoolSellBuy(updatesData)) {                
    //             return false;
    //         }    
    //         updateAssetPools();                   
    //     }        
    //     if (updatesData && updatesData.balance_usd_print != '') {
    //         updateUserProfile('$' + updatesData.balance_usd_print);
    //         userBuyandsellBalance.value = updatesData.balance_usdt_print;            
    //         updatesData = null;
    //     }
    // }

    const validatePoolSellBuy = response => {
        if (response.errors) {            
            validationErrorMsg.value = response.errors['sum'][0];
            //console.log(msg);
            return false;
        }               
        return true;        
    }

    onMounted(async () => {
        poolId.value = props.poolId;
        const userProfile = await fetchUserProfile();        
        userBuyandsellBalance.value = userProfile.balance_usdt_print;                   
        updateAssetPools();                
    })

</script>

<style scoped>

</style>