<?php
/**
 * Created by PhpStorm.
 * User: Daniel-Paz-Horta
 * Date: 9/22/17
 * Time: 10:27 AM
 */

//namespace aits_terms;


class aits_terms
{

    /**
     * The Campus you are querying for
     *
     * @var string
     */
    protected $campus;

    /**
     * The Period/Term you are querying for
     *
     * @var string
     */
    protected $period;

    /**
     * The senderAppID provided by AITS when registering your app's access with the AITS Term API
     *
     * @var string
     */
    protected $senderAppID;

    /**
     * The result of an AITS term request call
     *
     * @var object
     */
    protected $result;

    /**
     * aits_terms constructor.
     *
     * @param string $period
     * @param null $campus
     * @param null $senderAppID
     */
    public function __construct($period = 'current', $campus = NULL, $senderAppID = NULL)
    {

        $this->setSenderAppId($senderAppID);


        // Compute campus from $period if it is a Banner Term Code
        if(empty($campus)){

            // Compute a campus code from $period code, return a properly formatted code if passed validation, else throw exception
            $this->setCampusCode($this->computeCampusCode($period));

        } else {

            // Validate the Campus code, return a properly formatted code if passed validation, else throw exception
            $this->setCampusCode($this->checkCampusCode($campus));

        }

        // Set the period that is to be queried
        $this->setPeriod($period);

    }

    /**
     * Void method that validates and sets the $period property
     *
     * @param $period
     */
    public function setPeriod($period)
    {

        // Validate Period
        $period = $this->checkPeriodCode($period);

        $this->period = $period;

    }

    /**
     * Void method that sets the $campus property
     *
     * @param $code
     * @throws Exception
     */
    public function setCampusCode($campusCode)
    {

        if(empty($campusCode)){

            throw new Exception('The campus code cannot be blank. Please specify a campus code: 1 for UIUC, 2 for UIC, 4 for UIS.');

        }

        // Validate code (just in case if using this method standalone)
        $campusCode = $this->checkCampusCode($campusCode);

        $this->campus = $campusCode;

    }

    /**
     * Void method that sets the senderAppID property
     *
     * @param $period
     */
    public function setSenderAppId($senderAppID)
    {

        // Check to see if the $senderAppID was set
        if(empty($senderAppID)) {

            throw new Exception('The senderAppId cannot be blank. Please contact AITS for a senderAppId');

        }

        $this->senderAppID = $senderAppID;

    }

    /**
     * Method that returns the value of $this->period
     *
     * @return string
     */
    public function getPeriod()
    {

        return $this->period;

    }

    /**
     * Method that returns the value of $this->campus
     *
     * @return string
     */
    public function getCampus()
    {

        return $this->campus;

    }

    /**
     * Method that returns the value of $this->senderAppID
     *
     * @return string
     */
    public function getSenderAppId()
    {

        return $this->senderAppID;

    }

    /**
     * Method that returns the value of $this->result
     *
     * @return object
     */
    public function getCachedResult()
    {

        return $this->result;

    }

    /**
     * Method used to communicate with the AITS Term API and return data in a specified format
     * https://www.aits.uillinois.edu/cms/One.aspx?portalId=558&pageId=632773
     *
     * @param string $outputFormat
     * @return object
     */
    public function getAITSTerms($outputFormat = 'json')
    {

        // Validate format
        $outputFormat = $this->checkFormatParam($outputFormat);

        // AITS Term API Source
        $source = 'https://webservices.admin.uillinois.edu/studentWS/data/' . $this->senderAppID . '/Term/1_0/' . $this->period . '/' . $this->campus . '?format=' . ($outputFormat == 'xml' || $outputFormat == 'json' ? $outputFormat : 'json');

        // Initialize a curl resource
        $curl = curl_init();

        // Set curl options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $source);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array());

        // JSON Response
        $response = curl_exec($curl);

        //
        // Test that the AITS term response returned some type of valid output
        //

        if($outputFormat == 'xml') {

            // Initialize an XML parser
            $xmlParser = xml_parser_create();

            // Convert to array
            xml_parse_into_struct($xmlParser, $response, $values, $index);

            // Free the XML Parser
            xml_parser_free($xmlParser);

            // Check to see that $obj is an object, thus has data
            if (!is_array($values)) {

                throw new Exception('Communication with the AITS term API is not available. Try again later.');

            }

        } else {

            // Convert to Object
            $obj = json_decode($response);

            // Check to see that $obj is an object, thus has data
            if (!is_object($obj)) {

                throw new Exception('Communication with the AITS term API is not available. Try again later.');

            }

        }

        // Cache the response in $this->result
        $this->result = new stdClass();
        $this->result->type = $outputFormat;
        $this->result->data = $outputFormat == 'object' ? $obj : $response;


        // $output appropriately
        if($outputFormat == 'object'){

            return $obj;

        }

        // For JSON and XML requests
        return $response;

    }

    /**
     * Method used to validate a campus code against expected values
     *
     * @param $campus (uic|uiuc|uis|100|200|400)
     * @return string
     * @throws Exception
     */
    private function checkCampusCode($campus)
    {

        // Convert $campus to lowercase
        $campus = strtolower($campus);

        // Clean the code, check that it matches
        $campusArray = preg_grep("/(uic|uiuc|uis|100|200|400)/", explode("\n", $campus));

        // If $campus matches exactly one
        if(count($campusArray) > 1) {
            // $campus matches more than one check
            throw new Exception('The campus code: "' . $campus . '" has too many matches. Specify uic, uiuc or uis.');

        } else if(count($campusArray) == 1){

            switch($campus){
                case('100'):
                    $campus = 'uiuc';
                    break;
                case('200'):
                    $campus = 'uic';
                    break;
                case('400'):
                    $campus = 'uis';
                    break;
                default:
                    break;
            }

            return $campus;

        }  else {

            // $campus does not match checks
            throw new Exception('The campus code: "' . $campus . '" is not valid. Specify uic, uiuc or uis.');

        }

    }

