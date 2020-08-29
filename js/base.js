$( document ).ready(function() {
    $('#regest').click(function () {
        $("#contenido").load("registrarest.html")
    })  
    $('#asigtar').click(function () {
        $("#contenido").load("asignartar.html")
    })
    $('#seltar').click(function () {
        $("#contenido").load("sellartar.html")
    })
})
