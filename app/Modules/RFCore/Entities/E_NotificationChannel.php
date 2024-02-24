<?php /** @noinspection PhpUnused */

/** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="notificationChannels")
 **/
class E_NotificationChannel extends RF_Entity
{
	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

    /** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")**/
    protected $id;

    /** @Column(type="integer") */
    protected $index;

	// =================================================================================================================
	// FOREIGN KEYS
	// =================================================================================================================

     /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_Channel",fetch="EAGER")
     * @JoinColumn(name="channel",referencedColumnName="id")
     */
    protected $channel;

    /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_Notification",fetch="EAGER")
     * @JoinColumn(name="notification",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

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
	public function getIndex() : int
	{
		return $this->index;
	}

	/**
	 * @return mixed|E_Channel
	 */
	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 * @return mixed|E_Notification
	 */
	public function getNotification()
	{
		return $this->notification;
	}
}
