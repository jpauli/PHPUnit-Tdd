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
* Person class
*
* This class is designed to show how to practice Test Driven Developement
*
* @author Julien Pauli <jpauli@php.net>
* @copyright 2010 Julien Pauli <jpauli@php.net>
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://julien-pauli.developpez.com/tutoriels/php/phpunit-tdd/
* @version Release: @package_version@
*/
class Personne
{
    /**
     * name
     *
     * @var string
     */
    protected $nom;

    /**
     * floor
     *
     * @var int
     */
    protected $etage = 0;

    /**
     * Constructor
     *
     * @param string $nom the name
     */
    public function __construct($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Sets the floor
     *
     * @param int $etage the floor number
     */
    public function setEtage($etage)
    {
        $this->etage = abs((int)$etage);
        return $this;
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
     * Gets the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->nom;
    }

    /**
     * Stringification
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
