<?php /** @noinspection PhpUnused */

/** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_NotificationRepository") @Table(name="notifications")
 **/
class E_Notification extends RF_Entity
{
	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

    /** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")**/
	protected $id;

    /** @column(type="date") */
    protected $date;

    /** @column(type="integer") */
    protected $level;

    /** @column(type="string") */
    protected $informations;

	// =================================================================================================================
	// =================================================================================================================
	// GETTERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * @return mixed
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getDate() : DateTime
	{
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getLevel() : int
	{
		return $this->level;
	}

	/**
	 * @return mixed
	 */
	public function getInformations() : string
	{
		return $this->informations;
	}
}
