## Ajax_Controller - A CodeIgniter Controller that only accepts xmlhttprequest (AJAX) requests.

If you like to maintain Separation of Concerns (SoC) and keep your code DRY this class will help you achieve those goals.

When used as a base class, `Ajax_Controller` allows you to create controllers for responding to AJAX requests in a
consistent and simplified way.

`Ajax_Controller` extends `CI_Controller` and your application specific controllers should, in turn, extend `Ajax_Controller`.

The class has several useful features.

- It rejects any non-AJAX requests and shows a 404 error page to any direct access using a browser
- Provides a single method to fetch either the $_GET or $_POST superglobal array - either automatically or explicitly
  -  Can optionally check for the existence of keys that must be in the superglobal array. If the keys are not set an error is output
- Provides a single output method that will encode output according to the output types json, html, text, and xml string
- Provides a method to easily output a response that JavaScript will recognize as being an error condition

The docBlocks in `Ajax_Controller.php` attempt to provide an understanding of how to use the methods. Suggestions on 
improving the documentation are welcome. 


## Requirements
* PHP version 5.6 or newer is recommended.
* CodeIgniter (CI) version 3.0.0 or newer. Using the latest version is strongly recommended. 

The current CI release [is found here](https://codeigniter.com/).


## Installation
There is only one file - **Ajax_Controller.php** - which, in a standard CI files structure, should be placed in the *application/libraries* folder.


### The Installation Catch
For historical reasons CI wasn't designed with the ability to extend classes multiple times. 

The following progression of extended classes was a daydream when CI was first being developed.

        class Grandpa {...}

        class Father extends Grandpa {...}

        class Kids extends Father {...}

CI **can** accomplish the above inheritance chain but it needs some help to do it.
There are several different ways to make multiple inheritance work. 

In this repository CI's 
'pre_system' hook is used to inject a simple autoloader into the framework.
The files that define the hook and the autoloader are found in the *example/* folders of this repository.


## Basic Use
See the example [README](example/README.md) and examine the files.

## Acknowledgement
This controller was inspired by Josh Campbell's repository [Codeigniter-jQuery-Ajax](https://github.com/ThingEngineer/Codeigniter-jQuery-Ajax) 
*Ajax implementation for CI*. The code for `Ajax_controller::array_to_xml()` is taken directly from his `encode_xml_helper.php` file.
