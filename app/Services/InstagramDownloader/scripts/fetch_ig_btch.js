import axios from 'axios';
import { igdl } from 'btch-downloader';

const url = process.argv[2];
if (!url) {
    console.log(JSON.stringify({ status: false, error: 'No URL provided' }));
    process.exit(1);
}

// ----------------------------------------------------
// ENGINE 1: SnapSave JS Unpacker (Primary)
// ----------------------------------------------------
function unpackSnapSave(packedJs) {
    try {
        const match = packedJs.match(/decodeURIComponent\(escape\(r\)\)\}\s*\(\s*"([^"]+)"\s*,\s*(\d+)\s*,\s*"([^"]+)"\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/);
        if (!match) {
            const match2 = packedJs.match(/decodeURIComponent\(escape\(r\)\)\}\s*\(\s*'([^']+)'\s*,\s*'(\d+)'\s*,\s*'([^']+)'\s*,\s*'(\d+)'\s*,\s*'(\d+)'\s*,\s*'(\d+)'\s*\)/);
            if (!match2) return null;
            return decodeSnapSave(match2[1], parseInt(match2[2]), match2[3], parseInt(match2[4]), parseInt(match2[5]), parseInt(match2[6]));
        }
        return decodeSnapSave(match[1], parseInt(match[2]), match[3], parseInt(match[4]), parseInt(match[5]), parseInt(match[6]));
    } catch (e) {
        return null;
    }
}

function _0xe20c(d, e, f) {
    const charMap = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/";
    const h = charMap.slice(0, e);
    const i = charMap.slice(0, f);
    const j = d.split("").reverse().reduce((a, b, c) => {
        if (h.indexOf(b) !== -1) return a + h.indexOf(b) * (Math.pow(e, c));
        return a;
    }, 0);
    let k = "";
    let temp = j;
    while (temp > 0) {
        k = i[temp % f] + k;
        temp = (temp - (temp % f)) / f;
    }
    return k || "0";
}

function decodeSnapSave(h, u, n, t, e, r) {
    let result = "";
    for (let i = 0, len = h.length; i < len; i++) {
        let s = "";
        while (h[i] !== n[e]) {
            s += h[i];
            i++;
        }
        for (let j = 0; j < n.length; j++) {
            s = s.replace(new RegExp(n[j], "g"), j);
        }
        result += String.fromCharCode(_0xe20c(s, e, 10) - t);
    }
    return decodeURIComponent(escape(result));
}

async function fetchFromSnapSave(targetUrl) {
    try {
        const params = new URLSearchParams();
        params.append('url', targetUrl);

        const res = await axios.post('https://snapsave.app/action.php', params.toString(), {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Content-Type': 'application/x-www-form-urlencoded',
                'Referer': 'https://snapsave.app/',
                'Origin': 'https://snapsave.app'
            },
            timeout: 5000
        });

        const html = unpackSnapSave(res.data) || res.data;
        if (!html) return null;

        const hrefs = [];
        const hrefMatches = html.matchAll(/href=\\?"([^"]+)\\?"/gi);
        for (const m of hrefMatches) {
            const link = m[1].replace(/\\/g, '');
            if (link.includes('rapidcdn.app') || link.includes('fbcdn.net') || link.includes('cdninstagram.com') || link.includes('.mp4') || link.includes('token=')) {
                hrefs.push(link);
            }
        }

        const thumbMatch = html.match(/src=\\?"([^"]+)\\?"/i);
        const thumbnail = thumbMatch ? thumbMatch[1].replace(/\\/g, '') : null;

        if (hrefs.length > 0) {
            return {
                status: true,
                result: hrefs.map(link => ({
                    url: link,
                    thumbnail: thumbnail
                }))
            };
        }
    } catch (e) {
        // Continue to next engine
    }
    return null;
}

// ----------------------------------------------------
// ENGINE 2: Indown.io Parser
// ----------------------------------------------------
async function fetchFromInDown(targetUrl) {
    try {
        const pageRes = await axios.get('https://indown.io/reels', {
            headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)' },
            timeout: 4000
        });
        const htmlPage = pageRes.data;
        const tokenMatch = htmlPage.match(/name="_token"\s+value="([^"]+)"/);
        const cookies = pageRes.headers['set-cookie'] ? pageRes.headers['set-cookie'].join('; ') : '';

        if (tokenMatch && tokenMatch[1]) {
            const token = tokenMatch[1];
            const params = new URLSearchParams();
            params.append('_token', token);
            params.append('link', targetUrl);
            params.append('referer', 'https://indown.io/reels');

            const downRes = await axios.post('https://indown.io/download', params.toString(), {
                headers: {
                    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cookie': cookies,
                    'Referer': 'https://indown.io/reels'
                },
                timeout: 5000
            });

            const downHtml = downRes.data;
            const matches = downHtml.match(/href="([^"]+\.mp4[^"]*)"/i) || downHtml.match(/(https?:[\\/]+[^\s"'<>]+\.mp4[^\s"'<>]*)/i);
            if (matches && matches[1]) {
                const videoUrl = matches[1].replace(/\\/g, '');
                return {
                    status: true,
                    result: [{ url: videoUrl, thumbnail: null }]
                };
            }
        }
    } catch (e) {
        // Continue to next engine
    }
    return null;
}

// ----------------------------------------------------
// ENGINE 3: Btch-Downloader SDK
// ----------------------------------------------------
async function fetchFromIgdl(targetUrl, retries = 2) {
    for (let i = 0; i < retries; i++) {
        try {
            const data = await igdl(targetUrl);
            if (data && data.status && Array.isArray(data.result)) {
                const validItem = data.result.find(item => item && item.url && item.url.trim().length > 0);
                if (validItem) {
                    return data;
                }
            }
        } catch (e) {
            // retry
        }
        await new Promise(r => setTimeout(r, 300));
    }
    return null;
}

// ----------------------------------------------------
// MAIN PIPELINE
// ----------------------------------------------------
async function run() {
    try {
        let res = await fetchFromSnapSave(url);
        
        if (!res) {
            res = await fetchFromInDown(url);
        }

        if (!res) {
            res = await fetchFromIgdl(url, 2);
        }

        if (res) {
            console.log(JSON.stringify(res));
        } else {
            console.log(JSON.stringify({ status: false, error: 'Unable to fetch video payload' }));
        }
    } catch (e) {
        console.log(JSON.stringify({ status: false, error: e.message }));
    }
}

run();
