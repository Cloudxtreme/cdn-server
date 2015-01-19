<?php

namespace CDNServer\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserGroup
 *
 * @ORM\Entity
 * @ORM\Table(name="user_group")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package CDNServer\Core\Entity
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */

class UserGroup
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
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var string $path
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    protected $path;

    /**
     * @var \DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    protected $creationDate;

    /**
     * @var ArrayCollection<Project> $projects
     * @ORM\OneToMany(targetEntity="Project", mappedBy="project")
     * @ORM\OrderBy({"creationDate" = "ASC"})
     */
    protected $projects;

    /**
     * @var ArrayCollection<User> $users
     * @ORM\ManyToMany(targetEntity="User", mappedBy="userGroups")
     */
    protected $users;

    /**
     * Shouldn't be persisted.
     *
     * @var string $rootPath
     */
    protected $rootPath = null;

    public function __construct()
    {
        $this->creationDate = new \DateTime('now');
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @ORM\PrePersist()
     * @param bool $force
     */
    public function generatePath($force = false)
    {
        if ((!$this->path || $force) && $this->rootPath)
        {
            $this->path = sha1($this->getName().time());
            if (!mkdir($this->rootPath.$this->path, 0777, true))
            {
                if (!$force)
                    $this->generatePath(true);
            }
        }
    }

    public function setRootPath($p)     { $this->rootPath = $p; }

    public function getId()             { return $this->id; }
    public function getName()           { return $this->name; }
    public function getPath()           { return $this->path; }
    public function getCreationDate()   { return $this->creationDate; }
    public function getProjects()		{ return $this->projects; }
    public function getUsers()		    { return $this->users; }

    public function setName($n)             { $this->name = $n; }
    public function setPath($p)                 { $this->path = $p; }
    public function setCreationDate($cd)    { $this->creationDate = $cd; }
    public function setProjects($ps)        { $this->projects = $ps; }
    public function setUsers($us)           { $this->users = $us; }
} 