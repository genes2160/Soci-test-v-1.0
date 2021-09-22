**_ REQUIREMENTS _**

1. PHP 7.4 ++++
2. CURL (OPTIONAL)=> FOR TESTING restApi endpoint

`INFORMATION`
Please note there are two options stipulated below for accessing or running simulations (tests)

*** ALERT ***
NB: Running test scenarios would overwrite content in bookshelf.json (it is the data house of cos)
To revert to original file content please open bookshelf-bricked.json or enter this command
cp -rf bookshelf-bricked.json bookshelf.json


**_ STEPS (restApi) _**
Paste this command to fire up an enpoint using PHP server 1. php -S 127.0.0.1:5005 -t .

    2. submit data

        a. Using Curl
            **** GET ***
            curl "http://127.0.0.1:5005/restApi.php?limit=50&offset=0"  --compressed

            *** POST ***
            curl 'http://127.0.0.1:5005/restApi.php' \
            -H 'content-type: application/x-www-form-urlencoded; charset=UTF-8' \
            --data-raw 'title=Moby%20Dick&author=Eugene%20Duodu'  --compressed

        b. Using Javascript fetch
            *** GET ***
            fetch("http://127.0.0.1:5005/restApi.php?limit=50&offset=0",{
                "headers": {
                    "accept": "*/*",
                    "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
                    "sec-ch-ua": "\"Google Chrome\";v=\"93\", \" Not;A Brand\";v=\"99\", \"Chromium\";v=\"93\"",
                    "sec-ch-ua-mobile": "?0",
                    "x-requested-with": "XMLHttpRequest"
                },
                "referrer": "http://127.0.0.1:5005/restApi.php",
                "referrerPolicy": "strict-origin-when-cross-origin",
                "body": null,
                "method": "GET",
                "mode": "cors",
                "credentials": "omit"
            });

            *** POST ***
            fetch("http://127.0.0.1:5005/restApi.php", {
                "headers": {
                    "accept": "*/*",
                    "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
                    "sec-ch-ua": "\"Google Chrome\";v=\"93\", \" Not;A Brand\";v=\"99\", \"Chromium\";v=\"93\"",
                    "sec-ch-ua-mobile": "?0",
                    "sec-ch-ua-platform": "\"Windows\"",
                    "x-requested-with": "XMLHttpRequest"
                },
                "referrer": "http://127.0.0.1:5005/restApi.php",
                "referrerPolicy": "strict-origin-when-cross-origin",
                "body": "title=Moby%20Dick&author=Eugene%20Duodu",
                "method": "POST",
                "mode": "cors",
                "credentials": "omit"
            });


    *** VALIDATION ***
    1. For validation confirmation kindly omit or add values to body type
    Example:
    a. Using Curl
        **** GET *** => Already added extra process to capture empty params
            curl "http://127.0.0.1:5005/restApi.php"  --compressed

        *** POST ***
            curl 'http://127.0.0.1:5005/restApi.php' \
            -H 'content-type: application/x-www-form-urlencoded; charset=UTF-8' \
            --data-raw 'title=Moby%20Dick'  --compressed

**_ STEPS (App.php) _**
Paste this command to run file using php `in terminal` below
clear && php App.php
NB: Please make sure PHP is installed and can be invoked via terminal => added to path and ideally use terminal instead of cmd when using windows os
