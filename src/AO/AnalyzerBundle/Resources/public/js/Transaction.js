/**
 * Js Class for transaction Page (16/08/2015)
 * 
 *  @requires libraries     : jQuery
 *  @requires js file       : Base.js
 * 
 * @author Abdessamad O.
 */

function Transaction(formSelector, ajaxResult, deleteRoute) {

    try {
        this.page = 1;
        this.formSelector = formSelector || null;
        this.ajaxResult = ajaxResult || "#ajaxResult";
        this.deleteRoute = deleteRoute || null;
        this.Main();

    }
    catch (ex) {
        console.log(ex.message);
    }

}

/**
 * Main function 
 */
Transaction.prototype.Main = function() {
    $("#date_begin").datepicker({dateFormat: "yy-mm-dd"});
    $("#date_end").datepicker({dateFormat: "yy-mm-dd"});
    this.validateForm(this.formSelector);
};


/**
 * submit the form
 */
Transaction.prototype.validateForm = function(selector) {
    var self = this;
    $(selector).submit(function(e) {

        $(self.ajaxResult).html('Loading ...');
        e.preventDefault();
        $.ajax({
            url: this.action,
            type: this.method,
            data: $(this).serialize(),
            success: function(data)
            {
                $(self.ajaxResult).html(data);
                self.pagination();
                self.delete();
            }
        });
    });
};


/**
 * pagination function 
 */
Transaction.prototype.pagination = function() {
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
                self.delete();
            }
        });
    });
};

Transaction.prototype.delete = function() {
    var self = this;
    $('.deleteTrans').click(function() {
        var id = $(this).data('id');
        var clickedElement = this;
        $.ajax({
            url: self.deleteRoute,
            type: 'POST',
            data: {id: id},
            success: function(data) {
                if(data == 1) 
                {
                    $(clickedElement).parent().parent().remove();
                }
            }
        });
    });

};
