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
		$this->tf(); 
		$this->idf(); 
		$this->tfidf(); 
		$this->panjangVektor();
    }

    private function tf() 
	{	
		try {
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
				
				foreach ($tf as $term => $frekuensi) {								
					if (empty($this->termModel->find($term))) {
						$this->termModel->insert(['term' => $term]);		
					}					
					$this->bobotModel->builder()->ignore(true)->insert(['term' => $term, 'url' => $value['url'], 'tf' => 1 + log10($frekuensi)]);
				}
			}
		}
		catch (\Exception $e) {
			echo $e->getLine()."\n";
			print_r($e->getTrace())."\n";
			echo $e->getMessage()."\n";
            die();
		}
		finally {
			unset($dokumen, $tf);
		}
	}

	private function idf() 
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
			echo $e->getLine()."\n";
			print_r($e->getTrace())."\n";
			echo $e->getMessage()."\n";
            die();
		}
		finally {
			unset($terms, $n, $df, $idf);
		}
	}
	
	private function tfidf() 
	{		
		try {
			$bobot = $this->bobotModel->findAll();

			foreach ($bobot as $value) {
				$term = $this->termModel->find($value['term']);
				$tfidf = $value['tf'] * $term['idf'];			
				$this->bobotModel->where(['term' =>  $value['term'], 'url' => $value['url']])->set(['tf_idf' => $tfidf])->update();
			}
		}
		catch (\Exception $e) {
			echo $e->getLine()."\n";
			print_r($e->getTrace())."\n";
			echo $e->getMessage()."\n";
            die();
		}
		finally {
			unset($bobot, $term, $tfidf);
		}
	}
	
	private function panjangVektor() 
	{
		try {
			$url = $this->dokumenModel->findColumn('url');
			foreach ($url as $value) {			
				$panjang_vektor = 0;
				$bobot = $this->bobotModel->where('url', $value)->findColumn('tf_idf');
				foreach ($bobot as $tfidf) {
					$panjang_vektor += $tfidf**2;				
				}
				$this->dokumenModel->update($value, ['panjang_vektor' => sqrt($panjang_vektor)]);
			}
		}
		catch (\Exception $e) {
			echo $e->getLine()."\n";
			print_r($e->getTrace())."\n";
			echo $e->getMessage()."\n";
            die();
		}
		finally {
			unset($url, $panjang_vektor, $bobot);
		}
	}    
	
	private function getPanjangVektor(string $url) : float
	{
		return $this->dokumenModel->where('url', $url)->findColumn('panjang_vektor')[0];
	}

    public function cosineSimilarity(array $query, array $dokumen, float $paVek_query) : array
    {		
		try {
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
				$rangking[$url] = $sumsq / ($paVek_query * $this->getPanjangVektor($url));
			}		
			
			return $rangking;
		}
		catch (\Exception $e) {
			echo $e->getLine()."\n";
			print_r($e->getTrace())."\n";
			echo $e->getMessage()."\n";
            die();
		}
		finally {
			unset($bobot_dokumen, $sumsq_tfidf, $rangking);
		}
	}	
}