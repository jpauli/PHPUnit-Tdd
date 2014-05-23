<?php
/**
* PHPUnit-Tdd
*
* Copyright (c) 2010, Julien Pauli <jpauli@php.net>.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
* * Redistributions of source code must retain the above copyright
* notice, this list of conditions and the following disclaimer.
*
* * Redistributions in binary form must reproduce the above copyright
* notice, this list of conditions and the following disclaimer in
* the documentation and/or other materials provided with the
* distribution.
*
* * Neither the name of Julien Pauli nor the names of his
* contributors may be used to endorse or promote products derived
* from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
* FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
* COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
* LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
* ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* @author Julien Pauli <jpauli@php.net>
* @copyright 2010 Julien Pauli <jpauli@php.net>
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace TDD;

/**
* Lift class
*
* This class is designed to show how to practice Test Driven Development.
*
* @author Julien Pauli <jpauli@php.net>
* @copyright 2010 Julien Pauli <jpauli@php.net>
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @version Release: @package_version@
*/
class Lift implements \Countable , \IteratorAggregate
{
    /**
     * Maximum top floor
     */
    private const MAX_FLOORS = 50;

    /**
     * Maximum number of persons
     */
    public const MAX_LOAD = 5;

    /**
     * Current floor
     */
    private int $floor;

    /**
     * Persons inside (aggregate)
     */
    private \SplObjectStorage $people;

    /**
     * Constructor
     */
    public function __construct(public readonly string $name, int $floor)
    {
        $this->people = new \SplObjectStorage;
        $this->go($floor);
    }

    /**
     * Look for a person in the aggregate storage
     */
    private function searchPeople(People $p) : bool
    {
        return $this->people->contains($p);
    }

    /**
     * Put Person in
     *
     * @throws DomainException
     */
    public function load(People $p) : self
    {
        if ($p->getFloor() != $this->getFloor()) {
            throw new \LogicException(sprintf("Person %s isn't at floor %d", $p->name, $this->getFloor()));
        }
        if ($this->searchPeople($p)) {
            throw new \DomainException("$p->name already exists");
        }
        if (count($this) == self::MAX_LOAD) {
            throw new \OverflowException('Overload : too many people carried');
        }
        $this->people->attach($p);

        return $this;
    }

    /**
     * Put Person out
     */
    public function unload(People $p) : self
    {
        $this->people->detach($p);

        return $this;
    }

    /**
     * Retrieve a Person form the lift
     *
     * @throws DomainException
     */
    public function getPeople(People $p) : People
    {
        if ($this->searchPeople($p)) {
            return $p;
        }
        throw new \UnexpectedValueException("This person isn't in the elevator");
    }

    /**
     * Is the lift empty from Person ?
     */
    public function isEmpty() : bool
    {
        return count($this) == 0;
    }

    /**
     * Make the lift move to a floor
     * @throws DomainException
     */
    public function go(int $floor) : self
    {
        if ($floor < 0 || $floor > self::MAX_FLOORS) {
            throw new \InvalidArgumentException('invalid floor number');
        }
        foreach ($this as $person) {
            $person->setFloor($floor);
        }
        $this->floor = $floor;

        return $this;
    }

    /**
     * Gets the floor
     */
    public function getFloor() : int
    {
        return $this->floor;
    }

    /**
     * Countable interface
     */
    public function count() : int
    {
        return count($this->people);
    }

    /**
     * IteratorAggregate interface
     */
    public function getIterator() : \Iterator
    {
        return $this->people;
    }
}
