/**
 * Habilita y deshabilita campos del formulario de alta de usuario, dependiendo de si clicas
 * en 'Alumno' o 'Profesor'.
 * @param {String} fieldEnable el id del fieldset que se habilita
 * @param {String} fieldDisable el id del fieldset que se deshabilita
 */
function enableDisable(fieldEnable, fieldDisable) {
    document.getElementById(fieldDisable).disabled = true;
    document.getElementById(fieldEnable).disabled = false;
}

function hideShow(fieldShow, fieldHide) {
    document.getElementById(fieldShow).style.display = "block";
    document.getElementById(fieldHide).style.display = "none";
}

function initMenu() {
    if (window.innerWidth > 768) {
        document.getElementById("menu").className = "closed";


    } else {
        document.getElementById("main").className = "mobile";
        document.getElementById("menu").className = "closed-mobile";
    }
}

function openNav() {
    if (window.innerWidth > 768) {
        if (document.getElementById("menu").className == "closed") {
            document.getElementById("menu").className = "opened";
        } else {
            document.getElementById("menu").className = "closed";
        }
    } else {
        document.getElementById("menu").className = "opened-mobile";
    }

}

function closeNav() {
    document.getElementById("menu").className = "closed-mobile";
}

function changeViewport(x) {
    if (x.matches) {
        document.getElementById("main").className = "mobile";
        if (document.getElementById("menu").className == "opened") {
            document.getElementById("menu").className = "opened-mobile";
        } else if (document.getElementById("menu").className == "closed") {
            document.getElementById("menu").className = "closed-mobile";
        }
    } else {
        if (document.getElementById("menu").className == "opened-mobile") {
            document.getElementById("menu").className = "opened";

        } else if (document.getElementById("menu").className == "closed-mobile") {
            document.getElementById("menu").className = "closed";
            document.getElementById("main").className = "closed";
        }
    }
}

function editScheduleFieldAddForm(td, carne, dia, tramo) {
    $.get('form_horario.php', {
            action: "add-form",
            carne: carne,
            dia: dia,
            tramo: tramo
        },
        function(response) {
            $("#" + td).html(response);
        });
}

function editScheduleFieldEdit(td, oid, asignatura) {
    $.get('form_horario.php', {
            action: "edit",
            oid: oid,
            asignatura: asignatura
        },
        function(response) {
            $("#" + td).html(response);
        });
}

function muestraEstadistica(val) {
    $.get('controlador_estadisticas.php?' + val, {},
        function(response) {
            $("#tablaes").empty();
            $("#tablaes").html(response);
        }
    );
}

function printContent(el) {
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}

function tablaUsuarios(val) {
    $.get('tabla_usuarios.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaLibros(val) {
    $.get('tabla_libros.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaPrestamos(val) {
    $.get('tabla_prestamos.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaInventario(val) {
    $.get('tabla_inventario.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaHistorialReservas(val) {
    $.get('tabla_historialreservas.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaMateriales(val) {
    $.get('tabla_materiales.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}

function tablaEjemplares(val) {
    $.get('tabla_ejemplares.php?' + val, {},
        function(response) {
            $("#tabla").empty();
            $("#tabla").html(response);

        }
    );
}


function buscaEnTabla(tabla, input) {
    var table = document.getElementById(tabla);
    var bus = document.getElementById(input).value.toLowerCase();
    var celdas = "";
    var encontrado = false;
    var texto = "";

    for (var i = 1; i < table.rows.length; i++) {
        celdas = table.rows[i].getElementsByTagName('td');
        encontrado = false;

        for (var j = 0; j < celdas.length && !encontrado; j++) {
            texto = celdas[j].innerHTML.toLowerCase();

            if (bus.length == 0 || (texto.indexOf(bus) > -1)) {
                encontrado = true;
            }
        }
        if (encontrado) {
            table.rows[i].style.display = '';
        } else {
            // si no ha encontrado ninguna coincidencia, esconde la
            // fila de la tabla
            table.rows[i].style.display = 'none';
        }
    }
}

function validaPass() {
    var input = document.querySelector("#newPass");
    var input2 = document.querySelector("#repeatNewPass");

    if (input.value != input2.value) {
        input2.setCustomValidity("Las contrase\u00F1as no coinciden");
        input2.reportValidity();
        return false;
    } else {
        passcode_input.setCustomValidity("");
        return true;
    }
}

function validaBusqueda(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /^[0-9a-zA-ZÀ-ÿ\u00f1\u00d1º_ -]$/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function validaISBN() {
    var input = document.querySelector("#isbn");
    var isbn = input.value;
    var patron10 = /^[0-9]{10}$/;
    var patron13 = /^[0-9]{3}-[0-9]{10}$/;
    if (patron10.test(isbn)) {
        passcode_input.setCustomValidity("");
        return true;
    } else if(patron13.test(isbn)) {
        passcode_input.setCustomValidity("");
        return true;
    }else{
        input.setCustomValidity("El ISBN debe tener el formato adecuado.");
        input.reportValidity();
       return false;
    }
}