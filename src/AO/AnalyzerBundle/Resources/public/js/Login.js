/**
 * Class Js for Log in page
 * 
 * 
 *  @requires libraries     : jQuery
 *  
 * 
 * @author Abdessamad O.
 */

function Login(setLocalUrl) {
    
    try {
        
        this.setLocalUrl = setLocalUrl; 
        
        self = this ; 
        $('.locale').click(function(){
            
            var locale = $(this).val();
            
             $.ajax({
                    url: self.setLocalUrl,
                    type: 'POST',
                    data: {'locale' : locale},
                    success: function(data) {
                       if(data == 1)
                       {
                          location.reload();
                       }
                    }
                });
            
        });
       
    }
    catch (ex) {
        console.log(ex.message);
    }

}
