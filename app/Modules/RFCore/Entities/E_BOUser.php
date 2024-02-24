<?php /** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_BOUserRepository") @Table(name="BOUsers", uniqueConstraints={@UniqueConstraint(name="BOuser_email_unique", columns={"email"})})
 **/
class E_BOUser extends RF_Entity
{
    public function __construct($params = null)
	{
		parent::__construct($params);
	}

	public function update($params)
	{
		if (key_exists('password', $params)) $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
		parent::update($params);
	}

	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

	/** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")**/
	protected $id;

	/** @Column(type="string")**/
	protected $email;

	/** @Column(type="string") **/
	protected $password;

	// =================================================================================================================
	// =================================================================================================================
	// GETTERS & SETTERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getEmail() : string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * @param string $pass
	 */
	public function setPassword(string $pass)
	{
		$this->password = password_hash($pass, PASSWORD_BCRYPT);
	}

    /**
     * @return string
     */
    public function getPassword(): string
	{
        return $this->password;
    }

    public function toJson(): array
	{
        $ret = array();
        $ret['id'] = $this->id;
        $ret['username'] = $this->email;
        return $ret;
    }
}
