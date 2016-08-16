<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Model {
	var $table;

	public function __construct() {
		parent::__construct();
		$this->table = "clients";
	}

	public function get_all_clients() {
		return $this->db->get($this->table);
	}

	public function get_client_by_id($id) {
		$this->db->where("id", $id);
		return $this->db->get($this->table);
	}

	public function add_client() {
		$data = array(
			'name'		=> $this->input->post('name'),
			'gender'	=> $this->input->post('gender'),
		);

		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function edit_client($id, $put_data) {
		$data = array(
			'name'		=> $put_data['name'],
			'gender'	=> $put_data['gender'],
		);

		$this->db->where("id", $id);
		return $this->db->update($this->table, $data);
	}

	public function delete_client($id) {
		$this->db->where("id", $id);
		return $this->db->delete($this->table);
	}
}

/* End of file clients.php */
/* Location: ./application/models/clients.php */