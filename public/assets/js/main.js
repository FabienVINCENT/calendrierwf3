$(function () { //documentReady jQuery


    // Fonction qui affiche/cache les champs password
    $('.js-hidepassword').click(() => {
        $('.js-inputPassword').each((k, input) => {
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        })
    });

    let $loading = $('#loadingDiv').hide();
    $(document)
        .ajaxStart(function () {
            $loading.show();
            $('input[type=checkbox]').each((k, checkbox) => {
                $(checkbox).prop("disabled", true)
            });
        })
        .ajaxStop(function () {
            $loading.hide();
            $('input[type=checkbox]').each((k, checkbox) => {
                $(checkbox).prop("disabled", false)
            });
        });


    // Close Navbar when clicked outside
    $(window).on('click', function (event) {
        // element over which click was made
        var clickOver = $(event.target);
        if ($('.navbar .navbar-toggler').attr('aria-expanded') == 'true' && clickOver.closest('.navbar').length === 0) {
            // Click on navbar toggler button
            $('button[aria-expanded="true"]').click();
        }
    });

})