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
                    <th>Price</th>                            
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
                        colspan="7"
                    >
                        {{ data.group_name }}
                    </td>
                </tr>
                <tr> 
                    <td>{{ data.id }}</td>                            
                    <td>
                        <span class="text-bold">{{ data.name }}</span>   
                        <div v-if="data.template_name" class="text-gray">
                            <div>
                                <span class="d-inline-block">{{ data.template_name }}</span>,                             
                                <span class="d-inline-block ml-2">
                                    <a class="text-gray" target="_blank" :href="data.meta_cmc_category_url">[.]</a>
                                </span>
                                <span class="d-inline-block ml-2">{{ data.meta_num_tokens }}</span>
                            </div>                            
                            <div style="font-size:12px;">
                                <span>{{ data.rebalanced_at ? data.rebalanced_at : 'not yet' }}</span><br>
                                <span>{{ data.rebalanced_next_at }}</span>
                            </div>
                        </div>                                                                         
                    </td>
                    <td class="text-left">{{ data.price_usd }}$</td>
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
                <tr>
                    <td class="border-0"></td>
                    <td colspan="6" class="border-0 pt-0">
                        <div v-for="asset in data.assets" :key="asset.logo" class="d-inline-block mr-4 mb-1">
                            <img :src="'/'+asset.logo" style="width:18px;" class="mr-1"> 
                            <span class="align-middle">{{ asset.symbol }} {{ asset.fraction }}%</span>
                        </div>
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
            <div class="modal-dialog modal-dialog-centered" style="max-width:770px;" role="document">
                <div class="modal-content">
                    <div class="modal-header py-1">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ modalItemId ? 'Edit' : 'Add' }} pool</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex">
                        <div class="flex-grow-1">
                            <div class="form-group mb-1">
                                <label class="mb-1 text-gray">Group</label>
                                <select v-model="mFieldGroupId" :class="{'is-invalid':validation.poolGroup}" class="form-control form-control-sm">                            
                                    <option v-for="group in poolGroupsList" :key="group.id" :value="group.id">{{ group.name }}</option>                            
                                </select>
                            </div>                                                    
                            <div class="form-group mb-1">
                                <label class="mb-1 text-gray">Name</label>
                                <input v-model="mFieldName" :class="{'is-invalid':validation.poolName}" class="form-control form-control-sm" autocomplete="off">
                            </div>                            
                            <div class="form-group mb-1">
                                <label class="mb-1 text-gray">Name short</label>
                                <input v-model="mFieldNameShort" class="form-control form-control-sm" autocomplete="off">
                            </div>
                            <div class="form-group mb-1">
                                <label class="mb-1 text-gray">Description</label>
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
                            <div v-if="modalItemId" class="form-check mt-2 mb-1">
                                <input 
                                    type="checkbox" 
                                    :checked="mFieldIsTopmarketcapBased" 
                                    v-model="mFieldIsTopmarketcapBased" 
                                    class="form-check-input" 
                                    name="is_topmarketcap_based" 
                                    id="isTopmarketcapBased"
                                    @click="isTopmarketcapBasedChange"
                                >
                                <label class="form-check-label" for="isTopmarketcapBased">Top marketcap based</label>
                            </div>                       
                            <div v-if="showTemplateSelect" class="form-group mb-1">
                                <label class="mb-1 text-gray">Template</label>
                                <select v-model="mFieldTemplateId" class="form-control form-control-sm">
                                    <option key="0" value="0" selected> ---- No ---- </option>
                                    <option v-for="temp in poolTemplatesList" :key="temp.id" :value="temp.id">{{ temp.name }}</option>                            
                                </select>
                            </div>
                            <div v-if="showTemplateSelect" class="form-group mb-1">
                                <label class="mb-1 text-gray">Rebalance frequency(days)</label>
                                <input v-model="mFieldRebalanceFr" :class="{'is-invalid':validation.rebalanceFr}" class="form-control form-control-sm" autocomplete="off">
                                <span class="text-gray">{{ mFieldRebalancedAt }}, {{ mFieldRebalancedNextAt }}</span>
                            </div>
                            <div v-if="modalItemId" class="form-group mb-1 mt-2 d-flex align-items-center">
                                <div>
                                    <img v-if="mFieldLogo" width="64" :src="'/'+mFieldLogo" alt="">
                                    <img v-else width="64" src="/assets/imgs/pool/default.png" alt="">
                                </div>
                                <div class="flex-fill pl-3">
                                    <input type="file" @change="handleLogoFile" class="form-control form-control-sm" id="logoFile" >
                                </div>                        
                            </div>                            
                            <div class="form-check mt-3">
                                <input type="checkbox" :checked="mFieldIsAvaliable" v-model="mFieldIsAvaliable" class="form-check-input" name="is_active" id="isActive">
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                        <div v-show="modalItemId" class="ml-5" style="min-width:360px;">
                            <div class="form-group mb-0">
                                <label class="mb-1 text-gray">Assets</label>
                            </div>
                            <div style="max-height:340px; padding-top:2px; overflow-y:auto;">
                                <table style="margin-top:-3px;">
                                    <tr v-for="asset in mAssets" :key="asset.id" class="mb-2">
                                        <td class="pr-2">
                                            <img :src="'/'+asset.asset_logo" style="width:24px;" class="mr-1">                             
                                            <span class="align-middle">{{ asset.asset_symbol }}</span>
                                        </td>
                                        <td>
                                            <!-- <input class="form-control form-control-sm d-inline-block" style="width:50px;" v-model="asset.fraction" autocomplete="off">% -->
                                            {{asset.fraction}}%
                                        </td>
                                        <td class="pl-2 text-gray">
                                            {{ asset.asset_amount }}
                                        </td>
                                        <td class="pl-2">
                                            <i @click="deletePoolAsset(asset.id, asset.asset_name)" class="fa fa-trash text-danger table-row-icon pointer"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right text-secondary pr-1">sum:</td>
                                        <td colspan="3">
                                            <span class="text-success" :class="{'text-danger': assetFractionsSum != 100}">{{ assetFractionsSum }}%</span>
                                        </td>
                                    </tr>
                                </table>                         
                            </div>
                            <div class="mt-2">                                   
                                <div class="mt-4">
                                    <VueMultiselect
                                        v-model="newAssetItem"              
                                        :options="searchAssetsList"                          
                                        :custom-label="nameWithMultiselect"                                         
                                        placeholder="Select asset"
                                        label="id" 
                                        track-by="id"
                                        @select="modalSelectAssetItem"
                                        :class="{'is-invalid':validation.newAssetItem}"
                                        :searchable="true"
                                        :loading="isSearchAssetsLoading"                                 
                                        @search-change="searchAssets"
                                    >
                                    </VueMultiselect>
                                </div>                                        
                                <div class="mt-2">
                                    <input v-model="newAssetFraction" :class="{'is-invalid':validation.newAssetFraction}" class="form-control form-control-sm d-inline-block" style="width:70px;" autocomplete="off">%                            
                                
                                    <button class="ml-1 d-inline-block btn btn-success btn-sm" @click="addAssetToPoolModal()">+ add this item</button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="modal-footer justify-content-center">
                        <div class="flex-grow-1">
                            <button v-show="modalItemId" type="button" class="btn btn-secondary" @click="restartPoolModal()" data-dismiss="modal">Restart pool data</button>
                        </div>                        
                        <div>
                            <button type="button" class="btn btn-secondary" @click="closeModal()" data-dismiss="modal">Close</button>
                            <button type="button" :disabled="isSaveModalDisabled" class="btn btn-primary ml-3" @click="saveModal()">Save changes</button>
                        </div>                        
                    </div>
                </div>
            </div>            
        </div>
    </transition>
    </teleport>
