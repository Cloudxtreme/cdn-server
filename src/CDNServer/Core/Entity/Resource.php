<?php

namespace CDNServer\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Resource
 *
 * @ORM\Entity(repositoryClass="CDNServer\Core\Repository\ResourceRepository")
 * @ORM\Table(name="resource")
 *
 * @package CDNServer\Core\Entity
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class Resource
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string $filename
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    protected $filename;

    /**
     * @var \DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    protected $creationDate;

    /**
     * @var boolean $refresh
     * @ORM\Column(name="refresh", type="boolean", nullable=false)
     */
    protected $refresh;

    /**
     * @var Project $project
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    protected $project;

    public function __construct($name, $filename, Project $project)
    {
        $this->name = $name;
        $this->filename = $filename;
        $this->project = $project;
        $this->refresh = false;
        $this->creationDate = new \DateTime('now');
    }

    public function getPath()
    {
        return $this->getProject()->getFullPath().'/'.$this->getFilename();
    }

    public function getId()             { return $this->id; }
    public function getName()           { return $this->name; }
    public function getFilename()       { return $this->filename; }
    public function getCreationDate()   { return $this->creationDate; }
    public function getRefresh()        { return $this->refresh; }
    public function getProject()        { return $this->project; }

    public function setName($n)             { $this->name = $n; }
    public function setFilename($f)         { $this->filename = $f; }
    public function setCreationDate($cd)    { $this->creationDate = $cd; }
    public function setRefresh($r)          { $this->refresh = $r; }
    public function setProject(Project $p)  { $this->project = $p; }
}
