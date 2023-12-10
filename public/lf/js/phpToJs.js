window.addEventListener("DOMContentLoaded", function () {

    const $phpToJs = document.querySelectorAll('.php-to-js');

    $phpToJs.forEach(function (div) {
        const dataset = Object.entries(div.dataset)[0]
        
        window[dataset[0]] = JSON.parse(dataset[1]);
    });

});