    /**
     * Method used to validate the period code against expected values
     *
     * @param $period string ([0-9]{6}|current|nextterm|lastterm|nextsemester|lastsemester|nextyear|lastyear)
     * @return string
     * @throws Exception
     */
    private function checkPeriodCode($period)
    {

        // Convert $campus to lowercase
        $period = strtolower($period);

        // Clean the code, check that it matches
        $periodArray = preg_grep("/^([0-9]{6}|current|nextterm|lastterm|nextsemester|lastsemester|nextyear|lastyear)$/", explode("\n", $period));

        if(empty($periodArray)){

            // Throw exception, banner term code is not valid
            throw new Exception('The period code: "' . $period . '" is not a valid. Accepted values are valid Banner Term Codes ie. "120168", "220171", "420168" or relative periods: "current", "nextTerm", "lastTerm", "nextSemester", "lastSemester"');

        }

        // Check to see if the $period specified is a banner term code, thus checking for digits,
        // specifically first digit of string
        $bannerTermArray = preg_grep("/^([0-9]{6})$/", explode("\n", $period));
        if(count($bannerTermArray) == 1){

            // Get the first digit from $period, cast it as an integer (just in case it comes back as a string)
            $periodFirstDigit = (int) substr($period, 0 , 1);

            // Check that $period is a valid period (prefixed with a 1, 2 or 4
            if($periodFirstDigit == 1 OR $periodFirstDigit == 2 OR $periodFirstDigit == 4){

                return $period;

            } else {

                // Throw exception, banner term code is not valid
                throw new Exception('The period code: "' . $period . '" is not a valid Banner Term code.');
            }

        }

        // Okay, $period is a relative time, match to accepted AITS values
        switch($period){
            case('nextterm'):
            case('nextsemester'):
                $period = $this->computeNextLastPeriod('nextterm');
                break;

            case('lastterm'):
            case('lastsemester'):
                $period = $this->computeNextLastPeriod('lastterm');
                break;

            case('nextyear'):
                $period = 'nextYear';
                break;

            case('lastyear'):
                $period = 'lastYear';
                break;

            case('current'):
            default:
                $period = 'current';
                break;
        }

        return $period;

    }

    /**
     * Method used to compute a campus code (uic, uiuc or uis) from the first digit of a valid period code (Banner Term Code)
     * ie. "120168", "220171", "420168"
     * @param $period
     * @return string
     * @throws Exception
     */
    private function computeCampusCode($period)
    {

        $campusCode = (int) substr($period, 0, 1);

        switch($campusCode){

            case 1:
                return "uiuc";
                break;
            case 2:
                return "uic";
                break;
            case 4:
                return "uis";
                break;
            default:
                throw new Exception('The period code: "' . $period . '" cannot be used to compute the campus. Explicitly provide the campus when instantiating aits_terms');
                break;

        }
    }

    /**
     * Method used to validate the format for the response
     *
     * @param $format (json|xml)
     * @return string
     * @throws Exception
     */
    private function checkFormatParam($format)
    {
        $format = strtolower($format);

        // Clean the code, check that it matches
        $formatArray = preg_grep("/^(json|xml|object)$/", explode("\n", $format));

        if(empty($formatArray)){

            // Throw exception, banner term code is not valid
            throw new Exception('The format: "' . $format . '" is not supported. Use json or xml');

        }

        return $format;

    }

    /**
     * Method used to compute the next or last term based on the current term endpoint provided by AITS Term API
     *
     * @param $type
     * @return string
     * @throws Exception
     */
    private function computeNextLastPeriod($type)
    {
        //  Instantiate aits_terms to query the current term so this method can compute the next or last term
        $call = new aits_terms('current', $this->campus, $this->senderAppID);
        $result = $call->getAITSTerms('object');

        // Check that $result->list has 1 item in the array
        if(count($result->list) == 1){

            if(empty($result->list[0]->term[0]->termCode)){

                throw new Exception('Unable to retrieve term code for the current term.');

            }

            $termPrefix = substr($result->list[0]->term[0]->termCode, 0, 1); // Campus Designation
            $termSuffix = substr($result->list[0]->term[0]->termCode, -1); // Semester Designation
            $termYear = substr($result->list[0]->term[0]->termCode, 1, 4);

            switch($type){
                case 'nextterm':
                    $bannerTerm = $termPrefix;

                    if($termSuffix == "8"){

                        $bannerTerm .= (int) $termYear + 1;
                        $bannerTerm .= "1";

                    } else if($termSuffix == "5") {

                        $bannerTerm .= $termYear . "8";

                    } else if($termSuffix == "1"){

                        $bannerTerm .= $termYear . "5";

                    }
                    break;

                case 'lastterm':
                    $bannerTerm = $termPrefix;

                    if($termSuffix == "1"){

                        $bannerTerm .= (int) $termYear - 1;
                        $bannerTerm .= '8';

                    } else if($termSuffix == "5") {

                        $bannerTerm .= $termYear . "1";

                    } else if($termSuffix == "8"){

                        $bannerTerm .= $termYear . "5";

                    }
                    break;

                default:
                    $bannerTerm = $result->list[0]->term[0]->termCode;
                    break;

            }

            return $bannerTerm;

        } else {

            throw new Exception('Unable to retrieve data for the current term.');
        }

    }

}