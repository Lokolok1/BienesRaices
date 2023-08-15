<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class VendedorController {
    public static function crear(Router $router) {
        $vendedor = new Vendedor;
        $errores = Vendedor::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST);
            
            // Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
    
            // Setear la imagen
            // Realiza un resize a la imagen con Intervention
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 600);
                $vendedor->setImagen($nombreImagen);
            }
    
            /** VALIDAR **/
            $errores = $vendedor->validar();
    
            if (empty($errores)) {
                // Crear carpeta
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
    
                // Guardar imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);
    
                // Guardar en la base de datos
                $vendedor->guardar();
            }
        }

        $router->render("vendedores/crear", [
            "errores"=>$errores,
            "vendedor"=>$vendedor
        ]);
    }

    public static function actualizar(Router $router) {
        $errores = Vendedor::getErrores();
        $id = validarORedireccionar("/admin");

        // Obntener datos del vendedor a actualizar
        $vendedor = Vendedor::find($id);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sincronizar objeto en memoria con lo que el usuario escribio
            $vendedor->sincronizar($_POST);
    
            // Validación
            $errores = $vendedor->validar();
    
            // Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
    
            // Subida de archivos
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 600);
                $vendedor->setImagen($nombreImagen);
            }
    
            // Revisar que el arreglo de errores este vacio
            if (empty($errores)) {
                if ($_FILES["imagen"]["tmp_name"]) {
                    // Almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $vendedor->guardar();
            }
        }

        $router->render("vendedores/actualizar", [
            "errores"=>$errores,
            "vendedor"=>$vendedor
        ]);
    }

    public static function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $tipo = $_POST["tipo"];

                if (validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }
}