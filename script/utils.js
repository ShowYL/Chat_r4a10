
export default async function getXHR(url, method="GET", data=null) {
    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();

        xhr.open(method, url);

        if (data && method === "POST") {
            xhr.setRequestHeader("Content-Type", "application/json");
        }
        
        data ? xhr.send(data) : xhr.send()

        xhr.onload = () => {
            if (xhr.status !== 200) {
                console.log(`Error ${xhr.status}: ${xhr.statusText}`);
                reject(`Error ${xhr.status}: ${xhr.statusText}`);
            } else {
                console.log(`Done, got ${xhr.response.length} bytes`);
                resolve(xhr.responseText);
            }
        };

        xhr.onprogress = (event) => {
            if (event.lengthComputable) {
                console.log(`Received ${event.loaded} of ${event.total} bytes`);
            } else {
                console.log(`Received ${event.loaded} bytes`);
            }
        };

        xhr.onerror = () => {
            console.error(`Request failed`);
            reject(`Request failed`);
        };
    });
}
