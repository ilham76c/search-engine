<?php namespace App\Libraries;

use App\Models\BobotModel;
use App\Models\DokumenModel;
use App\Models\HasilModel;
use App\Libraries\CacheManager;

class Dokumen {
    private static $instance = null;

    function __construct() {
        $this->bobotModel = BobotModel::getInstance();
        $this->dokumenModel = DokumenModel::getInstance();
        $this->hasilModel = HasilModel::getInstance();
        $this->cacheManager = CacheManager::getInstance();
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
            		
            array_walk (
                $dokumen->getResult(),
                function($row) use (&$tfidf_dokumen) {
                    $tfidf_dokumen[$row->url][$row->term] = $row->tf_idf;
                }
            );
            
            return $tfidf_dokumen;
        }
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            $data = array(
                "pengambilan_dokumen" => $tfidf_dokumen
            );
            $this->cacheManager->setCache("proses_pengambilan_dokumen", $data);
            unset($dokumen, $tfidf_dokumen, $data);
        }
    }

    public function resetResult()
    {
        $this->hasilModel->builder()->emptyTable('tbl_hasil');
    }
    
    public function result(array $dokumen)
	{	        		
        try {
            $dokumen_relevan = $this->dokumenModel->builder()->select(['url','title','description','kategori'])->whereIn('url', array_keys($dokumen))->get();		        
            $this->resetResult();
                    
            foreach ($dokumen_relevan->getResult() as $row) {
                $this->hasilModel->builder()->ignore(true)->insert([
                    'url' => $row->url,
                    'title' => $row->title,
                    'description' => $row->description,
                    'kategori' => $row->kategori,
                    'rangking' => $dokumen[$row->url]
                ]);
            }		
        }
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            arsort($dokumen);
            $data = array(
                'dokumen_rangking' => $dokumen
            );
            $this->cacheManager->setCache('hasil_perangkingan', $data);
            unset($dokumen_relevan);
        }
	}
}