<template>
    <div class="card d-inline-flex">
        <div class="card-header">
            <div class="card-tools">
                <button class="btn btn-success btn-xs" @click="showModal()">+ add pool</button>
            </div>            
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
        <table class="table" style="max-width:900px;">
            <thead>
                <tr class="text-gray">
                    <th>ID</th>
                    <th>Name</th>                            
                    <th>Rate</th>                                                
                    <th>Active</th>                                                
                    <th>updated_at</th>
                    <th></th>
                    <th></th>                            
                </tr>
            </thead>
            <tbody>        
                <template v-for="(data, index) in poolsList" :key="data.id">
                <tr>
                    <td
                        v-if="data.asset_pool_group_id != poolsList[index-1]?.asset_pool_group_id"
                        class="py-1 text-secondary bg-light"
                        colspan="9"
                    >
                        {{ data.group_name }}
                    </td>
                </tr>
                <tr> 
                    <td>{{ data.id }}</td>                            
                    <td>
                        <span class="text-bold">{{ data.name }}</span>                                                    
                    </td>
                    <td class="text-left">{{ data.rate }}%</td>                    
                    <td class="text-left">                    
                        <i v-if="data.is_active" class="fa fa-check text-secondary table-row-icon"></i>                    
                    </td>                                                                 
                    <td>{{ data.updated_at }}</td>
                    <td class="text-center">                        
                        <i @click="editModal(data.id)" class="fa fa-edit table-row-icon pointer"></i>                        
                    </td>                            
                    <td>                        
                        <i @click="deleteModal(data.id)" class="fa fa-trash text-danger table-row-icon pointer"></i>                        
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix d-flex justify-content-center pt-4">                            
        </div>
    </div>
    <!-- /.card --> 
    <teleport to="body">
    <transition> 
        <div v-if="isModalActive" class="background-owerlay-gray">            
            <!-- @click.stop.prevent="closeModal()" -->
            <div class="modal-dialog modal-dialog-centered" style="max-width:600px;" role="document">
                <div class="modal-content">
                    <div class="modal-header py-1">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ modalItemId ? 'Edit' : 'Add' }} pool</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex">
                        <div class="flex-grow-1">
                            <div class="form-group">
                                <label>Group</label>
                                <select v-model="mFieldGroupId" :class="{'is-invalid':validation.poolGroup}" class="form-control form-control-sm">                            
                                    <option v-for="group in poolGroupsList" :key="group.id" :value="group.id">{{ group.name }}</option>                            
                                </select>
                            </div>                                                    
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="mFieldName" :class="{'is-invalid':validation.poolName}" class="form-control form-control-sm" autocomplete="off">
                            </div>
                            <div class="form-group mb-1">
                                <label class="mb-1 text-gray">Name short</label>
                                <input v-model="mFieldNameShort" class="form-control form-control-sm" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Currency</label>                                
                                <select v-model="mFieldCurrencyId" :class="{'is-invalid':validation.poolCurrencyId}" class="form-control form-control-sm">                            
                                    <option v-for="curr in currenciesList" :key="curr.id" :value="curr.id">{{ curr.name }}</option>                            
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <div class="position-relative" style="min-height:70px;">
                                    <textarea 
                                        v-show="localizationNavs.description_en"
                                        v-model="mFieldDescription" 
                                        :class="{'is-invalid':validation.poolDescription}" 
                                        class="position-absolute form-control form-control-sm" 
                                        autocomplete="off"
                                    >
                                    </textarea>
                                    <textarea 
                                        v-show="localizationNavs.description_ru"
                                        v-model="mFieldDescriptionRu" 
                                        :class="{'is-invalid':validation.poolDescription}" 
                                        class="position-absolute form-control form-control-sm" 
                                        autocomplete="off"
                                    >
                                    </textarea>
                                    <ul class="description_langs_nav">
                                        <li @click="switchLocNav('description_en')" :class="{'active':localizationNavs.description_en}">EN</li>
                                        <li @click="switchLocNav('description_ru')" :class="{'active':localizationNavs.description_ru}">RU</li>
                                    </ul>
                                </div>
                            </div>                       
                            <div v-if="modalItemId" class="form-group d-flex align-items-center">
                                <div>
                                    <img v-if="mFieldLogo" width="64" :src="'/'+mFieldLogo" alt="">
                                    <img v-else width="64" src="/assets/imgs/pool/default.png" alt="">
                                </div>
                                <div class="flex-fill pl-3">
                                    <input type="file" @change="handleLogoFile" class="form-control form-control-sm" id="logoFile" >
                                </div>                        
                            </div>
                            <div class="form-check">
                                <input type="checkbox" :checked="mFieldIsAvaliable" v-model="mFieldIsAvaliable" class="form-check-input" name="is_active" id="isActive">
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                        <div class="ml-5" style="min-width:200px;">
                            <div class="form-group mb-0">
                                <label>Rates</label>
                            </div>
                            <div style="max-height:200px; padding-top:2px; overflow-y:auto;">    
                                <div class="form-group">
                                    <label class="font-weight-normal">Rate:</label>                                 
                                    <input v-model="mFieldRate" :class="{'is-invalid':validation.mFieldRate}" class="form-control form-control-sm d-inline-block ml-2" style="width:80px;" autocomplete="off">%
                                </div>                                
                            </div>                            
                        </div>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()" data-dismiss="modal">Close</button>
                        <button type="button" :disabled="isSaveModalDisabled" class="btn btn-primary" @click="saveModal()">Save changes</button>
                    </div>
                </div>
            </div>            
        </div>
    </transition>
    </teleport>
