<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Blog;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index(Router $router) {
        $propiedades = Propiedad::get(3);
        $blogs = Blog::get(2);
        $inicio = true;

        $router->render("paginas/index",[
            "propiedades"=>$propiedades,
            "blogs"=>$blogs,
            "inicio"=>$inicio
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render("paginas/nosotros");
    }

    public static function propiedades(Router $router) {
        $propiedades = Propiedad::all();

        $router->render("paginas/propiedades",[
            "propiedades"=>$propiedades,
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarORedireccionar("/propiedades");

        $propiedad = Propiedad::find($id);

        $router->render("paginas/propiedad",[
            "propiedad"=>$propiedad,
        ]);
    }

    public static function blog(Router $router) {
        $blogs = Blog::all();

        $router->render("paginas/blog",[
            "blogs"=>$blogs
        ]);
    }

    public static function entrada(Router $router) {
        $id = validarORedireccionar("/blog");
        $blog = Blog::find($id);

        $router->render("paginas/entrada",[
            "blog"=>$blog
        ]);
    }

    public static function contacto(Router $router) {
        $mensaje = false;
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $respuestas = $_POST;

            // Crear una instancia de PHPmailer
            $mail = new PHPmailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV["EMAIL_HOST"];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV["EMAIL_PORT"];
            $mail->Username = $_ENV["EMAIL_USER"];
            $mail->Password = $_ENV["EMAIL_PASS"];
            $mail->SMTPSecure = 'tls';

            // Configurar el contenido del email
            $mail->setFrom("admin@bienesraices.com");
            $mail->addAddress("admin@bienesraices.com", "BienesRaices.com");
            $mail->Subject = "Tienes un Nuevo Mensaje";

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            // Definir el contenido
            $contenido = "<html>";
            $contenido .= "<p>Tienes un nuevo mensaje</p>";
            $contenido .= "<p>Nombre: " . $respuestas["nombre"] . "</p>";
            $contenido .= "<p>Mensaje: " . $respuestas["mensaje"] . "</p>";

            // Enviar de forma condicional algunos campos
            if ($respuestas["contacto"] === "telefono") {
                $contenido .= "<p>Eligió ser contactado por Telefóno:</p>";
                $contenido .= "<p>Teléfono: " . $respuestas["telefono"] . "</p>";
                $contenido .= "<p>Fecha Contacto: " . $respuestas["fecha"] . "</p>";
                $contenido .= "<p>Hora Contacto: " . $respuestas["hora"] . "</p>";
            } else {
                $contenido .= "<p>Eligió ser contactado por Email:</p>";
                $contenido .= "<p>Email: " . $respuestas["email"] . "</p>";
            }

            $contenido .= "<p>Vende o Compra: " . $respuestas["tipo"] . "</p>";
            $contenido .= "<p>Precio o Presupuesto: $" . $respuestas["precio"] . "</p>";
            $contenido .= "</html>";

            $mail->Body = $contenido;
            $mail->AltBody = "Esto es texto alternativo sin HTML";

            // Enviar el email
            if ($mail->send()) {
                $mensaje = "Mensaje enviado correctamente";
            } else {
                $mensaje = "Hubo un error al enviar el mensaje";
            }
        }

        $router->render("paginas/contacto", [
            "mensaje"=>$mensaje
        ]);
    }
}