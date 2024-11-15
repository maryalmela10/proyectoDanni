<?php
session_start();
require_once('bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['id']) && isset($_FILES['foto'])) {
        $id_usuario = $_SESSION['id'];
        $directorio = 'perfiles/'; 
        $archivo = $_FILES['foto'];
        
        // Validar el tipo de archivo
        $tipoArchivo = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $tipoArchivoPermitido = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($tipoArchivo), $tipoArchivoPermitido)) {
            // Genera un nombre único 
            $nombreArchivo = uniqid() . '.' . $tipoArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $directorio . $nombreArchivo)) {
                actualizarFotoPerfil($id_usuario, $directorio . $nombreArchivo);
                // Redirigir al perfil 
                if($_SESSION["logueado"]==0){
                     header("Location: perfil.php");
                exit();
                } else{
                    header("Location: perfilTecnico.php");
                exit();
                }
               
            } else {
                echo "Error al mover el archivo.";
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    }
}