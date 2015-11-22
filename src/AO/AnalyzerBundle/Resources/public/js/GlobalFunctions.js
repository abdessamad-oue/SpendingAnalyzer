/**
 * Global functions
 *
 * @author Abdessamad OUERYEMCHI
 */

/*
 * load global functions
 */
function ihmGlobals() {
    changeLocalePopover();
}

/**
 * change Locale  
 */
function changeLocalePopover() {
    $('#langageButton').popover({
        html: true
    });

    $('#langageButton').on('shown.bs.popover', function() {
        $('.btnLocale').click(function(e) {
            var locale = $(this).data('locale');
            $.ajax({
                url: '/set_locale',
                type: 'POST',
                data: {'locale': locale},
                success: function(data) {
                    if (data == 1)
                    {
                        location.reload();
                    }
                }
            });
        });
    });
}


