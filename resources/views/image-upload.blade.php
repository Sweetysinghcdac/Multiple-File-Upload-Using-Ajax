<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Multiple File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <div id="uploadedImages" class="row">
                @foreach($images as $image)
                    <div class="col-md-3">
                        <img src="{{ $image->file_path }}" class="img-thumbnail w-10 h-10" alt="{{ $image->file_name }}">
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
