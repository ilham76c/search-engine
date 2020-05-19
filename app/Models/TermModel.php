<?php namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model 
{
    /**
     * variable untuk konfigurasi database
     */
    protected $table = 'tbl_term';
    protected $primaryKey = 'term';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['term', 'df', 'idf'];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'term' => 'is_unique[tbl_term.term]'
    ];
    protected $validationMessages = [
        'term' => [
            'is_unique' => 'term sudah ada pada tbl_term'
        ]
    ];
    protected $skipValidation = false;

    /**
     * variable biasa
     */
    private static $instance = null;

    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new TermModel();
        }
        return self::$instance;
    }
}