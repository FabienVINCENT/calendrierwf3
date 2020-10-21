$(function () { //documentReady jQuery

    // Lance select2.js sur le champ formateur quand on est admin (recherche)
    $('.js-select2-formateur').select2({ theme: "bootstrap" });


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