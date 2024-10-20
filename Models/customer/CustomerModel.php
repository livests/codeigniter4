<?php
namespace App\Models\customer;

use CodeIgniter\Model;

class CustomerModel extends Model {
    protected $table = 'customer_info';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // If you plan on using soft deletes, change this to true
    
    protected $allowedFields = ['name', 'email', 'mobile_number', 'address', 'image_url'];

    // Automatically handle created and updated timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email',
        'mobile_number' => 'required|min_length[8]|max_length[15]',
        'address' => 'required|min_length[5]|max_length[100]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'The email has already been taken.',
        ],
        'mobile_number' => [
            'min_length' => 'Mobile number must be at least 8 digits.',
            'max_length' => 'Mobile number cannot exceed 15 digits.',
        ],
    ];

    protected $skipValidation = false;

    // Callbacks (Optional: you can add custom logic before/after insert, update, etc.)
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

}
