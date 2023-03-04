$(document).ready(function() {
    $('#search-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(data) {
                $('#search-results').html(data);
            },
        });
    });
});