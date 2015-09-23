/**
 * Js Class for transaction Page (16/08/2015)
 * 
 *  @requires libraries     : jQuery
 *  @requires js file       : Base.js
 * 
 * @author Abdessamad O.
 */

function Transaction(formSelector, ajaxResult, popUpSelector) {

    try {
        this.page =  1;
        Base.call(this, formSelector, ajaxResult, popUpSelector);
        this.Main();

    }
    catch (ex) {
        console.log(ex.message);
    }

}
/**
 * Heritage: Inherit class Base (by prototype)
 */
Transaction.prototype = new Base;

/**
 * Main function 
 */
Transaction.prototype.Main = function() {
    $("#date_begin").datepicker({dateFormat: "yy-mm-dd"});
    $("#date_end").datepicker({dateFormat: "yy-mm-dd"});
    this.submitForm(this.formSelector, true, this.Pagination);
};

/**
 * pagination function 
 */
Transaction.prototype.Pagination = function() {
    var self = this;
    $('#ajaxResult').on('click', 'a.page', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        self.page = page;
        $.ajax({
            url: $('#basicForm').attr('action'),
            type: 'POST',
            data: $('#basicForm').serialize() + '&page=' + page,
            success: function(data) {
                $(self.ajaxResult).html(data);
            }
        });
    });
};




