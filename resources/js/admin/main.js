import { countNotifications } from "./api/NotificationsLog";

document.addEventListener("DOMContentLoaded", _ => {    
    
    let dashboardMenuText = null;

    const updateNotifications = async () => {
        const element = document.querySelector('.sidebar .nav-link[href$=dashboard] p');
        if (!element) {
            return false;
        }
        if (!dashboardMenuText) {
            dashboardMenuText = element.textContent;
        }
        const data = await countNotifications({isChecked:false});
        const notificationsCount = data.count;
        
        element.innerHTML = dashboardMenuText + (notificationsCount > 0 ? `<span class="badge badge-danger right"> ${notificationsCount} </span>` : '');
        
    }
        
    updateNotifications(); 
    setInterval(updateNotifications, 10*1000);    
});

function deleteRow() {
    if (confirm("Delete?")) {
        return true;
    }
    return false;
}