
export function getUrl(params={}) {
    let url = `/api/v1/user-asset-pools`;
    url += params.userId ? `?user_id=${params.userId}` : '';

    return url;
};

export function getBaseHeaders(params={}) {
    let headers = {   
        'Content-Type': 'application/json', 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };

    return headers;
};

export function fetchPoolsList(params={}) {      
    const url = getUrl(params);
    return new Promise((resolve, reject) => {
        fetch(url, {
            headers: getBaseHeaders(),
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