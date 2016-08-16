<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rentals extends CI_Model {
	var $table;

	public function __construct() {
		parent::__construct();
		$this->table = "rentals";
	}

	public function get_all_rentals() {
		return $this->db->get($this->table);
	}

	public function get_rental_by_id($id) {
		$this->db->where("id", $id);
		return $this->db->get($this->table);
	}

	public function get_client_rental_histories($client_id) {
		$this->db->select("`c`.`brand`, `c`.`type`, `c`.`plate`, `r`.`date-from`, `r`.`date-to`");
		$this->db->from("`rentals` as `r`");
		$this->db->join("`cars` as `c`", "`r`.`car-id` = `c`.`id`", "left");
		$this->db->where("`r`.`client-id`", $client_id);

		return $this->db->get();

		// $query = $this->db->query("SELECT * FROM `rentals` as `r` LEFT JOIN `cars` as `c` ON `r`.`car-id` = `c`.`id` WHERE `r`.`client-id` = '$client_id'");

		// return $query;
	}

	public function get_rental_by_client_id_and_rent_date($client_id, $car_id, $start_rent, $end_rent) {
		$this->db->where("client-id", $client_id);
		$this->db->where("car-id <> ", $car_id);
		$this->db->where(" (('" . date("Y-m-d H:i:s", strtotime($start_rent)). "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($start_rent)) . "' <= `date-from` AND '" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' >= `date-to`)) ");

		return $this->db->get($this->table);
	}

	public function get_rental_by_car_id_and_rent_date($car_id, $start_rent, $end_rent) {
		$this->db->where("car-id", $car_id);
		$this->db->where(" (('" . date("Y-m-d H:i:s", strtotime($start_rent)). "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($start_rent)) . "' <= `date-from` AND '" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' >= `date-to`)) ");

		return $this->db->get($this->table);
	}

	public function check_current_car_rental($id, $start_rent, $end_rent) {
		$this->db->where("id != " . $id);
		$this->db->where(" (('" . date("Y-m-d H:i:s", strtotime($start_rent)). "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' NOT BETWEEN `date-from` AND `date-to`) OR ('" . date("Y-m-d H:i:s", strtotime($start_rent)) . "' <= `date-from` AND '" . date("Y-m-d H:i:s", strtotime($end_rent)) . "' >= `date-to`)) ");

		return $this->db->get($this->table);
	}

	public function add_rental() {
		$data = array(
			'car-id'	=> $this->input->post('car-id'),
			'client-id'	=> $this->input->post('client-id'),
			'date-from'	=> $this->input->post('date-from'),
			'date-to'	=> $this->input->post('date-to'),
		);

		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function edit_rental($id, $put_data) {
		$data = array(
			'car-id'	=> $put_data['car-id'],
			'client-id'	=> $put_data['client-id'],
			'date-from'	=> $put_data['date-from'],
			'date-to'	=> $put_data['date-to'],
		);

		$this->db->where("id", $id);
		return $this->db->update($this->table, $data);
	}

	public function delete_rental($id) {
		$this->db->where("id", $id);
		return $this->db->delete($this->table);
	}
}

/* End of file rentals.php */
/* Location: ./application/models/rentals.php */