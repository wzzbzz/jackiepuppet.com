<?php

include 'functions.php';
#this will process the upload if there is one in the post

if (isset($_FILES['file'])) {

    set_time_limit(0);

    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));
    $file_base = explode('.', $file_name);
    $file_base = strtolower($file_base[0]);

    // also allow videos, pdfs, and images
    $allowed = array('mp3', 'wav', 'ogg', 'mp4', 'avi', 'mov', 'pdf', 'jpg', 'jpeg', 'png', 'gif');
    
    if (in_array($file_ext, $allowed)) {
        if ($file_error === 0) {
            // 2 gig limit
            if ($file_size <= 2147483648) {
                $file_name_new = $file_base."_".time().".".$file_ext;
                $file_destination = 'sonic-twist-radio/dump/' . $file['name'];

                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $message = "Great. Three cheers for you. You uploaded a file.  Big deal. Now what?";
                }
            }
        }
    }
}
else{
    $message = "So you wanna upload a file?  Go ahead. See if I care.";
}

?>
<!-- boilerplate HTML page with upload button -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>

    <!-- include bootstrap for mobile responsiveness -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- include style.css from root folder -->
    <link rel="stylesheet" href="style.css">
    <style>
        body, .container {
            background-color: #000;
            color: #fff;
        }
        /* make the upload button look nice on mobile*/
        input[type=file] {
            background-color: #000;
            color: #fff;
            border: 1px solid #fff;
            padding: 10px;
            margin: 10px;
        }
        /* make the upload button look nice on desktop*/
        input[type=submit] {
            background-color: #000;
            color: #fff;
            border: 1px solid #fff;
            padding: 10px;
            margin: 10px;
        }
        /* size the fonts and button sizes for mobile and for desktop using media queries */
        @media (min-width: 768px) {
            input[type=file] {
                font-size: 2em;
                padding: 20px;
                margin: 20px;
            }
            /* the upload button too please */
            input[type=submit] {
                font-size: 2em;
                padding: 20px;
                margin: 20px;
            }
        }

        /* size the message better */
        #message {
            font-size: 2em;
            padding: 20px;
            margin: 20px;
        }

        /* make the button flash when clicked */
        input[type=submit]:active {
            background-color: #fff;
            color: #000;
        }

    </style>


</head>
<body class="trs80">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- if there is a message, display it -->
                <div id="message">
                    <?php if (isset($message)) { ?>
                        <p><?php echo $message; ?></p>
                    <?php } ?>
                </div>
            </div>
        <div class="row">
            <div class="col-md-12">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input type="submit" value="Upload">
            </div>
        </div>
    </div>
</form>
</body>
</html>
