document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('togglePassword');
    if (!toggle) return;

    const password = document.getElementById('password');
    const icon = toggle.querySelector('i');

    toggle.addEventListener('click', function () {
        if (!password || !icon) return;

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
        } else {
            password.type = 'password';
            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');
        }
    });
});
