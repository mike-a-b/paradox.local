import {createApp} from "vue";

//import 'vue-cool-select/dist/themes/bootstrap.css'

import AssetPoolsIndex from './pages/asset-pools/Index.vue';
import RatePoolsIndex from './pages/rate-pools/Index.vue';
import PoolTemplatesEdit from './pages/pool-templates/Edit.vue';
import DashboardTransactions from './pages/dashboard/Transactions.vue';
import DashboardNotificationsLog from './pages/dashboard/NotificationsLog.vue';
import DashboardExchangeInfo from './pages/dashboard/ExchangeInfo.vue';
import DashboardExchangeBotLog from './pages/dashboard/ExchangeBotLog.vue';

import UsersPoolAutocomplete from './pages/users/UsersPoolAutocomplete.vue';
import UsersRatePoolAutocomplete from './pages/users/UsersRatePoolAutocomplete.vue';


// const urlInfo = (() => {
//     const url = new URL(location.href);
//     const path = url.pathname;
//     const controller = path.split('/')[1];
//     const action = path.split('/')[2];
//     return { controller, 
//              action};
// })();

//console.log(urlInfo);

const app = createApp({});
// if (urlInfo.controller === 'projects' && urlInfo.action !== "") {
    app.component('asset-pools-index', AssetPoolsIndex);
    app.component('rate-pools-index', RatePoolsIndex);
    app.component('pools-templates-edit', PoolTemplatesEdit);
    app.component('dashboard-transactions', DashboardTransactions);
    app.component('dashboard-notifications_log', DashboardNotificationsLog);
    app.component('dashboard-exchange-info', DashboardExchangeInfo);
    app.component('dashboard-exchange-bot-log', DashboardExchangeBotLog);

    app.component('user-pool-autocomplete', UsersPoolAutocomplete);
    app.component('user-rate_pool-autocomplete', UsersRatePoolAutocomplete);
// }
app.mount('#body');