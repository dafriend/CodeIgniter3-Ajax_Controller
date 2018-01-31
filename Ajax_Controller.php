<?php
/**
 * Ajax_Controller extends CI_Controller and accepts only xmlhttprequest (AJAX) requests.
 * Directly requesting this controller (or one extending this class i.e. https://example.com/some_class/some_method) 
 * or a request including a method, i.e. https://example.com/groups_admin/groups_list, 
 * will produce the standard CodeIgniter "404 Page Not Found" screen. 
 * 
 * The methods of this class provide convenient gathering of inputs (with optional checking for required inputs), 
 * outputting the appropriately encoded response, or outputting an error response.
 *
 * @author dave friend
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_Controller extends CI_Controller
{
	protected $requestMethod;

	public function __construct()
	{
		parent:: __construct();

		if(FALSE === $this->input->is_ajax_request())
		{
			log_message('error', "Attempted non-xmlhttprequest to ".$this->router->class."::".$this->router->method);
			show_404('', FALSE);
		}
		$this->requestMethod = $this->input->method();
	}

	/**
	 * Captures either the $_POST or $_GET array and optionally checks for existence of certain keys. 
	 * 
	 * @param mixed $input_keys Can be a field name (string) or an array of field name strings. 
	 * Arrays should be simple lists i.e. ['id', 'year']
	 * $input_keys (if any) must be in the desired input array. 
	 * If any $input_keys are missing an error response is output ending the script.
	 * 
	 * @param string $requestMethod Determines which "super global" ($_POST or $_GET) is captured. 
	 * Valid values are 'auto' (default), 'get' or 'post'. The default will base the capture on the server request
	 * method returned by CI_Input::method(). An invalid $requestMethod value results in 'auto' being used.
	 * 
	 * @return mixed Returns the input array ($_GET or $_POST) determined by $this->requestMethod. 
	 * Returns NULL if the input array is empty and $input_keys is FALSE.
	 * If $input_keys is provided and any key is missing from the requested array an AJAX error is triggered.
	 * The AJAX error ends script execution.
	 */
	public function getInputs($input_keys = FALSE, $requestMethod = 'auto')
	{
		$requestOptions = ['auto', 'get', 'post'];
		if(!is_string($requestMethod) || FALSE === in_array($requestMethod, $requestOptions))
		{
			$this->ajax_error(); // Huh!?! Report internal server error
		}

		if($requestMethod !== 'auto')
		{
			$this->requestMethod = $requestMethod;
		}

		$inputs = $this->input->{$this->requestMethod}();

		if($input_keys)
		{
			if(empty($inputs))
			{
				$inputs = FALSE;
			}
			else
			{
				$input_keys = is_array($input_keys) ? $input_keys : [$input_keys]; //need an array
				foreach($input_keys as $key)
				{
					//if the key is not in requested array then mark $inputs as FALSE
					if(!array_key_exists($key, $inputs))
					{
						$inputs = FALSE;
						break;
					}
				}
			}

			if(!$inputs)
			{
				//Produce an AJAX error ending script execution
				$this->ajax_error("Bad Request", '400');
			}
		}
		return $inputs;
	}

	/**
	 * Output a response
	 *
	 * @param mixed $data The data to output. It will be encoded according to $output_type.
	 * @param string $output_type The output encode type. 
	 *    Options 'json', 'xml', 'html', or 'text'. Defaults to 'json'.
	 */
	public function response($data = NULL, $output_type = 'json')
	{
		// Encode data and set headers
		switch($output_type)
		{
			case 'json':
			default:
				$content_type = 'Content-Type: application/json';
				$out = json_encode($data);
				break;

			case 'html':
				$content_type = 'Content-Type: text/html';
				$out = $data;
				break;

			case 'text':
				$content_type = 'Content-Type: text/plain';
				$out = $data;
				break;

			case 'xml':
				$content_type = 'Content-Type: application/xhtml+xml';
				$out = $this->array_to_xml($data);
				break;
		}

		$this->output
			->set_status_header('200')
			->set_header($content_type)
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_header('Pragma: no-cache')
			->set_header('Access-Control-Allow-Origin: '.$this->config->base_url())
			->set_header('Content-Length: '.strlen($out))
			->set_output($out);
	}

	/**
	 * Output an error response with a message
	 *
	 * @param string $msg The reason string in the response header, default= "Internal Server Error"
	 * @param string $status Header status code, default '500' which is appropriate for an "Internal Server Error"
	 * @return void  Does not return. Instead, after outputting the error it calls exit() ending script execution.
	 */
	public function ajax_error($msg = "Internal Server Error", $status = '500')
	{
		$this->output
			->set_status_header($status)
			->set_header("Content-Type: text/plain")
			->set_output($msg)
			->_display();
		exit;
	}

	/**
	 * Convert an array to an XML string. Must be an associative array, eg. ('my_data' => 'something', 'data' => '1/1/1970')
	 *
	 * @param array $array The array to be converted to an XML string.
	 * @param integer $level The XML version. Default 1.0
	 * @return string
	 */
	protected function array_to_xml($array, $level = 1)
	{
		$xml = '';
		if($level == 1)
		{
			$xml .= '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n<array>\n";
		}
		foreach($array as $key => $value)
		{
			$key = strtolower($key);
			if(is_array($value))
			{
				$multi_tags = false;
				foreach($value as $key2 => $value2)
				{
					if(is_array($value2))
					{
						$xml .= str_repeat("\t", $level)."<$key>\n";
						$xml .= array_to_xml($value2, $level + 1);
						$xml .= str_repeat("\t", $level)."</$key>\n";
						$multi_tags = true;
					}
					else
					{
						if(trim($value2) != '')
						{
							if(htmlspecialchars($value2) != $value2)
							{
								$xml .= str_repeat("\t", $level)."<$key><![CDATA[$value2]]>"."</$key>\n";
							}
							else
							{
								$xml .= str_repeat("\t", $level)."<$key>$value2</$key>\n";
							}
						}
						$multi_tags = true;
					}
				}
				if(!$multi_tags and count($value) > 0)
				{
					$xml .= str_repeat("\t", $level)."<$key>\n";
					$xml .= array_to_xml($value, $level + 1);
					$xml .= str_repeat("\t", $level)."</$key>\n";
				}
			}
			else
			{
				if(trim($value) != '')
				{
					if(htmlspecialchars($value) != $value)
					{
						$xml .= str_repeat("\t", $level)."<$key>"."<![CDATA[$value]]></$key>\n";
					}
					else
					{
						$xml .= str_repeat("\t", $level)."<$key>$value</$key>\n";
					}
				}
			}
		}
		if($level == 1)
		{
			$xml .= "</array>\n";
		}
		return $xml;
	}
}
