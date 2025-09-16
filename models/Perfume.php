<?php
class Perfume {
    private $conn;
    private $table_name = "perfumes";

    // Propiedades
    public $id;
    public $nombre;
    public $marca_id;
    public $categoria_id;
    public $descripcion;
    public $precio;
    public $precio_oferta;
    public $stock;
    public $stock_minimo;
    public $volumen;
    public $tipo;
    public $popularidad;
    public $imagen_principal;
    public $imagenes_adicionales;
    public $notas_olfativas;
    public $genero;
    public $temporada;
    public $activo;
    public $destacado;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(){
        // implementar insert si lo necesitas
    }

    public function update($id){
        // implementar update si lo necesitas
    }

    public function delete($id){
        // implementar delete si lo necesitas
    }
}
