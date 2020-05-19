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

    public function getRelevanDokumen($query)
	{		                        
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
    
    public function result($dokumen)
	{	        		
		$dokumen_relevan = $this->dokumenModel->builder()->select(['url','title','description'])->whereIn('url', array_keys($dokumen))->get();		
        $this->hasilModel->builder()->emptyTable('tbl_hasil');
                
		foreach ($dokumen_relevan->getResult() as $row) {
			$this->hasilModel->builder()->ignore(true)->insert([
				'url' => $row->url,
				'title' => $row->title,
				'description' => $row->description,
				'rangking' => $dokumen[$row->url]
			]);
        }
        
		return $dokumen_relevan;		
	}
}