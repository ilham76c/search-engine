<?php namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model 
{    
    /**
     * variable untuk konfigurasi database
     */
    protected $table = 'tbl_dokumen';
    protected $primaryKey = 'url';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['url', 'title', 'description', 'panjang_vektor'];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * variable biasa
     */
    private static $instance = null;

    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new DokumenModel();
        }
        return self::$instance;
    }
}