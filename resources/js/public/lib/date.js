export const parseDate = str => {        
    const a = str.split(/[^0-9]/); 

    return new Date (a[0],a[1]-1,a[2],a[3],a[4],a[5]);        
};

export const formatDateYMD = (d) => {            
    const date = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2);

    return date;
};