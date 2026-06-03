/**
 * ToothCare Global App
 * Used for shared functions and configs across pages
 */

window.App = (function () {

    const config = {
        baseUrl: "/toothcare/public/",
        baseApi: "/toothcare/api/",
        version: "1.0.0"
    };

    function init() {
        console.log("App initialized v" + config.version);
    }

    function endpoint(url) {
        return config.baseUrl + url;
    }

    function api(url) {
        return config.baseApi + url;
    }

    return {
        init,
        endpoint,
        api,
        config
    };

})();

// Auto-init on page load
document.addEventListener("DOMContentLoaded", App.init);