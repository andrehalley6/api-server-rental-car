<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cars_m extends CI_Model {
	var $table;

	public function __construct() {
		parent::__construct();
		$this->table = "cars";
	}

	public function get_all_cars() {
		return $this->db->get($this->table);
	}

	public function get_car_by_id($id) {
		$this->db->where("id", $id);
		return $this->db->get($this->table);
	}

	public function get_car_year_type_plate_by_id($id) {
		$this->db->select("id, year, type, plate");
		$this->db->where("id", $id);
		return $this->db->get($this->table);
	}

	public function get_car_by_plate($plate) {
		$this->db->where("plate", $plate);
		return $this->db->get($this->table);
	}

	public function get_car_free_information($dd, $mm, $yyyy) {
		$SQL = sprintf("SELECT `brand`, `type`, `plate` FROM `%s` WHERE `id` NOT IN (SELECT `car-id` FROM `rentals` WHERE ('%s' BETWEEN `date-from` AND `date-to`))", $this->table, date("$yyyy-$mm-$dd"));
		// $query = $this->db->query("SELECT * FROM `" . $this->table . "` WHERE `id` NOT IN (SELECT `car-id` FROM `rentals` WHERE ('" . date("$yyyy-$mm-$dd") . "' BETWEEN `date-from` AND `date-to`))");
		$query = $this->db->query($SQL);

		return $query;
	}

	public function add_car() {
		$data = array(
			'brand'	=> $this->input->post('brand'),
			'type'	=> $this->input->post('type'),
			'color'	=> $this->input->post('color'),
			'year'	=> $this->input->post('year'),
			'plate'	=> $this->input->post('plate'),
		);

		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function edit_car($id, $put_data) {
		$data = array(
			'brand'	=> $put_data['brand'],
			'type'	=> $put_data['type'],
			'color'	=> $put_data['color'],
			'year'	=> $put_data['year'],
			'plate'	=> $put_data['plate'],
		);

		$this->db->where("id", $id);
		return $this->db->update($this->table, $data);
	}

	public function delete_car($id) {
		$this->db->where("id", $id);
		return $this->db->delete($this->table);
	}
}

/* End of file Cars_m.php */
/* Location: ./application/models/Cars_m.php */