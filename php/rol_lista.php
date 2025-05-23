<?php
/* Inicializa variables para la paginación y tabla */
$inicio=($pagina>0) ? (($pagina*$registros)-$registros) : 0;
$tabla="";

/* Define las consultas SQL según si hay búsqueda o no */
if(isset($busqueda)&&$busqueda!=""){
    $consulta_datos="select * from rol where nombre_rol 
    like '%$busqueda%'
    ORDER BY idrol DESC LIMIT $inicio,$registros";

    $consulta_total="select count(idrol) from rol  where
    nombre_rol like '%$busqueda%'";
}else{
    $consulta_datos="select * from rol 
    ORDER BY idrol DESC LIMIT $inicio,$registros";

    $consulta_total="select count(idrol) from rol";
}

/* Establece conexión y obtiene datos */
$start = new Conexion();
$conn = $start->Conexionbd();
$datos=$conn->query($consulta_datos);
$datos=$datos->fetchAll();
$total=$conn->query($consulta_total);
$total=(int) $total->fetchColumn();
$npaginas=ceil($total/$registros);

/* Construye la estructura HTML de la tabla */
$tabla.='
<div class="table-container ">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth ">
        <thead class="notification is-primary">
            <tr class="has-text-centered">
                <th>#</th>
                <th>Nombre</th>
                <th class="has-text-centered" colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
';

/* Genera las filas de la tabla con los datos */
if($total>=1 && $pagina<=$npaginas){
    $contador=$inicio+1;
    $pag_inicio=$inicio+1;

    foreach($datos as $rows){
        $tabla.='
            <tr class="has-text-centered">
                <td>'.$contador.'</td>
                <td>'.$rows['nombre_rol'].'</td>
                <td>
                    <a href="index.php?mostrar=rol_editar&id='.$rows['idrol'].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a onclick="mialertaeliminar(event);" href="" class="button is-danger is-rounded is-small" >Eliminar</a>
                </td>
            </tr>
        ';
        $contador++;
    }
    $pag_final=$contador-1;
}else{  
    /* Muestra mensajes según el caso */
    if($total>=1){
        $tabla.='
            <tr class="has-text-centered">
                <td colspan="11">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    }else{
        $tabla.='
            <tr class="has-text-centered ">
                <td colspan="11" class="notification is-primary">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}

/* Cierra la tabla y muestra información de paginación */
$tabla.='
</tbody>
</table>
</div>
';

if($total>=1 && $pagina<=$npaginas){
    $tabla.='
    <p class="has-text-right">Mostrando los Roles <strong>"'.$pag_inicio.'"</strong> al <strong>"'.$pag_final.'"</strong> de un <strong>total de '.$total.'"</strong></p>
    ';
}

$conn=null;
echo $tabla;

/* Muestra el paginador */
if($total>=1 && $pagina<=$npaginas){
    echo paginador_tablas($pagina, $npaginas,$url,7);
}
?>