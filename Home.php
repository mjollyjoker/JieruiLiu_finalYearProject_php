<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $ch = curl_init();
     
    curl_setopt($ch, CURLOPT_URL, join("", array($hostAddr,'combined/_design/general/_view/created_month?group_level=2')));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json',
        'Accept: */*'
    ));
     
    $response = curl_exec($ch);
     
    curl_close($ch);

    headerBegin('General');
    importPackage_general();
    
    print '<link rel="stylesheet" type="text/css" href="style.css"/>';
    print '</head>';
    print '<body>';
    print '<p>'.$response.'</p>';
    print '</body>';
    print '</html>';

?>