$( document ).ready(function() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $("#fecha").val(today);
});


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
            El estudiante ${datos.get('codigoe')} ya tiene una tarjeta asignada en el actual semestre ${datos.get('sem')}.
            </div>
            `
        }else if(data === 'er3'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            El estudiante ${datos.get('codigoe')} no puede tener dos tarjetas en el actual semestre ${datos.get('sem')}.
            </div>
            `
        }else if(data === 'er4'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            No existe el estudiante ${datos.get('codigoe')}.
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
            Al estudiante ${datos.get('codigoe')} se le ha asignado la tarjeta ${datos.get('codigot')} correctamente en el semestre ${datos.get('sem')}.
            </div>
            `
        }
    })

})