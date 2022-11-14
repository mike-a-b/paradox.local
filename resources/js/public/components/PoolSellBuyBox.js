function getBaseHeaders() {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };
};

export const fetchAssetPools = (params) => {
    let url = `/api/v1/user-pools-service/asset-pools`;
    if (params) {
        let p = [];
        if (params.poolId) {
            p.push(`pool_id=${params.poolId}`);
        }
        url += '?' + p.join('&');
    }
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

export const fetchUserAssetPools = (params) => {
    let url = `/api/v1/user-pools-service/user-asset-pools`;
    if (params) {
        let p = [];
        if (params.poolId) {
            p.push(`pool_id=${params.poolId}`);
        }
        url += '?' + p.join('&');
    }
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

export const fetchRatePools = () => {
    const url = `/api/v1/user-pools-service/rate-pools`;
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

export const fetchUserRatePools = () => {
    const url = `/api/v1/user-pools-service/user-rate-pools`;
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

export const fetchUserProfile = () => {
    const url = `/api/v1/user-pools-service/user-profile`;
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

export const postBuyandsellPool = (params) => {
    const url = `/api/v1/user-pools-service/pool-balance-update`;    
    const data = {
        pool_type: params.poolType,
        operation_type: params.operationType,
        pool_id: params.poolId,
        sum: params.sum,
        date_start: params.dateStart ? params.dateStart : null,
        date_end: params.dateEnd ? params.dateEnd : null,
    };
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
            headers: getBaseHeaders(),            
            body: JSON.stringify(data)
        })
        .then(async (itemsJson) => {                        
            const itemsData = await itemsJson.json();            
            const items = itemsJson.ok ? itemsData.data : itemsData;            
            resolve(items);                             
        })
        .catch(e => {            
            reject(e);
        });
    });
};