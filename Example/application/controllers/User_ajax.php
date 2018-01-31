<?php
/**
 * A simple class to test the use of Ajax_Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_ajax extends Ajax_Controller
{
	/**
	 * Expects no $_POST data and outputs a json encoded string of the $out array
	 */
	public function get_json()
	{
		$out = ['msg' => 'Hello World!'];
		$this->response($out);
	}

	/**
	 * Uses $_POST data but does not require it. Will simple echo back whatever it receives in $_POST['msg'] or
	 * it will echo a default message if $_POST['msg'] is not set
	 */
	public function get_html()
	{
		$posted = $this->getInputs();
		$out = isset($posted['msg']) ? $posted['msg'] : "<span class='no-msg'>No Message Available</span>";
		$this->response($out, 'html');
	}

	/**
	 * This responder will fail if either of the supplied field names - ['answer','msg'] - are missing.
	 * The call to $this->getInputs(['answer','msg']) will result in an ajax error being output 
	 * because of the missing 'msg' field.
	 */
	public function fail_input()
	{
		$posted = $this->getInputs(['answer', 'msg']);
		$out = $posted['msg']." ".$posted['answer'];
		$this->response($out, 'text');
	}
}
