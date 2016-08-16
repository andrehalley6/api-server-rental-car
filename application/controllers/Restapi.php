<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restapi extends CI_Controller {
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
	
	public function clients($id = NULL) {
		if($this->request_method == "POST") {
			// if request method is POST, then insert client

			// Check required parameter
			if(empty($this->input->post('name')) || empty($this->input->post('gender'))) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Check gender value
			if($this->input->post('gender') != "male" && $this->input->post('gender') != "female") {
				echo deliver_response(400, "fail", "Gender must be “male” or “female”");
				exit;
			}

			// Insert client data
			$id = $this->clients->add_client();
			echo car_rental_response("id", $id);
			exit;
		}
		elseif($this->request_method == "PUT") {
			// if request method is PUT, then edit current client data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $put_data);

			// Check required parameter
			if(empty($put_data['id']) || empty($put_data['name']) || empty($put_data['gender'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Check gender value
			if($put_data['gender'] != "male" && $put_data['gender'] != "female") {
				echo deliver_response(400, "fail", "Gender must be “male” or “female”");
				exit;
			}

			// Check current id on database
			$client = $this->clients->get_client_by_id($put_data['id'])->result_array();
			if(empty($client)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Update current client data
			if($this->clients->edit_client($put_data['id'], $put_data)) {
				echo car_rental_response("status", "Update successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "DELETE") {
			// if request method is DELETE, then delete current client data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $delete_data);

			// Check required parameter
			if(empty($delete_data['id'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Check current id on database
			$client = $this->clients->get_client_by_id($delete_data['id'])->result_array();
			if(empty($client)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Delete current client data
			if($this->clients->delete_client($delete_data['id'])) {
				echo car_rental_response("status", "Delete successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "GET") {
			// if request method is GET, then retrieve all clients data

			$clients = $this->clients->get_all_clients()->result_array();
			foreach($clients as $client) {
				$set_of_clients[] = $client;
			}

			echo car_rental_response("data", $set_of_clients);
			exit;
		}
	}

	public function cars($id = NULL) {
		if($this->request_method == "POST") {
			// if request method is POST, then insert car

			// Check required parameter
			if(empty($this->input->post('brand')) || empty($this->input->post('type')) || empty($this->input->post('year')) || empty($this->input->post('color')) || empty($this->input->post('plate'))) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Plate cannot be duplicated
			$car = $this->cars->get_car_by_plate($this->input->post('plate'))->result_array();
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
			$id = $this->cars->add_car();
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
			$car = $this->cars->get_car_by_plate($put_data['plate'])->result_array();
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
			$car = $this->cars->get_car_by_id($put_data['id'])->result_array();
			if(empty($car)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Update current car data
			if($this->cars->edit_car($put_data['id'], $put_data)) {
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
			$car = $this->cars->get_car_by_id($delete_data['id'])->result_array();
			if(empty($car)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Delete current car data
			if($this->cars->delete_car($delete_data['id'])) {
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

			$cars = $this->cars->get_all_cars()->result_array();
			foreach($cars as $car) {
				$set_of_cars[] = $car;
			}

			echo car_rental_response("data", $set_of_cars);
			exit;
		}
	}
}
