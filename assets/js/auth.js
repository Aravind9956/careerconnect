/**
 * CareerConnect - Authentication & Form Validation JS
 */

document.addEventListener('DOMContentLoaded', () => {
    // Password Toggle Visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }
            }
        });
    });

    // Real-Time Email Uniqueness AJAX Check
    const emailInput = document.getElementById('reg_email');
    const emailFeedback = document.getElementById('emailFeedback');
    if (emailInput && emailFeedback) {
        let debounceTimer;
        emailInput.addEventListener('keyup', () => {
            clearTimeout(debounceTimer);
            const email = emailInput.value.trim();
            if (email.length < 5 || !email.includes('@')) {
                emailFeedback.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`ajax/check-email.php?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            emailInput.classList.add('is-invalid');
                            emailInput.classList.remove('is-valid');
                            emailFeedback.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle me-1"></i>Email is already registered!</span>';
                        } else {
                            emailInput.classList.remove('is-invalid');
                            emailInput.classList.add('is-valid');
                            emailFeedback.innerHTML = '<span class="text-success small"><i class="bi bi-check-circle me-1"></i>Email is available!</span>';
                        }
                    })
                    .catch(() => {
                        emailFeedback.innerHTML = '';
                    });
            }, 400);
        });
    }

    // Password Match Validation
    const passwordInput = document.getElementById('reg_password');
    const confirmInput = document.getElementById('reg_confirm_password');
    const confirmFeedback = document.getElementById('confirmFeedback');

    if (passwordInput && confirmInput && confirmFeedback) {
        const validateMatch = () => {
            if (!confirmInput.value) {
                confirmFeedback.innerHTML = '';
                confirmInput.classList.remove('is-invalid', 'is-valid');
                return;
            }

            if (passwordInput.value !== confirmInput.value) {
                confirmInput.classList.add('is-invalid');
                confirmInput.classList.remove('is-valid');
                confirmFeedback.innerHTML = '<span class="text-danger small">Passwords do not match!</span>';
            } else {
                confirmInput.classList.remove('is-invalid');
                confirmInput.classList.add('is-valid');
                confirmFeedback.innerHTML = '<span class="text-success small">Passwords match.</span>';
            }
        };

        confirmInput.addEventListener('keyup', validateMatch);
        passwordInput.addEventListener('keyup', validateMatch);
    }
});
