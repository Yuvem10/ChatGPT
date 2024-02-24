<?php /** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="userChannels")
 **/
class E_UserChannel extends RF_Entity
{
	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

    /** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")**/
    protected $id;

    /** @Column(type="integer") */
    protected $currentIndex;

	// =================================================================================================================
	// FOREIGN KEYS
	// =================================================================================================================

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
	 * @JoinColumn(name="user",referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_Channel",fetch="EAGER")
	 * @JoinColumn(name="channel",referencedColumnName="id")
	 */
	protected $channel;

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
     * @return E_User|mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return E_Channel|mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getCurrentIndex(): int
    {
        return $this->currentIndex;
    }
}
