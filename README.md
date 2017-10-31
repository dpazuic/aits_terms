# aits_terms

PHP Library for using the AITS Term API (contact AITS for additional details on API)


## Usage
To use the library, you need to:

* Include library in your program 
```
include_once('aits_terms.php');
```
* or use composer `composer require dpazuic\aits_terms`
```
include_once('vendor/autoload.php');
```
* Instantiate an object of class `dpazuic\aits_term`
```
$period = 'current'; // Also accepted: 22017X|current|nextterm|lastterm|nextsemester|lastsemester|nextyear|lastyear 
$campusCode = 'uic'; // Also accepted: uic|uiuc|uis|100|200|400
$senderAppID = 'YOUR_SENDER_APP_ID'; // Contact AITS for this
$termAPI = new dpazuic\aits_terms($period, $campusCode, $senderAppID);
```
* Use one of the public methods on the object


### Getting Results from an API call
```
#3
$outputFormat = 'json';
$data = $termAPI->getAITSTerms($outputFormat);
print_r($data);
```

#### `$data` Outputs
```
{  
   "object":"Term",
   "version":"1_0",
   "list":[  
      {  
         "queryPeriod":"current",
         "queryCampus":"uic",
         "term":[  
            {  
               "termCode":"220178",
               "termDescription":"Fall 2017 - Chicago",
               "startDate":"2017-08-28",
               "endDate":"2017-12-16",
               "finaidProcYear":{  
                  "code":"1718",
                  "description":"2017-2018"
               },
               "academicYear":{  
                  "code":"1718",
                  "description":"2017-2018"
               },
               "housingStartDate":"2017-08-28",
               "housingEndDate":"2017-12-16",
               "termType":{  
                  "code":"S",
                  "description":"Semester"
               },
               "termPart":[  
                  {  
                     "termCode":"220178",
                     "description":"Full Term",
                     "startDate":"2017-08-28",
                     "endDate":"2017-12-08",
                     "weeks":16,
                     "censusDate":"2017-08-28"
                  },
                  {  
                     "termCode":"220178",
                     "description":"First Half",
                     "startDate":"2017-08-28",
                     "endDate":"2017-10-20",
                     "weeks":8,
                     "censusDate":"2017-08-28"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Second Half",
                     "startDate":"2017-10-23",
                     "endDate":"2017-12-08",
                     "weeks":8,
                     "censusDate":"2017-10-23"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Dentistry",
                     "startDate":"2017-08-28",
                     "endDate":"2017-12-15",
                     "weeks":16,
                     "censusDate":"2017-08-28"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Medicine",
                     "startDate":"2017-08-14",
                     "endDate":"2017-12-15",
                     "weeks":16,
                     "censusDate":"2017-08-14"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Tuition Waiver Fall",
                     "startDate":"2017-08-21",
                     "endDate":"2017-12-15",
                     "weeks":17,
                     "censusDate":"2017-08-28"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Urbana Calendar",
                     "startDate":"2017-08-28",
                     "endDate":"2017-12-13",
                     "weeks":16,
                     "censusDate":"2017-08-28"
                  },
                  {  
                     "termCode":"220178",
                     "description":"Extramural",
                     "startDate":"2017-08-28",
                     "endDate":"2017-12-08",
                     "weeks":16,
                     "censusDate":"2017-08-28"
                  }
               ]
            }
         ]
      }
   ]
}

```

### Return Result from Previous Call (PHP Object)
```
#3
$outputFormat = 'json';
$data = $termAPI->getCachedResult();
print_r($data);
```
#### `$data` Outputs
```
stdClass Object
(
    [type] => json
    [data] => {"object":"Term","version":"1_0","list":[{"queryPeriod":"current","queryCampus":"uic","term":[{"termCode":"220178","termDescription":"Fall 2017 - Chicago","startDate":"2017-08-28","endDate":"2017-12-16","finaidProcYear":{"code":"1718","description":"2017-2018"},"academicYear":{"code":"1718","description":"2017-2018"},"housingStartDate":"2017-08-28","housingEndDate":"2017-12-16","termType":{"code":"S","description":"Semester"},"termPart":[{"termCode":"220178","description":"Full Term","startDate":"2017-08-28","endDate":"2017-12-08","weeks":16,"censusDate":"2017-08-28"},{"termCode":"220178","description":"First Half","startDate":"2017-08-28","endDate":"2017-10-20","weeks":8,"censusDate":"2017-08-28"},{"termCode":"220178","description":"Second Half","startDate":"2017-10-23","endDate":"2017-12-08","weeks":8,"censusDate":"2017-10-23"},{"termCode":"220178","description":"Dentistry","startDate":"2017-08-28","endDate":"2017-12-15","weeks":16,"censusDate":"2017-08-28"},{"termCode":"220178","description":"Medicine","startDate":"2017-08-14","endDate":"2017-12-15","weeks":16,"censusDate":"2017-08-14"},{"termCode":"220178","description":"Tuition Waiver Fall","startDate":"2017-08-21","endDate":"2017-12-15","weeks":17,"censusDate":"2017-08-28"},{"termCode":"220178","description":"Urbana Calendar","startDate":"2017-08-28","endDate":"2017-12-13","weeks":16,"censusDate":"2017-08-28"},{"termCode":"220178","description":"Extramural","startDate":"2017-08-28","endDate":"2017-12-08","weeks":16,"censusDate":"2017-08-28"}]}]}]}
)

```

## Examples:
You can use the attached `examples/cli-test.php` file from the command line to test functionality.
`php cli-test.php YOUR_SENDER_APP_ID 220178 uic json`