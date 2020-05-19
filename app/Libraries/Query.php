<?php namespace App\Libraries;

use App\Models\TesaurusModel;
use App\Models\TermModel;
use App\Libraries\Preprocessing;

class Query {
    private static $instance = null;

    function __construct()
    {
        $this->preprocessing = Preprocessing::getInstance();
        $this->tesaurusModel = TesaurusModel::getInstance();		
        $this->termModel = TermModel::getInstance();	
    }
    
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Query();            
        }
        return self::$instance;
    }

    public function queryExpansion($query)
    {
        $term = $this->preprocessing->tokenizing($query);        
        $expansion = array();
                
        array_walk (
            array_keys($term),
            function($key) use (&$expansion) { 
                $expansion[$key] = implode(' ', explode(',', $this->tesaurusModel->where('kata', $key)->findColumn('gugus_kata')[0]));
            }
        );
                
        return $query.' '.implode(' ', $expansion);
    }

    public function bobotQuery($query)
	{
        $tf_query = 
		$this->preprocessing->tokenizing(
			$this->preprocessing->stopwordRemoval(
				$this->preprocessing->caseFolding($query)
			)
        );
        
		$tfidf_query = array();
		array_walk(
			$tf_query,
			function($tf, $term) use (&$tfidf_query) {
                 $tfidf = $tf * ($this->termModel->where('term', $term)->findColumn('idf'))[0];
                 if ($tfidf != 0) {
                    $tfidf_query[$term] = $tfidf;
                 }                 
			}
        );		        		
		
        $paVek_query = array_map(
            function($tfidf) {
                return $tfidf ** 2;
            },
            $tfidf_query
        );
               
		return array($tfidf_query,sqrt(array_sum($paVek_query)));
	}
}