$(document).ready(function () {
    // Disable scroll when focused on a number input.
    $("form").on("focus", "#phone", function (e) {
        $(this).on('wheel', function (e) {
            e.preventDefault();
        });
    });

    // Restore scroll on number inputs.
    $("form").on("blur", "#phone", function (e) {
        $(this).off('wheel');
    });

    // Disable up and down keys.
    $("form").on("keydown", "#phone", function (e) {
        if (e.which === 38 || e.which === 40)
            e.preventDefault();
    });
});