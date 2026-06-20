/* Shop listing — AJAX filters, sort, and pagination (no full page reload).
   Swaps #shop-results in place, updates the URL via history.pushState, and
   shows a loading state on the grid while the request is in flight.
   Backend filtering logic is untouched — this only changes how the request
   is sent and how the response is applied to the DOM. */
(function () {
    var form = document.getElementById('shop-filter-form');
    var resultsEl = document.getElementById('shop-results');
    if (!form || !resultsEl) return;

    function setLoading(on) {
        var grid = resultsEl.querySelector('.gridLayout-wrapper');
        if (!grid) return;
        grid.style.opacity = on ? '0.5' : '';
        grid.style.pointerEvents = on ? 'none' : '';
    }

    function loadResults(url, push) {
        setLoading(true);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.text(); })
            .then(function (html) {
                var temp = document.createElement('div');
                temp.innerHTML = html;
                var fresh = temp.querySelector('#shop-results') || temp;
                resultsEl.replaceWith(fresh);
                resultsEl = fresh;
                if (push) {
                    history.pushState({ shopAjax: true }, '', url);
                }
                if (window.lazySizes) {
                    document.dispatchEvent(new Event('lazybeforeunveil'));
                }
            })
            .catch(function () {
                window.location.href = url;
            })
            .finally(function () {
                setLoading(false);
            });
    }

    function currentFormUrl() {
        var params = new URLSearchParams(new FormData(form));
        return form.getAttribute('action') + '?' + params.toString();
    }

    // Filter checkboxes / number inputs → submit on change
    form.addEventListener('change', function (e) {
        if (e.target.matches('input[type="checkbox"], input[type="number"]')) {
            loadResults(currentFormUrl(), true);
        }
    });

    // "Apply Filters" submit button / Enter key in a number field
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        loadResults(currentFormUrl(), true);
    });

    // "Reset Filters" link
    form.addEventListener('click', function (e) {
        var resetLink = e.target.closest('#reset-filter');
        if (!resetLink) return;
        e.preventDefault();
        // form.reset() would only restore the checked/value attributes baked in at
        // initial page load (which may already reflect active filters) — clear explicitly.
        form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
        form.querySelectorAll('input[type="number"]').forEach(function (inp) { inp.value = ''; });
        loadResults(form.getAttribute('action'), true);
    });

    // Sort dropdown links + pagination links — both live inside #shop-results,
    // which gets replaced on every AJAX swap, so delegate from a stable ancestor.
    document.addEventListener('click', function (e) {
        var link = e.target.closest('.ajax-sort-link, .ajax-page-link');
        if (!link || !document.body.contains(resultsEl) || !resultsEl.contains(link)) return;
        e.preventDefault();
        loadResults(link.getAttribute('href'), true);
    });

    // Back/forward browser buttons
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.shopAjax) {
            loadResults(window.location.href, false);
        }
    });
})();
