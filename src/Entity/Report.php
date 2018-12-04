<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 * @ORM\Table(name="reports")
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice({"w", "m"})
     */
    private $patientSex;

    /**
     * @ORM\Column(type="integer", nullable=true)
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
    private $whileEmergency=0;

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
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $contributingFactors;

    /**
     * @ORM\Column(type="integer", nullable=true)
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
    public function getReporterProfession()
    {
        return $this->reporterProfession;
    }

    /**
     * @param mixed $reporterProfession
     */
    public function setReporterProfession($reporterProfession): void
    {
        $this->reporterProfession = $reporterProfession;
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
    public function getPatientSex()
    {
        return $this->patientSex;
    }

    /**
     * @param mixed $patientSex
     */
    public function setPatientSex($patientSex): void
    {
        $this->patientSex = $patientSex;
    }

    /**
     * @return mixed
     */
    public function getPatientAge()
    {
        return $this->patientAge;
    }

    /**
     * @param mixed $patientAge
     */
    public function setPatientAge($patientAge): void
    {
        $this->patientAge = $patientAge;
    }

    /**
     * @return mixed
     */
    public function getWhileEmergency()
    {
        return $this->whileEmergency;
    }

    /**
     * @param mixed $whileEmergency
     */
    public function setWhileEmergency($whileEmergency): void
    {
        $this->whileEmergency = $whileEmergency;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context): void
    {
        $this->context = $context;
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

    /**
     * @return mixed
     */
    public function getTextSituationNow()
    {
        return $this->textSituationNow;
    }

    /**
     * @param mixed $textSituationNow
     */
    public function setTextSituationNow($textSituationNow): void
    {
        $this->textSituationNow = $textSituationNow;
    }

    /**
     * @return mixed
     */
    public function getTextHowToPrevent()
    {
        return $this->textHowToPrevent;
    }

    /**
     * @param mixed $textHowToPrevent
     */
    public function setTextHowToPrevent($textHowToPrevent): void
    {
        $this->textHowToPrevent = $textHowToPrevent;
    }

    /**
     * @return mixed
     */
    public function getPatientHarmed()
    {
        return $this->patientHarmed;
    }

    /**
     * @param mixed $patientHarmed
     */
    public function setPatientHarmed($patientHarmed): void
    {
        $this->patientHarmed = $patientHarmed;
    }

    /**
     * @return mixed
     */
    public function getContributingFactors()
    {
        return $this->contributingFactors;
    }

    /**
     * @param mixed $contributingFactors
     */
    public function setContributingFactors($contributingFactors): void
    {
        $this->contributingFactors = $contributingFactors;
    }

    /**
     * @return mixed
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * @param mixed $occurrence
     */
    public function setOccurrence($occurrence): void
    {
        $this->occurrence = $occurrence;
    }

    public function setCreatedNow()
    {
        $this->date = $this->roundDateTime(new \DateTime('now'));
    }

    private function roundDateTime(\DateTime $dateTime)
    {
        $microseconds = $dateTime->format('u');
        $roundedDateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'));
        if ($microseconds > 500000) {
            $roundedDateTime->add(new \DateInterval('PT1S'));
        }
        return $roundedDateTime;
    }

}
