export const updateUserProfile = str => {        
    document.querySelectorAll('.general_menu_info1, .portfolio_item_info1').forEach(element => {
        element.innerHTML = str;
    });
};