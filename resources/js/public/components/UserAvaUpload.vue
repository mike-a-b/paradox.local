<template>    
    <div class="settings_ava" style="display: inline-block;vertical-align: middle;">        
        <img v-show="fieldAva" width="64" :src="'/'+fieldAva" alt="">                                                
    </div>         
    <div class="settings_file_btn_block" style="display: inline-block; vertical-align: middle;">
        <input class="file" ref="fileInput" type="file" @change="handleAvaFile" />
        <button class="settings_form_button_sm settings_form_button_gray" style="margin-left:10px;" @click.prevent="fileInputClick">
            {{ $t("user_ava_upload.Upload") }}
        </button>
    </div>                           
    <a 
        href="#" 
        class="settings_form_button_sm settings_form_button_lgray"         
        style="display: inline-block; margin-left:20px; vertical-align: middle;" 
        @click.prevent="deleteAvaFile"
    >
        {{ $t("user_ava_upload.Clear") }}
    </a>             
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchUserProfile, updateAva, deleteAva } from "../api/UserInfoServeice";

const avaDefault = 'assets/imgs/cabinet/ava-default.png';

const fileInput = ref(null);

const fieldAva = ref('');

const fileInputClick = async () => {
    fileInput.value.click();    
}

const handleAvaFile = async (event) => {
    const data = await updateAva({file:event.target.files[0]});
    updateAvaImg(data);    
}

const deleteAvaFile = async () => {
    const data = await deleteAva();
    updateAvaImg(data);
}

const updateAvaInfo = async () => {    
    const data = await fetchUserProfile();    
    updateAvaImg(data);
}

const updateAvaImg = async (data) => {        
    fieldAva.value = data.ava ? data.ava + '?' + Math.random() : avaDefault;
    const headerAva = document.querySelector('.general_menu_img img');
    if (headerAva) {
        headerAva.src = fieldAva.value;
    }
}

onMounted(() => {    
    updateAvaInfo();
});
</script>

<style scoped>
</style>