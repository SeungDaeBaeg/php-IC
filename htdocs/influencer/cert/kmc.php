<?php
    $ICERTSecu = './ICERTSecu';

    $cpId = 'LINM1001';
    $urlCode = '032001';
    $certNum = uniqid();
    $date = date('YmdHis');
    $certMet = 'M';    

    $tr_cert    = $cpId . "/" . $urlCode . "/" . $certNum . "/" . $date . "/" . $certMet;

    $enc_tr_cert  = exec("$ICERTSecu SEED 1 0 $tr_cert ");

    var_dump($enc_tr_cert);
?>