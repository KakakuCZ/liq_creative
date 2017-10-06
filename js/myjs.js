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

function enableDisble(val) {
    $("#finishing > option").each(function () {
        if (this.value === val || this.value === "0") {
            //$("#finishing-optional option[value=" + this.value + "]").removeAttr('disabled');
            $("#finishing-optional option[value=" + this.value + "]").show();
        } else {
            //$("#finishing-optional option[value=" + this.value + "]").attr('disabled', 'disabled');
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
                enableDisble("20");
                break;
            case "20":
                enableDisble("5");
                break;
            case "21":
                enableDisble("22");
                break;
            case "22":
                enableDisble("21");
                break;
        }
    } else {
        divFinishing.slideUp();
    }
    $("#finishing-optional").val("0");
}

$(document).ready(function () {
    $("#div-finishing-optional").hide();
    $("#finishing").change(function () {
        checkOption();
    });

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

    $('.product-select').change(function () {
        recalculatePrices();
    });
});