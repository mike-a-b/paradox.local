<template>
    <div class="card d-inline-flex">
        <div class="card-header d-flex">
            <div class="form-group mb-0 flex-grow-1">
                <!-- <label>Name</label> -->
                <input 
                    class="form-control" 
                    :class="{'is-invalid':validation.templeteName}"
                    v-model="templeteName" 
                    @change="changeTemplateName"
                    autocomplete="off"
                >
            </div>                    
            <div class="form-check pl-5" style="padding-top:7px;">
                <input 
                    type="checkbox" 
                    v-model="templeteIsAvaliable" 
                    class="form-check-input" 
                    id="isActive" 
                    value="1"
                    @change="changeTemplateActive"
                >
                <label class="form-check-label" for="isActive">Active</label>
            </div>                         
        </div>
        <div class="card-header d-flex">
            <div class="flex-grow-1 text-gray">
                <h5 class="mb-0"><span class="text-bold">{{ templeteAssetCount }}</span> assets</h5>
            </div>   
            <div class="form-check pl-4">                
                <button class="btn btn-success btn-xs" @click="addAsset()">+ add asset</button>                
            </div>         
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-sm table-hover text-gray" style="max-width:900px;">            
                <tbody>        
                    <template v-for="(data) in templeteBody" :key="data.index">                
                    <tr> 
                        <td class="pr-0 align-middle" style="width:30px;">{{ data.index + 1 }}</td>                    
                        <td class="text-left">
                            <input                                 
                                class="form-control form-control-sm d-inline-block" 
                                :class="{'is-invalid':validation.templeteBody[data.index]}"
                                style="width:50px;" 
                                v-model="data.fraction" 
                                @change="changeTemplateFration(data.index)"
                                autocomplete="off"
                            >%
                        </td>                                           
                        <td class="px-0 align-middle" style="width:35px;">
                            <i @click="deleteAsset(data.index)" class="fa fa-trash text-gray table-row-icon pointer"></i>                        
                        </td>
                    </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="card-body p-3 pb-4 text-center">
            <span 
                class="h4" 
                :class="{'text-danger' : fractionsSumm != 100, 'text-green' : fractionsSumm == 100}"
            >
                {{ fractionsSumm }}%
            </span>
        </div>
    </div>
    <!-- /.card -->   
    <teleport to="body">
    <transition> 
        <div v-if="isSavedModal" class="background-owerlay-gray" style="background-color:transparent; z-index: 1;">
            <div class="modal-dialog modal-dialog-centered" style="max-width:600px;" role="document">
                <div class="alert alert-success" role="alert" style="opacity:0.5;">
                    SAVED
                </div>
            </div>            
        </div>
    </transition>
    </teleport>  
</template>

<script setup>
import { onMounted, ref, reactive, computed } from "vue";
import { showTemplate, updateTemplate } from "../../api/AssetPoolTemplates";

const props = defineProps({
  templateId: Number
})

let templeteId = 0;
let templeteInfo = ref([]);
let templeteName = ref('');
let templeteIsAvaliable = ref(true);
let templeteAssetCount = ref(0);
let templeteBody = ref([]);
let isSavedModal = ref(false);

const fractionsSumm = computed(() => {    
    let ret = templeteBody.value.reduce((sum, current) => {
        const fr = parseFloat(current.fraction);
        return sum + (isNaN(fr) ? 0 : fr);
    }, 0);

    return ret;
})

const changeTemplateName = () => {        
    updateTemplateDo();
}

const changeTemplateActive = () => {        
    updateTemplateDo();
}

const changeTemplateFration = (index) => {        
    updateTemplateDo();
}

const updateTemplateDo = async (_validate=true) => {    
    if (!_validate || validate()) {
        templeteBody.value = templeteBody.value.map((item) => {
            const fr = parseFloat(item.fraction);
            item.fraction = (isNaN(fr) ? 0 : fr);
            return item;
        });
        await updateTemplate(
            templeteId,
            {
                name: templeteName.value,
                asset_count: templeteAssetCount.value,
                body: templeteBody.value,
                isAvaliable: templeteIsAvaliable.value ? 1 : 0
            }
        );
        isSavedModal.value = true;
        setTimeout(() => {isSavedModal.value = false}, 500);
    }    
}

const validation = reactive({
    templeteName: false,    
    templeteBody: []    
});

const validate = () => {
    validationReset();
    let ret = true;
    if (!templeteName.value) {
        validation.templeteName = true;
        ret = false;
    }
    if (templeteName.value.length < 2) {
        validation.templeteName = true;
        ret = false;
    }   
    //validation.templeteBody = 
    templeteBody.value.map((item) => {
        const fr = parseFloat(item.fraction);            
        const vale = (isNaN(fr) ? 0 : fr) <= 0;
        validation.templeteBody[item.index] = vale;
        if (vale) {
            ret = false;
        }        
        return item;
    }); 

    //console.log(validation.templeteBody);

    return ret;
}

const validationReset = () => {
    validation.templeteName = '';    
}


const updateTempleteInfo = async (id) => {
    templeteInfo.value = await showTemplate(id);    
    templeteName.value = templeteInfo.value.name;
    templeteAssetCount.value = templeteInfo.value.asset_count;
    templeteBody.value = templeteInfo.value.body;
    templeteIsAvaliable.value = templeteInfo.value.is_active ? true : false;    
}

const deleteAsset = async (index) => {
    if (confirm(`Delete ${index + 1}?`)) {
        templeteBody.value = templeteBody.value.filter(item => {
            return item.index != index;
        });
        templeteBody.value = templeteBody.value.map((item, index) => {
            item.index = index;
            return item;
        });
        templeteAssetCount.value = templeteBody.value.length;
        //console.log(templeteBody.value);
        await updateTemplateDo();
        updateTempleteInfo(templeteId);
    }    
}

const addAsset = async _ => {    
    templeteBody.value.push({
        index: templeteBody.value.length,
        fraction: 0
    });
    templeteAssetCount.value = templeteBody.value.length;

    await updateTemplateDo(false);
    updateTempleteInfo(templeteId);       
}

onMounted(() => {    
    templeteId = props.templateId;
    updateTempleteInfo(props.templateId);        
});
</script>

<style scoped>
</style>