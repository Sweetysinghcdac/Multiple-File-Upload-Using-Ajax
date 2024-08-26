<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Multiple File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Laravel Multiple File Upload Using Ajax</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="images" class="form-label">Select Images</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <div class="mt-4">
            <h4>Uploaded Images</h4>
            <div id="uploadedImages" class="uploaded-images">
                @foreach($images as $image)
                <div>
                    <img src="{{ $image->file_path }}" class="img-thumbnail" alt="{{ $image->file_name }}">
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <script>
        // $('#uploadForm').on('submit', function(e) {
        //     e.preventDefault();

        //     let formData = new FormData(this);

        //     // Check if any file is selected
        //     if ($('#images')[0].files.length === 0) {
        //         alert('Please select at least one image to upload.');
        //         return;
        //     }

        //     $.ajax({
        //         url: "{{ route('image.upload') }}",
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             alert(response.success);
        //             $('#uploadedImages').load(window.location.href + ' #uploadedImages');
        //         },
        //         error: function(xhr, status, error) {
        //             console.log(xhr.responseText);
        //             alert('Please select valid frontend error images to upload.');
        //         }
        //     });
        // });

        $('#uploadForm').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    // Check if any file is selected
    if ($('#images')[0].files.length === 0) {
        alert('Please select at least one image to upload.');
        return;
    }

    $.ajax({
        url: "{{ route('image.upload') }}",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.success);
            $('#uploadedImages').load(window.location.href + ' #uploadedImages');
        },
        error: function(xhr, status, error) {
            console.log("Error status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
            alert('Please select valid images to upload.');
        }
    });
});

    </script>
</body>
</html>
