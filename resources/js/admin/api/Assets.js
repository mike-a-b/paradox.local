
export function getUrl(params={}) {    
    let url = `/api/v1/assets`;
    let q = [];
    params.query ? q.push(`query=${params.query}`) : null;
    params.offset ? q.push(`offset=${params.offset}`) : q.push('offset=0');
    params.count ? q.push(`count=${params.count}`) : null;
    params.isStoplisted !== undefined ? q.push(`is_stoplisted=${params.isStoplisted}`) : null;
    url += q.length ? '?' + q.join('&') : '';
    return url;
};

export function getBaseHeaders() {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };
};

export function fetchAssetsList(params={}) {
    //const url = getUrl(params.pair, params.dateFrom, params.dateTo);
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

// export function createAsset(params) {    
//     const url = getUrl();
//     const data = {
//         name: params.name,
//         asset_type_id: 1,
//         is_active: params.isAvaliable ? 1 : 0
//     };
//     return new Promise((resolve, reject) => {
//         fetch(url, {
//             method: 'POST',
//             headers: getBaseHeaders(),            
//             body: JSON.stringify(data)
//         })
//         .then(async (itemsJson) => {
//             const itemsData = await itemsJson.json();
//             const items = itemsData.data;
//             resolve(items);                             
//         })
//         .catch(e => {
//             reject(e);
//         });
//     });    
// };

// export function updateAsset(id, params) {    
//     const url = getUrl() + `/${id}`;
//     const data = {        
//         name: params.name,
//         asset_type_id: 1,
//         is_active: params.isAvaliable ? 1 : 0
//     };
//     return new Promise((resolve, reject) => {
//         fetch(url, {
//             method: 'PUT',
//             headers: getBaseHeaders(),
//             body: JSON.stringify(data)
//         })
//         .then(async (itemsJson) => {
//             const itemsData = await itemsJson.json();
//             const items = itemsData.data;
//             resolve(items);                             
//         })
//         .catch(e => {
//             reject(e);
//         });
//     });    
// };

// export function deleteAsset(id) {    
//     const url = getUrl() + `/${id}`;    
//     return new Promise((resolve, reject) => {
//         fetch(url, {
//             method: 'DELETE',
//             headers: getBaseHeaders(),
//         })
//         .then(async (itemsJson) => {
//             const itemsData = await itemsJson.json();
//             const items = itemsData.data;
//             resolve(items);                             
//         })
//         .catch(e => {
//             reject(e);
//         });
//     });    
// };

// export function showAsset(id, params={}) {    
//     const url = getUrl() + `/${id}` + (params.withItems ? '?with_items=1' : '');    
//     return new Promise((resolve, reject) => {
//         fetch(url, {
//             method: 'GET',
//             headers: getBaseHeaders(),
//         })
//         .then(async (itemsJson) => {
//             const itemsData = await itemsJson.json();
//             const items = itemsData.data;
//             resolve(items);                             
//         })
//         .catch(e => {
//             reject(e);
//         });
//     });    
// };