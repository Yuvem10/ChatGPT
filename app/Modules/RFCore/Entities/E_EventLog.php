<?php /** @noinspection PhpUnused */

/** @noinspection PhpUnusedAliasInspection */

namespace RFCore\Entities;

use Doctrine\{Common\Collections\ArrayCollection,
	ORM\Mapping\Column,
	ORM\Mapping\Entity,
	ORM\Mapping\Id,
	ORM\Mapping\JoinColumn,
	ORM\Mapping\ManyToOne,
	ORM\Mapping\UniqueConstraint};
use DateTime;

/**
 * @Entity @Table(name="eventLogs")
 **/
class E_EventLog extends RF_Entity
{
	// EVENT LOG ENTITY PROPERTIES
	public const ELP_EVENT_TYPE 	= 'eventType';
	public const ELP_NEW_DATA		= 'newData';
	public const ELP_OLD_DATA 		= 'oldData';
	public const ELP_TARGET_ENTITY 	= 'targetEntity';

	public function __construct($params = null)
	{
		$this->eventDate = new DateTime();
		parent::__construct($params);
	}

	// =================================================================================================================
	// =================================================================================================================
	// PROPERTIES
	// =================================================================================================================
	// =================================================================================================================

	/** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY") **/
	protected $id;

    /** @Column(type="datetime") **/
    protected $eventDate;

    /** @Column(type="integer") **/
    protected $eventType;

    /** @Column(type="string", nullable=true) **/
    protected $targetEntity;

    /** @Column(type="text", nullable=true) **/
    protected $oldData;

    /** @Column(type="text", nullable=true) **/
    protected $newData;

	// =================================================================================================================
	// FOREIGN KEYS
	// =================================================================================================================

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
	 * @JoinColumn(name="loggedUser",referencedColumnName="id", onDelete="SET NULL", nullable=true)
	 */
	protected $loggedUser;

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
	 * @return DateTime
	 */
	public function getEventDate(): DateTime
	{
		return $this->eventDate;
	}

	/**
	 * @return int
	 */
	public function getEventType(): int
	{
		return $this->eventType;
	}

	/**
	 * @return E_User|null|mixed
	 */
	public function getLoggedUser()
	{
		return $this->loggedUser;
	}

	/**
	 * @return string|null
	 */
	public function getTargetEntity(): ?string
	{
		return $this->targetEntity;
	}

	/**
	 * @return string|null
	 */
	public function getOldData(): ?string
	{
		return $this->oldData;
	}

	/**
	 * @return string|null
	 */
	public function getNewData(): ?string
	{
		return $this->newData;
	}
}
