<template>
    <div class="home_user_buyandsell_box">
        <div class="home_user_buyandsell_box_body">
            <div class="home_user_buyandsell_box_form">
                <div class="home_user_buyandsell_box_form-header">
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isBuyTabSelected}" @click="selectTab('buy')">
                        {{ $t("sell_buy_box.Buy") }}
                    </div>
                    <div class="home_user_buyandsell_box-button-tab" :class="{'home_user_buyandsell_box-button-tab-selected': isSellTabSelected}" @click="selectTab('sell')">
                        {{ $t("sell_buy_box.Sell") }}
                    </div>
                </div>
                <div>
                    <div class="home_user_buyandsell_box_form-dir" :class="{'home_user_buyandsell_box_form-dir-rev': isBuyTabSelected}">
                        <div class="home_user_buyandsell_box_form-body">
                            <div class="home_user_buyandsell_box_form-select">
                                <div v-show="isBuyTabSelected">
                                    <label>{{ $t("sell_buy_box.Receive") }}</label>
                                    <div v-if="selectAssetPoolBuy" class="settings_form_input_field">
                                        <img v-show="selectAssetPoolBuy.pool_logo" class="option__image" :src="'/'+selectAssetPoolBuy.pool_logo" alt="">
                                        <span class="option__desc">
                                            <span class="option__title">
                                                {{ selectAssetPoolBuy.pool_name_short }}{{ selectAssetPoolBuy.price_usd ? ' - $' + selectAssetPoolBuy.price_usd_print - poolCommissionSellUsd : '' }}
                                            </span>
                                        </span>
                                    </div>
                                    <label v-if="selectAssetPoolBuy">
                                        {{ $t("sell_buy_box.Commission") }}: <span class="balance">{{ poolCommissionBuyUsd }}</span>$   <span class="commission">/ {{selectAssetPoolBuy.commission_sell}}%</span>
                                    </label>
                                </div>
                                <div v-show="isSellTabSelected">
                                    <label>{{ $t("sell_buy_box.Spend") }}</label>

                                    <div v-if="selectAssetPoolSell" class="settings_form_input_field">
                                        <img v-show="selectAssetPoolSell.pool_logo" class="option__image" :src="'/'+selectAssetPoolSell.pool_logo" alt="">
                                        <span class="option__desc">
                                            <span class="option__title">
                                                {{ selectAssetPoolSell.pool_name_short }}{{ selectAssetPoolSell.price_usd ? ' - $' + selectAssetPoolSell.price_usd_print - poolCommissionSellUsd : '' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="home_user_buyandsell_box_form-sum 1">
                            <label class="top">{{ isBuyTabSelected ? $t("sell_buy_box.Pay_with") : $t("sell_buy_box.Receive") }}</label>
                            <input
                                v-model="valuePool"
                                class="settings_form_input_field"
                                :class="{'is-invalid': validationErrorMsg}"
                                v-on:change="inputValuePool"
                                autocomplete="off"
                            >

                            <button :class = "(settings_form_button==50)?'settings_form_button_50 active':'settings_form_button_50'" @click="handelPoolSellBuyPercent(0.5)">50%</button>
                            <button :class = "(settings_form_button==100)?'settings_form_button_100 active':'settings_form_button_100'" @click="handelPoolSellBuyPercent(1)">100%</button>
                            <label v-show="invalidBalance" class="invalid-balance">{{ $t("sell_buy_box.invalidBalance") }}</label>
                            <br>
                            <label class="bottom">{{ $t("sell_buy_box.Balance") }} &#8211; <span class="balance" v-html="userBuyandsellBalance"></span> USDT</label>
                            <label v-if="isSellTabSelected" class="bottom">
                                <br>
                                {{ $t("sell_buy_box.Commission") }}: <span class="balance">{{ poolCommissionSellUsd }}</span>$  <span class="commission">/ {{selectAssetPoolBuy.commission_sell}}%</span>
                            </label>
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
                            {{ isSellTabSelected ? $t("sell_buy_box.SELL") : $t("sell_buy_box.BUY") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, ref, computed } from "vue";
    //import VueMultiselect from 'vue-multiselect'
    import { fetchUserAssetPools, fetchAssetPools, postBuyandsellPool, fetchUserProfile} from './PoolSellBuyBox.js';
    import { updateUserProfile } from '../helpers/redrawer';

    const poolId = ref(0);
    const valuePool = ref(0);
    const selectAssetPoolBuy = ref(null);
    const selectAssetPoolSell = ref(null);
    // const userSellAssetPools = ref([]);
    // const userBuyAssetPools = ref([]);
    const userBuyandsellBalance = ref(0);
    const isBuyTabSelected = ref(true);
    const isSellTabSelected = ref(false);
    const validationErrorMsg = ref('');
    const invalidBalance = ref(false);
    const settings_form_button = ref(0);

    const props = defineProps({
        poolId: Number
    })

    const calcPoolCommission = (val, comm) => {
        const ret = Math.floor(100*val*comm/100)/100;

        return ret ? ret : 0;
    }

    const poolCommissionBuyUsd = computed(() => {
        return calcPoolCommission(valuePool.value, selectAssetPoolBuy.value.commission_buy);
    });

    const poolCommissionSellUsd = computed(() => {
        return calcPoolCommission(valuePool.value, selectAssetPoolBuy.value.commission_sell);
    });

    const selectTab = async (tab) => {
        isBuyTabSelected.value = tab == 'buy';
        isSellTabSelected.value = tab == 'sell';
        if (isSellTabSelected.value) {
            updateSellAssetPools();
        } else if (isBuyTabSelected.value) {
            updateBuyAssetPools();
        }
        valuePool.value = 0;
        inputValuePool();
    }

    const updateBuyAssetPools = async () => {
        // userBuyAssetPools.value = await fetchAssetPools({poolId:props.poolId});
        const userBuyAssetPools = await fetchAssetPools({poolId:props.poolId});
        selectAssetPoolBuy.value = userBuyAssetPools[0];
    }

    const updateSellAssetPools = async () => {
        // userSellAssetPools.value = await fetchUserAssetPools({poolId:props.poolId});
        const userSellAssetPools = await fetchUserAssetPools({poolId:props.poolId});
        selectAssetPoolSell.value = userSellAssetPools[0] ? userSellAssetPools[0] : selectAssetPoolBuy.value;
    }
    const inputValuePool =  async () =>
    {
        let balance = userBuyandsellBalance.value.replace(',','');
        balance = balance.replace('<span>','');;
        settings_form_button.value =  (parseFloat(balance) / parseFloat(valuePool.value))* 100;
    }
    const handelPoolSellBuyPercent = async (percent) =>
    {
        settings_form_button.value = percent*100;
        let balance = userBuyandsellBalance.value.replace(',','');
        balance = balance.replace('<span>','');
        valuePool.value = parseFloat(balance) * parseFloat(percent);
    }
    const hendelPoolSellBuy = async (operationType) =>
    {
        const userProfile = await fetchUserProfile();
        const sum = parseFloat(valuePool.value);

        if(sum<=parseFloat(userProfile.balance_usdt)){
            validationErrorMsg.value = '';
            let updatesData = null;
            //if (sum > 0) {
                 const poolId = operationType === 'sell' ? selectAssetPoolSell.value.pool_id : selectAssetPoolBuy.value.pool_id;
                 updatesData = await postBuyandsellPool({
                                    poolType:'assets-pool',
                                    operationType: operationType,
                                    poolId,
                                    sum
                                });
                 if (!validatePoolSellBuy(updatesData)) {
                        return false;
                 }
                 updateSellAssetPools();
                 updateBuyAssetPools();
                 valuePool.value = 0;
               //}

            if (updatesData && updatesData.balance_usd_print != '') {
                updateUserProfile('$' + updatesData.balance_usd_print);
                userBuyandsellBalance.value = updatesData.balance_usdt_print;
                updatesData = null;
            }
        }else{
                         invalidBalance.value = true;
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
        poolId.value = props.poolId;
        // selectAssetPoolSellId.value = poolId.value;
        // selectAssetPoolBuyId.value = poolId.value;
        const userProfile = await fetchUserProfile();
        userBuyandsellBalance.value = userProfile.balance_usdt_print;
        await updateBuyAssetPools();
        // selectAssetPoolBuy.value = userBuyAssetPools.value[0];
        // selectAssetPoolSell.value = userBuyAssetPools.value[0];
        selectAssetPoolSell.value = selectAssetPoolBuy.value;
    })

</script>

<style scoped>

</style>
