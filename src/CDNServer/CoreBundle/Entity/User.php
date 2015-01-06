<?php
namespace CDNServer\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var string $firstName
	 *
	 * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
	 * @Assert\NotBlank
	 */
	protected $firstName;
	
	/**
	 * @var string $lastName
	 *
	 * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
	 * @Assert\NotBlank
	 */
	protected $lastName;

    /**
     * @var ArrayCollection<UserGroup> $userGroups
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(name="user_to_usergroup")
     */
    protected $userGroups;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function setEmail($e)		{ parent::setEmail($e); $this->setUsername($e); }
	
	public function getFirstName()		{ return $this->firstName; }
	public function getLastName()		{ return $this->lastName; }
    public function getUserGroups()		{ return $this->userGroups; }
    public function getStringRoles()    { return current($this->roles); }
	
	public function setFirstName($fn)	{ $this->firstName = $fn; }
	public function setLastName($ln)	{ $this->lastName = $ln; }
    public function setUserGroups($ugs) { $this->userGroups = $ugs; }
    public function setStringRoles($r)  { $this->roles[0] = $r; }
}
