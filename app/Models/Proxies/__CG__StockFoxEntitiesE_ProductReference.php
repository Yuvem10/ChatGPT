<?php

namespace DoctrineProxies\__CG__\StockFox\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class E_ProductReference extends \StockFox\Entities\E_ProductReference implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'referenceLabel', 'productName', 'code', 'composed', 'recountPeriodicity', 'photoURL', 'description', 'additionalDescription', 'disused', 'internalID', 'archivedOn', 'obsoleteOn', 'discontinuedOn', 'launchDate', 'brand', 'brandID', 'height', 'width', 'length', 'depth', 'weight', 'diameter', 'section', 'customsNomenclature', 'origin', 'lowStockCeiling', 'highStockCeiling', 'area', 'active', 'managedInStock', 'taxRate', 'unit', 'family', 'products', 'productTimes', 'priceUpdates', 'componentProductCompositions', 'productCompositions', 'nullableProperties'];
        }

        return ['__isInitialized__', 'id', 'referenceLabel', 'productName', 'code', 'composed', 'recountPeriodicity', 'photoURL', 'description', 'additionalDescription', 'disused', 'internalID', 'archivedOn', 'obsoleteOn', 'discontinuedOn', 'launchDate', 'brand', 'brandID', 'height', 'width', 'length', 'depth', 'weight', 'diameter', 'section', 'customsNomenclature', 'origin', 'lowStockCeiling', 'highStockCeiling', 'area', 'active', 'managedInStock', 'taxRate', 'unit', 'family', 'products', 'productTimes', 'priceUpdates', 'componentProductCompositions', 'productCompositions', 'nullableProperties'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (E_ProductReference $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getReferenceLabel(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReferenceLabel', []);

        return parent::getReferenceLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductName(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProductName', []);

        return parent::getProductName();
    }

    /**
     * {@inheritDoc}
     */
    public function getCode(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCode', []);

        return parent::getCode();
    }

    /**
     * {@inheritDoc}
     */
    public function isComposed(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isComposed', []);

        return parent::isComposed();
    }

    /**
     * {@inheritDoc}
     */
    public function getUnit(): \StockFox\Entities\E_QuantityUnit
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUnit', []);

        return parent::getUnit();
    }

    /**
     * {@inheritDoc}
     */
    public function getFamily(): \StockFox\Entities\E_ProductReferenceFamily
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFamily', []);

        return parent::getFamily();
    }

    /**
     * {@inheritDoc}
     */
    public function getProducts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProducts', []);

        return parent::getProducts();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductTimes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProductTimes', []);

        return parent::getProductTimes();
    }

    /**
     * {@inheritDoc}
     */
    public function getPriceUpdates()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPriceUpdates', []);

        return parent::getPriceUpdates();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastPriceUpdateByType($type)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastPriceUpdateByType', [$type]);

        return parent::getLastPriceUpdateByType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getComponentProductCompositions()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getComponentProductCompositions', []);

        return parent::getComponentProductCompositions();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductCompositions()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProductCompositions', []);

        return parent::getProductCompositions();
    }

    /**
     * {@inheritDoc}
     */
    public function getRecountPeriodicity(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRecountPeriodicity', []);

        return parent::getRecountPeriodicity();
    }

    /**
     * {@inheritDoc}
     */
    public function getPhotoURL(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhotoURL', []);

        return parent::getPhotoURL();
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', []);

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function getAdditionalDescription(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAdditionalDescription', []);

        return parent::getAdditionalDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function getInternalID(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInternalID', []);

        return parent::getInternalID();
    }

    /**
     * {@inheritDoc}
     */
    public function getArchivedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArchivedOn', []);

        return parent::getArchivedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function getObsoleteOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getObsoleteOn', []);

        return parent::getObsoleteOn();
    }

    /**
     * {@inheritDoc}
     */
    public function getDiscontinuedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDiscontinuedOn', []);

        return parent::getDiscontinuedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function getLaunchDate(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLaunchDate', []);

        return parent::getLaunchDate();
    }

    /**
     * {@inheritDoc}
     */
    public function getBrand(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBrand', []);

        return parent::getBrand();
    }

    /**
     * {@inheritDoc}
     */
    public function getBrandID(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBrandID', []);

        return parent::getBrandID();
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHeight', []);

        return parent::getHeight();
    }

    /**
     * {@inheritDoc}
     */
    public function getWidth(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWidth', []);

        return parent::getWidth();
    }

    /**
     * {@inheritDoc}
     */
    public function getLength(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLength', []);

        return parent::getLength();
    }

    /**
     * {@inheritDoc}
     */
    public function getDepth(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDepth', []);

        return parent::getDepth();
    }

    /**
     * {@inheritDoc}
     */
    public function getWeight(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWeight', []);

        return parent::getWeight();
    }

    /**
     * {@inheritDoc}
     */
    public function getDiameter(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDiameter', []);

        return parent::getDiameter();
    }

    /**
     * {@inheritDoc}
     */
    public function getSection(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSection', []);

        return parent::getSection();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomsNomenclature(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCustomsNomenclature', []);

        return parent::getCustomsNomenclature();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrigin(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrigin', []);

        return parent::getOrigin();
    }

    /**
     * {@inheritDoc}
     */
    public function getDisused(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisused', []);

        return parent::getDisused();
    }

    /**
     * {@inheritDoc}
     */
    public function getLowStockCeiling(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLowStockCeiling', []);

        return parent::getLowStockCeiling();
    }

    /**
     * {@inheritDoc}
     */
    public function getHighStockCeiling(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHighStockCeiling', []);

        return parent::getHighStockCeiling();
    }

    /**
     * {@inheritDoc}
     */
    public function getArea(bool $asArray = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArea', [$asArray]);

        return parent::getArea($asArray);
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isActive', []);

        return parent::isActive();
    }

    /**
     * {@inheritDoc}
     */
    public function isManagedInStock(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isManagedInStock', []);

        return parent::isManagedInStock();
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxRate(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxRate', []);

        return parent::getTaxRate();
    }

    /**
     * {@inheritDoc}
     */
    public function update($params)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'update', [$params]);

        return parent::update($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProperties', []);

        return parent::getProperties();
    }

    /**
     * {@inheritDoc}
     */
    public function updateNullablePropertiesArray()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'updateNullablePropertiesArray', []);

        return parent::updateNullablePropertiesArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty($propName, $asArray = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProperty', [$propName, $asArray]);

        return parent::getProperty($propName, $asArray);
    }

    /**
     * {@inheritDoc}
     */
    public function setDateProperty($propName, $dateString)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateProperty', [$propName, $dateString]);

        return parent::setDateProperty($propName, $dateString);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', []);

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function generateTargetEntityString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'generateTargetEntityString', []);

        return parent::generateTargetEntityString();
    }

    /**
     * {@inheritDoc}
     */
    public function getMandatoryProperties(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMandatoryProperties', []);

        return parent::getMandatoryProperties();
    }

    /**
     * {@inheritDoc}
     */
    public function checkForMissingFields(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkForMissingFields', []);

        return parent::checkForMissingFields();
    }

}