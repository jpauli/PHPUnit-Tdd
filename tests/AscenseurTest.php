<?php
use TDD\Ascenseur;
use TDD\Personne;

class AscenseurTest extends PHPUnit_Framework_TestCase
{
    private $ascenseur;
    private $personne;

    protected function setUp()
    {
        $this->ascenseur = new Ascenseur('ascenseur 1', 3);
        $this->personne  = new Personne('julien');
        $this->personne->setEtage(3);
    }

    public function assertPreConditions()
    {
        $this->assertEquals($this->ascenseur->getName(), 'ascenseur 1');
        $this->assertEquals(3, $this->ascenseur->getEtage());
        $this->assertTrue($this->ascenseur->isEmpty());
    }

    public function testAscenseurBouge()
    {
        $this->ascenseur->go(8);
        $this->assertEquals(8, $this->ascenseur->getEtage());
        $this->ascenseur->go(2);
        $this->assertEquals(2, $this->ascenseur->getEtage());

        $this->setExpectedException("InvalidArgumentException", "Etage invalide");
        $this->ascenseur->go(-9.9);
    }

    public function testAscenseurMonteUnPeuTropHautEchoue()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->ascenseur->go(200);
    }

    public function testChargeAscenseurAvecPersonnes()
    {
        $this->assertTrue($this->ascenseur->isEmpty());
        $this->ascenseur->charger($this->personne);
        $this->ascenseur->charger(clone $this->personne);
        $this->assertEquals(count($this->ascenseur), 2);
        $this->assertFalse($this->ascenseur->isEmpty());
    }

    public function testDechargeAscenseur()
    {
        $this->ascenseur->charger($this->personne);
        $this->assertFalse($this->ascenseur->isEmpty());
        $this->ascenseur->decharger($this->personne);
        $this->assertEquals(count($this->ascenseur), 0);
        $this->assertTrue($this->ascenseur->isEmpty());
    }

    public function testGetPersonne()
    {
        $this->ascenseur->charger($this->personne);
        $this->assertSame($this->personne, $this->ascenseur->getPersonne($this->personne));
        $this->setExpectedException('UnexpectedValueException');
        $this->ascenseur->getPersonne(new Personne('personne'));
    }

    public function testChargeAscenseurAvecDeuxFoisLaMemePersonneEchoue()
    {
        $this->ascenseur->charger($this->personne);
        $this->setExpectedException('DomainException');
        $this->ascenseur->charger($this->personne);
    }

    public function testAscenseurMonteAvecDesPersonnes()
    {
        $this->ascenseur->charger($this->personne);
        $this->ascenseur->go(8);
        $this->assertEquals(8, $this->ascenseur->getPersonne($this->personne)->getEtage());
    }

    public function testChargeAscenseurAvecUnePersonnePasAuMemeEtageEchoue()
    {
        $this->setExpectedException('LogicException');
        $this->ascenseur->charger($this->personne->setEtage(10));
    }

    public function testSurchargeAscenseur()
    {
        for ($i=0; $i< Ascenseur::CHARGE_MAXI; $i++) {
            $this->ascenseur->charger(clone $this->personne);
        }
        $this->setExpectedException('OverflowException');
        $this->ascenseur->charger(clone $this->personne);
    }

    public function testIterator()
    {
        $this->ascenseur->charger($this->personne);
        $this->assertContains($this->personne, $this->ascenseur);
    }
}
