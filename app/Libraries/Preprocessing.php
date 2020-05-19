<?php namespace App\Libraries;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Sastrawi\Tokenizer\TokenizerFactory;

class Preprocessing {
    private static $instance = null;

    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new Preprocessing();
        }
        return self::$instance;
    }
    public function caseFolding($sentence) 
    {
        // convert string to lowercase
        return strtolower($sentence);
    }
    
    public function stemming($sentence) 
    {
        // create stemmer
        // cukup dijalankan sekali saja, biasanya didaftarkan di service container
        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();        
        return $stemmer->stem($sentence);    
    }
    
    public function stopwordRemoval($sentence)
    {
        // create stopword remover
        $stopWordRemoverFactory = new StopWordRemoverFactory();
        $stopword = $stopWordRemoverFactory->createStopWordRemover();
        return $stopword->remove($sentence);        
    }
    
    public function tokenizing ($sentence) 
    {
        // create tokenizer
        $tokenizerFactory  = new TokenizerFactory();
        $tokenizer = $tokenizerFactory->createDefaultTokenizer();
        return array_count_values($this->tokenizerTambahan($tokenizer->tokenize($sentence)));
    }
    private function tokenizerTambahan($array) 
    {
        // regex kata (kata-kata, kata, 20.000 -> 20000)
        $data = array_map(function($value) { return preg_replace('/^[\W]+$|[.]|^\W*(-)\W*|\W*(-)\W*$/', null, $value); }, $array);
        // menghapus null value pada array
        return array_filter($data);        
    }
}