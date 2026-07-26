/**
 * Import & Export Utilities - Toolzy
 * Parses cURL commands, Postman collections v2.1, OpenAPI JSON specs, and exports to cURL, Postman, Markdown.
 */

window.ImportExport = (function () {
    /**
     * Parses a cURL command string into a request object
     * @param {string} curlStr
     * @returns {Object} { method, url, headers, queryParams, body, bodyType, formData, urlEncoded }
     */
    function parseCurl(curlStr) {
        if (!curlStr || typeof curlStr !== 'string') return null;

        // Clean up multiline backslashes
        let cleaned = curlStr.replace(/\\\r?\n/g, ' ').trim();

        // Tokenize command line string respecting single and double quotes
        const tokens = [];
        let current = '';
        let inSingle = false;
        let inDouble = false;
        let escaped = false;

        for (let i = 0; i < cleaned.length; i++) {
            const char = cleaned[i];

            if (escaped) {
                current += char;
                escaped = false;
                continue;
            }

            if (char === '\\' && !inSingle) {
                escaped = true;
                continue;
            }

            if (char === "'" && !inDouble) {
                inSingle = !inSingle;
                continue;
            }

            if (char === '"' && !inSingle) {
                inDouble = !inDouble;
                continue;
            }

            if (/\s/.test(char) && !inSingle && !inDouble) {
                if (current.length > 0) {
                    tokens.push(current);
                    current = '';
                }
            } else {
                current += char;
            }
        }
        if (current.length > 0) {
            tokens.push(current);
        }

        if (tokens.length === 0) return null;

        let method = null;
        let url = '';
        let headers = {};
        let formData = [];
        let urlEncoded = [];
        let body = '';
        let bodyType = 'none';

        for (let i = 0; i < tokens.length; i++) {
            const token = tokens[i];
            const nextToken = tokens[i + 1] || '';

            if (token === 'curl') continue;

            // -X or --request
            if (token === '-X' || token === '--request') {
                method = nextToken.toUpperCase();
                i++;
            } else if (token.startsWith('-X')) {
                method = token.substring(2).toUpperCase();
            }
            // -H or --header
            else if (token === '-H' || token === '--header') {
                parseHeader(nextToken, headers);
                i++;
            } else if (token.startsWith('-H') || token.startsWith('--header=')) {
                const headerStr = token.startsWith('-H') ? token.substring(2) : token.substring(9);
                parseHeader(headerStr, headers);
            }
            // -F or --form
            else if (token === '-F' || token === '--form') {
                parseKeyValuePair(nextToken, formData);
                i++;
            } else if (token.startsWith('-F') || token.startsWith('--form=')) {
                const formStr = token.startsWith('-F') ? token.substring(2) : token.substring(7);
                parseKeyValuePair(formStr, formData);
            }
            // --data-urlencode
            else if (token === '--data-urlencode') {
                parseKeyValuePair(nextToken, urlEncoded);
                i++;
            } else if (token.startsWith('--data-urlencode=')) {
                parseKeyValuePair(token.substring(17), urlEncoded);
            }
            // -d or --data or --data-raw or --data-binary or --data-ascii
            else if (token === '-d' || token === '--data' || token === '--data-raw' || token === '--data-binary' || token === '--data-ascii') {
                body = nextToken;
                i++;
            } else if (token.startsWith('-d') || token.startsWith('--data=')) {
                body = token.startsWith('-d') ? token.substring(2) : token.substring(7);
            }
            // -u or --user (Basic Auth)
            else if (token === '-u' || token === '--user') {
                headers['Authorization'] = 'Basic ' + btoa(nextToken);
                i++;
            }
            // URL detection
            else if (token.startsWith('http://') || token.startsWith('https://')) {
                url = token;
            } else if (!url && !token.startsWith('-')) {
                url = token;
            }
        }

        // Determine method if not explicitly set
        if (!method) {
            if (formData.length > 0 || urlEncoded.length > 0 || body) {
                method = 'POST';
            } else {
                method = 'GET';
            }
        }

        // Extract query parameters from URL
        let queryParams = [];
        if (url && url.includes('?')) {
            try {
                const u = new URL(url.startsWith('http') ? url : 'https://' + url);
                u.searchParams.forEach((val, key) => {
                    queryParams.push({ key: key, value: val, enabled: true });
                });
            } catch (e) {}
        }

        // Determine bodyType
        if (formData.length > 0) {
            bodyType = 'form';
        } else if (urlEncoded.length > 0) {
            bodyType = 'urlencoded';
        } else if (body) {
            const ct = (headers['content-type'] || headers['Content-Type'] || '').toLowerCase();
            if (ct.includes('x-www-form-urlencoded')) {
                bodyType = 'urlencoded';
                const params = new URLSearchParams(body);
                params.forEach((val, key) => {
                    urlEncoded.push({ key: key, value: val });
                });
            } else if (ct.includes('json') || (body.trim().startsWith('{') && body.trim().endsWith('}')) || (body.trim().startsWith('[') && body.trim().endsWith(']'))) {
                bodyType = 'json';
            } else if (ct.includes('xml') || (body.trim().startsWith('<') && body.trim().endsWith('>'))) {
                bodyType = 'xml';
            } else {
                bodyType = 'raw';
            }
        }

        return {
            method: method,
            url: url,
            headers: headers,
            queryParams: queryParams,
            body: body,
            bodyType: bodyType,
            formData: formData,
            urlEncoded: urlEncoded
        };
    }

    function parseHeader(headerStr, headersObj) {
        if (!headerStr) return;
        const parts = headerStr.split(':');
        if (parts.length >= 2) {
            const k = parts[0].trim();
            const v = parts.slice(1).join(':').trim();
            headersObj[k] = v;
        }
    }

    function parseKeyValuePair(pairStr, targetArr) {
        if (!pairStr) return;
        const eqIdx = pairStr.indexOf('=');
        if (eqIdx !== -1) {
            let k = pairStr.substring(0, eqIdx).trim();
            let v = pairStr.substring(eqIdx + 1).trim();
            k = unquote(k);
            v = unquote(v);
            targetArr.push({ key: k, value: v });
        } else {
            let k = unquote(pairStr.trim());
            if (k) targetArr.push({ key: k, value: '' });
        }
    }

    function unquote(str) {
        if (!str) return '';
        let res = str.trim();
        if ((res.startsWith('"') && res.endsWith('"')) || (res.startsWith("'") && res.endsWith("'"))) {
            res = res.substring(1, res.length - 1).trim();
        }
        if ((res.startsWith('"') && res.endsWith('"')) || (res.startsWith("'") && res.endsWith("'"))) {
            res = res.substring(1, res.length - 1);
        }
        return res;
    }

    /**
     * Imports Postman Collection v2.1 JSON
     */
    function parsePostmanCollection(jsonObj) {
        if (!jsonObj || !jsonObj.item) return [];

        let requests = [];
        function traverseItems(items, folderName = '') {
            items.forEach(item => {
                if (item.request) {
                    let r = item.request;
                    let method = r.method || 'GET';
                    let rawUrl = typeof r.url === 'string' ? r.url : (r.url ? r.url.raw : '');
                    let headers = {};
                    if (r.header && Array.isArray(r.header)) {
                        r.header.forEach(h => {
                            if (h.key && h.value && !h.disabled) headers[h.key] = h.value;
                        });
                    }
                    let body = '';
                    if (r.body && r.body.raw) body = r.body.raw;

                    requests.push({
                        name: item.name || (method + ' ' + rawUrl),
                        folder: folderName,
                        method: method,
                        url: rawUrl,
                        headers: headers,
                        body: body
                    });
                } else if (item.item && Array.isArray(item.item)) {
                    traverseItems(item.item, folderName ? folderName + ' / ' + item.name : item.name);
                }
            });
        }
        traverseItems(jsonObj.item);
        return requests;
    }

    /**
     * Imports Swagger / OpenAPI JSON
     */
    function parseOpenApi(jsonObj) {
        if (!jsonObj || !jsonObj.paths) return [];

        let requests = [];
        let baseUrl = '';
        if (jsonObj.servers && jsonObj.servers[0] && jsonObj.servers[0].url) {
            baseUrl = jsonObj.servers[0].url;
        }

        Object.keys(jsonObj.paths).forEach(path => {
            const methods = jsonObj.paths[path];
            Object.keys(methods).forEach(m => {
                if (['get', 'post', 'put', 'patch', 'delete', 'head', 'options'].includes(m.toLowerCase())) {
                    const details = methods[m];
                    const fullUrl = (baseUrl ? baseUrl.replace(/\/$/, '') : 'https://api.example.com') + path;
                    requests.push({
                        name: details.summary || (m.toUpperCase() + ' ' + path),
                        method: m.toUpperCase(),
                        url: fullUrl,
                        headers: { 'Accept': 'application/json' },
                        body: ''
                    });
                }
            });
        });
        return requests;
    }

    /**
     * Export Collection to Postman v2.1 format
     */
    function exportToPostman(collectionName, requests) {
        return {
            info: {
                name: collectionName || 'Toolzy API Collection',
                schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
            },
            item: requests.map(r => ({
                name: r.name || (r.method + ' ' + r.url),
                request: {
                    method: r.method,
                    header: Object.keys(r.headers || {}).map(k => ({ key: k, value: r.headers[k], type: 'text' })),
                    url: { raw: r.url },
                    body: r.body ? { mode: 'raw', raw: r.body } : undefined
                }
            }))
        };
    }

    /**
     * Export Request to Markdown documentation
     */
    function exportToMarkdown(req, res) {
        let md = `# API Documentation: ${req.name || req.url}\n\n`;
        md += `**Method:** \`${req.method}\`  \n`;
        md += `**URL:** \`${req.url}\`  \n\n`;

        if (Object.keys(req.headers || {}).length > 0) {
            md += `### Request Headers\n| Header | Value |\n| --- | --- |\n`;
            Object.keys(req.headers).forEach(k => {
                md += `| \`${k}\` | \`${req.headers[k]}\` |\n`;
            });
            md += `\n`;
        }

        if (req.body) {
            md += `### Request Body\n\`\`\`json\n${req.body}\n\`\`\`\n\n`;
        }

        if (res) {
            md += `### Response (${res.status} ${res.statusText})\n`;
            md += `- **Time:** ${res.responseTimeMs} ms\n`;
            md += `- **Size:** ${(res.sizeBytes / 1024).toFixed(2)} KB\n\n`;
            if (res.body) {
                md += `\`\`\`json\n${res.body}\n\`\`\`\n`;
            }
        }

        return md;
    }

    return {
        parseCurl: parseCurl,
        parsePostmanCollection: parsePostmanCollection,
        parseOpenApi: parseOpenApi,
        exportToPostman: exportToPostman,
        exportToMarkdown: exportToMarkdown
    };
})();
