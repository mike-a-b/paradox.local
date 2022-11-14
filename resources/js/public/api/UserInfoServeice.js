function getBaseHeaders(params={}) {
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

export const fetchUserProfile = () => {
    const url = `/api/v1/user-info-service/profile`;
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

export function updateAva(params={}) {    
    const url = `/api/v1/user-info-service/update-ava`;

    const formData = new FormData();
    formData.append('ava', params.file);
    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',            
            headers: getBaseHeaders({noContentType: true}),
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

export function deleteAva(params={}) {    
    const url = `/api/v1/user-info-service/delete-ava`;
    
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',            
            headers: getBaseHeaders(),
            body: ''
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