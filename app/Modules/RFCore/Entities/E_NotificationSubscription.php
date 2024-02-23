<?php /** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="notificationSubscriptions")
 **/
class E_NotificationSubscription extends RF_Entity
{
	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

    /** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")**/
	protected $id;

	/** @Column(type="text") */
	protected $subscriptionJSON;

	// =================================================================================================================
	// FOREIGN KEYS
	// =================================================================================================================

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
	 * @JoinColumn(name="user",referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

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
	 * @return E_User|mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getSubscriptionJSON(): string
	{
		return $this->subscriptionJSON;
	}

	/**
	 * @return array
	 */
	public function getSubscriptionJSONDecoded(): array
	{
		return json_decode($this->subscriptionJSON,true);
	}
}
