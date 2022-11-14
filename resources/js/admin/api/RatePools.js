
export function getUrl(params={}) {
    //const url = `/api/v1/agg-items?symbol=${pair}&date_from=${dateFrom}&date_to=${dateTo}`;
    let url = `/api/v1/rate-pools`;
    // url += params.withItems ? '?with_items=1' : '';

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
        currency_id: params.currency_id,
        description: params.description,
        description_ru: params.descriptionRu,
        logo: params.logo,        
        is_active: params.isAvaliable ? 1 : 0,
        asset_pool_group_id: params.assetPoolGroupId,
        rate: params.rate,
        // date_start: params.dateStart,
        // date_end: params.dateEnd,
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
        currency_id: params.currency_id,
        description: params.description,
        description_ru: params.descriptionRu,
        logo: params.logo,        
        is_active: params.isAvaliable ? 1 : 0,
        asset_pool_group_id: params.assetPoolGroupId,
        rate: params.rate,
        // date_start: params.dateStart,
        // date_end: params.dateEnd,
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

export function showPool(id, params={}) {    
    const url = getUrl() + `/${id}`; // + (params.withItems ? '?with_items=1' : '');    
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