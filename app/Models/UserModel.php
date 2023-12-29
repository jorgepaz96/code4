<?php namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model 
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'json';
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }
    public function get_all_data()
    {
        $query = $this->db->query('select * from ' . $this->table);
        return $query->getResult();
    }
}
