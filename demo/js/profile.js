$(document).ready(function () {
    const sessionToken = localStorage.getItem('sessionToken');

    if (!sessionToken) {
        window.location.href = 'login.html';
        return;
    }

    $.ajax({
        url: '/demo/php/profile.php',
        type: 'GET',
        data: {
            sessionToken: sessionToken,
        },
        success: function (response) {
            try {
                const result = JSON.parse(response);

                if (result.success) {
                    const user = result.data;

                    $('#name').val(user.name);
                    $('#dob').val(user.dob);
                    $('#phone').val(user.phone);
                    $('#gender').val(user.gender);
                } else {
                    alert(result.message);
                    window.location.href = 'login.html';
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                alert('Failed to load profile data.');
            }
        },
        error: function () {
            alert('An error occurred while fetching your profile.');
        },
    });

    $('#profileForm').submit(function (e) {
        e.preventDefault();

        const name = $('#name').val();
        const dob = $('#dob').val();
        const phone = $('#phone').val();
        const gender = $('#gender').val();

        $.ajax({
            url: '/demo/php/profile.php',
            type: 'POST',
            data: {
                sessionToken: sessionToken,
                name: name,
                dob: dob,
                phone: phone,
                gender: gender,
            },
            success: function (response) {
                try {
                    const result = JSON.parse(response);
                    alert(result.message);
                } catch (error) {
                    console.error('Error parsing response:', error);
                    alert('Failed to update profile.');
                }
            },
            error: function () {
                alert('An error occurred while updating your profile.');
            },
        });
    });

    $('#logoutBtn').click(function () {
        localStorage.removeItem('sessionToken');

        window.location.href = 'login.html';
    });
});
