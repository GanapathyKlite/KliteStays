<?php

if (isset($_POST['fileName']) && $_POST['fileData'])

{

    // Save uploaded file

    $uploadDir = $_POST['uploadurl'];

    file_put_contents(

        $uploadDir. $_POST['fileName'],

        base64_decode($_POST['fileData'])

    );



    // Done

    echo "Success";

}

?>