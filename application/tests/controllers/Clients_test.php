<?php

class Clients_test extends TestCase {
	public function test_index() {
        // Set header 'Accept' array to application/json
        $this->request->setHeader('Accept', 'application/json');
		
        // This is unit test for add clients
        try {
           $output = $this->request('POST', 'clients', [
           	'name' => 'Ahmad',
           	'gender' => 'male'
           ]);
        } catch (CIPHPUnitTestExitException $e) {
           $output = ob_get_clean();
        }
        $this->assertContains("{\"id\":30}", $output);

        // This is unit test for edit clients
        try {
        //     $output = $this->request('PUT', 'clients', json_encode([
        //         'id' => 1, 
        //         'name' => 'Andre', 
        //         'gender' => 'male'
        //     ]));
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Update successful.\"}", $output);

        // This is unit test for delete clients
        // try {
        //     $output = $this->request('DELETE', 'clients', 'id=1');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Delete successful.\"}", $output);

        // This is unit test for get all clients
        // try {
        //     $output = $this->request('GET', 'clients');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{"data":[{"id":"1","name":"Ahmad Nurwanto","gender":"male"},{"id":"2","name":"Ahmad","gender":"male"}]}", $output);
    }
}