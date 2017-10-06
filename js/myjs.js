function makePrice(price) {
    return '£' + parseFloat(Math.round(price * 100) / 100).toFixed(2);
}

function recalculatePrices() {
    var widthInput = $('#width');
    var lengthInput = $('#length');
    var baseMediaInput = $('#basemedia');
    var printMediaInput = $('#printmedia');
    var finishingInput = $('#finishing');
    var finishingOptInput = $('#finishing-optional');
    var shippingInput = $('#shipping');

    var totalPrice = $('#total-price');
    var inkPrice = $('#ink');
    var labourPrice = $('#labour');
    var labourTime = $('#labour-time');

    var finishingTot = [
        $(finishingInput).val(),
        $(finishingOptInput).val()
    ];
    var inputs = {
        'width': $(widthInput).val(),
        'length': $(lengthInput).val(),
        'baseMedia': $(baseMediaInput).val(),
        'printMedia': $(printMediaInput).val(),
        'finishing': finishingTot,
        'shipping': $(shippingInput).val(),
    };
    $.ajax({
        dataType: "json",
        method: "GET",
        url: "./recalculatePrice.php",
        data: {"inputs": JSON.stringify(inputs)},
        success: function (data) {
            $(totalPrice).val(makePrice(data.totalPrice));
            $(inkPrice).val(makePrice(data.inkPrice));
            $(labourPrice).val(makePrice(data.labourPrice));
            $(labourTime).val((data.totalHours * 60) + ' minutes');
        }
    });
}

function enableDisbleSelOption(val) {
    $("#finishing > option").each(function () {
        if (this.value === val || this.value === "0") {
            $("#finishing-optional option[value=" + this.value + "]").show();
        } else {
            $("#finishing-optional option[value=" + this.value + "]").hide();
        }
    });
}

function checkOption() {
    var finishing = $("#finishing");
    var divFinishing = $("#div-finishing-optional");
    if (finishing.val() !== "0") {
        divFinishing.slideDown();
        switch (finishing.val()) {
            case "5":
                enableDisbleSelOption("20");
                break;
            case "20":
                enableDisbleSelOption("5");
                break;
            case "21":
                enableDisbleSelOption("22");
                break;
            case "22":
                enableDisbleSelOption("21");
                break;
        }
    } else {
        divFinishing.slideUp();
    }
    $("#finishing-optional").val("0");
}

/**
 * @param {HTML IDs} element
 * @param {Array of key codes} keyCodes
 * 
 * Some codes
 * 38: arrow up
 * 40: arrow down
 * 69: letter e
 */
function disableKeyDown(element, keyCodes) {
    $("form").on("keydown", element, function (e) {
        for (var i = 0; i < keyCodes.length; i++)
            if (e.which === keyCodes[i])
                e.preventDefault();
    });
}

$(document).ready(function () {
    $("#div-finishing-optional").hide();
    $("#finishing").change(function () {
        checkOption();
    });

    // Disable scroll when focused on the phone number input
    $("form").on("focus", "#phone", function (e) {
        $(this).on("wheel", function (e) {
            e.preventDefault();
        });
    });

    // Disable up, down and e keys
    disableKeyDown("#phone", [38, 40, 69]);
    disableKeyDown("#width", [69]);
    disableKeyDown("#length", [69]);

    $('.product-select').change(function () {
        recalculatePrices();
    });
});