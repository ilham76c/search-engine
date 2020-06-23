<?php namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model 
{    
    /**
     * variable untuk konfigurasi database
     */
    protected $table = 'tbl_hasil';
    protected $primaryKey = 'url';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['url', 'title', 'description', 'rangking','kategori'];

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
            self::$instance = new HasilModel();
        }
        return self::$instance;
    }
}