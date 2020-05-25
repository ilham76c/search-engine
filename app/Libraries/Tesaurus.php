<?php namespace App\Libraries;

use App\Models\TesaurusModel;

class Tesaurus {
    private static $instance = null;

    public function __construct()
    {
        $this->tesaurusModel = TesaurusModel::getInstance();
    }

    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new Tesaurus();
        }
        return self::$instance;
    }

    public function get($id)
    {
        return $this->tesaurusModel->find($id);   
    }

    public function getAll()
    {
        return $this->tesaurusModel->findAll();
    }

    public function insert($kata, $gugus_kata)
    {
        $result = $this->tesaurusModel->builder()->ignore(true)->insert(
            [
                'kata' => trim($kata),
                'gugus_kata' => self::formatString($gugus_kata)
            ]            
        );
        return self::booleanResult($result);
    }

    public function update($id, $kata, $gugus_kata)
    {
        $result = $this->tesaurusModel->where('id', $id)->set(
            [
                'kata' => trim($kata),
                'gugus_kata' => self::formatString($gugus_kata)
            ]
        )->update();
        return self::booleanResult($result);
    }

    public function delete($id)
    {
        $result = $this->tesaurusModel->where('id', $id)->delete(false);                                
        return self::booleanResult($result->connID->affected_rows);
    }

    private function booleanResult($result) 
    {
        return ($result !== 0) ? true : false;
    }

    private function formatString($gugus_kata)
    {        
        return implode(',',array_map(function($val){return trim($val);},explode(',',$gugus_kata)));
    }
}