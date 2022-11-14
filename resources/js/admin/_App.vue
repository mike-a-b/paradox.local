<script setup>
// import { onMounted } from "vue";
// import { countNotifications } from "./api/NotificationsLog";

// let dashboardMenuText = null;

// const updateNotifications = async () => {
//     const element = document.querySelector('.sidebar .nav-link[href$=dashboard] p');
//     if (!dashboardMenuText) {
//         dashboardMenuText = element.textContent;
//     }
//     const data = await countNotifications({isChecked:false});
//     const notificationsCount = data.count;
    
//     element.innerHTML = dashboardMenuText + (notificationsCount > 0 ? `<span class="badge badge-danger right"> ${notificationsCount} </span>` : '');
    
// }
// onMounted(() => {        
//     updateNotifications(); 
//     setInterval(updateNotifications, 10*1000);
// });
</script>