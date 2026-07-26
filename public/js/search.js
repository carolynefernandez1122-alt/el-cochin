document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const resultsContainer = document.getElementById('resultadosProductos');
    // Si no estamos en la página de resultados, no hay nada que filtrar en vivo:
    // dejamos que el form se comporte de forma normal (submit -> navega).
    if (!resultsContainer) {
        return;
    }

    let debounceTimer;
    let abortController;

    function fetchResultados(query) {
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        const url = new URL(searchForm.action, window.location.origin);
        url.searchParams.set('q', query);
        url.searchParams.set('context', resultsContainer.dataset.context || 'catalogo');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: abortController.signal
        })
            .then(response => response.text())
            .then(html => {
                resultsContainer.innerHTML = html;
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error al buscar productos:', error);
                }
            });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = searchInput.value.trim();

        debounceTimer = setTimeout(() => {
            fetchResultados(query);
        }, 300);
    });

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        fetchResultados(searchInput.value.trim());
    });
});