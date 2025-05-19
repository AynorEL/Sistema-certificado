<?php
require_once "../conexion/coneccion.php";
require_once "./validadorsql.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = new Conexion();
    $conn = $start->conexionbd();

    $idrol = limpiar_cadena($_POST['idrol']);
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
        $verificar = $conn->prepare("SELECT COUNT(*) FROM rol WHERE nombre_rol = :nombre AND idrol != :id");
        $verificar->execute([":nombre" => $nombre_rol, ":id" => $idrol]);
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

        // Actualizar el rol
        $actualizar = $conn->prepare("UPDATE rol SET nombre_rol = :nombre WHERE idrol = :id");
        $resultado = $actualizar->execute([":nombre" => $nombre_rol, ":id" => $idrol]);

        if ($resultado) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Rol actualizado correctamente",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    window.location = "../index.php?mostrar=rol_form";
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "Información",
                    text: "No se realizaron cambios en el rol",
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
                text: "Error al actualizar el rol: ' . $e->getMessage() . '",
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