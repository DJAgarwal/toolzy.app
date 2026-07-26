/**
 * Environment Manager - Toolzy
 * Handles local variable interpolation {{var_name}} in request components.
 */

window.EnvManager = (function () {
    const STORAGE_KEY = 'toolzy_api_environments';
    const ACTIVE_ENV_KEY = 'toolzy_api_active_env';

    let environments = {
        'Default': {
            'base_url': 'https://jsonplaceholder.typicode.com',
            'api_key': 'demo_key_12345',
            'token': 'bearer_sample_token'
        }
    };
    let activeEnv = 'Default';

    function init() {
        try {
            const savedEnvs = localStorage.getItem(STORAGE_KEY);
            if (savedEnvs) environments = JSON.parse(savedEnvs);

            const savedActive = localStorage.getItem(ACTIVE_ENV_KEY);
            if (savedActive && environments[savedActive]) activeEnv = savedActive;
        } catch (e) {
            console.warn('LocalStorage unavailable for EnvManager:', e);
        }
    }

    function save() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(environments));
            localStorage.setItem(ACTIVE_ENV_KEY, activeEnv);
        } catch (e) {
            console.error('Failed to save environments:', e);
        }
    }

    function substitute(str) {
        if (!str || typeof str !== 'string') return str;
        const currentVars = environments[activeEnv] || {};
        return str.replace(/\{\{\s*([\w\-]+)\s*\}\}/g, (match, p1) => {
            return currentVars[p1] !== undefined ? currentVars[p1] : match;
        });
    }

    function getEnvironments() { return environments; }
    function getActiveEnv() { return activeEnv; }

    function setActiveEnv(envName) {
        if (environments[envName]) {
            activeEnv = envName;
            save();
        }
    }

    function setVariable(envName, key, val) {
        if (!environments[envName]) environments[envName] = {};
        environments[envName][key] = val;
        save();
    }

    function removeVariable(envName, key) {
        if (environments[envName]) {
            delete environments[envName][key];
            save();
        }
    }

    function addEnvironment(envName) {
        if (!environments[envName]) {
            environments[envName] = {};
            save();
        }
    }

    function deleteEnvironment(envName) {
        if (envName === 'Default') return;
        delete environments[envName];
        if (activeEnv === envName) activeEnv = 'Default';
        save();
    }

    init();

    return {
        substitute: substitute,
        getEnvironments: getEnvironments,
        getActiveEnv: getActiveEnv,
        setActiveEnv: setActiveEnv,
        setVariable: setVariable,
        removeVariable: removeVariable,
        addEnvironment: addEnvironment,
        deleteEnvironment: deleteEnvironment
    };
})();
