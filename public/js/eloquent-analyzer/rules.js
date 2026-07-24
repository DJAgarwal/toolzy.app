/**
 * Laravel Eloquent Query Analyzer - Modular Rule Registry
 * Toolzy (c) 2026 - 100% Client-side privacy first static analysis
 */

window.EloquentAnalyzerRules = [
    {
        id: 'ELOQUENT_N1_LOOP',
        name: 'N+1 Query Risk in Loop Iteration',
        category: 'performance',
        severity: 'critical',
        description: 'Relationship properties or method calls accessed inside loops without prior eager loading trigger N+1 separate database queries.',
        educational: 'The N+1 problem happens when parent records are loaded in 1 query, but related child models are queried individually inside a loop. For 1,000 items, this fires 1,001 SQL queries, overwhelming database connection pools.',
        suggestedFix: 'Eager load the relationship before iterating using `User::with(\'posts\')->get()` or `$users->load(\'posts\')`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            let inLoop = false;
            let loopVar = null;
            let loopStartLine = 0;

            lines.forEach((line, idx) => {
                const lineNum = idx + 1;
                // Match foreach ($users as $user) or $users->each(function($user) ...
                const foreachMatch = line.match(/foreach\s*\(\s*\$(\w+)\s+as\s+\$(\w+)\s*\)/i);
                const eachMatch = line.match(/\$(\w+)->each\s*\(\s*function\s*\(\s*\$(\w+)\s*\)/i) || line.match(/\$(\w+)->each\s*\(\s*fn\s*\(\s*\$(\w+)\s*\)/i);

                if (foreachMatch) {
                    inLoop = true;
                    loopVar = foreachMatch[2];
                    loopStartLine = lineNum;
                } else if (eachMatch) {
                    inLoop = true;
                    loopVar = eachMatch[2];
                    loopStartLine = lineNum;
                }

                if (inLoop && loopVar) {
                    // Check for relationship access like $user->posts, $user->comments(), $user->profile->avatar
                    const relRegex = new RegExp('\\$' + loopVar + '->([a-zA-Z0-9_]+)(\\(.*\\))?', 'g');
                    let match;
                    while ((match = relRegex.exec(line)) !== null) {
                        const propName = match[1];
                        // Ignore standard methods and non-relationship getters
                        const standardMethods = ['id', 'save', 'update', 'delete', 'toArray', 'toJson', 'getKey', 'getAttribute', 'fill', 'is', 'wasRecentlyCreated'];
                        if (!standardMethods.includes(propName)) {
                            // Check if line or preceding context has eager loading
                            const hasEagerLoading = code.includes("with('") || code.includes('with("') || code.includes("load('") || code.includes('load("');
                            if (!hasEagerLoading) {
                                issues.push({
                                    ruleId: 'ELOQUENT_N1_LOOP',
                                    line: lineNum,
                                    codeSnippet: line.trim(),
                                    title: 'N+1 Query Detected inside loop',
                                    message: `Accessing relationship \`$${loopVar}->${propName}\` inside a loop without eager loading creates N+1 database queries.`,
                                    whyItMatters: 'Triggers 1 extra SQL roundtrip per iteration. 100 rows = 101 database queries.',
                                    fix: `Add eager loading before the loop: \`Model::with('${propName}')->get()\``,
                                    optimizedCode: `$items = Model::with('${propName}')->get();\nforeach ($items as $item) {\n    echo $item->${propName};\n}`
                                });
                            }
                        }
                    }
                }

                if (line.includes('}') && inLoop) {
                    // Reset loop state when loop block ends
                    if (idx > loopStartLine + 5 || line.trim() === '}') {
                        inLoop = false;
                        loopVar = null;
                    }
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_MISSING_EAGER_LOADING',
        name: 'Missing Eager Loading on Model Query',
        category: 'performance',
        severity: 'high',
        description: 'Fetching collections with Model::get() or Model::all() without eager loading relationship fields accessed later.',
        educational: 'Lazy loading relationships in Laravel executes queries on-demand. When processing lists of objects, lazy loading leads to poor performance. Using `with()` batches relationship data in a single `WHERE IN (...)` query.',
        suggestedFix: 'Use `Model::with(\'relationshipName\')->get()` to pre-fetch related entities.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            const getNoWithRegex = /([A-Z]\w+)::(all|get|where\(.*?\)->get)\(\s*\)/gi;
            let match;
            while ((match = getNoWithRegex.exec(code)) !== null) {
                const model = match[1];
                if (!code.includes('with(') && !code.includes('load(')) {
                    issues.push({
                        ruleId: 'ELOQUENT_MISSING_EAGER_LOADING',
                        line: getLineNumber(code, match.index),
                        codeSnippet: match[0],
                        title: `Potential Missing Eager Loading on ${model}`,
                        message: `Querying \`${match[0]}\` without \`with()\` may lead to lazy loading overhead if relationships are accessed later.`,
                        whyItMatters: 'Lazy loading causes separate queries for each record when relationships are referenced.',
                        fix: `Specify required relationships: \`${model}::with(['posts', 'comments'])->get()\``,
                        optimizedCode: `$${model.toLowerCase()}s = ${model}::with(['posts'])->get();`
                    });
                }
            }
            return issues;
        }
    },

    {
        id: 'ELOQUENT_OVER_FETCHING_ALL',
        name: 'Unbounded Dataset Retrieval (Model::all())',
        category: 'memory',
        severity: 'high',
        description: 'Fetching all columns and records with Model::all() or DB::table()->get() without limit, pagination, or column selection.',
        educational: '`Model::all()` retrieves all rows and all columns into PHP memory. In production, as table sizes grow, this causes severe memory spikes, high network transfer costs, and Out-Of-Memory (OOM) fatal errors.',
        suggestedFix: 'Select specific columns with `select()`, restrict rows with `paginate()`, or use `chunk()` / `cursor()` for bulk processing.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                const allMatch = line.match(/([A-Z]\w+)::all\(\s*\)/);
                const getNoLimit = line.match(/(DB::table\(.*?\)|[A-Z]\w+)->get\(\s*\)/);

                if (allMatch && !line.includes('paginate') && !line.includes('select')) {
                    const model = allMatch[1];
                    issues.push({
                        ruleId: 'ELOQUENT_OVER_FETCHING_ALL',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: `Unbounded Data Fetch via ${model}::all()`,
                        message: `\`${model}::all()\` loads every column of every record into RAM without limit.`,
                        whyItMatters: 'Memory usage scales linearly with table size. Can reduce memory footprint by 70-90% with column selection and pagination.',
                        fix: `Use \`select()\` to fetch required fields and \`paginate()\`: \`${model}::select(['id', 'name'])->paginate(20)\``,
                        optimizedCode: `$${model.toLowerCase()}s = ${model}::select(['id', 'name', 'email'])->paginate(25);`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_COLLECTION_FILTERING',
        name: 'Filtering in PHP Memory instead of Database SQL',
        category: 'performance',
        severity: 'high',
        description: 'Using Model::get()->where(...) or Model::all()->filter(...) loads all database records into PHP first before filtering.',
        educational: 'Calling `where()` on a collection filters data in PHP memory after fetching everything from the database. Database SQL indexes are bypassed, causing huge IO and RAM waste.',
        suggestedFix: 'Move the `where()` constraint before `get()` so the database engine filters rows using indexes.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/(get\(\)|all\(\))->(where|filter|sortBy|take|first)\s*\(/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_COLLECTION_FILTERING',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Collection Method Used After get() / all()',
                        message: 'Filtering or sorting in PHP memory after `get()` loads unneeded rows from the database.',
                        whyItMatters: 'Database indexing cannot optimize PHP memory filtering, causing full table scans.',
                        fix: 'Apply `where()`, `orderBy()`, or `limit()` before calling `get()` or `first()`.',
                        optimizedCode: `// Before: Model::get()->where('status', 'active')\n// After:\n$results = Model::where('status', 'active')->get();`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_COUNT_COLLECTION_MISUSE',
        name: 'Inefficient Record Counting (get()->count())',
        category: 'performance',
        severity: 'medium',
        description: 'Using Model::get()->count() or Model::all()->count() retrieves all model instances just to compute an integer count.',
        educational: '`get()->count()` hydrates full PHP Eloquent model objects from SQL before counting array elements. `Model::count()` executes `SELECT COUNT(*) FROM table` directly in the database engine in milliseconds.',
        suggestedFix: 'Replace `get()->count()` with direct SQL count: `Model::count()` or `$user->posts()->count()`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/(get\(\)|all\(\))->count\(\)/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_COUNT_COLLECTION_MISUSE',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Hydrating Models to Count Records',
                        message: 'Calling `->get()->count()` hydrates full PHP model objects into RAM before counting.',
                        whyItMatters: 'Executes heavy memory allocation. Direct SQL `count()` is up to 100x faster and consumes near zero RAM.',
                        fix: 'Call `count()` directly on the query builder: `Model::where(...)->count()`.',
                        optimizedCode: `$count = Model::where('status', 'active')->count();`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_EXISTS_VS_COUNT',
        name: 'Existence Check Using count() > 0',
        category: 'performance',
        severity: 'medium',
        description: 'Checking count() > 0 or count() === 0 scans matching rows instead of returning immediately on first match.',
        educational: '`count()` iterates over all matching index entries to find the exact total. `exists()` emits `SELECT 1 ... LIMIT 1` which halts scan immediately upon finding a single matching row.',
        suggestedFix: 'Replace `count() > 0` with `exists()` and `count() === 0` with `doesntExist()`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/->count\(\)\s*(>|>=|!=|!==)\s*0/i) || line.match(/count\(\s*\$[a-zA-Z0-9_]+\s*\)\s*>\s*0/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_EXISTS_VS_COUNT',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Use exists() instead of count() > 0',
                        message: 'Using `count() > 0` scans all matching rows to calculate total count when only existence is needed.',
                        whyItMatters: '`exists()` stops execution at the first matching row (`LIMIT 1`), providing faster response times.',
                        fix: 'Use `Model::where(...)->exists()` or `doesntExist()`.',
                        optimizedCode: `if (User::where('email', $email)->exists()) {\n    // Record exists\n}`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_FIRST_VS_GET_FIRST',
        name: 'Single Record Retrieval via get()->first()',
        category: 'performance',
        severity: 'low',
        description: 'Using Model::where(...)->get()->first() loads all matching rows before selecting the first element.',
        educational: '`get()->first()` fetches all records matching the `WHERE` clause without a `LIMIT 1` SQL constraint. Using `first()` sends `LIMIT 1` directly to the database.',
        suggestedFix: 'Replace `where(...)->get()->first()` with `where(...)->first()` or `firstOrFail()`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/->get\(\)->first\(\)/i) || line.match(/->get\(\)\[0\]/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_FIRST_VS_GET_FIRST',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Use first() instead of get()->first()',
                        message: 'Calling `get()->first()` retrieves all matching records from SQL instead of requesting a single row.',
                        whyItMatters: 'Applies SQL `LIMIT 1`, preventing retrieval of unnecessary data.',
                        fix: 'Use `first()` or `firstOrFail()` directly on the query.',
                        optimizedCode: `$user = User::where('email', $email)->first();`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_VALUE_VS_PLUCK_FIRST',
        name: 'Single Value Extraction via pluck()->first()',
        category: 'readability',
        severity: 'low',
        description: 'Using pluck(\'column\')->first() builds an intermediate collection before returning the single scalar value.',
        educational: '`pluck(\'column\')->first()` constructs a collection array before retrieving the element. `value(\'column\')` directly returns the single scalar value from the database.',
        suggestedFix: 'Replace `pluck(\'column\')->first()` with `value(\'column\')`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                const match = line.match(/->pluck\s*\(\s*['"]([^'"]+)['"]\s*\)->first\(\)/i);
                if (match) {
                    const col = match[1];
                    issues.push({
                        ruleId: 'ELOQUENT_VALUE_VS_PLUCK_FIRST',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Use value() for single column extraction',
                        message: `\`pluck('${col}')->first()\` creates an unnecessary collection before returning the value.`,
                        whyItMatters: `\`value('${col}')\` directly fetches and returns the scalar value.`,
                        fix: `Replace with \`value('${col}')\`.`,
                        optimizedCode: `$name = User::where('id', $id)->value('${col}');`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_MISSING_PAGINATION',
        name: 'Unbounded Query without Pagination',
        category: 'performance',
        severity: 'high',
        description: 'Querying data for UI tables or APIs with get() without limit or pagination.',
        educational: 'UI tables and API endpoints displaying data without pagination will become slower over time as database table sizes grow.',
        suggestedFix: 'Implement `paginate(15)`, `simplePaginate(15)`, or `cursorPaginate(15)`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/\$([a-zA-Z0-9_]+)\s*=\s*([A-Z]\w+|DB::table\(.*?\))->(where|orderBy).*->get\(\)/i)) {
                    if (!line.includes('paginate') && !line.includes('take') && !line.includes('limit')) {
                        issues.push({
                            ruleId: 'ELOQUENT_MISSING_PAGINATION',
                            line: idx + 1,
                            codeSnippet: line.trim(),
                            title: 'Missing Pagination on Query',
                            message: 'Fetching dataset with `get()` without pagination or limit on potentially growing tables.',
                            whyItMatters: 'Unbounded result sets cause UI degradation and potential OOM errors in production.',
                            fix: 'Use `paginate()`, `simplePaginate()`, or `cursorPaginate()`.',
                            optimizedCode: `$results = Model::where('status', 'active')->latest()->paginate(15);`
                        });
                    }
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_REPEATED_QUERIES',
        name: 'Repeated Identical Database Lookup',
        category: 'performance',
        severity: 'high',
        description: 'Executing duplicate Model::find() or Model::where() queries in the same scope or inside a loop.',
        educational: 'Calling the exact same database lookup multiple times within the same request lifecycle causes unnecessary network roundtrips.',
        suggestedFix: 'Assign the query result to a local variable or wrap in `Cache::remember()`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            const findMatches = {};
            lines.forEach((line, idx) => {
                const match = line.match(/([A-Z]\w+::find\(\s*\$[a-zA-Z0-9_]+\s*\))/i);
                if (match) {
                    const stmt = match[1];
                    if (findMatches[stmt]) {
                        issues.push({
                            ruleId: 'ELOQUENT_REPEATED_QUERIES',
                            line: idx + 1,
                            codeSnippet: line.trim(),
                            title: 'Duplicate Query Execution Detected',
                            message: `\`${stmt}\` is called multiple times in this context.`,
                            whyItMatters: 'Repeated queries add latency. Store the result in a variable to reuse.',
                            fix: 'Store model instance in a variable: `$user = User::find($id);`',
                            optimizedCode: `$user = User::find($id);\n// Reuse $user instead of querying again`
                        });
                    } else {
                        findMatches[stmt] = idx + 1;
                    }
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_CHUNKING_OPPORTUNITY',
        name: 'Batch Processing without Chunking / Cursor',
        category: 'memory',
        severity: 'high',
        description: 'Iterating over large collections fetched via all() or get() during batch updates or data exports.',
        educational: 'Batch updates on thousands of records using `get()->each()` loads all model instances simultaneously. `chunk()` or `cursor()` streams records in small manageable batches.',
        suggestedFix: 'Use `Model::chunk(500, fn($rows) => ...)` or `Model::cursor()` to process records with minimal RAM.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/(all\(\)|get\(\))->each\s*\(/i) || line.match(/foreach\s*\(\s*([A-Z]\w+::get\(\)|[A-Z]\w+::all\(\))\s+as/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_CHUNKING_OPPORTUNITY',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Chunking / Cursor Recommended for Large Datasets',
                        message: 'Iterating over `get()` hydra- hydrates all records at once into memory.',
                        whyItMatters: '`chunk()` processes data in s- slices; `cursor()` uses PHP generators to use O(1) memory.',
                        fix: 'Use `Model::chunk(500, function($models) { ... })` or `Model::cursor()`.',
                        optimizedCode: `User::where('status', 'pending')->chunk(200, function ($users) {\n    foreach ($users as $user) {\n        $user->update(['status' => 'processed']);\n    }\n});`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_PHP_AGGREGATION',
        name: 'Calculating Aggregations in PHP Loops',
        category: 'performance',
        severity: 'high',
        description: 'Computing sums, counts, or averages by loading collections into PHP memory instead of SQL aggregation.',
        educational: 'Database engines are heavily optimized for aggregate functions (SUM, AVG, MIN, MAX). Computing aggregates in PHP forces data transmission over the wire for every row.',
        suggestedFix: 'Perform aggregations in SQL: `Model::sum(\'total\')`, `Model::avg(\'amount\')`, or `groupBy()`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/->get\(\)->sum\s*\(/i) || line.match(/->get\(\)->avg\s*\(/i) || line.match(/\$total\s*\+=\s*\$[a-zA-Z0-9_]+->[a-zA-Z0-9_]+/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_PHP_AGGREGATION',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'PHP In-Memory Aggregation Detected',
                        message: 'Calculating aggregations in PHP requires transferring all table rows over the network.',
                        whyItMatters: 'SQL native `sum()` and `avg()` run in the database engine using indexes.',
                        fix: 'Use `Model::where(...)->sum(\'amount\')` or `selectRaw(\'SUM(amount) as total\')`.',
                        optimizedCode: `$totalAmount = Order::where('status', 'completed')->sum('total_amount');`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_MISSING_INDEX_WHERE',
        name: 'Potential Missing Database Index Opportunity',
        category: 'database',
        severity: 'medium',
        description: 'Filtering or sorting by columns without ensuring corresponding database indexes exist in migrations.',
        educational: 'Queries filtering by columns like `status`, `user_id`, or `created_at` perform full table scans if unindexed. Composite indexes (e.g. `[status, created_at]`) accelerate queries with multiple filters.',
        suggestedFix: 'Ensure target columns are indexed in your Laravel migration: `$table->index([\'status\', \'created_at\']);`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                const whereMatches = line.match(/where\s*\(\s*['"]([^'"]+)['"]/gi);
                const orderMatch = line.match(/orderBy\s*\(\s*['"]([^'"]+)['"]/i);
                if (whereMatches && whereMatches.length >= 2) {
                    const cols = whereMatches.map(m => m.replace(/where\s*\(\s*['"]/i, '').replace(/['"]/g, ''));
                    issues.push({
                        ruleId: 'ELOQUENT_MISSING_INDEX_WHERE',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'Composite Index Opportunity Suggested',
                        message: `Filtering on multiple columns \`[${cols.join(', ')}]\` may require a composite database index.`,
                        whyItMatters: 'A composite index allows MySQL/PostgreSQL to evaluate both filters in a single index lookup.',
                        fix: `Add migration index: \`$table->index(['${cols.join("', '")}']);\``,
                        optimizedCode: `// In your Laravel Migration file:\nSchema::table('your_table', function (Blueprint $table) {\n    $table->index(['${cols.join("', '")}']);\n});`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_RAW_SQL_INJECTION',
        name: 'SQL Injection Vulnerability in Raw Expression',
        category: 'security',
        severity: 'critical',
        description: 'Concatenating PHP variables into DB::raw() or whereRaw() strings enables SQL injection attacks.',
        educational: 'Direct string concatenation in raw SQL statements allows attacker-controlled input to alter query logic, potentially leaking or destroying data.',
        suggestedFix: 'Pass parameters as binding arrays: `whereRaw(\'status = ?\', [$status])`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/whereRaw\s*\(\s*["'].*?\$[a-zA-Z0-9_]+/i) || line.match(/DB::raw\s*\(\s*["'].*?\.\s*\$[a-zA-Z0-9_]+/i)) {
                    issues.push({
                        ruleId: 'ELOQUENT_RAW_SQL_INJECTION',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'SQL Injection Risk in Raw Expression',
                        message: 'PHP variables concatenated inside raw SQL strings bypass prepared statement protection.',
                        whyItMatters: 'Exposes your application to SQL injection vulnerabilities.',
                        fix: 'Use parameterized array bindings: `whereRaw(\'column = ?\', [$var])`.',
                        optimizedCode: `// Before: ->whereRaw("status = '$status'")\n// After:\n->whereRaw('status = ?', [$status])`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'ELOQUENT_QUERY_CACHE_OPPORTUNITY',
        name: 'Static / Reference Data Caching Opportunity',
        category: 'best_practices',
        severity: 'info',
        description: 'Querying static tables (Settings, Categories, Roles) repeatedly on every HTTP request.',
        educational: 'Reference tables change infrequently. Querying them on every web request adds unnecessary database latency.',
        suggestedFix: 'Wrap query in `Cache::remember(\'key\', 86400, fn() => Category::all())`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mysql', 'mariadb', 'postgresql', 'sqlite', 'sqlsrv', 'mongodb'],
        detect: function (code, lines) {
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.match(/(Setting|Category|Role|Configuration|Permission)::(all|get)\(\)/i) && !code.includes('Cache::')) {
                    const match = line.match(/(Setting|Category|Role|Configuration|Permission)/i);
                    const model = match ? match[1] : 'Model';
                    issues.push({
                        ruleId: 'ELOQUENT_QUERY_CACHE_OPPORTUNITY',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: `Cache Recommendation for ${model}`,
                        message: `\`${model}\` appears to be reference data. Consider caching the result.`,
                        whyItMatters: 'Reduces database load for rarely changing configuration or metadata.',
                        fix: `Use \`Cache::remember()\`: \`Cache::remember('${model.toLowerCase()}s', 86400, fn() => ${model}::all())\``,
                        optimizedCode: `$${model.toLowerCase()}s = Cache::remember('${model.toLowerCase()}s_cache', 86400, function () {\n    return ${model}::all();\n});`
                    });
                }
            });
            return issues;
        }
    },

    // MongoDB Specific Rules
    {
        id: 'MONGODB_MISSING_PROJECTION',
        name: 'Missing MongoDB Field Projection',
        category: 'database',
        severity: 'medium',
        description: 'Querying MongoDB collections without specifying project() or select() returns massive BSON documents.',
        educational: 'MongoDB documents can contain deeply nested arrays and sub-documents. Retrieving full BSON documents consumes high bandwidth and memory.',
        suggestedFix: 'Use `project([\'name\' => 1, \'email\' => 1])` or `select([\'name\', \'email\'])`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mongodb'],
        detect: function (code, lines, dbEngine) {
            if (dbEngine !== 'mongodb') return [];
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.includes('::where') && !line.includes('project') && !line.includes('select')) {
                    issues.push({
                        ruleId: 'MONGODB_MISSING_PROJECTION',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'MongoDB Projection Recommended',
                        message: 'Querying MongoDB without `project()` returns entire BSON documents.',
                        whyItMatters: 'Selecting only needed fields reduces network transfer and Mongo driver deserialization overhead.',
                        fix: 'Use `project([\'field\' => 1])`.',
                        optimizedCode: `$users = User::where('status', 'active')->project(['name' => 1, 'email' => 1])->get();`
                    });
                }
            });
            return issues;
        }
    },

    {
        id: 'MONGODB_JS_INJECTION',
        name: 'MongoDB Server-Side JavaScript Injection ($where)',
        category: 'security',
        severity: 'critical',
        description: 'Using raw $where JavaScript expressions in MongoDB queries enables server-side JavaScript injection.',
        educational: 'MongoDB `$where` executes arbitrary JavaScript on the database server. Unsanitized inputs permit remote code execution inside the Mongo daemon.',
        suggestedFix: 'Use native BSON query operators instead of `$where`.',
        versions: ['v8', 'v9', 'v10', 'v11', 'v12'],
        databases: ['mongodb'],
        detect: function (code, lines, dbEngine) {
            if (dbEngine !== 'mongodb') return [];
            const issues = [];
            lines.forEach((line, idx) => {
                if (line.includes('$where') || line.match(/whereRaw\s*\(\s*['"]\$where['"]/i)) {
                    issues.push({
                        ruleId: 'MONGODB_JS_INJECTION',
                        line: idx + 1,
                        codeSnippet: line.trim(),
                        title: 'MongoDB $where Security Risk',
                        message: 'Using `$where` executes raw JavaScript on the MongoDB server.',
                        whyItMatters: 'Can lead to Denial of Service or server-side JavaScript code injection.',
                        fix: 'Replace `$where` with standard BSON operators: `where(\'status\', $status)`.',
                        optimizedCode: `// Replace $where with native query criteria:\n$users = User::where('age', '>', 21)->get();`
                    });
                }
            });
            return issues;
        }
    }
];

function getLineNumber(code, index) {
    return code.substring(0, index).split('\n').length;
}
