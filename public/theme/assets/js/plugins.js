// Function to dynamically load a script
function loadScript(src, callback) {
    const script = document.createElement('script');
    script.src = src;
    script.type = 'text/javascript';
    script.onload = callback || function() {};
    document.head.appendChild(script);
}

// Check for specific elements and load corresponding scripts
if (document.querySelector("[toast-list]") || document.querySelector("[data-choices]") || document.querySelector("[data-provider]")) {
    loadScript('https://cdn.jsdelivr.net/npm/toastify-js');
    loadScript('theme/assets/libs/choices.js/public/assets/scripts/choices.min.js');
    loadScript('theme/assets/libs/flatpickr/flatpickr.min.js');
}
