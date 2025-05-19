<?php
require_once "./conexion/coneccion.php";
require_once "./php/validadorsql.php";

if (!isset($_GET['id'])) {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "ID de rol no especificado",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            window.location = "index.php?mostrar=rol_form";
        });
    </script>';
    exit();
}

$id = limpiar_cadena($_GET['id']);
$start = new Conexion();
$conn = $start->conexionbd();

try {
    $stmt = $conn->prepare("SELECT * FROM rol WHERE idrol = :id");
    $stmt->execute([":id" => $id]);
    $rol = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rol) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Rol no encontrado",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                window.location = "index.php?mostrar=rol_form";
            });
        </script>';
        exit();
    }
} catch (PDOException $e) {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Error al obtener el rol: ' . $e->getMessage() . '",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            window.location = "index.php?mostrar=rol_form";
        });
    </script>';
    exit();
}
?>

<div class="container pb-6 pt-6 notification is-primary" style="background: #6F9AB0;">
    <div class="mb-6 mt-6">
        <h1 class="title center_mio">Actualizar Rol</h1>
        <hr class="division1">
    </div>

    <div class="form-rest mb-6 mt-6"></div>
    <form id="formActualizarRol" action="./php/rol_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
        <input type="hidden" name="idrol" value="<?php echo htmlspecialchars($rol['idrol']); ?>">
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>NOMBRE DEL ROL (*)</label>
                    <input class="input" type="text" name="rol_nombre" 
                           value="<?php echo htmlspecialchars($rol['nombre_rol']); ?>" 
                           required 
                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,50}" 
                           maxlength="50"
                           placeholder="Ingrese el nombre del rol">
                    <p class="help">El nombre debe tener entre 1 y 50 caracteres</p>
                </div>
            </div>
        </div>

        <br>
        <p class="has-text-centered">
            <a href="./index.php?mostrar=rol_form" class="button is-danger is-rounded">Cancelar</a>
            <button type="submit" class="button is-info is-rounded">Confirmar Actualización</button>
        </p>
    </form>
</div>

<script>
document.getElementById('formActualizarRol').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas actualizar este rol?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script> 