<?php

class Rentals_test extends TestCase {
	public function test_index() {
        // Set header 'Accept' array to application/json
        $this->request->setHeader('Accept', 'application/json');
		
        // This is unit test for add rentals
        try {
            $output = $this->request('POST', 'rentals', [
           	    'car-id'    => '1',
                'client-id' => '2',
                'date-from' => '2016-08-16',
           	    'date-to'   => '2016-08-18',
            ]);
        } catch (CIPHPUnitTestExitException $e) {
           $output = ob_get_clean();
        }
        $this->assertContains("{\"id\":1}", $output);

        // This is unit test for edit rentals
        // try {
        //     $output = $this->request('PUT', 'rentals', json_encode([
        //         'id' => 1, 
        //         'car-id'    => '1',
        //         'client-id' => '2',
        //         'date-from' => '2016-08-16',
        //         'date-to'   => '2016-08-18',
        //     ]));
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Update successful.\"}", $output);

        // This is unit test for delete rentals
        // try {
        //     $output = $this->request('DELETE', 'rentals', 'id=1');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Delete successful.\"}", $output);

        // This is unit test for get all rentals
        // try {
        //     $output = $this->request('GET', 'rentals');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"data\":[{\"id\":\"1\",\"name\":\"Ahmad\",\"brand\":\"Honda\",\"type\":\"civic\",\"plate\":\"D 1234 HND\",\"date-from\":\"2016-08-16\",\"date-to\":\"2016-08-18\"}]}", $output);
    }
}