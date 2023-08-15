<?php

namespace Model;

class Vendedor extends ActiveRecord {
    protected static $tabla = "vendedores";
    protected static $columnasDB = ["id", "nombre", "apellido", "imagen", "telefono", "email"];

    public $id;
    public $nombre;
    public $apellido;
    public $imagen;
    public $telefono;
    public $email;
    
    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->imagen = $args["imagen"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->email = $args["email"] ?? "";
    }

    public function validar() {
        if (!$this->nombre) {
            self::$errores[] = "El nombre es Obligatorio";
        }

        if (!$this->apellido) {
            self::$errores[] = "El apellido es Obligatorio";
        }

        if (!$this->telefono) {
            self::$errores[] = "El teléfono es Obligatorio";
        }

        if (!$this->email) {
            self::$errores[] = "El email es Obligatorio";
        }

        if (!$this->imagen) {
            self::$errores[] = "La imagen es Obligatoria";
        }

        if (!preg_match("/[0-9]{10}/", $this->telefono)) {
            self::$errores[] = "Formato de teléfono no válido";
        }

        if (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $this->email)) {
            self::$errores[] = "Formato de correo no válido";
        }

        return self::$errores;
    }
}