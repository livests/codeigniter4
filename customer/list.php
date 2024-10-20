<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/underscore.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jsv.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jsonform/jsonform.js'); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<h1>Customer List</h1>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal">Add Customer</button>

<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addCustomerForm">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mobile_number">Mobile:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="image_url">Image:</label>
                <input type="file" class="form-control-file" id="image_url" name="image_url" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<hr>


<h2>Customer List</h2>
<table id="customerTable" class="display">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php if (!empty($customers)) : ?>
            <?php foreach ($customers as $customer) : ?>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo $customer['name']; ?></td>
                    <td><?php echo $customer['email']; ?></td>
                    <td><?php echo $customer['mobile_number']; ?></td>
                    <td><?php echo $customer['address']; ?></td>
                    <td><img src="<?php echo base_url(!empty($customer['image_url']) ? $customer['image_url'] : 'default.jpeg'); ?>" alt="<?php echo esc($customer['name']); ?>" width="50"></td>
                    <td>
                        <a href="<?php echo site_url('customers/edit/'.$customer['id']); ?>">Edit</a> | 
                        <a href="<?php echo site_url('customers/delete/'.$customer['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No customers found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
$('#addCustomerForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this); 

    $.ajax({
        url: "<?php echo site_url('customers/add'); ?>",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);
            alert('Customer added successfully!');
            $('#addCustomerModal').modal('hide');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            alert('Failed to add customer. Please try again.');
        }
    });
});


function loadCustomerData() {
    $.ajax({
        url: "<?php echo site_url('customers/list'); ?>",  
        method: 'GET',
        success: function(data) {

            $('#customerTable tbody').html(data); 
            $('#customerTable').DataTable(); 
        },
        error: function() {
            console.error('Unable to load customer data');
        }
    });
}
</script>

</body>
</html>
