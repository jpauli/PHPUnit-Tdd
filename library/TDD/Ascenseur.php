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
* @link http://julien-pauli.developpez.com/tutoriels/php/phpunit-tdd/
*/

namespace TDD;

/**
* Lift class
*
* This class is designed to show how to practice Test Driven Developement.
*
* @author Julien Pauli <jpauli@php.net>
* @copyright 2010 Julien Pauli <jpauli@php.net>
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://julien-pauli.developpez.com/tutoriels/php/phpunit-tdd/
* @version Release: @package_version@
*/
class Ascenseur implements \Countable , \IteratorAggregate
{
    /**
     * Maximum top floor
     *
     * @var int
     */
    const MAX_ETAGES = 50;

    /**
     * Maximum number of persons
     *
     * @var int
     */
    const CHARGE_MAXI = 5;

    /**
     * Lift name
     *
     * @var string
     */
    protected $nom;

    /**
     * Current floor
     *
     * @var int
     */
    protected $etage;

    /**
     * Persons inside (aggregate)
     *
     * @var SplObjectStorage
     */
    protected $personnes;

    /**
     * Constructor
     *
     * @param string $nom
     * @param int $etage
     */
    public function __construct($nom, $etage)
    {
        $this->nom       = $nom;
        $this->personnes = new \SplObjectStorage;// Better prefer dependency injection here
        $this->go($etage);
    }

    /**
     * Look for a person in the aggregate storage
     *
     * @param Personne $p
     * @return bool
     */
    protected function searchPersonne(Personne $p)
    {
        return $this->personnes->contains($p);
    }

    /**
     * Put Person in
     *
     * @param Personne $p
     * @return Ascenseur
     * @throws DomainException
     */
    public function charger(Personne $p)
    {
        if($p->getEtage() != $this->getEtage()) {
            throw new \LogicException(sprintf("La personne %s n'est pas à l'étage %d", $p->getName(), $this->getEtage()));
        }
        if($this->searchPersonne($p)) {
            throw new \DomainException("{$p->getName()} existe déja");
        }
        if (count($this) == self::CHARGE_MAXI) {
            throw new \OverflowException('Surcharge');
        }
        $this->personnes->attach($p);
        return $this;
    }

    /**
     * Put Person out
     *
     * @param Personne $p
     * @return Ascenseur
     */
    public function decharger(Personne $p)
    {
        $this->personnes->detach($p);
        return $this;
    }

    /**
     * Retrieve a Person form the lift
     *
     * @param Personne $p
     * @return Personne|void
     * @throws DomainException
     */
    public function getPersonne(Personne $p)
    {
        if($this->searchPersonne($p)) {
            return $p;
        }
        throw new \UnexpectedValueException("Cette personne n'est pas dans l'ascenseur");
    }

    /**
     * Is the lift empty from Person ?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this) == 0;
    }

    /**
     * Make the lift move to a floor
     *
     * @param int $a_l_etage desired floor
     * @return Ascenseur
     * @throws DomainException
     */
    public function go($etage)
    {
        if (!is_int($etage) ||
            $etage < 0      ||
            $etage > self::MAX_ETAGES) {
                throw new \InvalidArgumentException('Etage invalide');
        }
        foreach ($this as $personne) {
            $personne->setEtage($etage);
        }
        $this->etage = $etage;
        return $this;
    }

    /**
     * Gets the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->nom;
    }

    /**
     * Gets the floor
     *
     * @return int
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->personnes);
    }

    /**
     * IteratorAggregate interface
     *
     * @return Iterator
     */
    public function getIterator()
    {
        return $this->personnes;
    }
}
