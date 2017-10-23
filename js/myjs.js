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

function loadOrders(customer) {
    $.ajax({
        dataType: "json",
        method: "GET",
        url: "./getOrders.php",
        data: {"customerId": customer.val()},
        success: function (data) {
            var orders = data.orders;
            $(orders).each(function (index, order) {
                $('#options-select').append($('<option>', {
                    value: order.id,
                    text: order.name + ", " + order.date
                }));
            });
        }
    });
}

function loadCustomerDetails(customer) {
    $.ajax({
        dataType: "json",
        method: "GET",
        url: "./getCustomerDetails.php",
        data: {"customerId": customer.val()},
        success: function (data) {
            var details = data.details;
            var dataArray;
            $(details).each(function (index, data) {
                dataArray = {
                    phone: data.phone_number,
                    email: data.email
                };
            });
            if ($("#customer-details").is(":visible"))
                $(".customer-details").fadeOut(275, function () {
                    $("#customer-phone").html(dataArray.phone);
                    $("#customer-email").html(dataArray.email);
                }).fadeIn(275);
            else {
                $("#customer-phone").html(dataArray.phone);
                $("#customer-email").html(dataArray.email);
                $("#customer-details").slideDown();
            }
        }
    });
}

function loadSingleOrder(order) {
    $.ajax({
        dataType: "json",
        method: "GET",
        url: "./getSpecOrder.php",
        data: {"orderID": order.val()},
        success: function (data) {
            var tmp = data.order;
            $(tmp).each(function (index, val) {
                $("#order-name").val(val.name);
                $("#width").val(val.size_1);
                $("#length").val(val.size_2);
                switch (val.type) {
                    case "1":
                        $("#basemedia").val(val.product_id);
                        break;
                    case "2":
                        $("#printmedia").val(val.product_id);
                        break;
                    case "3":
                        if ($("#finishing").val() === "0") {
                            $("#finishing").val(val.product_id);
                            checkOption();
                        } else
                            $("#finishing-optional").val(val.product_id);
                        break;
                }
                if (val.shipping === "1")
                    $("#shipping").val("1");
                else
                    $("#shipping").val("2");
            });
            recalculatePrices();
            $("#order-btn").html("Update");
            $("#new-order-form").attr("action", "updateOrder.php");
            changeOptionalText("#basemedia", "#printmedia");
            changeOptionalText("#printmedia", "#basemedia");
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
    } else
        divFinishing.slideUp();
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
    var inputBaseMedia = 4;
    var inputPrintMedia = 5;
    var borderOk = "1px solid rgba(0, 0, 0, 0.15)";
    var borderNotOk = "1px solid rgba(255, 0, 0, 0.75)";
    $("#new-order-form :input").not(":input[type=button], :input[type=submit], :input:disabled, #finishing-optional, #options-select").each(function (i, e) {
        inputs.push($(e));
    });
    if (inputs[inputBaseMedia].val() === "0" && inputs[inputPrintMedia].val() === "0") {
        isOk = false;
        inputs[inputBaseMedia].css("border", borderNotOk);
        inputs[inputPrintMedia].css("border", borderNotOk);
    } else {
        isOk = true;
        inputs[inputBaseMedia].css("border", borderOk);
        inputs[inputPrintMedia].css("border", borderOk);
    }
    for (var i = 0; i < inputs.length; i++)
        if (inputs[i].attr("id") !== "basemedia" && inputs[i].attr("id") !== "printmedia")
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
    var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return emailRegex.test(email);
}

function checkPhoneNumber(number) {
    var phoneNumberRegex = /^\+?([0-9]{1,3})\)?[-. ]?([0-9]{9,11})$/;
    return phoneNumberRegex.test(number);
}

function changeOptionalText(check, change) {
    if ($(check).val() !== "0")
        $(change + " option[value='0']").html("Optional...");
    else
        $(change + " option[value='0']").html("Choose...");
}

function choosedCustomer(customer) {
    if (customer.val() !== "0")
        if (customer.val() === "new-customer") {
            $("#add-customer-form").modal("show");
            customer.val("0");
            closeAllTabs();
        } else {
            $("#order-screen").slideUp("slow");
            loadOrders(customer);
            loadCustomerDetails(customer);
            $("#options").val("0");
            $("#options").slideDown();
        }
    else
        closeAllTabs();
}

function closeAllTabs() {
    $("#order-screen").slideUp("slow");
    $("#customer-details").slideUp();
    $("#options").slideUp();
    $("#options").val("0");
}

function choosedOption(option) {
    var orderScreen = $("#order-screen");
    if (option.val() !== "0")
        if (option.val() === "new-order") {
            orderScreen.slideUp(400, function () {
                $("#order-btn").html("Save");
                $("#new-order-form").attr("action", "saveOrder.php");
                clearOrderForm();
                checkOption();
                orderScreen.slideDown("slow");
            });
        } else {
            orderScreen.slideUp(400, function () {
                clearOrderForm();
                loadSingleOrder(option);
                $("#new-order-form :input").not(":input[type=button], :input[type=submit], :input:disabled, #finishing-optional, #options-select").each(function (i, e) {
                    $(e).css("border", "1px solid rgba(0, 0, 0, 0.15)");
                });
                orderScreen.slideDown("slow");
            });
        }
    else
        orderScreen.slideUp("slow");
}

function clearOptions() {
    $('#options-select').empty();
    $('#options-select').append('<option value="0" selected>Choose...</option>');
    $('#options-select').append('<option value="new-order">New order</option>');
    $('#options-select').append('<option value="null" disabled="true"></option>');
}

function clearOrderForm() {
    $("#order-name").val("");
    $("#width").val("");
    $("#length").val("");
    $("#basemedia").val("0");
    $("#printmedia").val("0");
    $("#finishing").val("0");
    $("#finishing-optional").val("0");
    $("#shipping").val("2");
    $('#total-price').val("£0.00");
    $('#ink').val("£0.00");
    $('#labour').val("£0.00");
    $('#labour-time').val("0 minutes");
    changeOptionalText("#basemedia", "#printmedia");
    changeOptionalText("#printmedia", "#basemedia");
}

$(document).ready(function () {
    $("#finishing").change(function () {
        checkOption();
    });

    $("#customer").change(function () {
        choosedCustomer($(this));
        clearOptions();
    });

    $("#options-select").change(function () {
        choosedOption($(this));
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
    disableKeyDown("#width", [69]);
    disableKeyDown("#length", [69]);

    $("#phone").keypress(function (evt) {
        if ((evt.charCode < 48 || evt.charCode > 57) && evt.charCode !== 43 && evt.charCode !== 13)
            evt.preventDefault();
    });

    $('.product-select').change(function () {
        recalculatePrices();
    });

    $('#add-customer-form').ajaxForm({
        beforeSubmit: function () {
            return checkNewCustomerForm();
        },
        success: function (response) {
            var customer = JSON.parse(response).customer;
            $('#customer').append($('<option>', {
                value: customer.id,
                text: customer.name
            }));

            $('#first-name').val('');
            $('#last-name').val('');
            $('#phone').val('');
            $('#email').val('');

            $('#add-customer-form').modal('hide');
        }
    });
});
