<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cars extends CI_Controller {
	var $http_header;
	var $request_method;

	public function __construct() {
		parent::__construct();

		header("Content-Type: application/json");

		// Get PHP Header
		$this->http_header = getallheaders();
		
		// Get request method (GET, POST, PUT, DELETE)
		$this->request_method = $this->input->server('REQUEST_METHOD');

		// Check header accept parameter
		if($this->http_header['Accept'] != "application/json") {
			echo deliver_response(400, "fail", "Content Type Not Supported");
			exit;
		}
	}

	public function index($id = NULL) {
		if($this->request_method == "POST") {
			// if request method is POST, then insert car

			// Check required parameter
			if(empty($this->input->post('brand')) || empty($this->input->post('type')) || empty($this->input->post('year')) || empty($this->input->post('color')) || empty($this->input->post('plate'))) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Plate cannot be duplicated
			$car = $this->cars_m->get_car_by_plate($this->input->post('plate'))->result_array();
			if(!empty($car)) {
				echo deliver_response(400, "fail", "Plate cannot be duplicated.");
				exit;
			}

			// Year cannot be future
			if($this->input->post('year') > date("Y")) {
				echo deliver_response(400, "fail", "Year cannot be future");
				exit;
			}

			// Insert car data
			$id = $this->cars_m->add_car();
			echo car_rental_response("id", $id);
			exit;
		}
		elseif($this->request_method == "PUT") {
			// if request method is PUT, then edit current car data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $put_data);

			// Check required parameter
			if(empty($put_data['id']) || empty($put_data['brand']) || empty($put_data['type']) || empty($put_data['year']) || empty($put_data['color']) || empty($put_data['plate'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Plate cannot be duplicated
			$car = $this->cars_m->get_car_by_plate($put_data['plate'])->result_array();
			if(!empty($car)) {
				echo deliver_response(400, "fail", "Plate cannot be duplicated.");
				exit;
			}

			// Year cannot be future
			if($put_data['year'] > date("Y")) {
				echo deliver_response(400, "fail", "Year cannot be future");
				exit;
			}

			// Check current id on database
			$car = $this->cars_m->get_car_by_id($put_data['id'])->result_array();
			if(empty($car)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Update current car data
			if($this->cars_m->edit_car($put_data['id'], $put_data)) {
				echo car_rental_response("status", "Update successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "DELETE") {
			// if request method is DELETE, then delete current car data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $delete_data);

			// Check required parameter
			if(empty($delete_data['id'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Check current id on database
			$car = $this->cars_m->get_car_by_id($delete_data['id'])->result_array();
			if(empty($car)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Delete current car data
			if($this->cars_m->delete_car($delete_data['id'])) {
				echo car_rental_response("status", "Delete successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "GET") {
			// if request method is GET, then retrieve all cars data

			$cars = $this->cars_m->get_all_cars()->result_array();
			foreach($cars as $car) {
				$set_of_cars[] = $car;
			}

			echo car_rental_response("data", $set_of_cars);
			exit;
		}
	}

	public function rented() {
		// Check if date parameter is exist
		$date = isset($_GET['date']) ? $_GET['date'] : NULL;
		if(is_null($date)) {
			echo deliver_response(400, "fail", "Must be within specified date");
			exit;
		}
		
		// Check date format
		list($dd, $mm, $yyyy) = explode('-',$date);
		if (!checkdate($mm, $dd, $yyyy)) {
		    echo deliver_response(400, "fail", "Month format must be `MM-YYYY`");
		    exit;
		}

		$car_rented_informations = $this->rentals_m->get_car_rented_information($dd, $mm, $yyyy)->result_array();
		$car_rented_data['date'] = $date;
		
		$i = 0;
		foreach($car_rented_informations as $information) {
			$car_rented_data['rented_cars'][$i]['brand'] = $information['brand'];
			$car_rented_data['rented_cars'][$i]['type'] = $information['type'];
			$car_rented_data['rented_cars'][$i]['plate'] = $information['plate'];
			$i++;
		}

		echo car_rental_response("data", $car_rented_data);
		exit;
	}
}
