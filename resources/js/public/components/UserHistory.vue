<template>
    <div class="history_wrapper">
        <h1 class="history_title">{{ $t('user_history.History') }}</h1>
        <div class="history_main_items_wrapper">
        <div class="invest-period-menu">
            <span :class = "(date_active=='24hours')?'item active':'item'" @click="handelShowDate('24hours')">24h</span>
            <span :class = "(date_active=='7days')?'item active':'item'" @click="handelShowDate('7days')">7d</span>
            <span :class = "(date_active=='30days')?'item active':'item'" @click="handelShowDate('30days')">M</span>
            <span :class = "(date_active=='90days')?'item active':'item'" @click="handelShowDate('90days')">3M</span>
            <span :class = "(date_active=='ALL')?'item active':'item'" @click="handelShowDate('ALL')">ALL</span>
        </div>
            <div v-for="history in historyList" class="history_main_item" :key="history.id+''+history.transaction_type">
                <p class="history_main_item_date">{{ history.date_print }}</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img v-if="history.transaction_type"
                                     :src="'/images/' + (history.deposit_usd > 0 ? 'child_img1.svg' : 'child_img5.svg')"
                                     alt="">
                                <img v-else
                                     :src="'/images/' + (history.changer_field == 'deposits_usdt' ? 'child_img1.svg' : 'child_img5.svg')"
                                     alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">
                                    <template v-if="history.transaction_type == 'P'">{{
                                            $t('user_history.Percentage')
                                        }}
                                    </template>
                                    <template v-else-if="history.transaction_type == 'U'">
                                        {{
                                            $t('user_history.' + history.transaction_type_print.replaceAll(' ', '_'))
                                        }}-{{
                                            history.deposit_usd > 0 ? $t('user_history.Buy') : $t('user_history.Sell')
                                        }}
                                    </template>
                                    <template v-else>
                                        {{ $t('user_history.' + history.transaction_type_print.replaceAll(' ', '_')) }}
                                    </template>
                                </p>
                                <p class="history_main_item_child_info">{{ history.time_print }}</p>
                            </div>
                        </div>
                        <div class="history_main_item_child2">
                            <div class="history_main_item_child_img_info">
                                <template v-if="history.pool_id">
                                    <div class="history_main_item_child_img2">
                                        <img :src="'/'+ history.pool_logo" alt=""/>
                                    </div>
                                    <div class="history_main_item_child_infos">
                                        <a :href="'/invests/pool/' + history.pool_id"
                                           class="history_main_item_child_info_link">{{ history.pool_name }}</a>
                                        <p class="history_main_item_child_info"></p>
                                    </div>
                                </template>
                                <template v-if="history.r_pool_id">
                                    <div class="history_main_item_child_img2">
                                        <img :src="'/'+ history.r_pool_logo" alt=""/>
                                    </div>
                                    <div class="history_main_item_child_infos">
                                        <div class="history_main_item_child_info_link">{{ history.r_pool_name }}</div>
                                        <p class="history_main_item_child_info"></p>
                                    </div>
                                </template>
                            </div>
                            <div class="history_main_item_child_info_wrapper">
                                <div class="history_main_item_child_info_img_wrapper"
                                     style="flex-wrap: wrap; width: 90px;justify-content: center;">
                                    <div
                                        :class="{
                                            'fraction_color_plus': (history.transaction_type && history.deposit_usd > 0) || (!history.transaction_type && history.changer_field == 'deposits_usdt'),
                                            'fraction_color_minus': (history.transaction_type && history.deposit_usd < 0) || (!history.transaction_type && history.changer_field != 'deposits_usdt'),
                                        }"
                                        style="flex: 0 0 100%;text-align:center"
                                    >
                                        {{ history.deposit_usd_print }}$
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isButtonVisible" class="more_transactions_btn_wrapper">
            <button @click="hendelShowMore" class="more_transactions_btn">{{
                    $t('user_history.More_transactions')
                }}
            </button>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";

import {fetchHistoryList} from "../api/UserHistoryServeice";

const historyList = ref([]);
const isButtonVisible = ref(false);
let historyListNext = [];
let offset = 0;
let count = 10;
let date = 'ALL';
const date_active = ref('ALL');


const updateHistoryList = async () => {
    const list = await fetchHistoryList({offset, count,date});
    historyList.value = Object.entries(list).map(item => item[1]);
    // console.log(historyList.value);
    offset += count;
    const listNext = await fetchHistoryList({offset, count, date});
    historyListNext = Object.entries(listNext).map(item => item[1]);
    offset += count;
    isButtonVisible.value = historyListNext.length > 0;
}

const hendelShowMore = async () => {
    isButtonVisible.value = historyListNext.length == count;
    // Object.entries(historyListNext).forEach(item => historyList.value[item[0]] = item[1]);
    historyList.value = [...historyList.value, ...historyListNext];
    const listNext = await fetchHistoryList({offset, count,date});
    historyListNext = Object.entries(listNext).map(item => item[1]);
    offset += count;
}

const handelShowDate = async (new_date) => {
    date = new_date;
    date_active.value = new_date;
    console.log(date_active);
    offset = 0;
    count = 10;
    updateHistoryList();
}

onMounted(async () => {
    updateHistoryList();
})

</script>

<style scoped>

</style>
