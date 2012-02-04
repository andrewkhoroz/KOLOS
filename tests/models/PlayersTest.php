<?php
require_once dirname(__FILE__) . '/../TestConfiguration.php';

require_once '../application/models/Players.php';

class Models_PlayersTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // reset database to known state
        TestConfiguration::setupDatabase();       
    }

    public function testFetchAll()
    {
        $playersFinder = new Players();
        $players = $playersFinder->fetchAll();
        
        $this->assertSame(3, $players->count());
    }
    
    public function testFetchLatest()
    {
        $playersFinder = new Places();
        $players = $playersFinder->fetchLatest(1);
        
        $this->assertSame(1, $players->count());
        
        $thisPlayer = $players->current();
        $this->assertSame(2, (int)$thisPlayer->id);
    }
}
