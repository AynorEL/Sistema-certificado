<?php
require_once "./conexion/coneccion.php";
require_once "./php/validadorsql.php";

if(isset($_GET['id'])){
    $id = limpiar_cadena($_GET['id']);
    
    $start = new Conexion();
    $conn = $start->conexionbd();
    
    $stmt = $conn->prepare("SELECT * FROM rol WHERE idrol = :id");
    $stmt->execute([":id" => $id]);
    $rol = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$rol){
        echo "Rol no encontrado";
        exit();
    }
} else {
    echo "Error: ID no especificado";
    exit();
}
?>

<div class="container pb-6 pt-6 notification is-primary" style="background: #6F9AB0;">
    <div class="mb-6 mt-6">
        <h1 class="title center_mio">Actualizar Rol</h1>
        <hr class="division1">
    </div>

    <div class="form-rest mb-6 mt-6"></div>
    <form action="./php/rol_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
        <input type="hidden" name="idrol" value="<?php echo $rol['idrol']; ?>">
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>NOMBRE (*)</label>
                    <input class="input" type="text" name="rol_nombre" value="<?php echo $rol['nombre_rol']; ?>" required>
                </div>
            </div>
            <div class="column">
            </div>
        </div>

        <br>
        <p class="has-text-centered">
            <a href="./index.php?mostrar=rol_form" class="button is-danger is-rounded">Cancelar</a>
            <button type="submit" class="button is-info is-rounded">Actualizar</button>
        </p>
    </form>
</div> 