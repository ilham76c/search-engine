<?php namespace App\Libraries;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Sastrawi\Tokenizer\TokenizerFactory;

class Preprocessing {
    private static $instance = null;

    public function __construct()
    {
        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
        $stopWordRemoverFactory = new StopWordRemoverFactory();
        $this->stopword = $stopWordRemoverFactory->createStopWordRemover();
        $tokenizerFactory  = new TokenizerFactory();
        $this->tokenizer = $tokenizerFactory->createDefaultTokenizer();
    }
    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new Preprocessing();
        }
        return self::$instance;
    }
    public function caseFolding(string $sentence) : string
    {
        // convert string to lowercase
        return strtolower($sentence);
    }
    
    public function stemming(string $sentence) : string
    {

        // create stemmer
        // cukup dijalankan sekali saja, biasanya didaftarkan di service container
        // $stemmerFactory = new StemmerFactory();
        // $stemmer = $stemmerFactory->createStemmer();
        return $this->stemmer->stem($sentence);    
    }
    
    public function stopwordRemoval(string $sentence) : string
    {
        // create stopword remover
        // $stopWordRemoverFactory = new StopWordRemoverFactory();
        // $stopword = $stopWordRemoverFactory->createStopWordRemover();
        return $this->stopword->remove($sentence);        
    }
    
    public function tokenizing (string $sentence) : array
    {
        // create tokenizer
        // $tokenizerFactory  = new TokenizerFactory();
        // $tokenizer = $tokenizerFactory->createDefaultTokenizer();
        return array_count_values($this->tokenizerTambahan($this->tokenizer->tokenize($sentence)));
    }
    private function tokenizerTambahan(array $array_term) : array
    {
        // regex kata (kata-kata, kata, 20.000 -> 20000)
        //$data = array_map(function($value) { return preg_replace('/^[\W]+$|[.]|^\W*(-)\W*|\W*(-)\W*$/', null, $value); }, $array);
        // menghapus null value pada array
        return array_filter(
            array_map(
                function($value) 
                { 
                    return preg_replace('/^[\W]+$|[.]|^\W*(-)\W*|\W*(-)\W*$/', null, $value); 
                }, 
                $array_term
            )
        );        
    }
}