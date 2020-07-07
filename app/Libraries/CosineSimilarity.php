<?php namespace App\Libraries;

use App\Libraries\PanjangVektor;

class CosineSimilarity {
    private static $instance = null;

    function __construct() 
    {
        $this->pavek = PanjangVektor::getInstance();       
    }

    public static function getInstance()
    {        
		return (self::$instance == null) ? self::$instance = new CosineSimilarity() : self::$instance;	
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
				$rangking[$url] = $sumsq / ($paVek_query * $this->pavek->getPanjangVektor($url));
			}		
			
			return $rangking;
		}
		catch (\Exception $e) {
			die("$e->getLine(); \n $e->getTrace(); \n $e->getMessage();");
		}
		finally {
			unset($bobot_dokumen, $sumsq_tfidf, $rangking);
		}
	}	
}