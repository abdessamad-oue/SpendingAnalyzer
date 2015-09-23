
/**
 * Base.js - a Base Javanscript Object for Spending analyzer webapp.
 * 
 * 
 * @requires libraries : jQuery, Colorbox
 * @author Abdessamad OUERYEMCHI
 * 
 */
function Base(formSelector, ajaxResult, popupSelector) {

    try
    {
        this.formSelector   = formSelector   || null;
        this.ajaxResult     = ajaxResult     || "#ajaxResult";
        this.popupSelector  = popupSelector  || ".popup";

        /**
         * submit un from en ajax
         * @param {type} selector
         * @returns {undefined}
         */
        this.submitForm = function(selector, initPopUp, callBack) {
            var self = this;
            initPopUp = initPopUp || false;
            
            $(selector).submit(function(e) {
                $(self.ajaxResult).html('Loading ...');
                e.preventDefault();
                $.ajax({
                    url: this.action,
                    type: this.method,
                    data: $(this).serialize(),
                    success: function(data) {
                        $(self.ajaxResult).html(data);
                        if (initPopUp === true) {
                            self.initPopUp(self.popupSelector);
                            if(callBack !== undefined)
                            {
                                callBack();
                            }
                        }
                    }
                });
            });
        };

        /**
         * init a colorbox popUp for an element
         * @param {type} selector
         * @returns {undefined}
         */
        this.initPopUp = function(selector) {

            $(selector).colorbox({
                scrolling: true,
                width: 900,
                height: 700,
                maxWidth: "95%",
                maxHeight: "95%",
                
            });

        };

    }
    catch (ex) {
        console.log(ex.message);
    }
}

