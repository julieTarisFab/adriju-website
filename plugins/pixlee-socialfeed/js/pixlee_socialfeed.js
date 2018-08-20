function socialFeedWindow(type) {
    var width = 1000;
    var height = 775;

    var loginLink = type === 'generate' ? 'https://socialfeed.pixlee.com/signup' :
        'https://socialfeed.pixlee.com/settings';

    // Fixes dual-screen position                       Most browsers      Firefox
    var dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop !== undefined ? window.screenTop : screen.top;

    //create a new window to do the login
    var left = ((screen.width / 2) - (width / 2)) + dualScreenLeft;
    var top = ((screen.height / 2) - (height / 2)) + dualScreenTop;
    var newWindow = window.open(loginLink, 'wp_socialfeed', 'scrollbars=yes, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);
}

function pixleeReceiveMessage(event) {
    var message;
    //only listen to events coming from Pixlee
    if (event.data && (event.origin === 'https://socialfeed.pixlee.com' || event.origin === 'http://instafeed.dev')) {
        try {
            message = JSON.parse(event.data);
        } catch (error) {
            return;
        }
    } else {
        return;
    }

    if (message && message.widget_id && message.api_key) {
        document.getElementById('widget_id').value = message.widget_id;
        document.getElementById('api_key').value = message.api_key;
        document.getElementById('options_form').submit();
    }
}

window.addEventListener('message', pixleeReceiveMessage, false);