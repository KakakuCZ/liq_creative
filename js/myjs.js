
function recalculatePrices()
{
    var widthInput = $('#width');
    var lengthInput = $('#length');
    var baseMediaInput = $('#basemedia');
    var printMediaInput = $('#printmedia');
    var finishingInput = $('#finishing');
    var shippingInput = $('#shipping');

    var inputs = {
        'width': $(widthInput).val(),
        'length': $(lengthInput).val(),
        'baseMedia': $(baseMediaInput).val(),
        'printMedia': $(printMediaInput).val(),
        'finishing': $(finishingInput).val(),
        'shipping': $(shippingInput).val()
    };
    console.log(inputs);
    $.ajax({
        method: "GET",
        url: "./recalculatePrice.php",
        data: { "inputs": JSON.stringify(inputs)}
    })
        .done(function( msg ) {
            console.log('ajax success');
        });
}

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

    $('.product-select').change(function(){
        recalculatePrices();
    });
});