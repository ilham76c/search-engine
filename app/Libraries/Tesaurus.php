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

    public function get(string $id) : object
    {
        return $this->tesaurusModel->find($id);   
    }

    public function getAll() : array
    {
        return $this->tesaurusModel->findAll();
    }

    public function insert(string $kata, string $gugus_kata) : bool
    {
        try {
            $result = $this->tesaurusModel->builder()->ignore(true)->insert(
                [
                    'kata' => trim($kata),
                    'gugus_kata' => $this->formatString($gugus_kata)
                ]            
            );
            return $this->booleanResult($result);
        }
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            unset($result);
        }
    }

    public function update(string $id, string $kata, string $gugus_kata) : bool
    {
        try {
            $result = $this->tesaurusModel->where('id', $id)->set(
                [
                    'kata' => trim($kata),
                    'gugus_kata' => $this->formatString($gugus_kata)
                ]
            )->update();
            return $this->booleanResult($result);
        }
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            unset($result);
        }
    }

    public function delete(string $id) : bool
    {
        try {
            $result = $this->tesaurusModel->where('id', $id)->delete(false);                                
            return $this->booleanResult($result->connID->affected_rows);
        }
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            unset($result);
        }
    }

    private function booleanResult(int $result) : bool
    {
        return ($result !== 0) ? true : false;
    }

    private function formatString(string $gugus_kata) : string
    {        
        return implode(',',array_map(function($val){return trim($val);},explode(',',$gugus_kata)));
    }
}