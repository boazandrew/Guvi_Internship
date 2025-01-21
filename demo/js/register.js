$(document).ready(function () {
    $('#registerForm').submit(function (e) {
        e.preventDefault();

        const name = $('#name').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }

        $.ajax({
            url: '/demo/php/register.php',
            type: 'POST',
            data: {
                name: name,
                email: email,
                password: password,
                confirm_password: confirmPassword,
            },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(result.message);
                    window.location.href = 'login.html';
                } else {
                    alert(result.message);
                }
            },
            error: function () {
                alert('An error occurred while processing your request.');
            },
        });
    });
});
