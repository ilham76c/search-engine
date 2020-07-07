<?php namespace App\Libraries;

use App\Models\DokumenModel;
use App\Models\TermModel;
use App\Models\BobotModel;
use App\Libraries\Preprocessing;

class TfIdf {
    private static $instance = null;

    function __construct() 
    {		
        $this->preprocessing = Preprocessing::getInstance();		
		$this->dokumenModel = DokumenModel::getInstance();
		$this->termModel = TermModel::getInstance();
		$this->bobotModel = BobotModel::getInstance();		
	}
    
    public static function getInstance()
    {        
		return (self::$instance == null) ? self::$instance = new TfIdf() : self::$instance;
    }

	public function tf() 
	{	
		try {
			$dokumen = $this->dokumenModel->findAll();
			
			foreach ($dokumen as $value) {			
				$tf = $this->preprocessing->tokenizing(
					$this->preprocessing->stemming(
						$this->preprocessing->stopwordRemoval(
							$this->preprocessing->caseFolding(
								$value['title'].' '.$value['description']
							)
						)
					)
				);				

				foreach ($tf as $term => $frekuensi) {								
					if (empty($this->termModel->find($term))) {
						$this->termModel->insert(['term' => $term]);		
					}					
					$this->bobotModel->builder()->ignore(true)->insert(
						['term' => $term, 'url' => $value['url'], 'tf' => 1 + log10($frekuensi)]
					);
				}
			}
		}
		catch (\Exception $e) {			
            die("$e->getLine(); \n $e->getTrace(); \n $e->getMessage();");
		}
		finally {
			unset($dokumen, $tf);
		}
	}

	public function idf() 
	{	
		try {	
			$terms = $this->termModel->findColumn('term');
			$n = count($this->dokumenModel->findAll());			

			foreach ($terms as $term) {			
				$df = count($this->bobotModel->where('term',$term)->findAll());
				$idf = log10($n/$df) + 1;	
				$this->termModel->update($term, ['df' => $df, 'idf' => $idf]);
			}
		}
		catch (\Exception $e) {
			die("$e->getLine(); \n $e->getTrace(); \n $e->getMessage();");
		}
		finally {
			unset($terms, $n, $df, $idf);
		}
	}
	
	public function tfidf() 
	{		
		try {
			$bobot = $this->bobotModel->findAll();

			foreach ($bobot as $value) {
				$term = $this->termModel->find($value['term']);
				$tfidf = $value['tf'] * $term['idf'];			
				$this->bobotModel->where(
					['term' =>  $value['term'], 'url' => $value['url']])->set(['tf_idf' => $tfidf]
				)->update();
			}
		}
		catch (\Exception $e) {
			die("$e->getLine(); \n $e->getTrace(); \n $e->getMessage();");
		}
		finally {
			unset($bobot, $term, $tfidf);
		}
	}	
}