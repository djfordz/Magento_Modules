
jQuery.fn.child = function(s, i) {
    return $(this).children(s)[i];
};

jQuery(document).ready(function (e) {
    e(".dropdown-menu").hide();
    function t(t) {
        e(t).bind("click", function (t) {
            t.preventDefault();
            e(this).parent().fadeOut()
        });
    }

    var placement = false, foil = false;


    e(".dropdown-toggle").click(function () {
        var t = e(this).parents(".button-dropdown").children(".dropdown-menu").is(":hidden");
        var z = e(this).closest(".dropdown-menu").children(".button-dropdown").children(".dropdown-menu").is(":visible");
        var u = e(this).parents(".dropdown-menu").prev(".dropdown-menu").children(".button-dropdown").children(".dropdown-menu").is(':visible');
        var a = e(this).parents(".dropdown-menu").next(".dropdown-menu").children(".button-dropdown").children(".dropdown-menu");
        var v = jQuery(e(".options-initial").child(".dropdown-menu", 0));
        var x = jQuery(e(".options-initial").child(".dropdown-menu", 1));
        var y = jQuery(e(".options-initial").child(".dropdown-menu", 2));

        e(this).closest('.dropdown-toggle').children('.change').children('.options-plus').text('+');
        e(this).closest(".button-dropdown").children(".dropdown-menu").hide();
        e(this).closest(v).children(".button-dropdown").children(".dropdown-menu").hide();
        e(this).closest(".dropdown-toggle").children('.change').children(".options-arrow").removeClass('up').addClass("down");
        e(this).closest(".button-dropdown").children(".options-list").children('.dropdown-toggle').children('.change').children('label').removeClass("bold").parent('.change').children("span").removeClass("bold");
        e(this).closest('.dropdown-toggle').children('.change').children('span').removeClass('bold');
       // e(this).closest('.button-dropdown').children('.options-title').removeClass('bold');

        if (t && !u && !z && e(this).parent().children(".dropdown-menu").is(":hidden")) {
            e(this).closest('.dropdown-toggle').children('.change').children('.options-plus').text('-');
            e(this).closest('.dropdown-toggle').children('.change').children(".options-title").addClass("bold");
            e(this).closest(".dropdown-toggle").children('.change').children(".options-plus").closest(".button-dropdown").children(".dropdown-menu").show();
        } else if (t && u && !z && a.is(":hidden") && placement) {
            e(this).closest(x).children(".button-dropdown").children(".dropdown-menu").show();
            e(this).closest(".dropdown-toggle").children(".change").children("label").addClass("bold");
            e(this).closest(".dropdown-toggle").children('.change').children(".options-arrow").addClass('up').removeClass("down");
        } else if (u && t && !z && placement && foil) {
            e(this).closest(y).children(".button-dropdown").children(".dropdown-menu").show();
            e(this).closest(".dropdown-toggle").children('.change').children(".options-arrow").addClass('up').removeClass("down");
            e(this).closest(".dropdown-toggle").children(".change").children("span").addClass("bold");
        } else if (t && !u && !z && a.is(":hidden")) {
            e(this).closest(v).children(".button-dropdown").children(".options-list").children('.dropdown-toggle').children('.change').children('label').addClass("bold").parent('.change').children("span").addClass("bold").addClass('up').removeClass('down');
            e(this).closest(v).children(".button-dropdown").children(".dropdown-menu").show();
        }
    });

    var radio = e(".options-initial").children(".dropdown-menu").children(".button-dropdown").children(".dropdown-menu").children(".input-box").children('.options-list').children('li').children('label').children('input[type=radio]').parent("label").parent("li").children("span").children("label");

    if (radio[0].innerText == "Bottom Right" || radio[0].innerText == "Bottom Right ") {
        e(".dropdown-menu .input-box ul li input[type=radio]")[0].setAttribute("checked", "checked");
        placement = true;
    }

    e(".dropdown-menu .input-box ul li input[type=radio]").change(function () {
        e(this).parents(".dropdown-menu").next(".dropdown-menu").children(".button-dropdown").children(".options-list").children('.dropdown-toggle').children('.change').children('label').addClass("bold").children("span").addClass("bold");
        e(this).parents(".dropdown-menu").next(".dropdown-menu").children(".button-dropdown").children(".options-list").children('.dropdown-toggle').children('.change').children('span').addClass("bold");
        var radio = e(this).closest(".dropdown-menu").children(".input-box").children('.options-list').children('li').children('label').children('input[type=radio]');

        if (radio.length >= 1 && radio.length <= 2 && radio.is(":checked")) {
            placement = true;
        }

        if (radio.length == 5 && radio.is(":checked")) {
            foil = true;
        }

    });

    e(".cancel-arrow").click(function() {
        document.getElementById('product_addtocart_form').reset();
        e('.dropdown-menu').hide();
        e('.bold').removeClass("bold");
        e(".dropdown-toggle").children('.change').children(".options-arrow").removeClass('up').addClass("down");
        e('.dropdown-toggle').children('.change').children('.options-plus').text('+');
        e('.product-custom-option').removeClass('option-required');
        flag = false;
        e('.amconf-image-selected').removeClass('amconf-image-selected');
        opConfig.reloadPrice();
    });

    e('input[type=text]').on('input', function() {
        opConfig.reloadPrice();
    });

    e('.personalization-options input[type=text], .personalization-options input[type=radio]').on('input', function() {
        e(this).parent('label').parent('li').parent('.options-list').children('li').children('label').children('.option-required').removeClass('option-required');
        e(this).parent('.input-box').parent('dd').parent('.options-text-box').children('dd').children('.input-box').children('.option-required').removeClass('option-required');
    });

    var flag = false;
    e(".product-custom-option:first-child, .product-custom-option:nth-child(2)").click(function() {
        var targets = e('.product-custom-option');
        for (var i = 0; i < targets.length; i++) {
            var count = targets[i].id.substring(targets[i].id.length - 1);
            if (!count.includes('t') && !flag) {
                 jQuery(targets[i]).addClass('option-required');
            } else if (count.includes('t') && !flag) {
                flag = true;
                jQuery(targets[i]).addClass('option-required');
            }
        }
    });
});
