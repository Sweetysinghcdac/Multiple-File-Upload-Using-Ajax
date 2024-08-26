<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Multiple File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .uploaded-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .uploaded-images img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }


        .image-container {
            position: relative;
            cursor: pointer;
            display: inline-block;
            width: 150px;  
            height: 150px; 
            margin: 10px;  
        }

        .image-container img {
            width: 100%;  
            height: 100%; 
            object-fit: cover;
            border-radius: 10px; 
        }

        .image-actions {
            position: absolute;
            top: 10px; 
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 10; 
        }

        .image-container:hover .image-actions {
            display: flex;
            justify-content: space-between;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            border-radius: 10px; 
            padding: 5px; 
        }


    </style>
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mt-5">Laravel Multiple File Upload</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="my-3">
                <label for="images" class="form-label">Select Images</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <div class="mt-4">
            <h4 class="mt-4">Uploaded Images</h4>
            <div id="uploadedImages" class="uploaded-images">
                @foreach($images as $image)
                <!-- <div class="image-container" data-id="{{ $image->id }}" style="position: relative;">
                    <img src="{{ $image->file_path }}" class="img-thumbnail" alt="{{ $image->file_name }}">
                    <div class="image-actions" style="display: none; position: absolute; top: 10px; right: 10px;">
                        <button class="btn btn-warning edit-image" data-id="{{ $image->id }}">Edit</button>
                        <button class="btn btn-danger delete-image" data-id="{{ $image->id }}">Delete</button>
                    </div>
                </div> -->
                <div class="col-md-3">
                    <div class="image-container">
                        <img src="{{ $image->file_path }}" alt="{{ $image->file_name }}" class="img-thumbnail">
                        <div class="image-actions">
                            <button class="btn btn-warning edit-image" data-id="{{ $image->id }}">Edit</button>
                            <button class="btn btn-danger delete-image" data-id="{{ $image->id }}">Delete</button>
                        </div>
                    </div>
                </div>


                @endforeach
            </div>
        </div>


       <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <img id="imageToEdit" src="" alt="Picture">
                        </div>
                        <div class="mt-2">
                            <button id="rotateLeft" class="btn btn-secondary">Rotate Left</button>
                            <button id="rotateRight" class="btn btn-secondary">Rotate Right</button>
                        </div>
                        <form id="editForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="croppedImage" id="croppedImage">
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <script>

        $(document).ready(function() {
            // Handle image upload
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                if ($('#images')[0].files.length === 0) {
                    alert('Please select at least one image to upload.');
                    return;
                }

                $.ajax({
                    url: "{{ route('images.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response.success);
                        $('#uploadedImages').load(window.location.href + ' #uploadedImages');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Failed to upload images.');
                    }
                });
            });

            // Show edit/delete buttons on image click
            $(document).on('click', '.image-container img', function() {
                $(this).siblings('.image-actions').toggle(); // Toggle the visibility of the buttons
            });

            // Handle delete image
            $(document).on('click', '.delete-image', function(e) {
                e.stopPropagation(); // Prevent the click from triggering the image click event
                let id = $(this).data('id');

                if (confirm('Are you sure you want to delete this image?')) {
                    $.ajax({
                        url: "/images/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            alert(response.success);
                            $('#uploadedImages').load(window.location.href + ' #uploadedImages');
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            alert('Failed to delete the image.');
                        }
                    });
                }
            });



        });

        $(document).ready(function() {
            let cropper;

            // Handle edit image
            $(document).on('click', '.edit-image', function(e) {
                e.stopPropagation();
                let id = $(this).data('id');
                console.log("Edit button clicked. Image ID:", id); 

                $('#editForm').data('id', id);

                $.ajax({
                    url: "/images/" + id + "/edit",
                    type: 'GET',
                    success: function(data) {
                        console.log("AJAX success. Data returned:", data);
                        if (data.file_path) {
                            $('#imageToEdit').attr('src', data.file_path); 
                            $('#editModal').modal('show');

                            $('#editModal').on('shown.bs.modal', function() {
                                if (cropper) {
                                    cropper.destroy();
                                }
                                cropper = new Cropper(document.getElementById('imageToEdit'), {
                                    aspectRatio: NaN, // Allow free aspect ratio
                                    viewMode: 1, // Make sure the image fits the container
                                    autoCropArea: 1, // Attempt to auto-crop the entire image
                                    responsive: true,
                                    zoomOnWheel: true,
                                    movable: true,
                                    rotatable: true,
                                    scalable: true,
                                    cropBoxResizable: true,
                                    ready: function () {
                                        console.log("Cropper ready event triggered.");

                                        const imageData = cropper.getImageData();
                                        cropper.setCropBoxData({
                                            left: 0,
                                            top: 0,
                                            width: imageData.naturalWidth,
                                            height: imageData.naturalHeight
                                        });

                                        // Check if the canvas can be created
                                        let canvas = cropper.getCroppedCanvas();
                                        if (!canvas) {
                                            console.error("Failed to get cropped canvas.");
                                            alert('Failed to initialize cropper correctly.');
                                        }
                                    }
                                });
                            }).on('hidden.bs.modal', function() {
                                if (cropper) {
                                    cropper.destroy();
                                    cropper = null;
                                }
                            });
                        } else {
                            console.error("Image path not found in the response");
                            alert("Error: Image path not found in the response.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Failed to fetch image data:", xhr.responseText); 
                        alert('Failed to fetch the image.');
                    }
                });
            });

            // Rotate left
            $(document).on('click', '#rotateLeft', function() {
                if (cropper) {
                    cropper.rotate(-90);
                }
            });

            // Rotate right
            $(document).on('click', '#rotateRight', function() {
                if (cropper) {
                    cropper.rotate(90);
                }
            });

            // Handle update image
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                console.log("Submitting form for image ID:", id); 

                // Ensure cropper is initialized
                if (!cropper) {
                    console.error("Cropper is not initialized.");
                    alert('Failed to update the image. Cropper is not initialized.');
                    return;
                }

                let canvas = cropper.getCroppedCanvas();
                if (!canvas) {
                    console.error("Failed to get cropped canvas.");
                    alert('Failed to update the image. Unable to get cropped canvas.');
                    return;
                }

                canvas.toBlob(function(blob) {
                    if (!blob) {
                        console.error("Failed to convert canvas to blob.");
                        alert('Failed to update the image. Unable to convert canvas to blob.');
                        return;
                    }

                    let formData = new FormData();
                    formData.append('croppedImage', blob); 
                    formData.append('_method', 'PUT');      
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: "/images/" + id,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log("Image update successful:", response);  
                            alert(response.success);
                            $('#uploadedImages').load(window.location.href + ' #uploadedImages');
                            $('#editModal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.log("Failed to update image:", xhr.responseText);  
                            alert('Failed to update the image.');
                        }
                    });
                });
            });
        });

    </script>
</body>
</html>
