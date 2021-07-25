
// $(document).ready(function() {

//     $(".mform").validate({
//         wrapper: "div",
//         rules: {
//             email: {
//                 required: true,
//                 email: true
//             },
//             password: {
//                 required: true,
//                 minlength: 5
//             }
//         },
//         messages: {
//             email: "Use una cuenta de correo v&aacute;lida",
//             password: {
//                 required: "Ingrese su contraseña",
//                 minlength: "La contrase&ntilde;a al menos debe tener 5 caracteres"
//             }
//         },
//         submitHandler: function(form) { // <- pass 'form' argument in
//             $(".submit").attr("disabled", true);
//             form.submit(); // <- use 'form' argument here.
//         }
//     });

// });

 
$(function()
  {
    $('.mform').validate(
      {
        rules:
        {
          answerid:{ required:true }
        },
        messages:
        {
          answerid:
          {
            required:"Please select one of answers provided!<br/>"
          }
        },
        errorPlacement: function(error, element) 
        {
            if ( element.is(":radio") ) 
            {
                error.appendTo( element.parents('.container') );
            }
            else 
            { // This is the default behavior 
                error.insertAfter( element );
            }
         }
      });
    
  });
