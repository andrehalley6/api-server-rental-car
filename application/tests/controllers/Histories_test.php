<?php

class Histories_test extends TestCase {
	public function test_client() {
        // Set header 'Accept' array to application/json
        $this->request->setHeader('Accept', 'application/json');

        // This is unit test for get all clients rental histories
        try {
            $output = $this->request('GET', 'histories/client/1');
        } catch (CIPHPUnitTestExitException $e) {
            $output = ob_get_clean();
        }
        $this->assertContains("{\"id\": 1,\"name\": \"Ahmad Nurwanto\",\"gender\": \"male\",\"histories\": [{\"brand\": \"Honda\",\"type\":\"Civic\",\"plate\":\"D 1234 HND\",\"date-from\":\"2016-08-17\",\"date-to\":\"2016-08-19\"},{\"brand\": \"Toyota\",\"type"\: \"Yaris\",\"plate\":\"D 4444 TYT\",\"date-from\":\"2016-08-29\",\"date-to\":\"2016-08-29\"}]}", $output);
    }

    public function test_car() {
        // Set header 'Accept' array to application/json
        $this->request->setHeader('Accept', 'application/json');

        // This is unit test for get all clients rental histories
        try {
            $output = $this->request('GET', 'histories/car?month=08-2016');
        } catch (CIPHPUnitTestExitException $e) {
            $output = ob_get_clean();
        }
        $this->assertContains("{\"id\": 1,\"brand\": \"Honda\",\"type\":\"Civic\",\"plate\":\"D 1234 HND\",\"histories\": [{\"rent-by\": \"Ahmad Nurwanto\",\"date-from\":\"2016-08-17\",\"date-to\":\"2016-08-19\"},{\"rent-by\": \"Rizky\",\"date-from\":\"2016-08-20\",\"date-to\":\"2016-08-20\"},{\"rent-by\": \"Ihsan\",\"date-from\":\"2016-08-22\",\"date-to\":\"2016-08-24\"},{\"rent-by\": \"Ahmad\",\"date-from\":\"2016-08-26\",\"date-to\":\"2016-08-28\"}]}", $output);
    }
}