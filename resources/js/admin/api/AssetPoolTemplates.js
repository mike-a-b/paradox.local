
export function getUrl(params={}) {    
    let url = `/api/v1/asset-pool-templates`;
    //url += params.g_type ? `?g_type=${params.g_type}` : '';

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

export function fetchTemplatesList(params={}) {
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

// export function createTemplate(params) {    
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

export function updateTemplate(id, params) {    
    const url = getUrl() + `/${id}`;
    const data = {        
        name: params.name,
        asset_count: params.asset_count,
        body: JSON.stringify(params.body),
        is_active: params.isAvaliable ? 1 : 0
    };
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'PUT',
            headers: getBaseHeaders(),            
            body: JSON.stringify(data)
        })
        .then(async (itemsJson) => {
            const itemsData = await itemsJson.json();
            const items = itemsData.data;
            resolve(items);                             
        })
        .catch(e => {
            reject(e);
        });
    });    
};

// export function deleteTemplate(id) {    
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

export function showTemplate(id, params={}) {    
    const url = getUrl() + `/${id}` + (params.withItems ? '?with_items=1' : '');    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'GET',
            headers: getBaseHeaders(),
        })
        .then(async (itemsJson) => {
            const itemsData = await itemsJson.json();
            const items = itemsData.data;
            resolve(items);                             
        })
        .catch(e => {
            reject(e);
        });
    });    
};