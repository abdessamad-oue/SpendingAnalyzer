/**
 * Class Js pour la Home Page
 * 
 * 
 *  @requires libraries     : jQuery
 *  @requires js file       : Base.js
 * 
 * @author Abdessamad O.
 */

function Home(formSelector, ajaxResult, popUpSelector) {
    
    try {
        Base.call(this, formSelector, ajaxResult, popUpSelector);
        this.Main();
       
    }
    catch (ex) {
        console.log(ex.message);
    }

}
/**
 * Heritage : Hériter de la class Base (par prorotype)
 */
Home.prototype = new Base;

/**
 * fonction principal 
 */
Home.prototype.Main = function() {    
    $("#date_begin").datepicker({ dateFormat: "yy-mm-dd" });
    $("#date_end").datepicker({ dateFormat: "yy-mm-dd" });
    this.submitForm(this.formSelector, true);
    
};


