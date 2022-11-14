
export function getUrl(params={}) {
    //const url = `/api/v1/agg-items?symbol=${pair}&date_from=${dateFrom}&date_to=${dateTo}`;
    let url = `/api/v1/asset-pools`;
    let q = [];
    params.withItems ? q.push('with_items=1') : null;
    params.isActive ? q.push('is_active=1') : null;
    url += q.length ? '?' + q.join('&') : '';

    return url;
};

export function getBaseHeaders(params={}) {
    let headers = {        
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };
    if (!params.noContentType) {
        headers['Content-Type'] = 'application/json';
    }    

    return headers;
};

export function fetchPoolsList(params={}) {
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

export function createPool(params) {    
    const url = getUrl();
    const data = {
        name: params.name,
        name_short: params.nameShort,
        description: params.description,
        description_ru: params.descriptionRu,
        logo: params.logo,
        asset_type_id: 1,
        is_active: params.isAvaliable ? 1 : 0,
        is_topmarketcap_based: params.isTopmarketcapBased ? 1 : 0,
        asset_pool_group_id: params.assetPoolGroupId,
        asset_pool_template_id: params.assetPoolTemplateId,
        rebalance_frequency: params.rebalanceFrequency,
    };
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
            headers: getBaseHeaders(),
            // 'Content-Type': 'application/x-www-form-urlencoded',            
            //headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
            //body: queryString.stringify({for:'bar', blah:1})
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

export function updatePool(id, params) {    
    const url = getUrl() + `/${id}`;
    const data = {        
        name: params.name,
        name_short: params.nameShort,
        description: params.description,
        description_ru: params.descriptionRu,
        logo: params.logo,
        asset_type_id: 1,
        is_active: params.isAvaliable ? 1 : 0,
        is_topmarketcap_based: params.isTopmarketcapBased ? 1 : 0,
        asset_pool_group_id: params.assetPoolGroupId,
        asset_pool_template_id: params.assetPoolTemplateId,
        rebalance_frequency: params.rebalanceFrequency,
        is_new_asset_action: params.isNewAssetAction ? 1 : 0
    };
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'PUT',
            headers: getBaseHeaders(),
            //headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
            //body: queryString.stringify({for:'bar', blah:1})
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

export function deletePool(id) {    
    const url = getUrl() + `/${id}`;    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'DELETE',
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

export function restartPool(id) {    
    const url = getUrl() + `/${id}/restart`;    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
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

export function showPool(id, params={}) {    
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

export function updatePoolLogo(id, params={}) {    
    const url = getUrl() + `/${id}/update-logo`;

    const formData = new FormData();
    formData.append('logo', params.file);
    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',            
            headers: getBaseHeaders({noContentType: true}),
            //headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
            //body: queryString.stringify({for:'bar', blah:1})
            body: formData
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