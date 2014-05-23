<?php

use PHPUnit\Framework\TestCase;
use TDD\Lift;
use TDD\People;

class LiftTest extends TestCase
{
    private Lift $elevator;
    private People $person;

    protected function setUp() : void
    {
        $this->elevator = new Lift('lift 1', 3);
        $this->person   = new People('julien');
        $this->person->setFloor(3);
    }

    public function assertPreConditions() : void
    {
        $this->assertEquals('lift 1', $this->elevator->name);
        $this->assertEquals(3, $this->elevator->getFloor());
        $this->assertTrue($this->elevator->isEmpty());
    }

    public function testLiftMoving()
    {
        $this->elevator->go(8);
        $this->assertEquals(8, $this->elevator->getFloor());
        $this->elevator->go(2);
        $this->assertEquals(2, $this->elevator->getFloor());

        $this->expectException(\InvalidArgumentException::class);
        $this->elevator->go(-9);
    }

    public function testLiftMovingTooHighFails()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->elevator->go(200);
    }

    public function testLoadPeopleIntoElevator()
    {
        $this->assertTrue($this->elevator->isEmpty());
        $this->elevator->load($this->person);
        $this->elevator->load(clone $this->person);
        $this->assertCount(2, $this->elevator);
        $this->assertFalse($this->elevator->isEmpty());
    }

    public function testUnloadElevator()
    {
        $this->elevator->load($this->person);
        $this->assertFalse($this->elevator->isEmpty());
        $this->elevator->unload($this->person);
        $this->assertCount(0, $this->elevator);
        $this->assertTrue($this->elevator->isEmpty());
    }

    public function testGetPerson()
    {
        $this->elevator->load($this->person);
        $this->assertSame($this->person, $this->elevator->getPeople($this->person));
        $this->expectException(\UnexpectedValueException::class);
        $this->elevator->getPeople(new People('me'));
    }

    public function testLoadElevatorWithTwiceTheSamePersonFails()
    {
        $this->elevator->load($this->person);
        $this->expectException(\DomainException::class);
        $this->elevator->load($this->person);
    }

    public function testElevatorMovingMakePeopleOnboardMove()
    {
        $this->elevator->load($this->person);
        $this->elevator->go(8);
        $this->assertEquals(8, $this->elevator->getPeople($this->person)->getFloor());
    }

    public function testLoadElevatorWithSomeoneNotAtTheSameFloorFails()
    {
        $this->expectException(\LogicException::class);
        $this->elevator->load($this->person->setFloor(10));
    }

    public function testLiftOverload()
    {
        for ($i=0; $i< Lift::MAX_LOAD; $i++) {
            $this->elevator->load(clone $this->person);
        }
        $this->expectException(\OverflowException::class);
        $this->elevator->load(clone $this->person);
    }

    public function testIterator()
    {
        $this->elevator->load($this->person);
        $this->assertContains($this->person, $this->elevator);
    }
}
