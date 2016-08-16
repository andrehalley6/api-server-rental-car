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
			echo deliver_response(400, "fail", "The client ID must be exists");
			exit;
		}

		// Check current id on database
		$client = $this->clients_m->get_client_by_id($id)->result_array();
		if(empty($client)) {
			echo deliver_response(400, "fail", "The client ID must be exists on the database");
			exit;
		}

		if($client) {
			$rental_histories = $this->rentals_m->get_client_rental_histories($id)->result_array();
			$client_data = $client[0];

			foreach($rental_histories as $rental_history) {
				$client_data['histories'][] = $rental_history;
			}

			echo car_rental_response("data", $client_data);
			exit;
		}
	}

	public function car($id) {
		// Check if id is exist
		if(empty($id) || is_null($id)) {
			echo deliver_response(400, "fail", "The car ID must be exists");
			exit;
		}

		// Check if month parameter is exist
		$month = isset($_GET['month']) ? $_GET['month'] : NULL;
		if(is_null($month)) {
			echo deliver_response(400, "fail", "Must be within specified month");
			exit;
		}

		// Check month format
		list($mm, $yyyy) = explode('-',$month);
		if (!checkdate($mm, 1, $yyyy)) {
		    echo deliver_response(400, "fail", "Month format must be `MM-YYYY`");
		    exit;
		}

		// Check current id on database
		$car = $this->cars_m->get_car_year_type_plate_by_id($id)->result_array();
		if(empty($car)) {
			echo deliver_response(400, "fail", "The car ID must be exists on the database");
			exit;
		}

		if($car) {
			$rental_histories = $this->rentals_m->get_car_rental_histories($id, $mm, $yyyy)->result_array();
			$car_data = $car[0];

			$i = 0;
			foreach($rental_histories as $rental_history) {
				$car_data['histories'][$i]['rent-by'] = $rental_history['name'];
				$car_data['histories'][$i]['date-from'] = date("Y-m-d", strtotime($rental_history['date-from']));
				$car_data['histories'][$i]['date-to'] = date("Y-m-d", strtotime($rental_history['date-to']));
				$i++;
			}

			echo car_rental_response("data", $car_data);
			exit;
		}
	}
}
