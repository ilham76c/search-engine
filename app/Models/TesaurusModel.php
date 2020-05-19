<?php namespace App\Models;

use CodeIgniter\Model;

class TesaurusModel extends Model 
{
    protected $table = 'tbl_tesaurus';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['kata', 'gugus_kata'];

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
            self::$instance = new TesaurusModel();
        }
        return self::$instance;
    }
}