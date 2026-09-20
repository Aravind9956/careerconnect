/**
 * CareerConnect - AJAX Live Job Search & Filter System
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('jobSearchKeyword');
    const categorySelect = document.getElementById('jobCategoryFilter');
    const typeSelect = document.getElementById('jobTypeFilter');
    const expSelect = document.getElementById('jobExpFilter');
    const jobsContainer = document.getElementById('jobsListContainer');
    const paginationContainer = document.getElementById('jobsPaginationContainer');
    const resultsCountEl = document.getElementById('jobsCountText');

    if (!jobsContainer) return; // Only run on pages with job catalog

    let debounceTimer;

    function fetchJobs(page = 1) {
        const keyword = searchInput ? searchInput.value.trim() : '';
        const category = categorySelect ? categorySelect.value : '';
        const type = typeSelect ? typeSelect.value : '';
        const exp = expSelect ? expSelect.value : '';

        // Show loading state
        jobsContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading jobs...</span>
                </div>
                <p class="text-muted mt-2">Searching live opportunities...</p>
            </div>
        `;

        const queryParams = new URLSearchParams({
            q: keyword,
            category: category,
            type: type,
            experience: exp,
            page: page
        });

        fetch(`ajax/filter-jobs.php?${queryParams.toString()}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    jobsContainer.innerHTML = data.html;
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                    if (resultsCountEl) resultsCountEl.innerText = `${data.total} Jobs Found`;
                } else {
                    jobsContainer.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(() => {
                jobsContainer.innerHTML = `<div class="alert alert-warning">Unable to load jobs. Please try again.</div>`;
            });
    }

    // Debounced Real-Time Search Event Listener
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchJobs(1), 350);
        });
    }

    // Filter Select Event Listeners
    [categorySelect, typeSelect, expSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', () => fetchJobs(1));
        }
    });

    // Delegate Pagination Link Clicks
    if (paginationContainer) {
        paginationContainer.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' && e.target.dataset.page) {
                e.preventDefault();
                fetchJobs(e.target.dataset.page);
            }
        });
    }
});
