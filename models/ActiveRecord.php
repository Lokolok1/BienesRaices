<?php

namespace Model;

class ActiveRecord {
    // Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = "";

    // Errores
    protected static $errores = [];

    // Definir la conexión a la base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {
        if (!is_null($this->id)) {
            // Actualizar
            $this->actualizar();
        } else {
            // Crear un registro
            $this->crear();
        }
    }

    public function actualizar() {
        // Sanitizar los datos
        $datos = $this->sanitizarDatos();

        $valores = [];
        foreach ($datos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(", ", $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $query .= " LIMIT 1 ";
        
        $resultado = self::$db->query($query);
        
        if ($resultado) {
            // Redireccionar al usuario
            header("Location:/admin?resultado=2");
        }
    }

    public function crear() {
        // Sanitizar los datos
        $datos = $this->sanitizarDatos();

        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($datos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($datos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        // Mensaje exito / error
        if ($resultado) {
            // Redireccionar al usuario
            header("Location:/admin?resultado=1");
        }
    }

    // Eliminar un registro
    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();

            // Redireccionar al usuario
            header("Location:/admin?resultado=3");
        }
    }

    // Identificar y unir los datos de la base de datos
    public function datos() {
        $datos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === "id") continue; // continue hace que pase al siguiente elemento, lo ignora, no se almacena el id
            $datos[$columna] = $this->$columna;
        }
        return $datos;
    }

    public function sanitizarDatos() {
        $datos = $this->datos();
        $sanitizado = [];

        foreach ($datos as $key => $value) {
            // key obiene el valor de llaves y value el valor de los datos
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    // Subida de archivos
    public function setImagen($imagen) {
        // Elimina la imagen previa
        if (!is_null($this->id)) {
            // Comprobar si existe el archivo
            $this->borrarImagen();
        }

        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Eliminar archivo
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
            
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    // Lista todos los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla; // static:: sirve como un self, salvo que este si se hereda

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // Obtiene determinado numero de regisros
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad; // static:: sirve como un self, salvo que este si se hereda

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // Busca un registro por su ID
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}