/**
 * Code Generator Module - Toolzy
 * Generates accurate executable code snippets across 10 programming languages and libraries.
 */

window.CodeGenerator = (function () {
    /**
     * Generates code snippet for specified language
     * @param {string} lang - 'curl'|'javascript'|'axios'|'php'|'laravel'|'python'|'node'|'go'|'java'|'csharp'
     * @param {Object} req - { method, url, headers, body, bodyType, formData, urlEncoded }
     */
    function generate(lang, req) {
        const { method = 'GET', url = '', headers = {}, body = '', bodyType = 'raw', formData = [], urlEncoded = [] } = req;
        const upperMethod = method.toUpperCase();

        switch (lang) {
            case 'curl':
                return generateCurl(upperMethod, url, headers, body, bodyType, formData, urlEncoded);
            case 'javascript':
                return generateFetch(upperMethod, url, headers, body);
            case 'axios':
                return generateAxios(upperMethod, url, headers, body);
            case 'php':
                return generatePhpCurl(upperMethod, url, headers, body);
            case 'laravel':
                return generateLaravelHttp(upperMethod, url, headers, body);
            case 'python':
                return generatePythonRequests(upperMethod, url, headers, body);
            case 'node':
                return generateNodeFetch(upperMethod, url, headers, body);
            case 'go':
                return generateGoNetHttp(upperMethod, url, headers, body);
            case 'java':
                return generateJavaHttpClient(upperMethod, url, headers, body);
            case 'csharp':
                return generateCSharpHttpClient(upperMethod, url, headers, body);
            default:
                return generateCurl(upperMethod, url, headers, body, bodyType, formData, urlEncoded);
        }
    }

    function generateCurl(method, url, headers, body, bodyType, formData, urlEncoded) {
        let parts = [`curl -X ${method} "${url}"`];
        Object.keys(headers).forEach(k => {
            if (bodyType === 'form' && k.toLowerCase() === 'content-type') return;
            parts.push(`  -H "${k}: ${escapeDoubleQuotes(headers[k])}"`);
        });

        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            if (bodyType === 'form' && Array.isArray(formData) && formData.length > 0) {
                formData.forEach(item => {
                    if (item.key) {
                        parts.push(`  --form '${item.key}="${item.value.replace(/'/g, "'\\''")}"'`);
                    }
                });
            } else if (bodyType === 'urlencoded' && Array.isArray(urlEncoded) && urlEncoded.length > 0) {
                urlEncoded.forEach(item => {
                    if (item.key) {
                        parts.push(`  --data-urlencode '${item.key}=${item.value.replace(/'/g, "'\\''")}'`);
                    }
                });
            } else if (body && typeof body === 'string') {
                parts.push(`  -d '${body.replace(/'/g, "'\\''")}'`);
            }
        }
        return parts.join(' \\\n');
    }

    function generateFetch(method, url, headers, body) {
        let options = { method: method };
        if (Object.keys(headers).length > 0) options.headers = headers;
        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) options.body = body;

        return `fetch("${url}", ${JSON.stringify(options, null, 2)})\n  .then(response => response.json())\n  .then(data => console.log(data))\n  .catch(error => console.error('Error:', error));`;
    }

    function generateAxios(method, url, headers, body) {
        let config = { method: method.toLowerCase(), url: url };
        if (Object.keys(headers).length > 0) config.headers = headers;
        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            try { config.data = JSON.parse(body); } catch(e) { config.data = body; }
        }

        return `import axios from 'axios';\n\naxios(${JSON.stringify(config, null, 2)})\n  .then(response => console.log(response.data))\n  .catch(error => console.error(error));`;
    }

    function generatePhpCurl(method, url, headers, body) {
        let headerLines = Object.keys(headers).map(k => `    "${k}: ${escapeDoubleQuotes(headers[k])}"`);
        let headerArr = headerLines.length ? `[\n${headerLines.join(',\n')}\n]` : '[]';

        let code = `<?php\n\n$curl = curl_init();\n\ncurl_setopt_array($curl, [\n  CURLOPT_URL => "${url}",\n  CURLOPT_RETURNTRANSFER => true,\n  CURLOPT_ENCODING => "",\n  CURLOPT_MAXREDIRS => 10,\n  CURLOPT_TIMEOUT => 30,\n  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,\n  CURLOPT_CUSTOMREQUEST => "${method}",\n`;

        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            code += `  CURLOPT_POSTFIELDS => ${JSON.stringify(body)},\n`;
        }

        code += `  CURLOPT_HTTPHEADER => ${headerArr},\n]);\n\n$response = curl_exec($curl);\n$err = curl_error($curl);\n\ncurl_close($curl);\n\nif ($err) {\n  echo "cURL Error #:" . $err;\n} else {\n  echo $response;\n}`;
        return code;
    }

    function generateLaravelHttp(method, url, headers, body) {
        let headersCode = Object.keys(headers).length > 0
            ? `withHeaders(${JSON.stringify(headers, null, 4)})->`
            : '';
        
        let m = method.toLowerCase();
        if (['post', 'put', 'patch', 'delete'].includes(m) && body) {
            return `use Illuminate\\Support\\Facades\\Http;\n\n$response = Http::${headersCode}withBody(\n    '${body.replace(/'/g, "\\'")}', 'application/json'\n)->${m}('${url}');\n\nreturn $response->json();`;
        } else {
            return `use Illuminate\\Support\\Facades\\Http;\n\n$response = Http::${headersCode}${m}('${url}');\n\nreturn $response->json();`;
        }
    }

    function generatePythonRequests(method, url, headers, body) {
        let code = `import requests\n\nurl = "${url}"\n`;
        let reqArgs = [];

        if (Object.keys(headers).length > 0) {
            code += `headers = ${JSON.stringify(headers, null, 4)}\n`;
            reqArgs.push('headers=headers');
        }

        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            code += `payload = ${JSON.stringify(body)}\n`;
            reqArgs.push('data=payload');
        }

        let argsStr = reqArgs.length > 0 ? `, ${reqArgs.join(', ')}` : '';
        code += `\nresponse = requests.${method.toLowerCase()}(url${argsStr})\n\nprint(response.status_code)\nprint(response.text)`;
        return code;
    }

    function generateNodeFetch(method, url, headers, body) {
        return `import fetch from 'node-fetch';\n\nconst response = await fetch('${url}', {\n  method: '${method}',\n  headers: ${JSON.stringify(headers, null, 2)},\n  ${body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method) ? `body: JSON.stringify(${JSON.stringify(body)})` : ''}\n});\n\nconst data = await response.text();\nconsole.log(data);`;
    }

    function generateGoNetHttp(method, url, headers, body) {
        let hasBody = body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method);
        let bodyCode = hasBody ? `strings.NewReader(${JSON.stringify(body)})` : 'nil';

        let code = `package main\n\nimport (\n\t"fmt"\n\t"io"\n\t"net/http"\n${hasBody ? '\t"strings"\n' : ''})\n\nfunc main() {\n\turl := "${url}"\n\treq, err := http.NewRequest("${method}", url, ${bodyCode})\n\tif err != nil {\n\t\tpanic(err)\n\t}\n\n`;

        Object.keys(headers).forEach(k => {
            code += `\treq.Header.Add("${k}", "${headers[k]}")\n`;
        });

        code += `\n\tres, err := http.DefaultClient.Do(req)\n\tif err != nil {\n\t\tpanic(err)\n\t}\n\tdefer res.Body.Close()\n\n\tbody, _ := io.ReadAll(res.Body)\n\tfmt.Println(res.StatusCode)\n\tfmt.Println(string(body))\n}`;
        return code;
    }

    function generateJavaHttpClient(method, url, headers, body) {
        let code = `import java.net.URI;\nimport java.net.http.HttpClient;\nimport java.net.http.HttpRequest;\nimport java.net.http.HttpResponse;\n\npublic class ApiClient {\n    public static void main(String[] args) throws Exception {\n        HttpClient client = HttpClient.newHttpClient();\n        HttpRequest.Builder builder = HttpRequest.newBuilder()\n                .uri(URI.create("${url}"))\n`;

        Object.keys(headers).forEach(k => {
            code += `                .header("${k}", "${headers[k]}")\n`;
        });

        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            code += `                .method("${method}", HttpRequest.BodyPublishers.ofString(${JSON.stringify(body)}));\n`;
        } else {
            code += `                .method("${method}", HttpRequest.BodyPublishers.noBody());\n`;
        }

        code += `\n        HttpResponse<String> response = client.send(builder.build(), HttpResponse.BodyHandlers.ofString());\n        System.out.println(response.statusCode());\n        System.out.println(response.body());\n    }\n}`;
        return code;
    }

    function generateCSharpHttpClient(method, url, headers, body) {
        let code = `using System;\nusing System.Net.Http;\nusing System.Text;\nusing System.Threading.Tasks;\n\nclass Program {\n    static async Task Main() {\n        var client = new HttpClient();\n        var request = new HttpRequestMessage(HttpMethod.${capitalize(method)}, "${url}");\n\n`;

        Object.keys(headers).forEach(k => {
            if (k.toLowerCase() !== 'content-type') {
                code += `        request.Headers.Add("${k}", "${headers[k]}");\n`;
            }
        });

        if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            let ctype = headers['content-type'] || headers['Content-Type'] || 'application/json';
            code += `        request.Content = new StringContent(${JSON.stringify(body)}, Encoding.UTF8, "${ctype}");\n`;
        }

        code += `\n        var response = await client.SendAsync(request);\n        var content = await response.Content.ReadAsStringAsync();\n        Console.WriteLine(response.StatusCode);\n        Console.WriteLine(content);\n    }\n}`;
        return code;
    }

    function escapeDoubleQuotes(str) {
        return (str || '').replace(/"/g, '\\"');
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    return {
        generate: generate
    };
})();