</template>

<script setup>
import { onMounted, ref, reactive } from "vue";
import { fetchGroupsList } from "../../api/AssetPoolGroups";
import { fetchCurrenciesList } from "../../api/Currencies";
import { fetchPoolsList, createPool, deletePool, showPool, updatePool, updatePoolLogo } from "../../api/RatePools";


// let search = ref(''); 
let poolsList = ref([]);
let poolGroupsList = ref([]);
let currenciesList = ref([]);
let isModalActive = ref(false);
let mFieldName = ref('');
let mFieldNameShort = ref('');
let mFieldDescription = ref('');
let mFieldDescriptionRu = ref('');
let mFieldLogo = ref('');
let mFieldCurrencyId = ref(0);
let mFieldIsAvaliable = ref(true);
let mFieldGroupId = ref(0);
let mFieldRate = ref(0);
// let mFieldDateFrom = ref('');
// let mFieldDateTo = ref('');
let isSaveModalDisabled = ref(false);
let modalItemId = ref(0);
let newAssetId = ref(0);
let newAssetItem = ref(0);

const validation = reactive({
    poolGroup: false,
    poolName: false,
    poolCurrencyId: false,
    poolDescription: false,
    newAssetItem: false,    
    mFieldRate: false,
    // mFieldDateFrom: false,
    // mFieldDateTo: false,
});

const localizationNavs = reactive({
    description_en: true,
    description_ru: false,
});

const switchLocNav = field => {
    const keyBase = field.split('_')[0];    
    for (const key of Object.keys(localizationNavs)) {
        if (key.includes(keyBase)) {
            localizationNavs[key] = false;            
        }        
    }
    localizationNavs[field] = true;
    //console.log(localizationNavs);
}

const validatePoolBaseInfo = () => {
    validationReset();
    let ret = true;
    if (!mFieldGroupId.value) {
        validation.poolGroup = true;
        ret = false;
    }
    if (mFieldName.value.length < 2) {
        validation.poolName = true;
        ret = false;
    }
    if (mFieldDescription.value.length < 2) {
        validation.poolDescription = true;
        ret = false;
    }
    return ret;
}

const validateNewItemInfo = () => {
    validationReset();
    let ret = true;
    if (!newAssetItem.value) {
        validation.newAssetItem = true;
        ret = false;
    }    

    return ret;
}

const validationReset = () => {
    validation.poolGroup = false;
    validation.poolName = false;
    validation.poolCurrencyId = false;
    validation.poolDescription = false;
    validation.newAssetItem = false;    
    validation.mFieldRate = 0;
    // validation.mFieldDateFrom = false;
    // validation.mFieldDateTo = false;    
}

const localizationReset = () => {      
    for (const key of Object.keys(localizationNavs)) {        
        localizationNavs[key] = key.includes('_en');
    }    
}

const nameWithMultiselect = ({ name, symbol }) => {
      return `${name} — [${symbol}]`
    };

const handleLogoFile = async (event) => {
    const ret = await updatePoolLogo(modalItemId.value, {file:event.target.files[0]});
    mFieldLogo.value = ret.logo + '?' + Math.random();    
}

