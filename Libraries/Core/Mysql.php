<?php

class Mysql extends Conexion
{
    private $conexion;
    private $strQuery;
    private $arrValues;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // Insertar un registro en la base de datos
    public function insert(string $query, array $arrvalues)
    {
        try {
            $this->strQuery = $query;
            $this->arrValues = $arrvalues;

            $insert = $this->conexion->prepare($this->strQuery);
            $resInsert = $insert->execute($this->arrValues);
            $idInsert = $this->conexion->lastInsertId();
            $insert->closeCursor();
            return $idInsert;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            return $response;
        }
    }

    // Devolver todos los registros
    public function select_all(string $query)
    {
        try {
            $this->strQuery = $query;

            $execute = $this->conexion->query($this->strQuery);
            $request = $execute->fetchall(PDO::FETCH_ASSOC); //ARRAY
            $execute->closeCursor();
            return $request;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            return $response;
        }
    }

    // Devolver 1 solo registros -- WHERE
    public function select(string $query, array $arrvalues)
    {
        try {
            $this->strQuery = $query;
            $this->arrValues = $arrvalues;

            $query = $this->conexion->prepare($this->strQuery);
            $query->execute($this->arrValues);
            $request = $query->fetch(PDO::FETCH_ASSOC); //ARRAY
            $query->closeCursor();
            return $request;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            return $response;
        }
    }

    // Actualizar registros en la base de datos
    public function update(string $query, array $arrvalues)
    {
        try {
            $this->strQuery = $query;
            $this->arrValues = $arrvalues;

            $update = $this->conexion->prepare($this->strQuery);
            $resUdpate = $update->execute($this->arrValues);
            $update->closeCursor();
            return $resUdpate;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            return $response;
        }
    }

    // Eliminar registros en la base de datos
    public function delete(string $query, array $arrvalues) {
        try {
            $this->strQuery = $query;
            $this->arrValues = $arrvalues;

            $delete = $this->conexion->prepare($this->strQuery);
            $del = $delete->execute($this->arrValues); 
            return $del;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            return $response;
        }
    }




}
