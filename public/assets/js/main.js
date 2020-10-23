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

    // Gestion du loading quand ajax en cours
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


    // Ferme NavbarBootstrap quand on click en dehors
    $(window).on('click', function (event) {
        var clickOver = $(event.target);
        if ($('.navbar .navbar-toggler').attr('aria-expanded') == 'true' && clickOver.closest('.navbar').length === 0) {
            $('button[aria-expanded="true"]').click();
        }
    });

})