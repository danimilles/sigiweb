<?php
session_start();
include_once("gestionBD.php");
include_once("gestionAulas.php");
include_once("gestionDepartamentos.php");
$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if ($carne != '00000P') {
    Header("Location: exito_login.php");
}
if (isset($_SESSION["departamento_controlado"])) unset($_SESSION["departamento_controlado"]);
if (isset($_SESSION["editando_departamento"])) unset($_SESSION["editando_departamento"]);
if (isset($_SESSION["aula_controlada"])) unset($_SESSION["aula_controlada"]);
if (isset($_SESSION["editando_aula"])) unset($_SESSION["editando_aula"]);
$con = crearConexionBD();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aulas y departamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">

            <h2>Aulas y departamentos</h2>
            <hr class="principal"><br>

            <?php if(isset($_SESSION["errores"])) {
                $errores = $_SESSION["errores"];
                unset($_SESSION["errores"]);
                echo "<div class='fallo'>";
                echo "<h4>Se han producido los siguientes errores:</h4>";
                foreach($errores as $error) {
                    echo $error;
                }
                echo "</div>";
            } ?>

            <?php if (isset($_SESSION["caula"]) && $_SESSION["caula"]) { ?>
                <div class="exito">
                    <p>El aula se ha introducido con éxito</p>
                </div>
            <?php unset($_SESSION["caula"]);
            }
            if (isset($_SESSION["cdepartamento"]) && $_SESSION["cdepartamento"]) { ?>
                <div class="exito">
                    <p>El departamento se ha introducido con éxito</p>
                </div>
            <?php unset($_SESSION["cdepartamento"]);
            }
            ?>

            <?php
            if (isset($_SESSION["baja_aula_exito"])) {
                $bajaAulaExito = $_SESSION["baja_aula_exito"];
                unset($_SESSION["baja_aula_exito"]);
                if ($bajaAulaExito) { ?>
                    <div class="exito">
                        <p>El aula se ha eliminado correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El aula no se ha podido eliminar de la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        } ?>

            <?php
            if (isset($_SESSION["baja_departamento_exito"])) {
                $bajaDepartamentoExito = $_SESSION["baja_departamento_exito"];
                unset($_SESSION["baja_departamento_exito"]);
                if ($bajaDepartamentoExito) { ?>
                    <div class="exito">
                        <p>El departamento se ha eliminado correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El departamento no se ha podido eliminar de la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        } ?>
            <?php
            if (isset($_SESSION["exito_edit"])) {
                $editDepartamentoExito = $_SESSION["exito_edit"];
                unset($_SESSION["exito_edit"]);
                if ($editDepartamentoExito) { ?>
                    <div class="exito">
                        <p>El departamento ha sido editado correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El departamento no se ha podido editar en la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        } ?>

            <?php
            if (isset($_SESSION["exito_edit_aula"])) {
                $editAulaExito = $_SESSION["exito_edit_aula"];
                unset($_SESSION["exito_edit_aula"]);
                if ($editAulaExito) { ?>
                    <div class="exito">
                        <p>El aula ha sido editada correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El aula no se ha podido editar en la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        } ?>
    <div class="row">
            <div class="divform hg margini prestamosalum inlineblock col-12-m col-6-es">
                    <legend>Departamentos </legend>
                    <div><button type="button" class="btn" onclick="window.location.href='accion_nuevo_departamento.php'"><img id="icon_busq" src="images/plus.png" alt="Buscar" width="18px" height="18px"></button>
                    <div class="inlineblock"><label for="busdep"><img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px"></label>
                        <input type="text" id="busdep" placeholder="Buscar... " onpaste="return false;" onkeypress="return validaBusqueda(event)" oninput="buscaEnTabla('dep','busdep')">
                      </div>
                    </div><br>
                    <table id="dep" class="hg tabla-pag">
                        <tr>
                            <th class="start-line">Departamento</th>
                            <th>Jefe</th>
                            <th class="end-line">Controles</th>
                        </tr>
                        <?php
                        $departamentos = todosLosDepartamentos($con);
                        foreach ($departamentos as $dept) { ?>
                            <tr>
                                <td class="start-line"><?php echo $dept["NOMBRE"] ?></td>
                                <td><?php echo $dept["JEFE_DEPARTAMENTO"] ?></td>
                                <td class="end-line">
                                    <form method="post" action="controlador_departamentos.php">
                                        <input id="OID_D" name="OID_D" type="hidden" value="<?php echo $dept["OID_D"]; ?>" />
                                        <input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $dept["NOMBRE"]; ?>" />
                                        <button id="editar" class="btn" name="editar" title="Editar" type="submit">
                                            <img src="images/edit_icon.png" alt="Editar departamento" width="18px" height="18px">
                                        </button>
                                        <button id="borrar" class="btn" name="borrar" title='Eliminar' type="submit">
                                            <img src="images/delete_icon.png" alt="Borrar departamento" width="18px" height="18px">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
            </div>

            <div class="divform prestamosalum hg inlineblock col-12-m col-6-es">
                    <legend>Aulas </legend>
                    <div><button type="button" class="btn " onclick="window.location.href='accion_nuevo_aula.php'"><img id="icon_busq" src="images/plus.png" alt="Buscar" width="18px" height="18px"></button>
                        <div class="inlineblock"><label for="busaul"><img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px"></label>
                        <input type="text" id="busaul" placeholder="Buscar... " onpaste="return false;" onkeypress="return validaBusqueda(event)" oninput="buscaEnTabla('aul','busaul')"></div>
                    </div><br>
                    <table id="aul" class="tabla-pag hg">
                        <tr>
                            <th class="start-line">Aula</th>
                            <th>Número</th>
                            <th class="end-line">Controles</th>
                        </tr>
                        <?php
                        $aulas = listaAulas($con);
                        foreach ($aulas as $aula) { ?>
                            <tr>
                                <td class="start-line"><?php echo $aula["NOMBRE"] ?></td>
                                <td><?php echo $aula["NUMERO"] ?></td>
                                <td class="end-line">
                                    <form method="post" action="controlador_aulas_ED.php">
                                        <input id="NUMERO" name="NUMERO" type="hidden" value="<?php echo $aula["NUMERO"]; ?>" />
                                        <input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $aula["NOMBRE"]; ?>" />
                                        <button id="editar" class="btn" name="editar" title="Editar" type="submit">
                                            <img src="images/edit_icon.png" alt="Editar aula" width="18px" height="18px">
                                        </button>
                                        <button id="borrar" class="btn" name="borrar" title="Eliminar" type="submit">
                                            <img src="images/delete_icon.png" alt="Borrar aula" width="18px" height="18px">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
            </div>
        </div>
        </div>
    </main>
    <br>
    <script>
        document.getElementById("sauldep").className = "active"
    </script>

    <?php
    cerrarConexionBD($con);
    include_once("footer.php") ?>


</body>

</html>