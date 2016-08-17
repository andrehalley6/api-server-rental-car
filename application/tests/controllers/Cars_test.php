<?php

class Cars_test extends TestCase {
	public function test_index() {
        // Set header 'Accept' array to application/json
        $this->request->setHeader('Accept', 'application/json');
		
        // This is unit test for add cars
        try {
            $output = $this->request('POST', 'cars', [
           	    'brand' => 'Honda',
                'type' => 'civic',
                'year' => 2011,
                'color' => 'Black',
           	    'plate' => 'D 1234 HND'
            ]);
        } catch (CIPHPUnitTestExitException $e) {
           $output = ob_get_clean();
        }
        $this->assertContains("{\"id\":30}", $output);

        // This is unit test for edit cars
        // try {
        //     $output = $this->request('PUT', 'cars', json_encode([
        //         'id' => 1, 
        //         'brand' => 'Honda',
        //         'type' => 'civic',
        //         'year' => 2011,
        //         'color' => 'Blue',
        //         'plate' => 'D 1234 HND'
        //     ]));
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Update successful.\"}", $output);

        // This is unit test for delete cars
        // try {
        //     $output = $this->request('DELETE', 'cars', 'id=1');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{\"status\":\"Delete successful.\"}", $output);

        // This is unit test for get all cars
        // try {
        //     $output = $this->request('GET', 'cars');
        // } catch (CIPHPUnitTestExitException $e) {
        //     $output = ob_get_clean();
        // }
        // $this->assertContains("{"data":[{"id":"1","brand":"Ahmad Nurwanto","type":"male","year":"2011","color":"Black","plate":"D 1234 HND"}]}", $output);
    }
}