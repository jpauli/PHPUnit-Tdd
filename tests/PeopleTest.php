<?php

use PHPUnit\Framework\TestCase;
use TDD\People;

class PeopleTest extends TestCase
{
    private People $person;

    protected function setUp() : void
    {
        $this->person = new People('julien');
    }

    public function assertPreConditions() : void
    {
        $this->assertEquals('julien', $this->person->name);
        $this->assertEquals($this->person->name, (string) $this->person);
        $this->assertEquals(0, $this->person->getFloor());
    }

    public function testPersonChangesFloor()
    {
        $this->assertInstanceOf(People::CLASS,$this->person->setFloor(6));
        $this->assertEquals(6, $this->person->getFloor());
    }

    public function testPersonChangesFloorNotCorrectly()
    {
        $this->person->setFloor(-6);
        $this->assertEquals(6, $this->person->getFloor());
    }
}