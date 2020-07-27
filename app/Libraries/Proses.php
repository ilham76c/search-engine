<?php namespace App\Libraries;

use App\Libraries\CacheManager;
use App\Libraries\Preprocessing;

class Proses {
    private static $instance = null;
    private static string $query = "";
    private static array $kata_sinonim = array();
    private static string $query_expansion = "";

    public function __construct()
    {
        $this->cacheManager = CacheManager::getInstance();
        $this->preprocessing = Preprocessing::getInstance();
    }

    public static function getInstance()
    {
        return (self::$instance == null) ? self::$instance = new Proses() : self::$instance;
    }

    public function getProsesQueryExpansion() : string
    {
        return $this->createTableProsesQueryExpansion();
    }

    public function getProsesPembobotanQuery() : string
    {
        return $this->createTableProsesPembobotanQuery();
    }

    public function getProsesPengambilanDokumen() : string
    {
        return $this->createTableProsesPengambilanDokumen();
    }

    public function getProsesPreprocessingQuery() : string 
    {
        return $this->createTableProsesPreprocessingQuery();
    }

    public function getProsesPerangkinganDokumen() : string 
    {
        return $this->createTableProsesPerangkingan();
    }

    private function createTableProsesQueryExpansion() : string
    {        
        try {
            $data = $this->cacheManager->getCache('proses_query_expansion');
            $rowspan = count($data['kata_sinonim']);
            $hasil_KataSinonim = "";
        
            $idx = 0;        
            foreach ($data['kata_sinonim'] as $key => $value) {            
                //$hasil_QE = ($idx == 0) ? "<td class='align-middle' rowspan='".$rowspan."'>".$data['query_expansion']."</td>" : "";
                if ($idx == 0) {
                    $hasil_KataSinonim .= "
                    <tr>
                        <td class='align-middle' rowspan='".$rowspan."'>".$data['query']."</td>                        
                        <td class='align-middle'>".$key."</td>
                        <td class='align-middle'>".preg_replace('/\s/',', ',$value)."</td>
                        <td class='align-middle' rowspan='".$rowspan."'>".$data['query_expansion']."</td>
                    </td>
                    ";
                }
                else {    
                    $hasil_KataSinonim .= 
                    "
                    <tr>
                        <td class='align-middle'>".$key."</td>
                        <td class='align-middle'>".preg_replace('/\s/',', ',$value)."</td>                        
                    </tr>
                    ";
                }
                $idx++;
            }
            return 
                "
                <table class='table table-bordered table-hover text-center'> 
                    <thead class='thead-dark'>
                        <tr>
                            <th>Query</th>
                            <th>Kata</th>
                            <th>Sinonim</th>                        
                            <th>Hasil query expansion</th>
                        </tr>
                    </thead>
                    <tbody>                
                        {$hasil_KataSinonim}            
                    </tbody>
                </table>
                "
            ;
        }
        catch (\Exception $e) {            
            if (is_null($data)) {
                die("Cache 'proses query' telah dihapus");
            }else {
                echo "Caught exception: 
                <br>&emsp;File : {$e->getFile()}
                <br>&emsp;Line : {$e->getLine()}
                <br>&emsp;Message : {$e->getMessage()}";                                                   
                die();
            }            
        }
    }

    private function createTableProsesPembobotanQuery() : string
    {
        $data = $this->cacheManager->getCache('proses_pembobotan_query');
        $rowspan = count($data['bobot_query']);

        $bobot_query = "";
        $idx = 0; 
        foreach ($data['bobot_query'] as $key => $value) {
            $paVek_query = ($idx == 0) ? "<td class='align-middle' rowspan='".$rowspan."'>".$data['paVek_query']."</td>" : "";
            $bobot_query .= "
            <tr>
                <td class='align-middle'>".$key."</td>
                <td class='align-middle'>".$value[0]."</td>
                <td class='align-middle'>".$value[1]."</td>
                <td class='align-middle'>".$value[2]."</td>
                {$paVek_query}
            </tr>
            ";
            $idx++;
        }
        return "
            <table class='table table-bordered table-hover text-center'>
                <thead class='thead-dark'>
                    <tr>
                        <th>Term</th>
                        <th>TF</th>
                        <th>IDF</th>
                        <th>TF-IDF</th>
                        <th>Panjang Vektor</th>
                    </tr>
                </thead>
                <tbody>
                    {$bobot_query}
                </tbody>
            </table>";
    }

    private function createTableProsesPengambilanDokumen() : string
    {
        $data = $this->cacheManager->getCache('proses_pengambilan_dokumen');
        $hasil_dokumenTerm = "";
        $no = 1;
        foreach ($data['pengambilan_dokumen'] as $key => $value) {
            $idx = 0;            
            foreach ($value as $term => $tfidf) {
                if ($idx == 0) {
                    $hasil_DokumenTerm .= "
                        <tr>
                            <td class='align-middle' rowspan='".(count($value))."'>".$no."</td>
                            <td class='align-middle' rowspan='".(count($value))."'>".$key."</td>
                            <td class='align-middle'>".$term."</td>
                            <td class='align-middle'>".$tfidf."</td>
                        </tr>
                    ";
                    $no++;
                }
                else {
                    $hasil_DokumenTerm .= "
                        <tr>
                            <td class='align-middle'>".$term."</td>
                            <td class='align-middle'>".$tfidf."</td>
                        </tr>
                    ";
                }
                $idx++;                
            }                                
        }
        return "
        <table class='table table-hover table-bordered'>
            <thead class='thead-dark'>
                <tr>
                    <th>No</th>
                    <th>Dokumen</th>
                    <th>Term</th>
                    <th>TF-IDF</th>
                </tr>
            </thead>
            <tbody>
                {$hasil_DokumenTerm}           
            </tbody>
        </table>
        ";
    }

    private function createTableProsesPerangkingan() : string 
    {
        $data = $this->cacheManager->getCache('hasil_perangkingan');
        $hasil_DokumenRangking = "";
        $no = 1;
        
        foreach ($data['dokumen_rangking'] as $key => $value) {
            $hasil_DokumenRangking .= "
            <tr>
                <td class='align-middle'>".$no."</td>
                <td class='align-middle'>".$key."</td>
                <td class='align-middle'>".$value."</td>
            </tr>
            ";
            $no++;
        }

        return "
        <table class='table table-hover table-bordered'>
            <thead class='thead-dark text-center'>
                <tr>
                    <th>No</th>
                    <th>Dokumen</th>
                    <th>Rangking</th>
                </tr>
            </thead>
            <tbody>
                {$hasil_DokumenRangking}
            </tbody>
        </table> 
        ";
    }

    private function createTableProsesPreprocessingQuery() : string 
    {
        $data = $this->cacheManager->getCache('proses_query_expansion');
        $sebelum = $this->preprocessing->tokenizing($data['query_expansion']);                
        $hasil = "";

        foreach (array_keys($sebelum) as $value) {
            $sesudah = $this->preprocessing->stemming($this->preprocessing->stopwordRemoval($this->preprocessing->caseFolding($value)));
            $hasil .= "
            <tr>
                <td class='align-middle'>".$value."</td> 
                <td class='align-middle'>".$sesudah."</td> 
            </tr>
            ";
        }
        return "
        <table class='table table-hover table-bordered text-center'>                    
            <thead class='thead-dark'>
                <tr>
                    <th colspan='2'>Preprocessing</th>                
                </tr>
                <tr>
                    <th>Sebelum</th>
                    <th>Sesudah</th>
                </tr>
            </thead>
            <tbody>        
                {$hasil}
            </tbody>
        </table>
        ";
    }
}
