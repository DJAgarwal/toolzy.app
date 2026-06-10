@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Input Section -->
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-code-slash me-2 text-primary"></i>XML Input</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnExample" class="btn btn-outline-primary">Example</button>
                        <button id="btnClear" class="btn btn-outline-danger">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <textarea id="xmlInput" class="form-control border-0 font-monospace p-3" rows="12" placeholder='<!-- Paste your XML here... -->
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <item id="1">Hello World</item>
</root>'></textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <input type="file" id="fileInput" class="d-none" accept=".xml,.txt,.svg,.rss,.atom">
                            <button id="btnUpload" class="btn btn-sm btn-outline-secondary"><i class="bi bi-upload me-1"></i>Upload XML</button>
                        </div>
                        <div class="small text-muted italic">
                            <span id="charCount">0</span> Chars | <span id="lineCount">0</span> Lines
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Results (Hidden by default) -->
            <div id="validationArea" class="alert alert-danger d-none py-3 mb-4 shadow-sm">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>XML Parsing Error</h6>
                <div id="validationError" class="small"></div>
            </div>

            <!-- Action Buttons -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-25">
                <div class="card-body py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button id="btnFormat" class="btn btn-primary px-4 fw-bold flex-grow-1">
                                    <i class="bi bi-magic me-2"></i>Format XML
                                </button>
                                <button id="btnMinify" class="btn btn-outline-secondary px-3">
                                    <i class="bi bi-arrows-angle-contract me-2"></i>Minify
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Output Section -->
            <div id="outputContainer" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Formatted Result</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnCopyOutput" class="btn btn-outline-secondary" title="Copy"><i class="bi bi-clipboard"></i></button>
                        <button id="btnDownload" class="btn btn-outline-success" title="Download"><i class="bi bi-download"></i></button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <pre id="xmlOutput" class="p-3 mb-0 font-monospace bg-dark text-white rounded-bottom"></pre>
                </div>
            </div>

            <!-- Tree View Section -->
            <div id="treeArea" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>XML Tree Explorer</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnExpandAll" class="btn btn-outline-secondary">Expand All</button>
                        <button id="btnCollapseAll" class="btn btn-outline-secondary">Collapse All</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="treeSearch" class="form-control border-start-0" placeholder="Search nodes...">
                    </div>
                    <div id="xmlTree" class="xml-tree-container font-monospace small">
                        <!-- Tree nodes will be injected here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Options -->
        <div class="col-lg-4">
            <!-- Formatting Controls -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Formatting Rules</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Indentation</label>
                        <select id="indentSize" class="form-select form-select-sm">
                            <option value="2">2 Spaces</option>
                            <option value="4" selected>4 Spaces</option>
                            <option value="8">8 Spaces</option>
                            <option value="tabs">Tabs</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Attributes</label>
                        <select id="attrWrap" class="form-select form-select-sm">
                            <option value="same">Keep on same line</option>
                            <option value="new">One per line</option>
                            <option value="auto">Auto (wrap long lines)</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="preserveComments" checked>
                        <label class="form-check-label small" for="preserveComments">Preserve Comments</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="preserveCDATA" checked>
                        <label class="form-check-label small" for="preserveCDATA">Preserve CDATA</label>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Max Line Width</label>
                        <input type="number" id="lineWidth" class="form-control form-control-sm" value="120">
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div id="statsCard" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Document Analysis</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Elements</span>
                            <span id="statElements" class="badge bg-primary rounded-pill">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Attributes</span>
                            <span id="statAttributes" class="fw-bold">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Max Depth</span>
                            <span id="statDepth" class="fw-bold">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Namespaces</span>
                            <span id="statNamespaces" class="text-info fw-bold">0</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Namespace Summary -->
            <div id="nsCard" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-tags me-2"></i>Namespaces</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 x-small">
                            <tbody id="nsList">
                                <!-- Namespaces list -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- XPath Helper -->
            <div id="xpathCard" class="card mb-4 shadow-sm border-info border-opacity-25 bg-info bg-opacity-10 d-none">
                <div class="card-body py-3">
                    <h6 class="fw-bold text-info-emphasis mb-2 small text-uppercase">XPath Helper</h6>
                    <div class="small text-muted mb-2">Select a node in the tree to generate its XPath.</div>
                    <div class="input-group input-group-sm">
                        <input type="text" id="xpathValue" class="form-control font-monospace" readonly>
                        <button class="btn btn-outline-info" id="btnCopyXPath"><i class="bi bi-clipboard"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 border-top pt-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Mastering XML Structure & Validation</h2>
                <div class="row g-4 text-muted small">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Why Format Your XML?</h6>
                        <p>XML is highly structured and relies on nested tags. When XML is minified or generated by a machine, it becomes a "wall of text" that is nearly impossible for a human to audit. Professional formatting reapplies indentation and newlines, allowing you to quickly verify parent-child relationships and attribute values.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Strict Validation Matters</h6>
                        <p>Unlike HTML, which is designed to be "forgiving," XML parsers are built to fail on the slightest error. A missing closing tag, an unescaped special character, or a mismatched case in a tag name will invalidate the entire document. Our tool uses a real browser-based DOM parser to ensure your XML is 100% well-formed.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Understanding Namespaces (xmlns)</h6>
                        <p>Namespaces are used to provide uniquely named elements and attributes. They are critical in complex documents like SOAP envelopes or Android manifests where tags from different schemas might overlap. Our analyzer identifies all namespaces and their URIs automatically.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">XPath Navigation</h6>
                        <p>XPath (XML Path Language) is a query language for selecting nodes from an XML document. Use our tree explorer to click on any node and instantly generate its absolute XPath, which you can use in your code, transformation scripts (XSLT), or automated testing tools.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#xmlInput {
    resize: none;
    font-size: 0.9rem;
    line-height: 1.5;
    background-color: #fcfcfc;
}

