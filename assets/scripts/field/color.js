jQuery(function ($) {
    $('.colorpicker').hide();

    $('.colorpicker').each(function () {
        $(this).farbtastic($(this).closest('.colorpicker-wrapper').find('.color'));
    });

    $('.color').click(function () {
        $(this).closest('.colorpicker-wrapper').find('.colorpicker').fadeIn();
    });

    $(document).mousedown(function () {
        $('.colorpicker').each(
            function () {
                var display = $(this).css('display');
                if (display == 'block') {
                    $(this).fadeOut();
                }
            }
        );
    });
})
