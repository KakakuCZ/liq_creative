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
        'shipping': $(shippingInput).val()
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

function checkOrderForm() {
    var inputs = [];
    var isOk = true;
    var borderOk = "1px solid rgba(0, 0, 0, 0.15)";
    var borderNotOk = "1px solid rgba(255, 0, 0, 0.75)";
    $("#new-order-form :input").not(":input[type=button], :input[type=submit], :input:disabled, #finishing-optional").each(function (i, e) {
        inputs.push($(e));
    });
    if (inputs[3].val() === "0" && inputs[4].val() === "0") {
        isOk = false;
        inputs[3].css("border", borderNotOk);
        inputs[4].css("border", borderNotOk);
    } else {
        isOk = true;
        inputs[3].css("border", borderOk);
        inputs[4].css("border", borderOk);
    }
    for (var i = 0; i < inputs.length; i++)
        if (i !== 3 && i !== 4)
            if (inputs[i].val() === "" || inputs[i].val() === "0") {
                isOk = false;
                inputs[i].css("border", borderNotOk);
            } else
                inputs[i].css("border", borderOk);
    return isOk;
}

function checkNewCustomerForm() {
    var inputs = [];
    var isOk = true;
    var borderOk = "1px solid rgba(0, 0, 0, 0.15)";
    var borderNotOk = "1px solid rgba(255, 0, 0, 0.75)";
    $("#new-customer-form :input").not(":input[type=button], :input[type=submit]").each(function (i, e) {
        inputs.push($(e));
    });
    for (var i = 0; i < inputs.length; i++)
        if (inputs[i].val() === "") {
            isOk = false;
            inputs[i].css("border", borderNotOk);
            if (inputs[i].attr("id") === "email")
                $("#email-hint").slideUp();
            if (inputs[i].attr("id") === "phone")
                $("#phone-hint").slideUp();
        } else {
            inputs[i].css("border", borderOk);
            if (inputs[i].attr("id") === "phone")
                if (!checkPhoneNumber(inputs[i].val())) {
                    isOk = false;
                    inputs[i].css("border", borderNotOk);
                    $("#phone-hint").slideDown();
                } else
                    $("#phone-hint").slideUp();
            if (inputs[i].attr("id") === "email")
                if (!(isEmailValid(inputs[i].val()))) {
                    isOk = false;
                    inputs[i].css("border", borderNotOk);
                    $("#email-hint").slideDown();
                } else
                    $("#email-hint").slideUp();
        }
    return isOk;
}

function singleCheck(input) {
    var borderOk = "1px solid rgba(0, 0, 0, 0.15)";
    if (input.val() !== "0" || input.val() !== "") {
        switch (input.attr("id")) {
            case "basemedia":
                input.css("border", borderOk);
                $("#printmedia").css("border", borderOk);
                break;
            case "printmedia":
                input.css("border", borderOk);
                $("#basemedia").css("border", borderOk);
                break;
            case "phone":
                if (checkPhoneNumber(input.val()))
                    input.css("border", borderOk);
                break;
            case "email":
                if (isEmailValid(input.val()))
                    input.css("border", borderOk);
                break;
            default:
                input.css("border", borderOk);
                break;
        }
    }
}

function isEmailValid(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function checkPhoneNumber(number) {
    if ((number.length >= 9 && number.length <= 20))
        return true;
    else
        return false;
}

function changeOptionalText(check, change) {
    if ($(check).val() !== "0")
        $(change + " option[value='0']").html("Optional...");
    else
        $(change + " option[value='0']").html("Choose...");
}

$(document).ready(function () {
    $("#finishing").change(function () {
        checkOption();
    });

    $("#basemedia").change(function () {
        changeOptionalText("#basemedia", "#printmedia");
    });

    $("#printmedia").change(function () {
        changeOptionalText("#printmedia", "#basemedia");
    });

    $("form :input").not(":input[type=button], :input[type=submit], :input:disabled, #finishing-optional").change(function () {
        singleCheck($(this));
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

    $('#addingUserForm').ajaxForm(function(response) {
        var customer = JSON.parse(response).customer;
        $('#customer').append($('<option>', {
            value: customer.id,
            text: customer.name,
        })).val(customer.id);

        $('#add-customer-form').modal('hide');

        $('#first-name').val('');
        $('#last-name').val('');
        $('#phone').val('');
        $('#email').val('');


    });
});