</template>

<script setup>
import { onMounted, ref, reactive, computed } from "vue";
import { fetchAssetsList } from "../../api/Assets";
import { fetchGroupsList } from "../../api/AssetPoolGroups";
import { fetchPoolsList, createPool, deletePool, showPool, updatePool, updatePoolLogo, restartPool } from "../../api/AssetPools";
import { fetchItemsList, updateItem, createItem, deleteItem } from "../../api/AssetPoolItems";
import { fetchTemplatesList } from "../../api/AssetPoolTemplates";

import VueMultiselect from 'vue-multiselect'

let poolsList = ref([]);
let poolGroupsList = ref([]);
let poolTemplatesList = ref([]);
let isModalActive = ref(false);
let mFieldName = ref('');
let mFieldNameShort = ref('');
let mFieldDescription = ref('');
let mFieldDescriptionRu = ref('');
let mFieldLogo = ref('');
let mFieldIsAvaliable = ref(true);
let mFieldIsTopmarketcapBased = ref(false);
let mFieldGroupId = ref(0);
let mFieldTemplateId = ref(0);
let mFieldRebalanceFr = ref(0);
let mFieldRebalancedAt = ref('');
let mFieldRebalancedNextAt = ref('');
let mFieldMetaType = null;
let showTemplateSelect = ref(false);
let isSaveModalDisabled = ref(false);
let mAssets = ref([]);
let modalItemId = ref(0);
//let baseAssetsList = ref([]);
let searchAssetsList = ref([]);
let newAssetId = ref(0);
let newAssetItem = ref(null);
let newAssetFraction = ref(0.0);
let isNewAssetAction = ref(false);
let isSearchAssetsLoading = ref(false);
// let selection = ref(null);

