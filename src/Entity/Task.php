<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $nameLong;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task")
     * @ORM\JoinColumn(name="parent_task_id", referencedColumnName="id")
     */
    private $parentTask;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min=0,
     *     max=1000,
     *     invalidMessage="Must be between 0 and 1000."
     * )
     */
    private $riskFactor;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNameLong()
    {
        return $this->nameLong;
    }

    /**
     * @param mixed $nameLong
     */
    public function setNameLong($nameLong): void
    {
        $this->nameLong = $nameLong;
    }

    /**
     * @return mixed
     */
    public function getParentTask()
    {
        return $this->parentTask;
    }

    /**
     * @param mixed $parentTask
     */
    public function setParentTask($parentTask): void
    {
        $this->parentTask = $parentTask;
    }

    /**
     * @return mixed
     */
    public function getRiskFactor()
    {
        return $this->riskFactor;
    }

    /**
     * @param mixed $riskFactor
     */
    public function setRiskFactor($riskFactor): void
    {
        $this->riskFactor = $riskFactor;
    }

    //    private $dependentTasks;


}