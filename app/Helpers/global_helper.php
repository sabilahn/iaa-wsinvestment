<?php
    use Config\Services;

    function validateSecretKey($secretKey) {
        if($secretKey === getenv('X-SECRET-KEY')) {
            return true;
        } else {
            return false;
        }
    }

?>