const modalSelectAssetItem = (item) => {
    newAssetId.value = item.id;    
}

const updateList = async () => {
    const pList = await fetchPoolsList(); // {withItems:true}
    poolsList.value = pList.map(item => {
        item.price_usd = Math.floor(1000*item.price_usd)/1000;
        return item;
    });
}

const updatePoolGroupsList = async () => {
    poolGroupsList.value = await fetchGroupsList({g_type:2});
}
const updateCurrenciesList = async () => {
    currenciesList.value = await fetchCurrenciesList();
}

const closeModal = () => {
    modalItemId.value = 0;   
    mFieldName.value = '';
    mFieldNameShort.value = '';
    mFieldCurrencyId.value = 0;
    mFieldDescription.value = '';
    mFieldDescriptionRu.value = '';
    mFieldLogo.value = '';
    mFieldIsAvaliable.value = true;
    mFieldRate.value = 0;
    // mFieldDateFrom.value = false;
    // mFieldDateTo.value = false;
    isModalActive.value = false;    
    newAssetId.value = 0;
    newAssetItem.value = null;    
    mFieldGroupId.value = 0;
    validationReset();
    localizationReset();
    updateList();
}
const showModal = () => {
    isModalActive.value = true;    
}
const editModal = async (id) => {    
    modalItemId.value = id;
    showModal();
    const data = await showPool(id);
    //const assets = await getAssetsList({poolId:id, withAssets:true});    
    //console.log(data);    
    mFieldName.value = data.name;
    mFieldNameShort.value = data.name_short;
    mFieldCurrencyId.value = data.currency_id;
    mFieldDescription.value = data.description ? data.description : '';
    mFieldDescriptionRu.value = data.description_ru ? data.description_ru : '';
    mFieldLogo.value = data.logo ? (data.logo + '?' + Math.random()) : '';
    mFieldIsAvaliable.value = data.is_active;
    mFieldGroupId.value = data.asset_pool_group_id;
    mFieldRate.value = data.rate;
    // mFieldDateFrom.value = data.date_start;
    // mFieldDateTo.value = data.date_end;
    //mAssets.value = assets;
    //console.log(modalItemId.value);
}
const saveModal = async () => {
    if (!validatePoolBaseInfo()) {
        return false;
    }

    isSaveModalDisabled.value = true;

    if (modalItemId.value) {                      
        await updatePool(
            modalItemId.value,
            {
                name: mFieldName.value,
                nameShort: mFieldNameShort.value,
                currency_id: mFieldCurrencyId.value,
                description: mFieldDescription.value, 
                descriptionRu: mFieldDescriptionRu.value,                                
                isAvaliable: mFieldIsAvaliable.value,
                assetPoolGroupId: mFieldGroupId.value,
                rate: mFieldRate.value,
                // dateStart: mFieldDateFrom.value,
                // dateEnd: mFieldDateTo.value,
            }
        );
        closeModal();
    } else {
        const pool = await createPool({
            name: mFieldName.value,
            nameShort: mFieldNameShort.value,
            currency_id: mFieldCurrencyId.value,
            description: mFieldDescription.value,  
            descriptionRu: mFieldDescriptionRu.value,                                          
            isAvaliable: mFieldIsAvaliable.value,
            assetPoolGroupId: mFieldGroupId.value,
            rate: mFieldRate.value,
            // dateStart: mFieldDateFrom.value,
            // dateEnd: mFieldDateTo.value,
        });
        closeModal();
        editModal(pool.id);
    }
    isSaveModalDisabled.value = false;
}
const deleteModal = async (id) => {
    if (confirm(`Delete ${id}?`)) {
        await deletePool(id);
        updateList();
    }    
}

onMounted(() => {    
  // const dateFrom = "2021-01-01";
  // const dateTo = "2021-10-01";
  // const url = new URL(location.href);
  // const coin = url.searchParams.get("symbol");
  // const symbolBlockchain = coin ? coin.split("-") : ["btc", "bitcoin"];
  // const symbol = symbolBlockchain[0];
  // const blockchain = symbolBlockchain[1];
  // const pair = symbol.toUpperCase() + "USDT";
    
    updateList();        
    updatePoolGroupsList();
    updateCurrenciesList();
});
</script>

<style scoped>
</style>