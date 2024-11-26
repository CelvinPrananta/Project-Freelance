let open = false;
let threshold = 160;

const devtools = {
    isOpen: false,
    orientation: undefined,
};

setInterval(() => {
    const widthThreshold = window.outerWidth - window.innerWidth > threshold;
    const heightThreshold = window.outerHeight - window.innerHeight > threshold;
    const orientation = widthThreshold ? 'vertical' : 'horizontal';

    if (!(heightThreshold && widthThreshold) &&
        ((window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized) || widthThreshold || heightThreshold)) {
        if (!open || devtools.orientation !== orientation) {
            devtools.isOpen = true;
            devtools.orientation = orientation;
            open = true;

            // Redirect ke halaman yang lain
            window.location.href = "about:blank";
        }
    } else {
        devtools.isOpen = false;
        open = false;
    }
}, 500);