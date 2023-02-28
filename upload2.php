<html>
    <head>
        <title>Upload</title>
    </head>
    <body>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file">
            <input type="submit" name="submit" value="Upload">
        </form>
            <!-- Upload File -->
    <?php
    // Include the database configuration file
    include 'dbConfig.php';
    $statusMsg = '';

    // File upload path
    $targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {
    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = $db->prepare("INSERT INTO images (image_name, uploaded_on) VALUES (?, NOW())");
            $insert->bind_param("s", $fileName);
            if ($insert->execute()) {
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            } else {
                $statusMsg = "File upload failed, please try again.";
            }
            $insert->close();
        } else {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, and GIF files are allowed to upload.';
    }
} else {
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
echo $statusMsg;


    ?>

<?php
    // Include the database configuration file
    include 'dbConfig.php';

    // Get images from the database
    $query = $db->query("SELECT * FROM images ORDER BY uploaded_on DESC");

    if($query->num_rows > 0){
        while($row = $query->fetch_assoc()){
            $imageURL = 'uploads/'.$row["file_name"];
    ?>
    <img src="<?php echo $imageURL; ?>" alt="<?php echo $row["file_name"]; ?>"><br>
    <?php 
        }
    } else {
    ?>
    <p>No image(s) found...</p>
    <?php 
    }
    ?>


</body>
</html>