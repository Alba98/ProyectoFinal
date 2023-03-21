
document.addEventListener('DOMContentLoaded', comprobaciones);
function comprobaciones(){
    var form1 = document.getElementById('invitadoForm');
    if(form1)
        form1.addEventListener('submit',validarDatos);

    var form2 = document.getElementById('nuevoUsuario');
    if(form2)
        form2.addEventListener('submit',validarDatos);
    var toast = document.getElementById('toast');
    console.log(toast);
}

function validarDatos(e){
    e.preventDefault();
    var telefono = "";
    var nombre ="";
    var email = "";
    if(e.target.id == 'invitadoForm'){
        console.log('soy invitado');
        var telefonoInput = document.getElementById('telefonoInvitado');
        telefono = telefonoInput.value;
        var nombreInput = document.getElementById('nombreInvitado');
        nombre = nombreInput.value;
        var emailInput = document.getElementById('correoInvitado');
        email = emailInput.value;
    }
    if(e.target.id == 'nuevoUsuario'){
        console.log('soy nuevo');
       var telefonoInput = document.getElementById('nuevoTelefono');
      
       telefono = telefonoInput.value;
       var nombreInput = document.getElementById('nuevoNombre');
       nombre = nombreInput.value;
       var emailInput = document.getElementById('nuevoCorreo');
       email = emailInput.value;
    }
    
    var telefonoValido = false;
    var nombreValido = false;
    var emailValido = false;

    telefonoValido = validarTelefono(telefono);
    nombreValido = validarNombre(nombre);
    emailValido = validarCorreo(email);

    if(telefonoValido && nombreValido && emailValido){
        this.submit();
    }

}
function validarTelefono(telefono){
    try{
        var valido = false;
        var expRegTelf = new RegExp(/[6-9][0-9]{8}/);
        if(!expRegTelf.test(telefono)){
            throw "El telefono no es valido";
        }
        var valido = true;
        return valido;
    }catch(err){
        
        var tos = new bootstrap.Toast(toast);
        var toastBody = document.getElementById('body');
        toastBody.innerHTML = err;
        tos.show();
        return valido;
    }
}
function validarCorreo(correo){
    try{
     
        var expRegCorreo = new RegExp(/[\w]+@{1}[\w]+\.[a-z]{2,3}/);
        if(!expRegCorreo.test(correo)){
            throw "El correo no es valido";
        }
        var valido = true;
        return valido;
    }catch(err){
        
        var tos = new bootstrap.Toast(toast);
        var toastBody = document.getElementById('body');
        toastBody.innerHTML = err;
        tos.show();
        return valido;
    }
}
function validarNombre(nombre){
    try{
        var expRegNombre = new RegExp(/^[A-Z][a-z]+(\s+[A-Z]?[a-z]+)*$/);
        if(!expRegNombre.test(nombre)){
            throw "Por favor, compruebe que el nombre comienza por mayúsculas y que usa caracteres alfabéticos";
        }
        var valido = true;
        return valido;
    }catch(err){
        var tos = new bootstrap.Toast(toast);
        var toastBody = document.getElementById('body');
        toastBody.innerHTML = err;
        tos.show();
        
        return valido;
    }
}