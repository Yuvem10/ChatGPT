<?php /** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\{Common\Collections\ArrayCollection,
	ORM\Mapping\Column,
	ORM\Mapping\Entity,
	ORM\Mapping\Id,
	ORM\Mapping\UniqueConstraint};
use DateTime;

/**
 * @Entity @Table(name="users", uniqueConstraints={@UniqueConstraint(name="user_email_unique", columns={"email"})})
 **/
class E_User extends RF_Entity
{
    public function update($params)
    {
        if (key_exists('password', $params) && !empty($params['password']))
        {
            $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
        }
        else
        {
            unset($params['password']);
        }
        parent::update($params);
    }

    public function __construct($params = null)
	{
		parent::__construct($params);
	}

	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

	/** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY") **/
	protected $id;

    /** @Column(type="string") **/
    protected $email;

    /** @Column(type="string", nullable=true) **/
	protected $password;

    /** @Column(type="integer") **/
    protected $roles;

    /** @Column(type="string", nullable=true) **/
    protected $securityToken;

    /** @Column(type="string", nullable=true) **/
    protected $cookieToken;

    /** @Column(type="date", nullable=true) **/
    protected $securityTokenExpiration;

    /** @Column(type="boolean", nullable=true, options={"default" : "0"}) **/
    protected $isActive;

    /** @Column(type="string", nullable=true) **/
    protected $firstname;

    /** @Column(type="string", nullable=true) **/
    protected $lastname;

    /** @Column(type="string", nullable=true) **/
    protected $phone;

    /** @Column(type="boolean", nullable=true, options={"default" : "0"}) **/
    protected $CGUValidated;

    /** @Column(type="datetime", nullable=true) **/
    protected $CGUValidatedDate;

	/** @Column(type="string", nullable=true) **/
	protected $avatar;

	// =================================================================================================================
	// =================================================================================================================
	// GETTERS
	// =================================================================================================================
	// =================================================================================================================

    /**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

    /**
     * @return string
     */
    public function getPassword(): ?string
	{
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRoles(): int
	{
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getEmail(): string
	{
        return $this->email;
    }

	/**
	 * @return mixed
	 */
	public function getSecurityToken() : ?string
	{
		return $this->securityToken;
	}

	/**
	 * @return mixed
	 */
	public function getSecurityTokenExpiration() : ?DateTime
	{
		return $this->securityTokenExpiration;
	}

	/**
	 * @return mixed
	 */
	public function getIsActive() : ?bool
	{
		return $this->isActive;
	}

	/**
	 * @return mixed
	 */
	public function getFirstname() : ?string
	{
		return $this->firstname;
	}

	/**
	 * @return mixed
	 */
	public function getLastname() : ?string
	{
		return $this->lastname;
	}

	/**
	 * @return mixed
	 */
	public function getPhone() : ?string
	{
		return $this->phone;
	}

    /**
     * @return mixed
     */
    public function getCookieToken() : ?string
    {
        return $this->cookieToken;
    }

    /**
     * @return bool
     */
    public function isCGUValidated(): bool
    {
        return $this->CGUValidated;
    }

    /**
     * @return mixed
     */
    public function getCGUValidatedDate() : ?DateTime
    {
        return $this->CGUValidatedDate;
    }

	/**
	 * @return mixed
	 */
	public function getAvatar() : ?string
	{
		return $this->avatar;
	}
}
