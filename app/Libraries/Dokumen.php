<?php namespace App\Libraries;

use App\Models\BobotModel;
use App\Models\DokumenModel;
use App\Models\HasilModel;


class Dokumen {
    private static $instance = null;

    function __construct() {
        $this->bobotModel = BobotModel::getInstance();
        $this->dokumenModel = DokumenModel::getInstance();
        $this->hasilModel = HasilModel::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Dokumen();
        }
        return self::$instance;
    }

    public function getRelevanDokumen(array $query) : array
	{		           
        try {
            $dokumen = $this->bobotModel->builder()->select(['term','url','tf_idf'])->whereIn('term', $query)->get();        
            $tfidf_dokumen = array();				
            array_walk (
                $dokumen->getResult(),
                function($row) use (&$tfidf_dokumen) {
                    $tfidf_dokumen[$row->url][$row->term] = $row->tf_idf;
                }
            );
            
            return $tfidf_dokumen;
        }
        catch (\Exception $e) {
            die($e->getMessage());
        }
        finally {
            unset($dokumen, $tfidf_dokumen);
        }
    }

    public function resetResult()
    {
        $this->hasilModel->builder()->emptyTable('tbl_hasil');
    }
    
    public function result(array $dokumen)
	{	        		
        try {
            $dokumen_relevan = $this->dokumenModel->builder()->select(['url','title','description'])->whereIn('url', array_keys($dokumen))->get();		        
            $this->resetResult();
                    
            foreach ($dokumen_relevan->getResult() as $row) {
                $this->hasilModel->builder()->ignore(true)->insert([
                    'url' => $row->url,
                    'title' => $row->title,
                    'description' => $row->description,
                    'rangking' => $dokumen[$row->url]
                ]);
            }		
        }
        catch (\Exception $e) {
            die($e->getMessage());
        }
        finally {
            unset($dokumen_relevan);
        }
	}
}