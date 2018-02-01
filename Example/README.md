## Ajax_Controller Basic Use Example
The `application` subfolders contain only the files needed to produce an example. You need a functioning CI setup to run this example.

The folder structure inside `application/` matches the standard CodeIgniter (CI) installation.
Copy the files to the matching folders of your CI setup. 

Because this example uses a "hook" be sure to set `$config['enable_hooks'] = TRUE;` in your `config.php` file.
Once the files are in place browse to `http://yoursite.com/user` and give the example app a try.


### Some Details
The view loaded by `User.php` contains three buttons. Each button makes an ajax request to `User_ajax` in a slightly different way. 
The intent is to show how to respond appropriately to various `ajax.dataType` option values. 

The bottom button demonstrates how to use `getInputs()` to verify that required fields have been posted.

The JavaScript that handles button clicks (and triggers ajax requests) is in the view file `ajax_test_v.php`.

### Experiments
There are a couple of commented lines in the JavaScript that, when uncommented, will change the outcomes for a couple buttons. Try out these
variations - hopefully they are instructive. 

To see what happens when an attempt is made to access `User_ajax` directly point a browser to
`http://yoursite.com/user_ajax/get_json` and enjoy the "404 Page Not Found" screen that appears.

Use this example to experiment according to your needs.

One thing this example does not do is call `ajax_error()` explicitly. You might want to think up a case where you would
want to do that and try it out. 

For instance:

        if(FALSE === $isAuthorized)
        {
            $this->ajax_error('You must Sign In to continue!', 401);
        }
        // authorized user - continue with your code here

Keep in mind that a call to `$this->ajax_error();` will end script execution after the error response
is sent. This means you do not need an `else` block to handle the case where `$isAuthorized` is TRUE.
 

