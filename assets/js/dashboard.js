/**
 * CareerConnect - User & Recruiter Dashboard Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // Quick Save Job AJAX Trigger
    document.querySelectorAll('.btn-save-job').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const jobId = this.getAttribute('data-job-id');
            if (!jobId) return;

            fetch('ajax/save-job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `job_id=${encodeURIComponent(jobId)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    this.classList.toggle('btn-danger');
                    this.classList.toggle('btn-outline-danger');
                } else {
                    showToast('danger', data.message);
                }
            })
            .catch(() => {
                showToast('danger', 'Error updating saved job.');
            });
        });
    });
});
