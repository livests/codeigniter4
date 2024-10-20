<?php
namespace App\Controllers\Customer;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\customer\CustomerModel;

class CustomerController extends ResourceController {
    protected $modelName = "App\Models\customer\CustomerModel"; 
    protected $format = "json";

    public function index(): string {
        $customerModel = model('App\Models\customer\CustomerModel');
        $customers = $customerModel->findAll();
        return view('customer/list', ['customers' => $customers]);
    }

    public function editCustomer($customer_id) {
        $customerModel = model('App\Models\customer\CustomerModel');
        $customer = $customerModel->find($customer_id);
        
        if (!$customer) {
            return redirect()->to('/customers/list')->with('error', 'Customer not found.');
        }
        return view('customer/customer_form', ['customer' => $customer]);
    }

    public function addCustomer() {

        $model = new CustomerModel();
        $validationRules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|is_unique[customer_info.email]',
            'mobile_number' => 'required|min_length[8]|max_length[15]',
            'address' => 'required|min_length[5]|max_length[100]',
            'image_url' => 'uploaded[image_url]|is_image[image_url]|mime_in[image_url,image/jpg,image/jpeg,image/png]'
        ];
    
        if (!$this->validate($validationRules)) {
            return $this->fail($this->validator->getErrors());
        }
    

        $imageFile = $this->request->getFile('image_url');
        $customerImageURL= null;
        if ($imageFile && $imageFile->isvalid()) {
            $newFileName =  $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/customers', $newFileName); 
            $customerImageURL = 'uploads/customers/' . $newFileName; 
        } else {
            return $this->fail(['message' => 'Image upload failed.']);
        }
    
        $data = $this->request->getPost();
        $name = $data['name'];
        $email = $data['email'];
        $mobile_number = $data['mobile_number'];
        $address = $data['address'];
        $image_url = $customerImageURL;
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'address' => $data['address'],
            'image_url' => $customerImageURL

        ];
    

        if ($model->insert($insertData)) {
            return $this->respondCreated(['message' => 'Customer added successfully']);
        } else {
            return $this->fail(['message' => 'Failed to add customer']);
        }
    }
    public function updateCustomer($customer_id) {
        $model = new CustomerModel();

        $customer = $model->find($customer_id);
        
        if (!$customer) {
            return redirect()->to('/customers/list')->with('error', 'Customer not found.');
        }

        $data = $this->request->getPost();
        $email = $data['email'];
        
        if ($email !== $customer['email']) {
            $validationRules = [
                'name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email|is_unique[customer_info.email,id,' . $customer_id . ']', 
                'mobile_number' => 'required|min_length[8]|max_length[15]',
                'address' => 'required|min_length[5]|max_length[100]',
                'image_url' => 'if_exist|uploaded[image_url]|is_image[image_url]|mime_in[image_url,image/jpg,image/jpeg,image/png]'
            ];
        } else {

            $validationRules = [
                'name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email', 
                'mobile_number' => 'required|min_length[8]|max_length[15]',
                'address' => 'required|min_length[5]|max_length[100]',
                'image_url' => 'if_exist|uploaded[image_url]|is_image[image_url]|mime_in[image_url,image/jpg,image/jpeg,image/png]'
            ];
        }
        
        if (!$this->validate($validationRules)) {
            log_message('debug', 'Validation Errors: ' . print_r($this->validator->getErrors(), true));
            return $this->fail($this->validator->getErrors());
        }
    
        $imageFile = $this->request->getFile('image_url');
        $customerImageURL = $customer['image_url']; 
        

        if ($imageFile && $imageFile->isValid()) {
            $oldFilePath = FCPATH . 'uploads/customers/' . $customerImageURL;
        
            if (file_exists($oldFilePath)) {
                if (!is_dir($oldFilePath)) {

                    if (unlink($oldFilePath)) {
                        //log_message('debug', 'Successfully deleted: ' . $oldFilePath);
                    } else {
                        log_message('error', 'Failed to delete file: ' . $oldFilePath);
                    }
                } else {
                    log_message('error', 'Target is a directory, not a file: ' . $oldFilePath);
                }
            } else {
                log_message('warning', 'File not found for deletion: ' . $oldFilePath);
            }
        

            $newFileName = $imageFile->getRandomName();
            if ($imageFile->move(FCPATH . 'uploads/customers', $newFileName)) {
                //log_message('debug', 'Successfully uploaded: ' . FCPATH . 'uploads/customers/' . $newFileName);
                
                $customerImageURL = 'uploads/customers/' . $newFileName; 
            } else {
                log_message('error', 'Failed to move uploaded file');
            }
        } else {
            log_message('warning', 'No valid image file uploaded');
        }
    

        $updateData = [
            'name' => $data['name'],
            'email' => $email, 
            'mobile_number' => $data['mobile_number'],
            'address' => $data['address'],
            'image_url' => $customerImageURL 
        ];
    
        //log_message('debug', 'Update Data: ' . print_r($updateData, true));
    
        if ($model->update($customer_id, $updateData)) {
            //log_message('debug', 'Update Success: ' . print_r($updateData, true));
            return redirect()->to('/customers/list')->with('success', 'Customer updated successfully.');
        } else {
            log_message('debug', 'Update Failed: ' . print_r($model->errors(), true));
            return redirect()->to('/customers/list')->with('error', 'Failed to update customer.');
        }
    }
    
    
    public function deleteCustomer($customer_id) {
        $customerModel = model('App\Models\customer\CustomerModel');
        if ($customerModel->find($customer_id)) {
            $customerModel->delete($customer_id);
            return redirect()->to('/customers/list')->with('success', 'Customer deleted successfully');
        } else {
            return redirect()->to('/customers/list')->with('error', 'Customer not found');
        }
    }
}