const validation = reactive({
    poolGroup: false,
    poolName: false,
    poolDescription: false,
    newAssetItem: false,
    newAssetFraction: false,
    rebalanceFr: false
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
    // if (assetFractionsSum.value != 100) {
    //     console.log(assetFractionsSum.value);
    // }    
    if (newAssetFraction.value <= 0 ) { // || assetFractionsSum.value != 100
        validation.newAssetFraction = true;
        ret = false;
    }    

    return ret;
}

const validationReset = () => {
    validation.poolGroup = false;
    validation.poolName = false;
    validation.poolDescription = false;
    validation.newAssetItem = false;
    validation.newAssetFraction = false;
    validation.rebalanceFr = false;
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

const updateAssetFractionsSum = () => {    
    let ret = mAssets.value.reduce((sum, current) => {
        return sum + parseFloat(current.fraction);
    }, 0);

    ret += newAssetFraction.value ? parseFloat(newAssetFraction.value) : 0;
    if (modalItemId.value) {
        isSaveModalDisabled.value = ret != 100;
    }

    return ret;
}

const assetFractionsSum = computed(updateAssetFractionsSum)

const isTopmarketcapBasedChange = _ => {
    const checked = !mFieldIsTopmarketcapBased.value;
    if (checked) {
        showTemplateSelect.value = true;
        isSaveModalDisabled.value = false;
    } else {
        showTemplateSelect.value = mFieldMetaType != 'market_cap' ? false : true;
        isSaveModalDisabled.value = true;
        updateAssetFractionsSum();
    }
    //console.log(checked);
}

const searchAssets = async (query) => {
    isSearchAssetsLoading.value = true
    const assets = await fetchAssetsList({query, isStoplisted: 0, offset:0, count:10});
    searchAssetsList.value = assets;
    isSearchAssetsLoading.value = false    
}

const updateList = async () => {
    const pList = await fetchPoolsList({withItems:true});        
    poolsList.value = pList.map(item => {
        item.price_usd = Math.floor(1000*item.price_usd)/1000;
        item.meta = item.meta ? JSON.parse(item.meta) : null;
        item.meta_num_tokens = item.meta ? item.meta.num_tokens : 0;        
        item.meta_name = item.meta ? item.meta.name : '';        
        item.meta_name_url = item.meta ? item.meta.name.toLowerCase() : '';
        item.meta_name_url = item.meta_name_url.replaceAll(' ', '-');
        item.meta_cmc_category_url = `https://coinmarketcap.com/view/${item.meta_name_url}/`;
        //console.log(item.assets);
        if (item.assets) {
            item.assets = item.assets.map(asset => {
                asset.fraction = Math.round(asset.fraction*100)/100;
                return asset;
            });
        }                
        //item.meta = item.meta ? JSON.parse(item.meta) : null;
        const template = item.asset_pool_template_id ? poolTemplatesList.value.filter(tpl => {
            return tpl.id == item.asset_pool_template_id;
        }) : null;
        item.template_name = template && template[0] ? template[0].name : '';
        //console.log(item);
        return item;
    });
}

const getAssetsList = async (params = {}) => {
    const itemsData = await fetchItemsList(params);
    const assets = itemsData.map(item => ({
        id: item.id,
        asset_id: item.asset_id,
        asset_pool_id: item.asset_pool_id,
        asset_amount: item.asset_amount,
        fraction: Math.round(item.fraction*100)/100,
        pos: item.pos,
        price_usd: item.price_usd,
        asset_logo: item.asset.logo,
        asset_name: item.asset.name,
        asset_symbol: item.asset.symbol,
    }));

    return assets;
}
const updateAssetsListModal = async () => {
    mAssets.value = await getAssetsList({poolId:modalItemId.value, withAssets:true});    
    mAssets.value = mAssets.value.map(item => {
        item.asset_amount = Math.round(item.asset_amount*100000000)/100000000;        
        return item;
    });
    //console.log(mAssets.value);
}
// const updateBaseAssetsList = async () => {
//     baseAssetsList.value = await fetchAssetsList();    
//     //console.log(baseAssetsList.value);
// }
const updatePoolGroupsList = async () => {
    poolGroupsList.value = await fetchGroupsList({g_type:1});
}
const updatePoolTemplatesList = async () => {
    poolTemplatesList.value = await fetchTemplatesList();
}

const closeModal = () => {
    modalItemId.value = 0;   
    mFieldName.value = '';
    mFieldNameShort.value = '';
    mFieldDescription.value = '';
    mFieldDescriptionRu.value = '';
    mFieldLogo.value = '';
    mFieldIsAvaliable.value = true;
    mFieldIsTopmarketcapBased.value = false;
    isModalActive.value = false;
    newAssetId.value = 0;
    newAssetItem.value = null;
    newAssetFraction.value = '0';
    isNewAssetAction.value = false;
    mFieldGroupId.value = 0;
    mFieldTemplateId.value = 0;
    mFieldRebalanceFr.value = 0;
    mFieldRebalancedAt.value = '';
    mFieldRebalancedNextAt.value = '';
    isSaveModalDisabled.value = false;
    validationReset();
    localizationReset();
    updateList();
}
const showModal = () => {
    isModalActive.value = true;    
    showTemplateSelect.value = false;
}
const editModal = async (id) => {    
    modalItemId.value = id;
    showModal();    
    const data = await showPool(id);
    //const assets = await getAssetsList({poolId:id, withAssets:true});    
    //console.log(assets);    
    mFieldName.value = data.name;
    mFieldNameShort.value = data.name_short;
    mFieldDescription.value = data.description ? data.description : '';
    mFieldDescriptionRu.value = data.description_ru ? data.description_ru : '';
    mFieldLogo.value = data.logo ? (data.logo + '?' + Math.random()) : '';
    mFieldIsAvaliable.value = data.is_active;
    mFieldIsTopmarketcapBased.value = data.is_topmarketcap_based;
    mFieldGroupId.value = data.asset_pool_group_id;
    mFieldTemplateId.value = data.asset_pool_template_id;
    mFieldRebalanceFr.value = data.rebalance_frequency;
    mFieldRebalancedAt.value = data.rebalanced_at ? data.rebalanced_at : 'not yet';
    mFieldRebalancedNextAt.value = data.rebalanced_next_at;

    mFieldMetaType = (data.meta && data.meta.includes('market_cap')) ? 'market_cap' : '';

    showTemplateSelect.value = mFieldMetaType == 'market_cap' || mFieldIsTopmarketcapBased.value;
    
    //mAssets.value = assets;
    //console.log(data.meta);
    await updateAssetsListModal();
}

const restartPoolModal = async () => {    
    if (confirm("Sure?")) {
        await restartPool(modalItemId.value);
        closeModal();
    }
}

const addAssetToPoolModal = async () => {
    if (!validateNewItemInfo()) {
        return false;
    }

    if (newAssetId.value) {
        //console.log(asset.id, newAssetFraction.value);
        await createItem({
            assetPoolId: modalItemId.value,
            assetId: newAssetId.value,
            fraction: newAssetFraction.value,            
        });
        isNewAssetAction.value = true;
    }    
    updateAssetsListModal();
    newAssetId.value = 0;
    newAssetFraction.value = '0';
    newAssetItem.value = null;
}
const saveModal = async () => {
    if (!validatePoolBaseInfo()) {
        return false;
    }

    isSaveModalDisabled.value = true;

    if (modalItemId.value) {        
        if (mAssets.value.length && isNewAssetAction.value) {
            for (let item of mAssets.value) {
                await updateItem(item.id, {fraction: item.fraction});
            }
            //console.log(mAssets.value);
        }        
        await updatePool(
            modalItemId.value,
            {
                name: mFieldName.value,
                nameShort: mFieldNameShort.value,
                description: mFieldDescription.value,                
                descriptionRu: mFieldDescriptionRu.value,                
                isAvaliable: mFieldIsAvaliable.value,
                isTopmarketcapBased: mFieldIsTopmarketcapBased.value,
                assetPoolGroupId: mFieldGroupId.value,
                assetPoolTemplateId: mFieldTemplateId.value,
                rebalanceFrequency: mFieldRebalanceFr.value, 
                isNewAssetAction: isNewAssetAction.value               
            }
        );
        closeModal();
    } else {
        const pool = await createPool({
            name: mFieldName.value,
            nameShort: mFieldNameShort.value,
            description: mFieldDescription.value,         
            descriptionRu: mFieldDescriptionRu.value,            
            isAvaliable: mFieldIsAvaliable.value,
            isTopmarketcapBased: mFieldIsTopmarketcapBased.value,
            assetPoolGroupId: mFieldGroupId.value,
            assetPoolTemplateId: mFieldTemplateId.value,
            rebalanceFrequency: mFieldRebalanceFr.value,
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
const deletePoolAsset = async (assetPoolItemId, name) => {
    if (confirm(`Delete ${name}?`)) {
        await deleteItem(assetPoolItemId);
        updateAssetsListModal();
        isNewAssetAction.value = true;
    }    
}

onMounted(async () => {    
    
    await updatePoolTemplatesList();
    updateList();    
    // if (!baseAssetsList.value.length) {
    //     updateBaseAssetsList();
    // }    
    updatePoolGroupsList();    
});
</script>

<style scoped>
</style>