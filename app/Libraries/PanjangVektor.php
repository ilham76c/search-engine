<?php namespace App\Libraries;

use App\Models\DokumenModel;
use App\Models\BobotModel;

class PanjangVektor {
    private static $instance = null;

    public function __construct() 
    {
        $this->dokumenModel = DokumenModel::getInstance();
        $this->bobotModel = BobotModel::getInstance();
    }

    public static function getInstance()
    {        
		return (self::$instance == null) ? self::$instance = new PanjangVektor() : self::$instance;
    }

    public function panjangVektor() 
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
		catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
		finally {
			unset($url, $panjang_vektor, $bobot);
		}
	}    
	
	public function getPanjangVektor(string $url) : float
	{
		return $this->dokumenModel->where('url', $url)->findColumn('panjang_vektor')[0];
	}
}