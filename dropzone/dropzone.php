<!-- app/Views/dropzone/dropzone.php -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<style>
    #my-dropzone {
        border: 2px dashed #007bff; /* Dashed border style */
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        margin: 20px 0;
        color: #007bff;
        transition: background-color 0.3s;
    }

    #my-dropzone:hover {
        background-color: #f0f8ff; /* Light blue background on hover */
    }
</style>

<div class="container">
    <div class="jumbotron">
        <h1>File Upload using Dropzone Example</h1>
        <p>CodeIgniter 4.</p>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form action="<?php echo base_url('dropzone/upload'); ?>" 
                  class="dropzone" 
                  id="my-dropzone">
                <div class="dz-message" data-dz-message>
                    <h3>Drop files here or click to upload</h3>
                    <p>Only images (jpg, png, gif) up to 2 MB are allowed.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    Dropzone.options.myDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.gif", // Accepted file types
        success: function(file, response) {
            var result = JSON.parse(response);
            alert(result.message);
        },
        error: function(file, response) {
            alert("Error: " + response.message);
        }
    };
</script>