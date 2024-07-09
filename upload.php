<?php

//print_r($_FILES);

error_reporting(E_ALL);



$target_dir = "hotel/img/uploads/property/1111/";
$uploadDir = "hotel/img/uploads/property/1111/";

if(!is_dir($uploadDir))
{
  mkdir($uploadDir, 0777, true);
}

$count=count($_FILES['fileToUpload']['name']);
for($i=0;$i<=$count;$i++)
{

if($_FILES['fileToUpload']['name'][$i]!='')
{


   $uploadFile = $uploadDir.basename($_FILES['fileToUpload']['name'][$i]);


   
 if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'][$i], $uploadFile))
    {

        // Prepare remote upload data

        $uploadRequest = array(

            'uploadurl' => $target_dir,
            'fileName' => basename($uploadFile),
            'fileData' => base64_encode(file_get_contents($uploadFile))

        );



        // Execute remote upload

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://buddiestechnologies.com/rec.php');

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;



        // Now delete local temp file

        unlink($uploadFile);
    }
}
} 




?>