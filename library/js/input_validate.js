 /*
  * name --> name of the field.
  * field_type --> type of the field , i.e., email , url, etc.  
  */

 function validate(form,name,field_type)
    {                     
        var text_elem = document.querySelector('[name='+name+']');         
        var field_value = text_elem.value;       
        var submit_button =  document.querySelector('[type="submit"]');         
        if(field_type == "email")
        {
            var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (reg.test(field_value) == false && field_value!='' ) 
            {
                alert('Invalid Email Address');
                text_elem.style.border =  "thin solid red";  
                handle_submit(form);                             
            }
            else
            {                                   
                text_elem.style.border =  ""; 
                if(submit_button.hasAttribute("disabled"))
                {       
                    submit_button.removeAttribute("disabled");
                    submit_button.style.backgroundColor = "";           
                } 
                handle_submit(form);                                    
            }            
           
        }   
            
        else if(field_type == "url")
        {
            var reg = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
            if (reg.test(field_value) == false && field_value!='' ) 
            {
                alert('Invalid url Address');
                text_elem.style.border =  "thin solid red"; 
                handle_submit(form);                                  
            }
            
            else
            {
                text_elem.style.border =  "";   
                if(submit_button.hasAttribute("disabled"))
                {       
                    submit_button.removeAttribute("disabled");
                    submit_button.style.backgroundColor = "";           
                } 
                handle_submit(form);                                                      
                     
            }
                     
        }         
        
        else if(field_type == "tel")
        {
            var reg = /^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/;
            if (reg.test(field_value) == false && field_value!='' ) 
            {
                alert('Invalid Entry');
                text_elem.style.border =  "thin solid red"; 
                handle_submit(form);                                  
            }            
            else
            {              
                var formatted_no = text_elem.value.replace(/\D/g, ''),char = {0:'(',3:')',6:'-'};
                text_elem.value = '';
                for (var i = 0; i < formatted_no.length; i++)
                {
                    text_elem.value += (char[i]||'') + formatted_no[i];
                }
                text_elem.style.border =  "";   
                if(submit_button.hasAttribute("disabled"))
                {       
                    submit_button.removeAttribute("disabled");
                    submit_button.style.backgroundColor = "";           
                } 
                handle_submit(form);                                                      
                     
            }
                     
        }          

        /*
            More field checks to be added..
        */
        
   }  
    
function handle_submit(form)
{   
    var form_elem = document.querySelectorAll("input[type]");
    var submit_button =  document.querySelector('[type="submit"]');  
    var i;
    var flag = 0;
    for (i = 0; i < form.length; i++) 
    {        
        if(form_elem[i].style.border == "thin solid red")
        {
            flag = 1;
            break;
        }
    }
    if(flag == 1)
    {
        submit_button.setAttribute("disabled", "true");   
        submit_button.style.backgroundColor = "red";
    }   

}
