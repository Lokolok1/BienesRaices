<?php

namespace Controllers;
use Model\Blog;
use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image;

class BlogController {
    public static function crear(Router $router) {
        $blog = new Blog;
        $errores = Blog::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Creamos una nueva instancia
            $blog = new Blog($_POST);

            // Generamos el nombre de la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // Seteamos la imagen y le ajustamos el tamaño
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,600);
                $blog->setImagen($nombreImagen);
            }

            // Validamos
            $errores = $blog->validar();

            if (empty($errores)) {
                // Creamos la carpeta de imagenes
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guardar la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                // Guardamos en la base de datos
                $blog->guardar();
            }
        }

        $router->render("blogs/crear", [
            "blog" => $blog,
            "errores" => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/admin");

        $blog = Blog::find($id);
        $errores = Blog::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sincronizamos la instancia
            $blog->sincronizar($_POST);

            // Validamos los datos
            $errores = $blog->validar();

            // Generamos el nombre de la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // Seteamos la imagen y le ajustamos el tamaño
            if ($_FILES["imagen"]["tmp_name"]) {
                $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,600);
                $blog->setImagen($nombreImagen);
            }

            if (empty($errores)) {
                if ($_FILES["imagen"]["tmp_name"]) {
                    // Almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                // Guardamos en la base de datos
                $blog->guardar();
            }
        }

        $router->render("blogs/actualizar", [
            "blog" => $blog,
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
                    $blog = Blog::find($id);
                    $blog->eliminar();
                }
            }
        }
    }
}