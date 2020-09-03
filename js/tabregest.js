var tablaest = document.querySelector('#tablaest');
$(function(){
  fetch('php/funcionesbas.php')
    .then( res => res.json() )//porque no le estamos enviando de forma correcta (objeto json desde php)
    .then( data => {
    //console.log(data[0].nombre)
    tabla(data)//A esta funcion le estamos mandando la data
  })
})

//Funcion que genera tablas
function tabla(data){
//console.log(dato)
tablaest.innerHTML = ''
  for(let value of data){//Recorre el array de objetos, y lo separa en los objetos que tenga con "valor" para acceder a sus propiedades
  console.log(value.nombre)
  tablaest.innerHTML += `
          
  <tr>
    <th scope="row">${ value.codigo }</th>
    <td>${ value.dni }</td>
    <td>${ value.nombre }</td>
    <td>${ value.apellido }</td>
    <td>${ value.facultad }</td>
    <td>${ value.carrera }</td>
    <td>${ value.telefono }</td>
    <td>${ value.direccion }</td>
  </tr>
            
  `
  }
//Con el += va ir concatenando las tablas, es decir, muestra la primera tabla, luego muestra la segunda sin borrar la primera
}