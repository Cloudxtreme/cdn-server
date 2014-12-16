<?php

namespace CDNServer\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Project
 *
 * @ORM\Entity(repositoryClass="CDNServer\CoreBundle\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package CDNServer\CoreBundle\Entity
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $ukey
     * @ORM\Column(name="ukey", type="string", length=32, nullable=true)
     */
    protected $ukey;

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
     * @var UserGroup $userGroup
     * @ORM\ManyToOne(targetEntity="UserGroup")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     * })
     */
    protected $userGroup;

    /**
     * @var ArrayCollection<Resource> $resources
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="project")
     * @ORM\OrderBy({"creationDate" = "ASC"})
     */
    protected $resources;

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

    public function getFullPath()
    {
        return $this->getUserGroup()->getPath().'/'.$this->getPath();
    }

    /**
     * @ORM\PrePersist()
     */
    public function generateKey()
    {
        if (!$this->ukey)
            $this->ukey = sha1($this->getName().time().'cdn-key');
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
            if (!mkdir($this->rootPath.$this->getUserGroup()->getPath().'/'.$this->path, 0777, true))
            {
                if (!$force)
                    $this->generatePath(true);
            }
        }
    }

    public function setRootPath($p)     { $this->rootPath = $p; }

    public function getId()             { return $this->id; }
    public function getUkey()           { return $this->ukey; }
    public function getName()           { return $this->name; }
    public function getPath()           { return $this->path; }
    public function getCreationDate()   { return $this->creationDate; }
    public function getUserGroup()      { return $this->userGroup; }
    public function getResources()		{ return $this->resources; }

    public function setUkey($k)                 { $this->ukey = $k; }
    public function setName($n)                 { $this->name = $n; }
    public function setPath($p)                 { $this->path = $p; }
    public function setCreationDate($cd)        { $this->creationDate = $cd; }
    public function setUserGroup(UserGroup $ug) { $this->userGroup = $ug; }
    public function setResources($rs)           { $this->resources = $rs; }
}
