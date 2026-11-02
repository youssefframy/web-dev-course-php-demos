document.addEventListener('DOMContentLoaded', function () {
    darkmode_init();
});

function darkmode_init() {
    let darkmodeSwitch = document.querySelector('.darkmode');

    let darkmodeCookie = {
        set: function (
            value,
            key = 'darkmode',
            time = 2628000000,
            path = '/',
            secure = false
        ) {
            let expires = new Date();
            expires.setTime(expires.getTime() + time);
            var path =
                typeof path !== 'undefined'
                    ? (pathValue = 'path=' + path + ';')
                    : '';
            var secure = secure ? ';secure' : '';
            document.cookie =
                key +
                '=' +
                value +
                ';' +
                path +
                'expires=' +
                expires.toUTCString() +
                secure;
        },
        get: function () {
            let keyValue = document.cookie.match(
                '(^|;) ?darkmode=([^;]*)(;|$)'
            );
            return keyValue ? keyValue[2] : null;
        },
        remove: function () {
            document.cookie = 'darkmode=; Max-Age=0; path=/';
        }
    };

    // Extra credit: on the first visit there is no cookie yet, so follow the
    // operating system's light or dark setting. The page was drawn before the
    // cookie existed, so reload it to let PHP add the dark theme.
    if (darkmodeCookie.get() === null) {
        let prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        darkmodeCookie.set(prefersDark ? 'true' : 'false');
        if (prefersDark) {
            location.reload();
        }
    }

    if (darkmodeCookie.get() === 'true') {
        document.querySelector('.darkmode').classList.add('active');
    }

    darkmodeSwitch.addEventListener('click', (event) => {
        event.preventDefault();
        event.target.classList.toggle('active');

        if (darkmodeCookie.get() === 'true') {
            darkmodeCookie.set('false');
        } else {
            darkmodeCookie.set('true');
        }
        location.reload();
    });
}
