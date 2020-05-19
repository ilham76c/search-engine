<?php namespace App\Models;

use CodeIgniter\Model;

class BobotModel extends Model 
{
    /**
     * variable untuk konfigurasi database
     */        
    protected $table = 'tbl_bobot';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['term', 'url', 'tf', 'tf_idf'];

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
            self::$instance = new BobotModel();
        }
        return self::$instance;
    }
}