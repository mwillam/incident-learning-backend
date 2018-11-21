<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="report")
 */
class Report extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable="true")
     */
    private $reporterName;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice({
     *     "nurse",
     *     "mta",
     *     "physician",
     *     "physicist"
     * })
     */
    private $reporterProfession;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice({"w", "m"})
     */
    private $patientSex;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min=0,
     *     max=150,
     *     minMessage="Give a valid age over {{ limit }} years.",
     *     maxMessage="Nobody is that old."
     * )
     */
    private $patientAge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $whileEmergency;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice({"prevention","diagnosing","non-invasive","invasive","organization","other"})
     */
    private $context;

    /**
     * @ORM\Column(type="text")
     */
    private $textWhatHappened;

    /**
     * @ORM\Column(type="text")
     */
    private $textSituationNow;

    /**
     * @ORM\Column(type="text")
     */
    private $textHowToPrevent;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min=0,
     *     max=10,
     *     invalidMessage="Give a valid indicator between 0 and 10."
     * )
     */
    private $patientHarmed;

    /**
     * @ORM\Column(type="json_array")
     */
    private $contributingFactors;

    /**
     * @ORM\Column(type="integer", nullable="true")
     * @Assert\Range(
     *     min=0,
     *     max=10,
     *     invalidMessage="Give a valid indicator between 0 and 10."
     * )
     */
    private $occurrence;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getReporterName()
    {
        return $this->reporterName;
    }

    /**
     * @param mixed $reporterName
     */
    public function setReporterName($reporterName): void
    {
        $this->reporterName = $reporterName;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getTextWhatHappened()
    {
        return $this->textWhatHappened;
    }

    /**
     * @param mixed $textWhatHappened
     */
    public function setTextWhatHappened($textWhatHappened): void
    {
        $this->textWhatHappened = $textWhatHappened;
    }
}
