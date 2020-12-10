<?php namespace App\Libraries;

use App\Models\TesaurusModel;
use App\Models\TermModel;
use App\Libraries\Preprocessing;
use App\Libraries\CacheManager;

class Query {
    private static $instance = null;    

    function __construct()
    {
        $this->preprocessing = Preprocessing::getInstance();
        $this->tesaurusModel = TesaurusModel::getInstance();		
        $this->termModel = TermModel::getInstance();	
        $this->cacheManager = CacheManager::getInstance();
    }
    
    public static function getInstance()
    {
        return (self::$instance == null) ? self::$instance = new Query() : self::$instance;
    }

    public function queryExpansion(string $query, $isQE) : string
    {        
        try {   
            $term = $this->preprocessing->tokenizing($query);                 
            if ($isQE == 'true') {                
                array_walk (
                    array_keys($term),
                    function($key) use (&$expansion) { 
                        $expansion[$key] = implode(' ', explode(',', $this->tesaurusModel->where('kata', $key)->findColumn('gugus_kata')[0]));
                    }
                );
                return $query . ' ' . implode(' ', $expansion);  
            }else {
                $expansion = array_map(function(){return '';},$term);
            }
            return $query;
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
                "query" => $query,
                "kata_sinonim" =>  $expansion,
                "query_expansion" => $query.' '.implode(' ', $expansion)
            );
            $this->cacheManager->setCache("proses_query_expansion", $data);
            unset($term, $expansion, $data);
        }
    }

    public function bobotQuery(string $query) : array
	{
        try {
            $tf_query = $this->preprocessing->tokenizing(
                $this->preprocessing->stemming(
                    $this->preprocessing->stopwordRemoval(
                        $this->preprocessing->caseFolding($query)
                    )
                )
            );
            
            $tfidf_query = array();
            array_walk(
                $tf_query,
                function($tf, $term) use (&$tfidf_query, &$cache_pembobotan_query) {
                    $idf = ($this->termModel->where('term', $term)->findColumn('idf'))[0];
                    $tfidf = (log10($tf) + 1) * (empty($idf) ? 0 : $idf);
                    if ($tfidf != 0) {
                        $tfidf_query[$term] = $tfidf;
                    } 

                    $cache_pembobotan_query[$term] = array(
                        $tf, 
                        empty($idf) ? 0 : $idf,
                        $tfidf
                    );
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
        catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
        finally {
            $data = array(
                'bobot_query' => $cache_pembobotan_query,
                'paVek_query' => sqrt(array_sum($paVek_query))
            );
            $this->cacheManager->setCache("proses_pembobotan_query", $data);
            unset($idf, $tfidf, $tf_query, $tfidf_query, $paVek_query, $cache_pembobotan_query);
        }
    }    
}