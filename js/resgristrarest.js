var options = {
    2 : ["Administracion de Empresas1","Contabilidad2","Educacion Primaria Intercultural3"],
    1 : ["Ingenieria Agroindustrial4","Ingenieria Ambiental5","Ingenieria de Sistemas6"]
}
  
$(function(){
    var fillCarrera = function(){
        var selected = $('#facultad').val();
        $('#carrera').empty();
        options[selected].forEach(function(element,index){
            $('#carrera').append('<option value="'+element.slice(-1)+'">'+element.slice(0,-1)+'</option>');
        });
    }
    $('#facultad').change(fillCarrera);//Cada vez que se elija (cambie de eleccion) una nueva facultad, change ejecuta fillCarrera
    fillCarrera();//para darle los valores iniciales al segundo combo la primera vez
})


var formulario = document.getElementById('formulario')
var respuesta = document.getElementById('respuesta')

//Detecta el submit (cada vez que sube el formulario), y realiza una funcion con parametro de entrada
formulario.addEventListener('submit', function(e){//EL e sirve para evitar que se ejecute lo que por defecto viene en el navegador
    e.preventDefault();//Evita qeue el navegador procese el formulario
    //console.log('Me diste un click');

    var datos = new FormData(formulario);//FormData: Hace una nueva informacion del formulario del formulario 

    //console.log(datos);
    //console.log(datos.get('usuario'))//get(name del imput)
    //console.log(datos.get('pass'))

    fetch('php/funcionesbas.php',{
        method: 'POST',//Para que sea post
        body: datos//MAndandole los datos del formdata al php
    })
    .then( res => res.json())//Transformando la respuesta a json
    .then( data => {//Mostrando la respuesta (del php) en donde querramos
        console.log(data)
        if(data === 'er1'){
            respuesta.innerHTML = `
            <div class='.alert alert-danger' role="alert">
            No se pudieron ingresar los datos.
            </div>
            `
        }else if(data === 'er2'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            Ya existe el estudiante con codigo ${datos.get('codigo')}.
            </div>
            `
        }else if(data === 'er3'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            El DNI ${datos.get('dni')} ya existe.
            </div>
            `
        }else if(data === 'er4'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            El estudiante ${datos.get('nombre')} ${datos.get('apellido')} ya existe.
            </div>
            `
        }else if(data === 'erif'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            Faltan datos.
            </div>
            `
        }else if(data === 'succ'){
            respuesta.innerHTML = `
            <div class='.alert alert-primary' role="alert">
            El estudiante ${datos.get('nombre')} ${datos.get('apellido')} se ha creado con exito con codigo ${datos.get('codigo')}
            </div>
            `
        }
    })

})