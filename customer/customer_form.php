<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/underscore.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jsv.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jsonform/jsonform.js'); ?>"></script>
    
</head>
<body>

<h1>Edit Customer</h1>

<form id="customerForm" method="post" action="<?php echo site_url('customers/update/' . $customer['id']); ?>" enctype="multipart/form-data">
    <div id="form"></div>

    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

    <input type="hidden" name="id" value="<?php echo isset($customer) ? $customer['id'] : ''; ?>"> 

    <input type="submit" value="Save">
</form>


<div id="success" style="display: none;">Customer updated successfully!</div>
<div id="error" style="display: none;">There was an error processing your request.</div>
<div id="loading" style="display: none;">Loading...</div>

<script>
const customerData = <?php echo isset($customer) ? json_encode($customer) : 'null'; ?>;

$(document).ready(function() {

    var schema = {
        type: 'object',
        properties: {
            name: { type: 'string', title: 'Name', required: true },
            email: { type: 'string', title: 'Email', required: true },
            mobile_number: { type: 'string', title: 'Mobile', required: true },
            address: { type: 'string', title: 'Address', required: true },
            image_url: { type: 'string', title: 'Profile Image', required: false, format: "data-url" }
        }
    };


    var form = [
        {
            key: 'name',
            placeholder: 'Enter customer name'
        },
        {
            key: 'email',
            placeholder: 'Enter customer email',
            readonly: false
        },
        {
            key: 'mobile_number',
            placeholder: 'Enter customer mobile number'
        },
        {
            key: 'address',
            placeholder: 'Enter customer address'
        },
        {
            key: 'image_url',
            type: 'file',
            label: 'Profile Image'
        }
    ];

    $('#form').jsonForm({
        schema: schema,
        form: form,
        value: customerData || {},
        onSubmit: function (errors, values) {
            console.log('Form Values:', values);
            if (errors) {
                console.log('Validation errors', errors);
                $('#error').show(); 
                return;
            }

            $('#loading').show(); 
            const form = document.getElementById('customerForm');
            const formData = new FormData(form);
            
            for (const key in values) {
                if (values.hasOwnProperty(key) && key !== 'image_url') {
                    formData.append(key, values[key]);
                }
            }

            const imageFile = document.querySelector('[name="image_url"]').files[0];
            if (imageFile) {
                formData.append('image_url', imageFile);
            }

            $.ajax({
            url: '/customers/update/' + customerId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>' 
            },
            success: function (response) {
            },
            error: function (xhr, status, error) {
            }
        });

        }
    });
});
</script>
</body>
</html>