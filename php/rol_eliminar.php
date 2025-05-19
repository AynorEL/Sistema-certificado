<?php
require_once "../conexion/coneccion.php";
require_once "./validadorsql.php";

if(isset($_GET['idrol_del'])){
    $idrol = limpiar_cadena($_GET['idrol_del']);
    
    $start = new Conexion();
    $conn = $start->conexionbd();
    
    try {
        // Verificar si el rol está en uso
        $verificar = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE rol_id = :id");
        $verificar->execute([":id" => $idrol]);
        $existe = $verificar->fetchColumn();
        
        if($existe > 0){
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se puede eliminar el rol porque está en uso por usuarios",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    window.location = "../index.php?mostrar=rol_form";
                });
            </script>';
            exit();
        }
        
        // Eliminar el rol
        $eliminar = $conn->prepare("DELETE FROM rol WHERE idrol = :id");
        $resultado = $eliminar->execute([":id" => $idrol]);
        
        if($resultado){
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Rol eliminado correctamente",
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
                    text: "Error al eliminar el rol",
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
                text: "Error al eliminar el rol: ' . $e->getMessage() . '",
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
            text: "ID de rol no especificado",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            window.location = "../index.php?mostrar=rol_form";
        });
    </script>';
}
?> 