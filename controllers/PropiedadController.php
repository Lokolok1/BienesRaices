<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Model\Blog;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {
    public static function index(Router $router) {
        $propiedades = Propiedad::all();

        $vendedores = Vendedor::all();

        $blogs = Blog::all();

        // Muestra mensaje condicional
        $resultado = $_GET["resultado"] ?? null;

        $router->render("propiedades/admin", [
            "propiedades" => $propiedades,
            "resultado" => $resultado,
            "vendedores" => $vendedores,
            "blogs" => $blogs
        ]);
    }

    public static function crear(Router $router) {
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
            
        // Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            /** CREAR NUEVA INSTANCIA **/
            $propiedad = new Propiedad($_POST);

            /** SUBIDA DE ARCHIVOS **/

            // Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // Setear la imagen
            // Realiza un resize a la imagen con Intervention
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            /** VALIDAR **/
            $errores = $propiedad->validar();

            if (empty($errores)) {
                // Crear carpeta
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guardar imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                // Guardar en la base de datos
                $propiedad->guardar();
            }
        }

        $router->render("propiedades/crear", [
            "propiedad" => $propiedad,
            "vendedores" => $vendedores,
            "errores" => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/admin");

        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();

        // Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        // Ejecutar el codigo despues de enviar forumulario
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Asignar los atributos
            $propiedad->sincronizar($_POST);

            // Validar la informacoón
            $errores = $propiedad->validar();

            // Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // Metodo post para actualizar
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // Revisar que el arreglo de errores este vacio
            if (empty($errores)) {
                if ($_FILES["imagen"]["tmp_name"]) {
                    // Almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render("/propiedades/actualizar", [
            "propiedad" => $propiedad,
            "vendedores" => $vendedores,
            "errores" => $errores
        ]);
    }

    public static function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if ($id) {
                $tipo = $_POST["tipo"];
    
                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }
}