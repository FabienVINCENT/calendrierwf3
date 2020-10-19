$(function () { //documentReady jQuery






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