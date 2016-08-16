<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histories extends CI_Controller {
	var $http_header;
	var $request_method;

	public function __construct() {
		parent::__construct();

		header("Content-Type: application/json");

		// Get controller method
		$method = $this->uri->segment(2);

		// Get PHP Header
		$this->http_header = getallheaders();
		
		// Get request method (GET, POST, PUT, DELETE)
		$this->request_method = $this->input->server('REQUEST_METHOD');

		// Check header accept parameter
		if($this->http_header['Accept'] != "application/json") {
			echo deliver_response(400, "fail", "Content Type Not Supported");
			exit;
		}

		// Check if method not exists
		if(!method_exists(__CLASS__, $method)) {
			echo deliver_response(404, "fail", "Method Not Found");
			exit;
		}
	}
	
	public function client($id = NULL) {
		// Check if id is exist
		if(empty($id) || is_null($id)) {
			echo deliver_response(400, "fail", "The ID must be exists");
			exit;
		}

		// Check current id on database
		$client = $this->clients->get_client_by_id($id)->result_array();
		if(empty($client)) {
			echo deliver_response(400, "fail", "The ID must be exists on the database");
			exit;
		}

		if($client) {
			$rental_histories = $this->rentals->get_client_rental_histories($id)->result_array();
			$client_data = $client[0];

			foreach($rental_histories as $rental_history) {
				$client_data['histories'][] = $rental_history;
			}

			echo car_rental_response("data", $client_data);
			exit;
		}
	}

	public function car($id = NULL) {
		
	}

	public function rentals($id = NULL) {
		
	}
}
