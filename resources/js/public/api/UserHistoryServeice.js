function getBaseHeaders() {
    let headers = {        
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };     

    return headers;
};

export const fetchHistoryList = (params={}) => {
    let url = `/api/v1/user-history-service/`;
    let q = [];        
    params.offset ? q.push(`offset=${params.offset}`) : (params.offset !== undefined ? q.push('offset=0') : null);
    params.count ? q.push(`count=${params.count}`) : null;
    params.date ? q.push(`date=${params.date}`) : (params.offset !== undefined ? q.push('date=ALL') : null);
    url += q.length ? '?' + q.join('&') : '';
    return new Promise((resolve, reject) => {
        fetch(url, {
            headers: getBaseHeaders(params),
        })
        .then(async (itemsJson) => {
            const itemsData = await itemsJson.json();
            const items = itemsData.data;
            //console.log(items);
            resolve(items);                             
        })
        .catch(e => {
            reject(e);
        });
    });    
};