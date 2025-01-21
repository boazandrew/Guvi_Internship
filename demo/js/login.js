$(document).ready(function () {
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: '/demo/php/login.php',
            type: 'POST',
            data: {
                email: email,
                password: password,
            },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    localStorage.setItem('sessionToken', result.sessionToken);
                    window.location.href = 'profile.html';
                } else {
                    alert(result.message);
                }
            },
            error: function () {
                alert('An error occurred while processing your request.');
            },
        });
    });

    $('#forgotPasswordLink').click(function (e) {
        e.preventDefault();
        window.location.href = 'forgot_password.html';
    });
});
