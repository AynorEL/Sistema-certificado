<?php

require_once "../conexion/coneccion.php";
require_once "./validadorsql.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = new Conexion();
    $conn = $start->conexionbd();

    $nombre_rol = limpiar_cadena($_POST['rol_nombre']);

    if ($nombre_rol == "") {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "El nombre del rol es obligatorio",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                window.location = "../index.php?mostrar=rol_form";
            });
        </script>';
        exit();
    }

    try {
        // Verificar si el nombre ya existe
        $verificar = $conn->prepare("SELECT COUNT(*) FROM rol WHERE nombre_rol = :nombre");
        $verificar->execute([":nombre" => $nombre_rol]);
        $existe = $verificar->fetchColumn();

        if ($existe > 0) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ya existe un rol con ese nombre",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    window.location = "../index.php?mostrar=rol_form";
                });
            </script>';
            exit();
        }

        // Guardar el rol
        $guardar = $conn->prepare("INSERT INTO rol (nombre_rol) VALUES (:nombre)");
        $resultado = $guardar->execute([":nombre" => $nombre_rol]);

        if ($resultado) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Rol guardado correctamente",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    window.location = "../index.php?mostrar=rol_form";
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Error al guardar el rol",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    window.location = "../index.php?mostrar=rol_form";
                });
            </script>';
        }
    } catch (PDOException $e) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al guardar el rol: ' . $e->getMessage() . '",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                window.location = "../index.php?mostrar=rol_form";
            });
        </script>';
    }

    $conn = null;
} else {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Método no permitido",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            window.location = "../index.php?mostrar=rol_form";
        });
    </script>';
}
?>









