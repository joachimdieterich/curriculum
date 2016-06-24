<?php

class WebTest extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl(BASE_URL);
    }

    public function testTitle() {
        $this->open(BASE_URL);
        $this->assertTitle('regexp:^Login');
    }
}

?>
