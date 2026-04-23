/**
 * +1s per second while the tab is visible. Flush every 10s to the server. Stored in DB as total minutes.
 * Single setInterval(1000) avoids chained setTimeout under-count. Global guard stops double init.
 */
(function () {
    'use strict';

    if (window.__skoolystBlogReadTracker) {
        return;
    }
    window.__skoolystBlogReadTracker = true;

    function getConfig() {
        var el = document.getElementById('blog-reading-config');
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
                var b = new FormData();
                b.append('_token', cfg.token);
                b.append('seconds', String(cap));
                return b;
            })(),
        }).catch(function () { /* ignore */ });
    }

    function start() {
        var cfg = getConfig();
        if (!cfg) {
            return;
        }

        var pending = 0;

        setInterval(function () {
            if (document.hidden) {
                return;
            }
            pending += 1;
        }, 1000);

        var intervalMs = 10000;
        function runFlush() {
            while (pending > 0) {
                var chunk = Math.min(pending, 120);
                pending -= chunk;
                sendSeconds(cfg, chunk, false);
            }
        }
        setTimeout(runFlush, intervalMs);
        setInterval(runFlush, intervalMs);

        function flush(useBeacon) {
            while (pending > 0) {
                var chunk = Math.min(pending, 120);
                pending -= chunk;
                sendSeconds(cfg, chunk, useBeacon);
            }
        }

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
