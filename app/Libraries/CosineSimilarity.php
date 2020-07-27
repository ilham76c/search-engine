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
			$rangking = array();	
			foreach ($dokumen as $url => $terms) {
				$sumsq_tfidf = 0;				
				foreach ($terms as $term => $tfidf) {
					$sumsq_tfidf += $query[$term] * $tfidf;					
				}
				$rangking[$url] = $sumsq_tfidf  / ($paVek_query * $this->pavek->getPanjangVektor($url));
			}						
			
			return $rangking;
		}
		catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
		finally {
			unset($sumsq_tfidf, $rangking);
		}
	}	
}