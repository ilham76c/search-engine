<?php namespace App\Libraries;

use App\Models\DokumenModel;
use App\Models\TermModel;
use App\Models\BobotModel;
use App\Libraries\Preprocessing;

class Pembobotan {
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
        if (self::$instance == null) {
            self::$instance = new Pembobotan();
        }
        return self::$instance;
    }

    public function hitungBobot()
    {
        self::tf(); self::idf(); self::tfidf(); self::panjangVektor();
    }

    private function tf() 
	{	
		$dokumen = $this->dokumenModel->findAll();

		foreach ($dokumen as $value) {			
			$tf = 
			$this->preprocessing->tokenizing(
				$this->preprocessing->stemming(
					$this->preprocessing->stopwordRemoval(
						$this->preprocessing->caseFolding($value['title'].' '.$value['description'])
					)
				)
			);

			// foreach ($tf as $term => $frekuensi) {				
			// 	if (empty($this->termModel->find($term))) {
			// 		$this->termModel->insert(['term' => $term]);		
			// 	}									
			// 	if (empty($this->bobotModel->where(['term' => $term, 'url' => $value['url']])->findAll())) {
			// 		$this->bobotModel->insert(['term' => $term, 'url' => $value['url'], 'tf' => $frekuensi]);																
			// 	}
			// }
			foreach ($tf as $term => $frekuensi) {				
				// $this->termModel->builder()->ignore(true)->insert(['term' => $term]);
				if (empty($this->termModel->find($term))) {
					$this->termModel->insert(['term' => $term]);		
				}						
				$this->bobotModel->builder()->ignore(true)->insert(['term' => $term, 'url' => $value['url'], 'tf' => $frekuensi]);
			}
		}
	}

	private function idf() 
	{		
		$terms = $this->termModel->findColumn('term');
		$n = count($this->dokumenModel->findAll());
		
		foreach ($terms as $term) {			
			$df = count($this->bobotModel->where('term',$term)->findAll());
			$idf = (log10($n/$df))+1;			
			$this->termModel->update($term, ['df' => $df, 'idf' => $idf]);
		}				
	}
	
	private function tfidf() 
	{		
		$bobot = $this->bobotModel->findAll();

		foreach ($bobot as $value) {
			$term = $this->termModel->find($value['term']);
			$tfidf = $value['tf'] * $term['idf'];			
			$this->bobotModel->where(['term' =>  $value['term'], 'url' => $value['url']])->set(['tf_idf' => $tfidf])->update();
		}
	}
	
	private function panjangVektor() 
	{		
		$panjang_vektor = 0;
		$url = $this->dokumenModel->findColumn('url');
		foreach ($url as $value) {			
			$bobot = $this->bobotModel->where('url', $value)->findColumn('tf_idf');
			foreach ($bobot as $tfidf) {
				$panjang_vektor += $tfidf**2;				
			}
			$this->dokumenModel->update($value, ['panjang_vektor' => sqrt($panjang_vektor)]);
		}
	}    
	
	private function getPanjangVektor($url)
	{
		return $this->dokumenModel->where('url', $url)->findColumn('panjang_vektor')[0];
	}

    public function cosineSimilarity($query, $dokumen, $paVek_query)
    {
		$bobot_dokumen = array();
		foreach ($dokumen as $url => $terms) {
			$sumsq_tfidf = 0;
			foreach ($terms as $term => $tfidf) {
				$sumsq_tfidf += $query[$term] * $tfidf;
			}
			$bobot_dokumen[$url] = $sumsq_tfidf;
		}
		$rangking = array();
		foreach ($bobot_dokumen as $url => $sumsq) {						
			$rangking[$url] = $sumsq / ($paVek_query * self::getPanjangVektor($url));
		}		
		
		return $rangking;
	}
}