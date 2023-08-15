<?php

namespace Model;

class Blog extends ActiveRecord{
    protected static $tabla = "blogs";
    protected static $columnasDB = ["id", "titulo", "autor", "fecha", "imagen", "descripcion", "texto"];

    public $id;
    public $titulo;
    public $autor;
    public $fecha;
    public $imagen;
    public $descripcion;
    public $texto;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->titulo = $args["titulo"] ?? "";
        $this->autor = $args["autor"] ?? "";
        $this->fecha = date("Y/m/d");
        $this->imagen = $args["imagen"] ?? "";
        $this->descripcion = $args["descripcion"] ?? "";
        $this->texto = $args["texto"] ?? "";
    }

    public function validar() {
        if (!$this->titulo) {
            self::$errores[] = "El titulo es Obligatorio";
        }

        if (!$this->autor) {
            self::$errores[] = "El autor es Obligatorio";
        }

        if (!$this->imagen) {
            self::$errores[] = "La imagen es Obligatoria";
        }

        if (!$this->descripcion) {
            self::$errores[] = "La descripcion es Obligatoria";
        }

        if (!$this->texto) {
            self::$errores[] = "El texto es Obligatorio";
        }

        return self::$errores;
    }
}