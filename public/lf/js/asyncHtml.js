window.addEventListener("DOMContentLoaded", function () {

    function parseHTML(htmlString) {
        var range = document.createRange();
        var fragment = range.createContextualFragment(htmlString);
        return fragment.firstChild;
    }

    function buildQueryString(params) {
        return Object.keys(params)
            .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
            .join('&');
    }

    window.asyncHtml = function ($async, params) {
        if ($async.dataset['async_html']) {

            const componentId = $async.dataset['async_html'];

            const initParams = {
                LF_ASYNCHTML: componentId
            };
    
            const queryString = buildQueryString({...initParams, ...params});
    
            const headers = new Headers();
            headers.append('LF_TOKEN', window.lf_token);
    
            const url = window.location.href + '?' + queryString;
    
            fetch(url, { headers })
                .then(response => {
                    if (response.headers.has('LF_TOKEN')) {
                        window.lf_token = response.headers.get('LF_TOKEN');
                    }
                    
                    return response.text();
                })
                .then(responseText => {
                    $new = parseHTML(responseText);
                    $new.dataset['async_html'] = componentId;
                    $async.replaceWith($new);
                })
                .catch(error => console.error(error));
        }
    }

});