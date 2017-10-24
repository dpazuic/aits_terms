# aits_terms

PHP Library for using the AITS Term API (contact AITS for additional details on API)


## Usage
To use the library, you need to:

1. Include library in your program 
2. Instantiate an object of class `aits_term`
3. Use one of the public methods on the object
```
# 1
include_once('aits_terms.class.php');
$period = current;
$campusCode = 'uic';
#2
$termAPI = new aits_terms($period, $campusCode, $senderAppID);
```
### Getting Results from an API call
```
#3
$outputFormat = 'json';
$termAPI->getAITSTerms($outputFormat);
```

### Return Result from Previous Call (cached)
```
#3
$outputFormat = 'json';
$termAPI->getCachedResult();
```

## Examples:
You can use the attached `cli-test.php` file from the command line to test functionality.
`php cli-test.php YOUR_SENDER_APP_ID 220178 uic json`