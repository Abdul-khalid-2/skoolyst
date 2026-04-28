/**
 * Tracks active visible time on a video page. The server stores aggregate time in minutes.
 */
(function () {
    'use strict';

    if (window.__skoolystVideoWatchTracker) {
        return;
    }
    window.__skoolystVideoWatchTracker = true;

    function getConfig() {
        var el = document.getElementById('video-watch-config');
        if (!el) {
            return null;
        }

        var endpoint = el.getAttribute('data-endpoint');
        if (!endpoint) {
            return null;
        }

        return {
            endpoint: endpoint,
            token: el.getAttribute('data-csrf') || '',
        };
    }

    function updateDisplay(formattedTime) {
        if (!formattedTime) {
            return;
        }

        var displays = document.querySelectorAll('[data-video-watch-time-display]');
        displays.forEach(function (display) {
            display.textContent = formattedTime;
        });

        var wrappers = document.querySelectorAll('[data-video-watch-time-wrapper]');
        wrappers.forEach(function (wrapper) {
            wrapper.classList.remove('d-none');
        });
    }

    function sendSeconds(cfg, seconds, useBeacon) {
        if (seconds < 1) {
            return;
        }

        var cap = Math.min(seconds, 120);

        if (useBeacon && typeof navigator.sendBeacon === 'function') {
            var fd = new FormData();
            fd.append('_token', cfg.token);
            fd.append('seconds', String(cap));
            navigator.sendBeacon(cfg.endpoint, fd);
            return;
        }

        fetch(cfg.endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': cfg.token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: (function () {
                var body = new FormData();
                body.append('_token', cfg.token);
                body.append('seconds', String(cap));
                return body;
            })(),
        })
            .then(function (response) {
                if (!response.ok) {
                    return null;
                }
                return response.json();
            })
            .then(function (data) {
                if (data && data.formatted_tracked_watch_time) {
                    updateDisplay(data.formatted_tracked_watch_time);
                }
            })
            .catch(function () { /* ignore tracking errors */ });
    }

    function start() {
        var cfg = getConfig();
        if (!cfg) {
            return;
        }

        var pending = 0;
        var intervalSeconds = 5;

        setInterval(function () {
            if (document.hidden) {
                return;
            }

            pending += 1;
        }, 1000);

        function flush(useBeacon) {
            while (pending >= intervalSeconds) {
                pending -= intervalSeconds;
                sendSeconds(cfg, intervalSeconds, useBeacon);
            }
        }

        setInterval(function () {
            flush(false);
        }, intervalSeconds * 1000);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                flush(false);
            }
        });

        window.addEventListener('pagehide', function () {
            flush(true);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
