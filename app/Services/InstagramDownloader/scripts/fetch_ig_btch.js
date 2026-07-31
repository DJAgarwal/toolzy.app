import { igdl } from 'btch-downloader';

const url = process.argv[2];
if (!url) {
    console.log(JSON.stringify({ status: false, error: 'No URL provided' }));
    process.exit(1);
}

async function getMedia(url, retries = 3) {
    for (let i = 0; i < retries; i++) {
        try {
            const data = await igdl(url);
            if (data && data.status && Array.isArray(data.result)) {
                const validItem = data.result.find(item => item && item.url && item.url.trim().length > 0);
                if (validItem) {
                    return data;
                }
            }
        } catch (e) {
            // retry on failure
        }
        await new Promise(r => setTimeout(r, 500));
    }
    return null;
}

async function run() {
    try {
        const res = await getMedia(url, 3);
        if (res) {
            console.log(JSON.stringify(res));
        } else {
            console.log(JSON.stringify({ status: false, error: 'Empty media payload received' }));
        }
    } catch (e) {
        console.log(JSON.stringify({ status: false, error: e.message }));
    }
}

run();
