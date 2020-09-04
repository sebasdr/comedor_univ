$( document ).ready(function() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    var hour = ("0" + now.getHours()).slice(-2);
    var min = ("0" + now.getMinutes()).slice(-2);
    var seg = ("0" + now.getSeconds()).slice(-2);
    var tohour = (hour)+":"+(min)+":"+(seg);
    $("#fecha").val(today);
    $("#hora").val(tohour);
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
            La tarjeta ${datos.get('codigot')} ya se ha sellado.
            </div>
            `
        }else if(data === 'er3'){
            respuesta.innerHTML = `
            <div class='.alert alert-warning' role="alert">
            No existe la tarjeta ${datos.get('codigot')} en el actual semestre ${datos.get('sem')}
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
            La tarjeta ${datos.get('codigot')} ha sido sellada el dia ${datos.get('fecha')} a las ${datos.get('hora')}.
            </div>
            `
            fetch('php/tabselltar.php')
            .then( res => res.json() )//porque no le estamos enviando de forma correcta (objeto json desde php)
            .then( data => {
            //console.log(data[0].nombre)
            tabla(data)//A esta funcion le estamos mandando la data
          })
        }
    })

})

var tablasell = document.querySelector('#tablasell');
$(function(){
  fetch('php/tabselltar.php')
    .then( res => res.json() )//porque no le estamos enviando de forma correcta (objeto json desde php)
    .then( data => {
    //console.log(data[0].nombre)
    if(data === "erse"){
      respuesta.innerHTML = `
      <script>${window.location.href='index.html'}</script>
      `
  }
    tabla(data)//A esta funcion le estamos mandando la data
  })
})

//Funcion que genera tablas
function tabla(data){
//console.log(dato)
tablasell.innerHTML = ''
  for(let value of data){//Recorre el array de objetos, y lo separa en los objetos que tenga con "valor" para acceder a sus propiedades
  console.log(value.codC)
  tablasell.innerHTML += `
          
  <tr>
    <th scope="row">${ value.codT }</th>
    <td>${ value.sem }</td>
    <td>${ value.codC }</td>
    <td>${ value.fechC }</td>
    <td>${ value.hora }</td>
    <td>${ value.usuario }</td>
  `
  }
//Con el += va ir concatenando las tablas, es decir, muestra la primera tabla, luego muestra la segunda sin borrar la primera
}