#xmlOutput {
    white-space: pre-wrap;
    word-break: break-all;
    font-size: 0.85rem;
    line-height: 1.5;
    border-radius: 0 0 0.375rem 0.375rem;
}

.italic { font-style: italic; }
.x-small { font-size: 0.75rem; }

/* Tree View Styling */
.xml-tree-container {
    max-height: 500px;
    overflow: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 10px;
    background-color: #fff;
}

.tree-node {
    margin-left: 20px;
    border-left: 1px solid #eee;
    padding-left: 10px;
}

.tree-toggle {
    cursor: pointer;
    margin-right: 5px;
    user-select: none;
    color: #6c757d;
}

.tree-tag { color: #800000; font-weight: bold; cursor: pointer; }
.tree-tag:hover { text-decoration: underline; }
.tree-attr { color: #ff0000; }
.tree-val { color: #0000ff; }
.tree-text { color: #333; }
.tree-comment { color: #008000; font-style: italic; }

.node-highlight {
    background-color: #fff3cd;
}

/* Syntax Highlighting (for output) */
.xml-kw { color: #569cd6; } /* Tags */
.xml-attr { color: #9cdcfe; } /* Attributes */
.xml-str { color: #ce9178; } /* Values */
.xml-cmt { color: #6a9955; } /* Comments */
.xml-txt { color: #d4d4d4; } /* Text */
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const XMLTool = (function() {
    let currentDoc = null;
    let lastFormatted = '';
    let nodeMap = new Map(); // Map elements to nodes for tree selection
    
    const elements = {
        input: document.getElementById('xmlInput'),
        output: document.getElementById('xmlOutput'),
        outputContainer: document.getElementById('outputContainer'),
        tree: document.getElementById('xmlTree'),
        treeArea: document.getElementById('treeArea'),
        validationArea: document.getElementById('validationArea'),
        validationError: document.getElementById('validationError'),
        treeSearch: document.getElementById('treeSearch'),
        xpathCard: document.getElementById('xpathCard'),
        xpathValue: document.getElementById('xpathValue'),
        statsCard: document.getElementById('statsCard'),
        nsCard: document.getElementById('nsCard'),
        nsList: document.getElementById('nsList'),
        charCount: document.getElementById('charCount'),
        lineCount: document.getElementById('lineCount')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        document.getElementById('btnExample').addEventListener('click', loadExample);
        document.getElementById('btnClear').addEventListener('click', clear);
        document.getElementById('btnUpload').addEventListener('click', () => document.getElementById('fileInput').click());
        document.getElementById('btnFormat').addEventListener('click', format);
        document.getElementById('btnMinify').addEventListener('click', minify);
        document.getElementById('btnCopyOutput').addEventListener('click', copyOutput);
        document.getElementById('btnCopyXPath').addEventListener('click', copyXPath);
        document.getElementById('btnDownload').addEventListener('click', () => download('formatted'));
        document.getElementById('btnExpandAll').addEventListener('click', expandAll);
        document.getElementById('btnCollapseAll').addEventListener('click', collapseAll);
        
        elements.input.addEventListener('input', updateCounts);
        document.getElementById('fileInput').addEventListener('change', handleFileUpload);
        elements.treeSearch.addEventListener('input', searchTree);
        
        // Ctrl + Enter shortcut
        elements.input.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                format();
            }
        });

        // Tree delegation
        elements.tree.addEventListener('click', (e) => {
            if (e.target.classList.contains('tree-toggle')) {
                const node = e.target.closest('.tree-node');
                const children = node.querySelector('.tree-children');
                if (children) {
                    children.classList.toggle('d-none');
                    e.target.classList.toggle('bi-chevron-down');
                    e.target.classList.toggle('bi-chevron-right');
                }
            } else if (e.target.classList.contains('tree-tag')) {
                const node = nodeMap.get(e.target);
                if (node) selectNode(node, e.target);
            }
        });
    }

    function format() {
        const raw = elements.input.value.trim();
        if (!raw) return showToast('Please enter some XML', 'warning');

        const options = {
            indent: document.getElementById('indentSize').value,
            attrWrap: document.getElementById('attrWrap').value,
            preserveComments: document.getElementById('preserveComments').checked,
            preserveCDATA: document.getElementById('preserveCDATA').checked,
            lineWidth: parseInt(document.getElementById('lineWidth').value) || 120
        };

        const result = processXML(raw, 'beautify', options);
        if (result.success) {
            elements.output.innerHTML = highlightXML(result.output);
            lastFormatted = result.output;
            elements.outputContainer.classList.remove('d-none');
            elements.validationArea.classList.add('d-none');
            
            analyze(result.doc);
            renderTree(result.doc);
            showToast('XML Formatted successfully', 'success');
        } else {
            showError(result.error);
        }
    }

    function minify() {
        const raw = elements.input.value.trim();
        if (!raw) return;

        const result = processXML(raw, 'minify');
        if (result.success) {
            elements.output.textContent = result.output;
            lastFormatted = result.output;
            elements.outputContainer.classList.remove('d-none');
            elements.validationArea.classList.add('d-none');
            
            analyze(result.doc);
            renderTree(result.doc);
            showToast('XML Minified', 'info');
        } else {
            showError(result.error);
        }
    }

    function processXML(xml, mode, opts) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(xml, 'text/xml');
        
        const parseError = doc.getElementsByTagName('parsererror');
        if (parseError.length > 0) {
            return { success: false, error: parseError[0].textContent };
        }

        if (mode === 'minify') {
            const minified = serializeMinified(doc);
            return { success: true, output: minified, doc: doc };
        }

        const beautified = serializeBeautified(doc, opts);
        return { success: true, output: beautified, doc: doc };
    }

    function serializeMinified(doc) {
        const serializer = new XMLSerializer();
        let xml = serializer.serializeToString(doc);
        return xml.replace(/>\s+</g, '><').trim();
    }

    function serializeBeautified(doc, opts) {
        const indentStr = opts.indent === 'tabs' ? '\t' : ' '.repeat(parseInt(opts.indent));
        let result = '';

        // Add XML declaration if present in original or doc
        const original = elements.input.value.trim();
        if (original.startsWith('<?xml')) {
            const declMatch = original.match(/^<\?xml.*?\?>/);
            if (declMatch) result += declMatch[0] + '\n';
        } else if (doc.xmlVersion) {
            result += `<?xml version="${doc.xmlVersion}" encoding="${doc.xmlEncoding || 'UTF-8'}"?>\n`;
        }

        function walk(node, level) {
            const indent = indentStr.repeat(level);

            if (node.nodeType === Node.ELEMENT_NODE) {
                let tag = `<${node.nodeName}`;
                
                // Format attributes
                const attrs = Array.from(node.attributes);
                if (attrs.length > 0) {
                    if (opts.attrWrap === 'new') {
                        attrs.forEach(attr => {
                            tag += `\n${indent}${indentStr}${attr.name}="${attr.value}"`;
                        });
                        tag += `\n${indent}`;
                    } else {
                        attrs.forEach(attr => {
                            tag += ` ${attr.name}="${attr.value}"`;
                        });
                    }
                }

                if (node.childNodes.length === 0) {
                    result += `${indent}${tag}/>\n`;
                } else {
                    const hasOnlyText = node.childNodes.length === 1 && node.firstChild.nodeType === Node.TEXT_NODE;
                    if (hasOnlyText) {
                        result += `${indent}${tag}>${node.firstChild.textContent}</${node.nodeName}>\n`;
                    } else {
                        result += `${indent}${tag}>\n`;
                        Array.from(node.childNodes).forEach(child => walk(child, level + 1));
                        result += `${indent}</${node.nodeName}>\n`;
                    }
                }
            } else if (node.nodeType === Node.TEXT_NODE) {
                const text = node.textContent.trim();
                if (text) result += `${indent}${text}\n`;
            } else if (node.nodeType === Node.COMMENT_NODE && opts.preserveComments) {
                result += `${indent}<!--${node.textContent}-->\n`;
            } else if (node.nodeType === Node.CDATA_SECTION_NODE && opts.preserveCDATA) {
                result += `${indent}<![CDATA[${node.textContent}]]>\n`;
            }
        }

        Array.from(doc.childNodes).forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                walk(node, 0);
            }
        });

        return result.trim();
    }

    function highlightXML(xml) {
        return xml.replace(/[<>&]/g, m => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[m]))
            .replace(/&lt;(\/?[a-zA-Z0-9:]+)/g, '<span class="xml-kw">&lt;$1</span>') // Tags
            .replace(/\s([a-zA-Z0-9:]+)="([^"]*)"/g, ' <span class="xml-attr">$1</span>="<span class="xml-str">$2</span>"') // Attrs
            .replace(/&lt;!--([\s\S]*?)--&gt;/g, '<span class="xml-cmt">&lt;!--$1--&gt;</span>'); // Comments
    }

    function analyze(doc) {
        elements.statsCard.classList.remove('d-none');
        elements.nsCard.classList.remove('d-none');

        let elementsCount = 0;
        let attributeCount = 0;
        let maxDepth = 0;
        const namespaces = new Map();

        function traverse(node, depth) {
            if (node.nodeType === Node.ELEMENT_NODE) {
                elementsCount++;
                attributeCount += node.attributes.length;
                maxDepth = Math.max(maxDepth, depth);

                // Analyze namespaces
                Array.from(node.attributes).forEach(attr => {
                    if (attr.name.startsWith('xmlns')) {
                        namespaces.set(attr.name, attr.value);
                    }
                });

                Array.from(node.childNodes).forEach(child => traverse(child, depth + 1));
            }
        }

        if (doc.documentElement) {
            traverse(doc.documentElement, 1);
        }

        document.getElementById('statElements').textContent = elementsCount;
        document.getElementById('statAttributes').textContent = attributeCount;
        document.getElementById('statDepth').textContent = maxDepth;
        document.getElementById('statNamespaces').textContent = namespaces.size;

        // Render Namespaces
        let nsHtml = '';
        namespaces.forEach((uri, prefix) => {
            nsHtml += `<tr><td class="fw-bold">${prefix}</td><td class="text-break">${uri}</td></tr>`;
        });
        elements.nsList.innerHTML = nsHtml || '<tr><td colspan="2" class="text-muted">No namespaces found</td></tr>';
    }

    function renderTree(doc) {
        elements.treeArea.classList.remove('d-none');
        elements.tree.innerHTML = '';
        nodeMap.clear();

        function createTreeNode(node) {
            if (node.nodeType === Node.ELEMENT_NODE) {
                const container = document.createElement('div');
                container.className = 'tree-node';

                const header = document.createElement('div');
                header.className = 'd-flex align-items-center';

                if (node.childNodes.length > 0 && Array.from(node.childNodes).some(n => n.nodeType === Node.ELEMENT_NODE)) {
                    const toggle = document.createElement('span');
                    toggle.className = 'tree-toggle bi bi-chevron-down';
                    header.appendChild(toggle);
                }

                const tag = document.createElement('span');
                tag.className = 'tree-tag';
                tag.textContent = node.nodeName;
                nodeMap.set(tag, node);
                header.appendChild(tag);

                // Attributes in tree
                Array.from(node.attributes).forEach(attr => {
                    const a = document.createElement('span');
                    a.className = 'ms-2 x-small';
                    a.innerHTML = `<span class="tree-attr">${attr.name}</span>=<span class="tree-val">"${attr.value}"</span>`;
                    header.appendChild(a);
                });

                container.appendChild(header);

                if (node.childNodes.length > 0) {
                    const childrenContainer = document.createElement('div');
                    childrenContainer.className = 'tree-children';
                    Array.from(node.childNodes).forEach(child => {
                        const childEl = createTreeNode(child);
                        if (childEl) childrenContainer.appendChild(childEl);
                    });
                    container.appendChild(childrenContainer);
                }

                return container;
            } else if (node.nodeType === Node.TEXT_NODE) {
                const val = node.textContent.trim();
                if (!val) return null;
                const txt = document.createElement('div');
                txt.className = 'tree-text ms-4 text-muted italic';
                txt.textContent = val;
                return txt;
            } else if (node.nodeType === Node.COMMENT_NODE) {
                const cmt = document.createElement('div');
                cmt.className = 'tree-comment ms-4';
                cmt.textContent = `<!-- ${node.textContent} -->`;
                return cmt;
            }
            return null;
        }

        if (doc.documentElement) {
            const rootNode = createTreeNode(doc.documentElement);
            if (rootNode) elements.tree.appendChild(rootNode);
        }
    }

    function selectNode(node, el) {
        // Highlight in tree
        document.querySelectorAll('.node-highlight').forEach(n => n.classList.remove('node-highlight'));
        el.classList.add('node-highlight');

        // Generate XPath
        const xpath = getAbsoluteXPath(node);
        elements.xpathCard.classList.remove('d-none');
        elements.xpathValue.value = xpath;
    }

    function getAbsoluteXPath(node) {
        const parts = [];
        while (node && node.nodeType === Node.ELEMENT_NODE) {
            let index = 0;
            let sibling = node.previousSibling;
            while (sibling) {
                if (sibling.nodeType === Node.ELEMENT_NODE && sibling.nodeName === node.nodeName) {
                    index++;
                }
                sibling = sibling.previousSibling;
            }
            const tagName = node.nodeName;
            const pathIndex = index > 0 || (node.nextSibling && node.nextSibling.nodeName === node.nodeName) ? `[${index + 1}]` : '';
            parts.unshift(`${tagName}${pathIndex}`);
            node = node.parentNode;
        }
        return parts.length ? '/' + parts.join('/') : '';
    }

    function searchTree() {
        const query = elements.treeSearch.value.toLowerCase();
        
        // Reset all nodes first
        document.querySelectorAll('.tree-node, .tree-text, .tree-comment').forEach(n => {
            n.classList.remove('d-none');
            n.classList.remove('node-highlight');
        });

        if (!query) return;

        const allNodes = document.querySelectorAll('.tree-node');
        allNodes.forEach(node => {
            const tag = node.querySelector('.tree-tag');
            const attrs = Array.from(node.querySelectorAll('.tree-attr, .tree-val'));
            const text = tag.textContent.toLowerCase() + attrs.map(a => a.textContent.toLowerCase()).join('');
            
            if (text.includes(query)) {
                // Show this node and all its parents
                let current = node;
                while (current && current !== elements.tree) {
                    current.classList.remove('d-none');
                    const parentChildren = current.closest('.tree-children');
                    if (parentChildren) {
                        parentChildren.classList.remove('d-none');
                        const toggle = parentChildren.previousElementSibling.querySelector('.tree-toggle');
                        if (toggle) {
                            toggle.classList.remove('bi-chevron-right');
                            toggle.classList.add('bi-chevron-down');
                        }
                    }
                    current = current.parentElement.closest('.tree-node');
                }
            } else {
                node.classList.add('d-none');
            }
        });
    }

    function updateCounts() {
        const val = elements.input.value;
        elements.charCount.textContent = val.length;
        elements.lineCount.textContent = val ? val.split('\n').length : 0;
    }

    function showError(msg) {
        elements.validationArea.classList.remove('d-none');
        elements.validationError.textContent = msg;
        elements.outputContainer.classList.add('d-none');
        elements.treeArea.classList.add('d-none');
        elements.statsCard.classList.add('d-none');
        elements.nsCard.classList.add('d-none');
    }

    function handleFileUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            elements.input.value = event.target.result;
            updateCounts();
            format();
        };
        reader.readAsText(file);
    }

    function loadExample() {
        elements.input.value = `<?xml version="1.0" encoding="UTF-8"?>
<catalog xmlns:ext="http://example.com/ext">
    <product id="P001" category="Electronics">
        <name>Smartphone X Pro</name>
        <ext:spec>OLED Display</ext:spec>
        <price currency="USD">899.99</price>
        <description><![CDATA[The latest smartphone with <b>5G</b> support.]]></description>
        <!-- In stock availability -->
        <stock>150</stock>
    </product>
    <product id="P002" category="Accessories">
        <name>Wireless Earbuds</name>
        <price currency="USD">129.50</price>
        <stock>0</stock>
    </product>
</catalog>`;
        updateCounts();
        format();
    }

    function clear() {
        elements.input.value = '';
        elements.outputContainer.classList.add('d-none');
        elements.treeArea.classList.add('d-none');
        elements.statsCard.classList.add('d-none');
        elements.nsCard.classList.add('d-none');
        elements.validationArea.classList.add('d-none');
        elements.xpathCard.classList.add('d-none');
        updateCounts();
    }

    function copyOutput() {
        navigator.clipboard.writeText(lastFormatted).then(() => showToast('XML Copied!', 'success'));
    }

    function copyXPath() {
        navigator.clipboard.writeText(elements.xpathValue.value).then(() => showToast('XPath Copied!', 'success'));
    }

    function download(type) {
        const blob = new Blob([lastFormatted], { type: 'text/xml' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = type === 'minified' ? 'minified.xml' : 'formatted.xml';
        a.click();
        URL.revokeObjectURL(url);
    }

    function expandAll() {
        document.querySelectorAll('.tree-children').forEach(c => c.classList.remove('d-none'));
        document.querySelectorAll('.tree-toggle').forEach(t => {
            t.classList.remove('bi-chevron-right');
            t.classList.add('bi-chevron-down');
        });
    }

    function collapseAll() {
        document.querySelectorAll('.tree-children').forEach(c => c.classList.add('d-none'));
        document.querySelectorAll('.tree-toggle').forEach(t => {
            t.classList.remove('bi-chevron-down');
            t.classList.add('bi-chevron-right');
        });
    }

    return { init, loadExample, clear, copyOutput, copyXPath, download, expandAll, collapseAll };
})();

document.addEventListener('DOMContentLoaded', () => {
    XMLTool.init();
});
</script>
@endpush
