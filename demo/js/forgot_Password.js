$(document).ready(function () {
    $('#forgotPasswordForm').submit(function (e) {
        e.preventDefault();

        const email = $('#email').val();

        $.ajax({
            url: '/demo/php/forgot_password.php',
            type: 'POST',
            data: {
                email: email,
            },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#emailError').hide();
                    $('#resetPasswordSection').show();
                    $('#resetPasswordSection form').submit(function (e) {
                        e.preventDefault();

                        const newPassword = $('#newPassword').val();
                        const confirmPassword = $('#confirmPassword').val();
                        const resetToken = result.resetToken;

                        if (newPassword !== confirmPassword) {
                            alert('Passwords do not match!');
                            return;
                        }

                        $.ajax({
                            url: 'forgot_password.php',
                            type: 'POST',
                            data: {
                                reset_token: resetToken,
                                new_password: newPassword,
                            },
                            success: function (response) {
                                const result = JSON.parse(response);
                                alert(result.message);
                                window.location.href = 'login.html';
                            },
                            error: function () {
                                alert('An error occurred while resetting your password.');
                            },
                        });
                    });
                } else {
                    $('#emailError').text(result.message).show();
                }
            },
            error: function () {
                alert('An error occurred while sending the reset email.');
            },
        });
    });
});
