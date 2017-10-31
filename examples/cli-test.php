<?php
/**
 * Created by PhpStorm.
 * User: Daniel-Paz-Horta
 * Date: 9/22/17
 * Time: 10:27 AM
 */

try {

    include_once( __DIR__  . '/../src/aits_terms.php');

    if(empty($argv[1])){

        throw new \Exception("Error: Specify senderAppId ID as provided from AITS as the 3rd argument.");

    }

    if(empty($argv[2])){

        echo "Notice: Defaulting period to \"current\"." . PHP_EOL;
        echo PHP_EOL;

    }

    if(empty($argv[3])){

        echo "Notice: Defaulting campus to \"uic\"." . PHP_EOL;
        echo PHP_EOL;

    }

    if(empty($argv[4])){

        echo "Defaulting format to \"JSON\"." . PHP_EOL;
        echo PHP_EOL;

    }

    // Call the AITS Term API
    $termAPI = new dpazuic\aits_terms(empty($argv[2]) ? 'current' : $argv[2], empty($argv[3]) ? 'uic' : $argv[3], $argv[1]);

    // Get the results of a call
    $data = $termAPI->getAITSTerms(empty($argv[4]) ? 'json' : $argv[4]);

    // Show results
    print_r($data);

    // Uncomment to see the object
    //print_r($termAPI);

    // Uncomment to see the cached Results
    //print_r($termAPI->getCachedResult());

} catch (\Exception $e){

    print_r($e->getMessage());
    echo PHP_EOL;
    echo PHP_EOL;

}