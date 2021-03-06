<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rentals extends CI_Controller {
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
			// if request method is POST, then insert rental

			// Check required parameter
			if(empty($this->input->post('car-id')) || empty($this->input->post('client-id')) || empty($this->input->post('date-from')) || empty($this->input->post('date-to'))) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// * Car id and client id must be exists.
			$car = $this->cars_m->get_car_by_id($this->input->post('car-id'))->result_array();
			$client = $this->clients_m->get_client_by_id($this->input->post('client-id'))->result_array();
			if(empty($car) || empty($client)) {
				echo deliver_response(400, "fail", "Car id and client id must be exists.");
				exit;
			}

			// * Client is not rent another car at selected rent date.
			$client_rent_car = $this->rentals_m->get_rental_by_client_id_and_rent_date($this->input->post('client-id'), $this->input->post('car-id'), $this->input->post('date-from'), $this->input->post('date-to'))->result_array();
			if($client_rent_car || !empty($client_rent_car)) {
				echo deliver_response(400, "fail", "Client is rented another car at selected rent date.");
				exit;
			}

			// * Car is not rented at selected rent date.
			$car_not_available = $this->rentals_m->get_rental_by_car_id_and_rent_date($this->input->post('car-id'), $this->input->post('date-from'), $this->input->post('date-to'), $this->input->post('id'))->result_array();
			if(!empty($car_not_available) || $car_not_available) {
				echo deliver_response(400, "fail", "Car is rented at selected rent date.");
				exit;
			}

			// * Rented duration max 3 days
			if(strtotime($this->input->post('date-to')) > strtotime("+3 days", strtotime($this->input->post('date-from')))) {
				echo deliver_response(400, "fail", "Rented duration max 3 days");
				exit;
			}

			// * Rent date only between current day + 1 days until current date +7 days.
			if(strtotime($this->input->post('date-to')) < strtotime($this->input->post('date-from')) 
				|| ((strtotime($this->input->post('date-from')) < strtotime("today") || strtotime($this->input->post('date-from')) > strtotime("+1 days")) 
				&& (strtotime($this->input->post('date-from')) > strtotime("today +7") || strtotime($this->input->post('date-to')) > strtotime("+7 days")))
				) {
				echo deliver_response(400, "fail", "Rent date only between current day +1 days until current date +7 days.");
				exit;
			}

			// Insert rental data
			$id = $this->rentals_m->add_rental();
			echo car_rental_response("id", $id);
			exit;
		}
		elseif($this->request_method == "PUT") {
			// if request method is PUT, then edit current rental data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $put_data);

			// Check required parameter
			if(empty($put_data['id']) || empty($put_data['car-id']) || empty($put_data['client-id']) || empty($put_data['date-from']) || empty($put_data['date-to'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// * Car id and client id must be exists.
			$car = $this->cars_m->get_car_by_id($put_data['car-id'])->result_array();
			$client = $this->clients_m->get_client_by_id($put_data['client-id'])->result_array();
			if(empty($car) || empty($client)) {
				echo deliver_response(400, "fail", "Car id and client id must be exists.");
				exit;
			}

			// * Client is not rent another car at selected rent date.
			$client_rent_car = $this->rentals_m->get_rental_by_client_id_and_rent_date($put_data['client-id'], $put_data['car-id'], $put_data['date-from'], $put_data['date-to'])->result_array();
			if($client_rent_car || !empty($client_rent_car)) {
				echo deliver_response(400, "fail", "Client is rented another car at selected rent date.");
				exit;
			}

			// * Car is not rented at selected rent date.
			$car_not_available = $this->rentals_m->get_rental_by_car_id_and_rent_date($put_data['car-id'], $put_data['date-from'], $put_data['date-to'], $put_data['id'])->result_array();
			if(!empty($car_not_available) || $car_not_available) {
				echo deliver_response(400, "fail", "Car is rented at selected rent date.");
				exit;
			}

			// * Rented duration max 3 days
			if(strtotime($put_data['date-to']) > strtotime("+3 days", strtotime($put_data['date-from']))) {
				echo deliver_response(400, "fail", "Rented duration max 3 days");
				exit;
			}

			// * Rent date only between current day + 1 days until current date +7 days.
			if(strtotime($put_data['date-to']) < strtotime($put_data['date-from']) 
				|| ((strtotime($put_data['date-from']) < strtotime("today") || strtotime($put_data['date-from']) > strtotime("+1 days")) 
				&& (strtotime($put_data['date-from']) > strtotime("today +7") || strtotime($put_data['date-to']) > strtotime("+7 days")))
				) {
				echo deliver_response(400, "fail", "Rent date only between current day +1 days until current date +7 days.");
				exit;
			}

			// Check current id on database
			$car = $this->rentals_m->get_rental_by_id($put_data['id'])->result_array();
			if(empty($car)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Update current rental data
			if($this->rentals_m->edit_rental($put_data['id'], $put_data)) {
				echo car_rental_response("status", "Update successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "DELETE") {
			// if request method is DELETE, then delete current rental data

			// This is how we get data when the server request is DELETE / PUT, for POST and GET can use normal $_GET / $_POST
			parse_str(file_get_contents("php://input"), $delete_data);

			// Check required parameter
			if(empty($delete_data['id'])) {
				echo deliver_response(400, "fail", "All fields are required.");
				exit;
			}

			// Check current id on database
			$rental = $this->rentals_m->get_rental_by_id($delete_data['id'])->result_array();
			if(empty($rental)) {
				echo deliver_response(400, "ok", "The ID must be exists on the database");
				exit;
			}

			// Delete current rental data
			if($this->rentals_m->delete_rental($delete_data['id'])) {
				echo car_rental_response("status", "Delete successful.");
				exit;
			}
			else {
				echo deliver_response(500, "fail", "The ID must be exists on the database");
				exit;
			}
		}
		elseif($this->request_method == "GET") {
			// if request method is GET, then retrieve all rentals data

			$rentals = $this->rentals_m->get_all_rentals()->result_array();
			foreach($rentals as $rental) {
				$set_of_rentals[] = $rental;
			}

			echo car_rental_response("data", $set_of_rentals);
			exit;
		}
	}
}
