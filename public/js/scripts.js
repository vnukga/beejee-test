$(document).ready(validateLoginForm());

function validateLoginForm() {
    let form = $('#login-form');
    form.submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '/login',
            data: $(this).serialize(),
            success: function(response)
            {
                let success = response.success;
                if (success === undefined && response.errors === true) {
                    showValidationErrors(form, response);
                }
                if (success === true)
                    redirectToIndex();
            }
        })
    });
}


function showValidationErrors(form, response) {
    form.find('input').each(function (index, el) {
        $(this).removeClass('is-invalid');
        $(this).parent().children('.invalid-feedback').remove();
    });
    let errors = response.data;
    for (let key in errors) {
        let input = $('#'+key);
        input.addClass('is-invalid');
        let errorDiv = '<div class="invalid-feedback">' + errors[key] + '</div>';
        input.parent().append(errorDiv);
    }
}

function redirectToIndex() {
    $(location).attr('href','/');
}