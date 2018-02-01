<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-2.2.2.js"></script>
        <title>AJAX Controller Test</title>
        <style  type="text/css">
            div{ padding: .5em 1em; }
            .no-msg{color: red; }
            .test-this{color: blue; }
            .answer{color: green; }
        </style>
    </head>
    <body>
        <div>
            <button type="button" id='json-test'>JSON Test</button>
            <div id="json-response"></div>
        </div>

        <div>
            <button type="button" id='html-test'>HTML Test</button>
            <div id="html-response"></div>
        </div>

        <div>
            <button type="button" id='fail-input'>Missing Input Test</button>
            <div id="failed-response" class="answer"></div>
        </div>

        <script>
            var postData;
            var baseURL = window.location.protocol + "//" + window.location.hostname + '/user_ajax/';

            $('#json-test').click(function () {
                postData = null;
                do_ajax('get_json', 'json', jsonCallback);
            });

            $('#html-test').click(function () {
                postData = {msg: "<span class='test-this'>Does this work?</span>"};
                //uncommnet the next line to get a "No Message Available" response
                //postData = null; 
                do_ajax('get_html', 'html', htmlCallback);
            });

            $('#fail-input').click(function () {
                postData = {answer: 42};
                //uncommnet the next line to make this work
                //postData = {answer: 42, msg: "Answer to the Ultimate Question of Life, the Universe, and Everything = "};
                do_ajax('fail_input', 'text', missingInput);
            });

            function do_ajax(url, dataType, callback) {
                $.ajax({
                    url: baseURL + url,
                    type: "POST", //this can be changed to "GET" and everything still works!
                    dataType: dataType,
                    data: postData,
                    success: callback,
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                        alert(textStatus + ' code:' + jqXHR.status + " " + errorThrown);
                    }
                });
            }

            function jsonCallback(response) {
                console.log(response);
                $('#json-response').text(response.msg);
            }

            function htmlCallback(response) {
                console.log(response);
                $('#html-response').html(response);
            }

            function missingInput(response) {
                console.log(response);
                $('#failed-response').text(response);
            }
        </script>
    </body>
</html>