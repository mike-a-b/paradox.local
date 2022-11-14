<template>
    <div class="card d-inline-flex">
        <div v-if="isDashboardMode" class="card-header py-1 font-weight-bold text-gray">
            Notifications
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
        <table class="table table-sm" :style="`max-width:${isDashboardMode ? 700 : 900}px;`">
            <thead>
                <tr class="text-gray">
                    <th>ID</th>
                    <th>type</th>                            
                    <!-- <th>code</th>                                                 -->
                    <th>title</th>                                                
                    <th>description</th>                                                
                    <th>
                        <button 
                            class="btn btn-success btn-xs"                             
                            :disabled="!newNotificationsCount"
                            @click="checkAll()"
                        >seen</button>
                    </th>
                    <th>created_at</th>                                           
                </tr>
            </thead>
            <tbody style="font-size:smaller;">        
                <template v-for="(data) in notificationsList" :key="data.id">                
                <tr> 
                    <td>{{ data.id }}</td>                            
                    <td>
                        {{ data.type_name }}
                    </td>                                       
                    <!-- <td class="text-left">{{ data.code }}</td>                     -->
                    <td class="text-left">{{ data.title }}</td>                    
                    <td class="text-left">{{ data.description }}</td>                    
                    <td class="text-center">                    
                        <i 
                            class="fa fa-check text-secondary table-row-icon" 
                            :class="{'text-green':data.is_checked}" 
                            role="button"
                            @click="toggleIsChecked(data.id, data.is_checked)"
                        ></i>
                    </td>                   
                    <td>{{ data.created_at }}</td>                    
                </tr>
                </template>
            </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        <div v-if="!isDashboardMode" class="card-footer clearfix d-flex justify-content-center pt-2">    
            <v-pagination
                v-model="page"
                :pages="pagesCount"
                :range-size="1"
                active-color="#DCEDFF"
                @update:modelValue="pageUpdateHandler"
            />                        
        </div>
        <div v-else class="card-footer clearfix d-flex justify-content-center pt-2">
            <a href="/admin/dashboard/notifications_log">See all</a>
        </div>
    </div>
    <!-- /.card -->     
</template>

<script setup>
import { onMounted, ref } from "vue";
import { fetchNotificationsList, countNotifications, updateNotification, checkAllNotifications } from "../../api/NotificationsLog";

import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

const props = defineProps({
  mode: String
})

let onPageItems = 10;

let isDashboardMode = ref(false);
let notificationsList = ref([]);
let newNotificationsCount = ref(0);
let page = ref(1);
let pagesCount = ref(1);
let itemsCount = 0;

const updateListCount = async () => {
    const data = await countNotifications();        
    itemsCount = data.count;
}

const updateList = async () => {
    // console.log({offset:itemsCount*(page.value-1), count:onPageItems});
    notificationsList.value = await fetchNotificationsList({offset:onPageItems*(page.value-1), count:onPageItems});    
    pagesCount.value = Math.ceil(itemsCount/onPageItems);
}

const pageUpdateHandler = async (newPage) => {      
    updateList();
}

const toggleIsChecked = async (id, isChecked) => {
    const val = !isChecked;                    
    await updateNotification(
        id,
        {
            is_checked: val         
        }
    );
    notificationsList.value = notificationsList.value.map(item => {
        if (item.id == id) {
            item.is_checked = val;
        }
        return item;
    });  
}

const checkAll = async () => {                    
    await checkAllNotifications();
    notificationsList.value = notificationsList.value.map(item => {        
        item.is_checked = true;        
        return item;
    });  
}

// const deleteModal = async (id) => {
//     if (confirm(`Delete ${id}?`)) {
//         await deletePool(id);
//         updateList();
//     }    
// }

onMounted(async () => {    
    isDashboardMode.value = props.mode == 'dashboard';
    onPageItems = isDashboardMode.value ? 2 : onPageItems;
    await updateListCount();
    updateList();   
    const data = await countNotifications({isChecked:false});
    newNotificationsCount.value = data.count;             
});
</script>

<style scoped>
</style>