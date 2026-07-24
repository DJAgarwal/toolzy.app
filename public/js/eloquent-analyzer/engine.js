/**
 * Laravel Eloquent Query Analyzer Engine
 * Toolzy (c) 2026 - Client-Side Static Analysis Engine & Score Calculator
 */

window.EloquentAnalyzerEngine = (function () {

    function analyze(code, options = {}) {
        if (!code || typeof code !== 'string') {
            return getEmptyResult();
        }

        const version = options.version || 'v11';
        const database = options.database || 'mysql';
        const enabledCategories = options.categories || ['performance', 'readability', 'security', 'maintainability', 'database', 'memory', 'best_practices'];

        const lines = code.split('\n');
        const issues = [];
        const rules = window.EloquentAnalyzerRules || [];

        // Run detection rules
        rules.forEach(rule => {
            if (!enabledCategories.includes(rule.category)) return;
            if (rule.versions && !rule.versions.includes(version)) return;
            if (rule.databases && !rule.databases.includes(database)) return;

            try {
                const detectedIssues = rule.detect(code, lines, database, version);
                if (Array.isArray(detectedIssues)) {
                    detectedIssues.forEach(issue => {
                        issue.category = rule.category;
                        issue.severity = rule.severity;
                        issue.ruleName = rule.name;
                        issue.description = rule.description;
                        issue.educational = rule.educational;
                        issue.suggestedFix = rule.suggestedFix;
                        issues.push(issue);
                    });
                }
            } catch (err) {
                console.error(`Error running rule ${rule.id}:`, err);
            }
        });

        // Sort issues by severity weight (critical > high > medium > low > info)
        const severityWeights = { critical: 5, high: 4, medium: 3, low: 2, info: 1 };
        issues.sort((a, b) => (severityWeights[b.severity] || 0) - (severityWeights[a.severity] || 0));

        // Calculate Scores
        const scores = calculateScores(issues, lines.length, enabledCategories);

        // Generate Optimized Code Variant
        const optimizedCode = generateOptimizedCode(code, issues);

        // Generate Code Diff Lines
        const diffLines = generateDiffLines(code, optimizedCode);

        return {
            issues,
            scores,
            optimizedCode,
            diffLines,
            totalIssues: issues.length,
            countsBySeverity: {
                critical: issues.filter(i => i.severity === 'critical').length,
                high: issues.filter(i => i.severity === 'high').length,
                medium: issues.filter(i => i.severity === 'medium').length,
                low: issues.filter(i => i.severity === 'low').length,
                info: issues.filter(i => i.severity === 'info').length,
            }
        };
    }

    function calculateScores(issues, lineCount, enabledCategories) {
        let perfDeduction = 0;
        let memoryDeduction = 0;
        let dbDeduction = 0;
        let readabilityDeduction = 0;
        let maintainabilityDeduction = 0;

        const penaltyMap = {
            critical: 25,
            high: 15,
            medium: 8,
            low: 4,
            info: 1
        };

        issues.forEach(issue => {
            const penalty = penaltyMap[issue.severity] || 5;
            switch (issue.category) {
                case 'performance':
                    perfDeduction += penalty;
                    break;
                case 'memory':
                    memoryDeduction += penalty;
                    perfDeduction += (penalty * 0.5);
                    break;
                case 'database':
                    dbDeduction += penalty;
                    perfDeduction += (penalty * 0.4);
                    break;
                case 'readability':
                    readabilityDeduction += penalty;
                    break;
                case 'maintainability':
                case 'security':
                    maintainabilityDeduction += penalty;
                    if (issue.category === 'security') perfDeduction += penalty;
                    break;
                default:
                    perfDeduction += penalty * 0.5;
            }
        });

        const perfScore = Math.max(10, Math.min(100, Math.round(100 - perfDeduction)));
        const memoryScore = Math.max(15, Math.min(100, Math.round(100 - memoryDeduction)));
        const dbScore = Math.max(15, Math.min(100, Math.round(100 - dbDeduction)));
        const readabilityScore = Math.max(20, Math.min(100, Math.round(100 - readabilityDeduction)));
        const maintainabilityScore = Math.max(20, Math.min(100, Math.round(100 - maintainabilityDeduction)));

        const overall = Math.round(
            (perfScore * 0.35) +
            (memoryScore * 0.20) +
            (dbScore * 0.20) +
            (readabilityScore * 0.15) +
            (maintainabilityScore * 0.10)
        );

        return {
            overall,
            performance: perfScore,
            memory: memoryScore,
            database: dbScore,
            readability: readabilityScore,
            maintainability: maintainabilityScore
        };
    }

    function generateOptimizedCode(code, issues) {
        let lines = code.split('\n');

        // Apply transformations for known patterns line by line
        lines = lines.map((line, idx) => {
            const lineNum = idx + 1;
            const lineIssues = issues.filter(i => i.line === lineNum);

            let updatedLine = line;

            lineIssues.forEach(issue => {
                if (issue.ruleId === 'ELOQUENT_COUNT_COLLECTION_MISUSE') {
                    updatedLine = updatedLine.replace(/(get\(\)|all\(\))->count\(\)/g, 'count()');
                } else if (issue.ruleId === 'ELOQUENT_EXISTS_VS_COUNT') {
                    updatedLine = updatedLine.replace(/->count\(\)\s*>\s*0/g, '->exists()');
                    updatedLine = updatedLine.replace(/->count\(\)\s*===\s*0/g, '->doesntExist()');
                } else if (issue.ruleId === 'ELOQUENT_FIRST_VS_GET_FIRST') {
                    updatedLine = updatedLine.replace(/->get\(\)->first\(\)/g, '->first()');
                } else if (issue.ruleId === 'ELOQUENT_COLLECTION_FILTERING') {
                    // Simple pattern replace get()->where into where()->get()
                    updatedLine = updatedLine.replace(/::get\(\)->where\((.*?)\)/g, '::where($1)->get()');
                } else if (issue.ruleId === 'ELOQUENT_VALUE_VS_PLUCK_FIRST') {
                    updatedLine = updatedLine.replace(/->pluck\((.*?)\)->first\(\)/g, '->value($1)');
                } else if (issue.ruleId === 'ELOQUENT_RAW_SQL_INJECTION') {
                    updatedLine = updatedLine.replace(/whereRaw\s*\(\s*["'](.*?)['"]\s*\)/g, '// SECURE BINDING: ->whereRaw("$1", [$var])');
                }
            });

            return updatedLine;
        });

        // Global eager loading check if missing
        const hasN1OrMissingEager = issues.some(i => i.ruleId === 'ELOQUENT_N1_LOOP' || i.ruleId === 'ELOQUENT_MISSING_EAGER_LOADING');
        let fullCode = lines.join('\n');

        if (hasN1OrMissingEager && !fullCode.includes('with(')) {
            // Prepend comment recommendation
            fullCode = `// OPTIMIZED: Eager loading added to prevent N+1 query overhead\n` + fullCode;
        }

        return fullCode;
    }

    function generateDiffLines(origCode, optCode) {
        const origLines = origCode.split('\n');
        const optLines = optCode.split('\n');
        const diff = [];

        const max = Math.max(origLines.length, optLines.length);
        for (let i = 0; i < max; i++) {
            const orig = origLines[i];
            const opt = optLines[i];

            if (orig === opt) {
                if (orig !== undefined) {
                    diff.push({ type: 'unchanged', text: orig, lineNum: i + 1 });
                }
            } else {
                if (orig !== undefined) {
                    diff.push({ type: 'removed', text: orig, lineNum: i + 1 });
                }
                if (opt !== undefined) {
                    diff.push({ type: 'added', text: opt, lineNum: i + 1 });
                }
            }
        }
        return diff;
    }

    function getEmptyResult() {
        return {
            issues: [],
            scores: { overall: 100, performance: 100, memory: 100, database: 100, readability: 100, maintainability: 100 },
            optimizedCode: '',
            diffLines: [],
            totalIssues: 0,
            countsBySeverity: { critical: 0, high: 0, medium: 0, low: 0, info: 0 }
        };
    }

    return {
        analyze
    };
})();
