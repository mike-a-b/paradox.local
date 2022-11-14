import {createApp} from "vue";
import {createI18n} from "vue-i18n"
import {cookies} from './lib/cookies.js';

const lng = cookies.getItem('lng') ? cookies.getItem('lng') : 'en';

import en from './lang/en.json'
import ru from './lang/ru.json'
import es from './lang/es.json';

const i18n = createI18n({
    locale: lng,
    fallbackLocale: 'en',
    messages: {
        en,
        ru,
        es
    },
});

import AssetPoolSellBuyBox from './components/AssetPoolSellBuyBox.vue';
import UserHistory from './components/UserHistory.vue';
import UserAvaUpload from './components/UserAvaUpload.vue';

import {initPage as initPageHome} from './pages/home';
import {initPage as initPageInvestShow} from './pages/invest-show';

document.addEventListener("DOMContentLoaded", async _ => {
    await initPageHome();
    await initPageInvestShow();
});

const app = createApp({});
// if (urlInfo.controller === 'projects' && urlInfo.action !== "") {
app.component('asset-pool-sell-buy-box', AssetPoolSellBuyBox);
app.component('user-ava-upload', UserAvaUpload);
app.component('user-history-page', UserHistory);
// }
app.use(i18n);
app.mount('#body');
