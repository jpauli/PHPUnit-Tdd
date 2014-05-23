<?php
use TDD\People;
use PHPUnit\Framework\TestCase;

class PeopleTest extends TestCase
{
    private $person;

    protected function setUp()
    {
        $this->person = new People('julien');
    }

    public function assertPreConditions()
    {
        $this->assertEquals($this->person->getName(), 'julien');
        $this->assertEquals($this->person->getName(), (string) $this->person);
        $this->assertEquals($this->person->getFloor(), 0);
    }

    public function testPeopleCanChangeFloor()
    {
        $this->assertInstanceOf(People::class, $this->person->setFloor(6));
        $this->assertEquals($this->person->getFloor(), 6);
    }

    public function testPeopleCantChangeFloorwithIrregularFloor()
    {
        $this->person->setFloor(-6);
        $this->assertEquals($this->person->getFloor(), 6);
    }
}
