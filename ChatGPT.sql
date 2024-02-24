-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : ven. 23 fév. 2024 à 14:55
-- Version du serveur : 10.10.2-MariaDB
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ChatGPT`
--

-- --------------------------------------------------------

--
-- Structure de la table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `lastUpdate` datetime NOT NULL,
  `sendingDate` datetime DEFAULT NULL,
  `activityType` int(11) NOT NULL,
  `recipientReference` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `realDeadline` datetime DEFAULT NULL,
  `estimatedDuration` int(11) DEFAULT NULL,
  `realDuration` int(11) DEFAULT NULL,
  `tags` longtext DEFAULT NULL,
  `mailTo` longtext DEFAULT NULL,
  `mailCC` longtext DEFAULT NULL,
  `mailCCI` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `authorUser` int(11) DEFAULT NULL,
  `authorCompany` int(11) DEFAULT NULL,
  `authorContact` int(11) DEFAULT NULL,
  `authorPerson` int(11) DEFAULT NULL,
  `recipientUser` int(11) DEFAULT NULL,
  `recipientCompany` int(11) DEFAULT NULL,
  `recipientContact` int(11) DEFAULT NULL,
  `recipientPerson` int(11) DEFAULT NULL,
  `linkedBusiness` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B5F1AFE5818DB401` (`authorUser`),
  KEY `IDX_B5F1AFE59CF2E17D` (`authorCompany`),
  KEY `IDX_B5F1AFE59F2F0E0A` (`authorContact`),
  KEY `IDX_B5F1AFE5AFC01811` (`authorPerson`),
  KEY `IDX_B5F1AFE55529DE9F` (`recipientUser`),
  KEY `IDX_B5F1AFE525D6908F` (`recipientCompany`),
  KEY `IDX_B5F1AFE5260B7FF8` (`recipientContact`),
  KEY `IDX_B5F1AFE5ED65E367` (`recipientPerson`),
  KEY `IDX_B5F1AFE5EEA5D584` (`linkedBusiness`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `activityAttachments`
--

DROP TABLE IF EXISTS `activityAttachments`;
CREATE TABLE IF NOT EXISTS `activityAttachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` int(11) DEFAULT NULL,
  `fileName` varchar(255) NOT NULL,
  `fileType` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `GEDFile` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F7F1BB71AC74095A` (`activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `API`
--

DROP TABLE IF EXISTS `API`;
CREATE TABLE IF NOT EXISTS `API` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `API`
--

INSERT INTO `API` (`id`, `key`, `description`, `value`) VALUES
(1, 'API_CHATGPT_ENABLED', NULL, 'true'),
(2, 'API_CHATGPT_BEARER_TOKEN', NULL, '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'),
(3, 'API_CHATGPT_VERSION', NULL, '1'),
(4, 'API_CHATGPT_MODEL', NULL, 'gpt-3.5-turbo');

-- --------------------------------------------------------

--
-- Structure de la table `areas`
--

DROP TABLE IF EXISTS `areas`;
CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `barcodes`
--

DROP TABLE IF EXISTS `barcodes`;
CREATE TABLE IF NOT EXISTS `barcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(255) NOT NULL,
  `barcodeType` int(11) NOT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF48A564C922E447` (`productReference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `BOUsers`
--

DROP TABLE IF EXISTS `BOUsers`;
CREATE TABLE IF NOT EXISTS `BOUsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `BOuser_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) DEFAULT NULL,
  `contact` int(11) DEFAULT NULL,
  `businessStatus` int(11) NOT NULL,
  `creationDate` date NOT NULL,
  `businessComment` varchar(255) DEFAULT NULL,
  `folderPath` varchar(255) DEFAULT NULL,
  `chronoNumber` int(11) NOT NULL,
  `businessType` int(11) DEFAULT NULL,
  `businessManager` int(11) DEFAULT NULL,
  `naturalPerson` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2DCA55EC970AB45C` (`businessManager`),
  KEY `IDX_2DCA55ECC7440455` (`client`),
  KEY `IDX_2DCA55EC4C62E638` (`contact`),
  KEY `IDX_2DCA55EC1ABDC7A2` (`naturalPerson`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `channels`
--

DROP TABLE IF EXISTS `channels`;
CREATE TABLE IF NOT EXISTS `channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `firstIndex` int(11) NOT NULL,
  `lastIndex` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `externalID` int(11) DEFAULT NULL,
  `internalID` varchar(255) DEFAULT NULL,
  `clientID` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `codeNAF` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `usefulData` longtext DEFAULT NULL,
  `SIRET` varchar(255) DEFAULT NULL,
  `companyType` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `globalDiscount` int(11) DEFAULT NULL,
  `outstandingAllowedAmount` double DEFAULT NULL,
  `outstandingCurrentAmount` double DEFAULT NULL,
  `intraCommunityVAT` varchar(255) DEFAULT NULL,
  `VATRate` double DEFAULT NULL,
  `franchiseName` varchar(255) DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `parentCompany` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8244AA3A36221496` (`parentCompany`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` int(11) DEFAULT NULL,
  `site` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `naturalPerson` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_334015731ABDC7A2` (`naturalPerson`),
  KEY `IDX_334015734FBF094F` (`company`),
  KEY `IDX_33401573694309E4` (`site`),
  KEY `IDX_334015738D93D649` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discountProfiles`
--

DROP TABLE IF EXISTS `discountProfiles`;
CREATE TABLE IF NOT EXISTS `discountProfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) DEFAULT NULL,
  `profileType` int(11) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `naturalPerson` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B0066B0AC7440455` (`client`),
  KEY `IDX_B0066B0A1ABDC7A2` (`naturalPerson`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `profile` int(11) DEFAULT NULL,
  `discountType` int(11) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL,
  `naturalPerson` int(11) DEFAULT NULL,
  `productReference` int(11) DEFAULT NULL,
  `productReferenceFamily` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FC5702B8C7440455` (`client`),
  KEY `IDX_FC5702B81ABDC7A2` (`naturalPerson`),
  KEY `IDX_FC5702B8D34A04AD` (`product`),
  KEY `IDX_FC5702B8C922E447` (`productReference`),
  KEY `IDX_FC5702B8F906B5DC` (`productReferenceFamily`),
  KEY `IDX_FC5702B88157AA0F` (`profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `eventLogs`
--

DROP TABLE IF EXISTS `eventLogs`;
CREATE TABLE IF NOT EXISTS `eventLogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventDate` datetime NOT NULL,
  `eventType` int(11) NOT NULL,
  `targetEntity` varchar(255) DEFAULT NULL,
  `oldData` longtext DEFAULT NULL,
  `newData` longtext DEFAULT NULL,
  `loggedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C19B54B7DAF03B` (`loggedUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `expertiseFields`
--

DROP TABLE IF EXISTS `expertiseFields`;
CREATE TABLE IF NOT EXISTS `expertiseFields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fiscalYears`
--

DROP TABLE IF EXISTS `fiscalYears`;
CREATE TABLE IF NOT EXISTS `fiscalYears` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fiscalYearName` varchar(255) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) DEFAULT NULL,
  `inventoryDate` datetime NOT NULL,
  `comment` longtext DEFAULT NULL,
  `additionalData` longtext DEFAULT NULL,
  `startedOn` datetime DEFAULT NULL,
  `completedOn` datetime DEFAULT NULL,
  `controlCompletedOn` datetime DEFAULT NULL,
  `inventoryManager` int(11) DEFAULT NULL,
  `controlManager` int(11) DEFAULT NULL,
  `scheduleReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_936C863DCC76670D` (`inventoryManager`),
  KEY `IDX_936C863DD9CB2F46` (`controlManager`),
  KEY `IDX_936C863D5E9E89CB` (`location`),
  KEY `IDX_936C863DBE7967E6` (`scheduleReference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventorySchedules`
--

DROP TABLE IF EXISTS `inventorySchedules`;
CREATE TABLE IF NOT EXISTS `inventorySchedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) DEFAULT NULL,
  `schedulePeriod` longtext DEFAULT NULL,
  `inventoryParameters` longtext DEFAULT NULL,
  `bypassProductRecount` tinyint(1) NOT NULL,
  `lastInventory` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2940A1DC5E9E89CB` (`location`),
  KEY `IDX_2940A1DC657DC86B` (`lastInventory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` int(11) DEFAULT NULL,
  `selfBalancingVAT` tinyint(1) NOT NULL DEFAULT 0,
  `creditNote` tinyint(1) NOT NULL DEFAULT 0,
  `netOfTax` tinyint(1) NOT NULL DEFAULT 0,
  `paymentDate` date DEFAULT NULL,
  `situation` int(11) DEFAULT NULL,
  `metadata` longtext DEFAULT NULL,
  `workflow` bigint(20) DEFAULT NULL,
  `paymentMethod` bigint(20) DEFAULT NULL,
  `paymentState` bigint(20) DEFAULT NULL,
  `paymentComment` longtext DEFAULT NULL,
  `filePath` varchar(255) DEFAULT NULL,
  `fiscalYear` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2F2F95F2ACAE13` (`fiscalYear`),
  KEY `IDX_6A2F2F956B71CBF4` (`quote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `naturalPersons`
--

DROP TABLE IF EXISTS `naturalPersons`;
CREATE TABLE IF NOT EXISTS `naturalPersons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT 'represent the type of client : 1=prospect, 2=client (named status to match the field used for this information in companies table',
  `naturalPersonType` int(11) DEFAULT NULL COMMENT 'used to distinct a client form another type of contact',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notificationChannels`
--

DROP TABLE IF EXISTS `notificationChannels`;
CREATE TABLE IF NOT EXISTS `notificationChannels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` int(11) DEFAULT NULL,
  `notification` int(11) DEFAULT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6B34EB2AA2F98E47` (`channel`),
  KEY `IDX_6B34EB2ABF5476CA` (`notification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `level` int(11) NOT NULL,
  `informations` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notificationSubscriptions`
--

DROP TABLE IF EXISTS `notificationSubscriptions`;
CREATE TABLE IF NOT EXISTS `notificationSubscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `subscriptionJSON` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EC5C49688D93D649` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paymentDetails`
--

DROP TABLE IF EXISTS `paymentDetails`;
CREATE TABLE IF NOT EXISTS `paymentDetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) DEFAULT NULL,
  `paymentMeans` varchar(255) DEFAULT NULL,
  `IBAN` varchar(255) DEFAULT NULL,
  `BIC` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_61904E03C7440455` (`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `priceUpdates`
--

DROP TABLE IF EXISTS `priceUpdates`;
CREATE TABLE IF NOT EXISTS `priceUpdates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` int(11) DEFAULT NULL,
  `price` double NOT NULL,
  `updateDate` datetime NOT NULL,
  `priceType` int(11) NOT NULL,
  `providerReference` varchar(255) DEFAULT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3E5F657FC922E447` (`productReference`),
  KEY `IDX_3E5F657F92C4739C` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productCompositions`
--

DROP TABLE IF EXISTS `productCompositions`;
CREATE TABLE IF NOT EXISTS `productCompositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `proportion` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1919936249FEA157` (`component`),
  KEY `IDX_19199362D34A04AD` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productReferenceFamilies`
--

DROP TABLE IF EXISTS `productReferenceFamilies`;
CREATE TABLE IF NOT EXISTS `productReferenceFamilies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `defaultCode` varchar(255) DEFAULT NULL,
  `referenceLabelSuffix` varchar(255) DEFAULT NULL,
  `recountPeriodicity` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `parentFamily` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1AFF3AFDE4435C52` (`parentFamily`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productReferences`
--

DROP TABLE IF EXISTS `productReferences`;
CREATE TABLE IF NOT EXISTS `productReferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit` int(11) DEFAULT NULL,
  `family` int(11) DEFAULT NULL,
  `referenceLabel` varchar(255) NOT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `composed` tinyint(1) NOT NULL,
  `recountPeriodicity` varchar(255) DEFAULT NULL,
  `photoURL` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `additionalDescription` longtext DEFAULT NULL,
  `disused` tinyint(1) NOT NULL DEFAULT 0,
  `internalID` varchar(255) DEFAULT NULL,
  `archivedOn` datetime DEFAULT NULL,
  `obsoleteOn` datetime DEFAULT NULL,
  `discontinuedOn` datetime DEFAULT NULL,
  `launchDate` datetime DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `brandID` varchar(255) DEFAULT NULL,
  `height` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `length` double DEFAULT NULL,
  `depth` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `diameter` double DEFAULT NULL,
  `section` double DEFAULT NULL,
  `customsNomenclature` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `lowStockCeiling` double DEFAULT NULL,
  `highStockCeiling` double DEFAULT NULL,
  `area` longtext DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `managedInStock` tinyint(1) NOT NULL DEFAULT 1,
  `taxRate` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F37F39DCDCBB0C53` (`unit`),
  KEY `IDX_F37F39DCA5E6215B` (`family`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) DEFAULT NULL,
  `quantity` double NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `currentWeightedAverageValue` double DEFAULT NULL,
  `area` longtext DEFAULT NULL,
  `traceabilityData` longtext DEFAULT NULL,
  `lowStockCeiling` double DEFAULT NULL,
  `highStockCeiling` double DEFAULT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B3BA5A5AC922E447` (`productReference`),
  KEY `IDX_B3BA5A5A5E9E89CB` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productTimes`
--

DROP TABLE IF EXISTS `productTimes`;
CREATE TABLE IF NOT EXISTS `productTimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` int(11) DEFAULT NULL,
  `days` double NOT NULL,
  `timeType` int(11) NOT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CDB9332C922E447` (`productReference`),
  KEY `IDX_CDB933292C4739C` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ProjectConfig`
--

DROP TABLE IF EXISTS `ProjectConfig`;
CREATE TABLE IF NOT EXISTS `ProjectConfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quantityUnits`
--

DROP TABLE IF EXISTS `quantityUnits`;
CREATE TABLE IF NOT EXISTS `quantityUnits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `shortLabel` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_label_unique` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quoteLines`
--

DROP TABLE IF EXISTS `quoteLines`;
CREATE TABLE IF NOT EXISTS `quoteLines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` int(11) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `lineType` smallint(6) NOT NULL,
  `content` longtext DEFAULT NULL,
  `referenceLabel` varchar(255) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `days` double DEFAULT NULL,
  `daysTotal` double DEFAULT 0,
  `timeType` smallint(6) DEFAULT 0,
  `basePrice` double DEFAULT NULL,
  `basePriceTotal` double DEFAULT 0,
  `priceType` smallint(6) DEFAULT 0,
  `quantity` double DEFAULT NULL,
  `costPrice` double DEFAULT 0,
  `unitPrice` double DEFAULT 0,
  `totalPrice` double DEFAULT 0,
  `locked` tinyint(1) NOT NULL,
  `discount` double DEFAULT 0,
  `lineOrder` int(11) DEFAULT 0,
  `quantityUnit` varchar(255) DEFAULT NULL,
  `deliveredQuantity` double DEFAULT NULL,
  `deliveryProblemType` int(11) DEFAULT NULL,
  `deliveryProblemComment` longtext DEFAULT NULL,
  `VATValue` double NOT NULL DEFAULT 0,
  `VATType` int(11) DEFAULT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_41028C036B71CBF4` (`quote`),
  KEY `IDX_41028C033D8E604F` (`parent`),
  KEY `IDX_41028C03C922E447` (`productReference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) DEFAULT NULL,
  `buyer` int(11) DEFAULT NULL,
  `seller` int(11) DEFAULT NULL,
  `business` int(11) DEFAULT NULL,
  `chronoNumber` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `tnc` longtext DEFAULT NULL,
  `validityPeriod` longtext DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `deliveryDate` date DEFAULT NULL,
  `lastSyncDate` datetime DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `hourlyRate` double NOT NULL DEFAULT 0,
  `coefficient` double NOT NULL DEFAULT 0,
  `discount` double DEFAULT 0,
  `tradeDiscount` double DEFAULT 0,
  `tradeDiscountType` int(11) DEFAULT 0,
  `proRata` double NOT NULL DEFAULT 0,
  `travelExpenses` double NOT NULL DEFAULT 0,
  `computedHourlyRate` double NOT NULL DEFAULT 0,
  `computedCoefficient` double NOT NULL DEFAULT 0,
  `discountAmount` double NOT NULL DEFAULT 0,
  `workforceHours` double NOT NULL DEFAULT 0,
  `hoursCost` double NOT NULL DEFAULT 0,
  `purchases` double NOT NULL DEFAULT 0,
  `sales` double NOT NULL DEFAULT 0,
  `workforceSharePercent` double NOT NULL DEFAULT 0,
  `purchasesSharePercent` double NOT NULL DEFAULT 0,
  `costPrice` double NOT NULL DEFAULT 0,
  `grossMargin` double NOT NULL DEFAULT 0,
  `totalAmount` double NOT NULL DEFAULT 0,
  `entityType` int(11) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `referenceNumber` varchar(255) DEFAULT NULL,
  `referenceDate` datetime DEFAULT NULL,
  `businessManager` int(11) DEFAULT NULL,
  `currentVersion` int(11) DEFAULT NULL,
  `referenceQuote` int(11) DEFAULT NULL,
  `buyerPerson` int(11) DEFAULT NULL,
  `sellerPerson` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A1B588C5BDAFD8C8` (`author`),
  KEY `IDX_A1B588C5970AB45C` (`businessManager`),
  KEY `IDX_A1B588C58E5BD7B2` (`currentVersion`),
  KEY `IDX_A1B588C580566C4B` (`referenceQuote`),
  KEY `IDX_A1B588C584905FB3` (`buyer`),
  KEY `IDX_A1B588C54E11BF79` (`buyerPerson`),
  KEY `IDX_A1B588C5FB1AD3FC` (`seller`),
  KEY `IDX_A1B588C519402361` (`sellerPerson`),
  KEY `IDX_A1B588C58D36E38` (`business`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quoteVersionLines`
--

DROP TABLE IF EXISTS `quoteVersionLines`;
CREATE TABLE IF NOT EXISTS `quoteVersionLines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `lineType` smallint(6) NOT NULL,
  `content` longtext DEFAULT NULL,
  `referenceLabel` varchar(255) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `days` double DEFAULT 0,
  `daysTotal` double DEFAULT 0,
  `timeType` smallint(6) DEFAULT 0,
  `basePrice` double DEFAULT 0,
  `basePriceTotal` double DEFAULT 0,
  `priceType` smallint(6) DEFAULT 0,
  `quantity` double DEFAULT 0,
  `costPrice` double DEFAULT 0,
  `unitPrice` double DEFAULT 0,
  `totalPrice` double DEFAULT 0,
  `locked` tinyint(1) NOT NULL,
  `discount` double DEFAULT 0,
  `lineOrder` int(11) DEFAULT 0,
  `quantityUnit` varchar(255) DEFAULT NULL,
  `quoteVersion` int(11) NOT NULL,
  `productReference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_787A9BF5B32AA39F` (`quoteVersion`),
  KEY `IDX_787A9BF5C922E447` (`productReference`),
  KEY `IDX_787A9BF53D8E604F` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quoteVersions`
--

DROP TABLE IF EXISTS `quoteVersions`;
CREATE TABLE IF NOT EXISTS `quoteVersions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` int(11) DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `tnc` longtext NOT NULL,
  `validityPeriod` longtext NOT NULL,
  `hourlyRate` double NOT NULL DEFAULT 0,
  `coefficient` double NOT NULL DEFAULT 0,
  `discount` double DEFAULT 0,
  `proRata` double NOT NULL DEFAULT 0,
  `travelExpenses` double NOT NULL DEFAULT 0,
  `computedHourlyRate` double NOT NULL DEFAULT 0,
  `computedCoefficient` double NOT NULL DEFAULT 0,
  `discountAmount` double NOT NULL DEFAULT 0,
  `workforceHours` double NOT NULL DEFAULT 0,
  `hoursCost` double NOT NULL DEFAULT 0,
  `purchases` double NOT NULL DEFAULT 0,
  `sales` double NOT NULL DEFAULT 0,
  `workforceSharePercent` double NOT NULL DEFAULT 0,
  `purchasesSharePercent` double NOT NULL DEFAULT 0,
  `costPrice` double NOT NULL DEFAULT 0,
  `grossMargin` double NOT NULL DEFAULT 0,
  `totalAmount` double NOT NULL DEFAULT 0,
  `versionName` varchar(255) NOT NULL,
  `versionComment` longtext NOT NULL,
  `versionSentTo` longtext NOT NULL,
  `versionDate` datetime NOT NULL,
  `lastSyncDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7B0D6B7D6B71CBF4` (`quote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `RFModules`
--

DROP TABLE IF EXISTS `RFModules`;
CREATE TABLE IF NOT EXISTS `RFModules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `releaseNote` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfmodule_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `RFModulesProjectDependencies`
--

DROP TABLE IF EXISTS `RFModulesProjectDependencies`;
CREATE TABLE IF NOT EXISTS `RFModulesProjectDependencies` (
  `id` int(11) NOT NULL,
  `Project_dependency_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`Project_dependency_id`),
  KEY `IDX_C30FC3BFBF396750` (`id`),
  KEY `IDX_C30FC3BF81AFC5FD` (`Project_dependency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `RFModulesRFDependencies`
--

DROP TABLE IF EXISTS `RFModulesRFDependencies`;
CREATE TABLE IF NOT EXISTS `RFModulesRFDependencies` (
  `id` int(11) NOT NULL,
  `RF_dependency_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`RF_dependency_id`),
  KEY `IDX_63CEC33EBF396750` (`id`),
  KEY `IDX_63CEC33EF75E4717` (`RF_dependency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sites`
--

DROP TABLE IF EXISTS `sites`;
CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` int(11) DEFAULT NULL,
  `contact1` int(11) DEFAULT NULL,
  `contact2` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `technicalData` longtext DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `additionalData` longtext DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BC00AA63D7943D68` (`area`),
  KEY `IDX_BC00AA63AB9235CF` (`contact1`),
  KEY `IDX_BC00AA63329B6475` (`contact2`),
  KEY `IDX_BC00AA63C7440455` (`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `smsTemplates`
--

DROP TABLE IF EXISTS `smsTemplates`;
CREATE TABLE IF NOT EXISTS `smsTemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `smsTemplates_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stockStates`
--

DROP TABLE IF EXISTS `stockStates`;
CREATE TABLE IF NOT EXISTS `stockStates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventorist` int(11) DEFAULT NULL,
  `inventory` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `stateDate` datetime NOT NULL,
  `quantity` double NOT NULL,
  `verificationQuantity` double DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `controlUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_204682161190A649` (`inventorist`),
  KEY `IDX_20468216EC949BDE` (`controlUser`),
  KEY `IDX_20468216B12D4A36` (`inventory`),
  KEY `IDX_20468216D34A04AD` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stockUpdates`
--

DROP TABLE IF EXISTS `stockUpdates`;
CREATE TABLE IF NOT EXISTS `stockUpdates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `updateDate` datetime NOT NULL,
  `quantityUpdate` double NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `updateType` int(11) DEFAULT NULL,
  `priceUpdate` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B9E01FB9D34A04AD` (`product`),
  KEY `IDX_B9E01FB93E78D4B2` (`priceUpdate`),
  KEY `IDX_B9E01FB98D93D649` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `traceabilities`
--

DROP TABLE IF EXISTS `traceabilities`;
CREATE TABLE IF NOT EXISTS `traceabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceProduct` int(11) DEFAULT NULL,
  `finalProduct` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EBEE0A36533BFF54` (`sourceProduct`),
  KEY `IDX_EBEE0A3615CA51A5` (`finalProduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `userChannels`
--

DROP TABLE IF EXISTS `userChannels`;
CREATE TABLE IF NOT EXISTS `userChannels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `channel` int(11) DEFAULT NULL,
  `currentIndex` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_26277F978D93D649` (`user`),
  KEY `IDX_26277F97A2F98E47` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `roles` int(11) NOT NULL,
  `securityToken` varchar(255) DEFAULT NULL,
  `cookieToken` varchar(255) DEFAULT NULL,
  `securityTokenExpiration` date DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT 0,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `CGUValidated` tinyint(1) DEFAULT 0,
  `CGUValidatedDate` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `FK_B5F1AFE525D6908F` FOREIGN KEY (`recipientCompany`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE5260B7FF8` FOREIGN KEY (`recipientContact`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE55529DE9F` FOREIGN KEY (`recipientUser`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE5818DB401` FOREIGN KEY (`authorUser`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE59CF2E17D` FOREIGN KEY (`authorCompany`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE59F2F0E0A` FOREIGN KEY (`authorContact`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE5AFC01811` FOREIGN KEY (`authorPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE5ED65E367` FOREIGN KEY (`recipientPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5F1AFE5EEA5D584` FOREIGN KEY (`linkedBusiness`) REFERENCES `businesses` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `activityAttachments`
--
ALTER TABLE `activityAttachments`
  ADD CONSTRAINT `FK_F7F1BB71AC74095A` FOREIGN KEY (`activity`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `barcodes`
--
ALTER TABLE `barcodes`
  ADD CONSTRAINT `FK_BF48A564C922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `businesses`
--
ALTER TABLE `businesses`
  ADD CONSTRAINT `FK_2DCA55EC1ABDC7A2` FOREIGN KEY (`naturalPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DCA55EC4C62E638` FOREIGN KEY (`contact`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DCA55EC970AB45C` FOREIGN KEY (`businessManager`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_2DCA55ECC7440455` FOREIGN KEY (`client`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `FK_8244AA3A36221496` FOREIGN KEY (`parentCompany`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `FK_334015731ABDC7A2` FOREIGN KEY (`naturalPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_334015734FBF094F` FOREIGN KEY (`company`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_33401573694309E4` FOREIGN KEY (`site`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_334015738D93D649` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `discountProfiles`
--
ALTER TABLE `discountProfiles`
  ADD CONSTRAINT `FK_B0066B0A1ABDC7A2` FOREIGN KEY (`naturalPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B0066B0AC7440455` FOREIGN KEY (`client`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `FK_FC5702B81ABDC7A2` FOREIGN KEY (`naturalPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FC5702B88157AA0F` FOREIGN KEY (`profile`) REFERENCES `discountprofiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FC5702B8C7440455` FOREIGN KEY (`client`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FC5702B8C922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FC5702B8D34A04AD` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FC5702B8F906B5DC` FOREIGN KEY (`productReferenceFamily`) REFERENCES `productreferencefamilies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `eventLogs`
--
ALTER TABLE `eventLogs`
  ADD CONSTRAINT `FK_C19B54B7DAF03B` FOREIGN KEY (`loggedUser`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `FK_936C863D5E9E89CB` FOREIGN KEY (`location`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_936C863DBE7967E6` FOREIGN KEY (`scheduleReference`) REFERENCES `inventoryschedules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_936C863DCC76670D` FOREIGN KEY (`inventoryManager`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_936C863DD9CB2F46` FOREIGN KEY (`controlManager`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `inventorySchedules`
--
ALTER TABLE `inventorySchedules`
  ADD CONSTRAINT `FK_2940A1DC5E9E89CB` FOREIGN KEY (`location`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2940A1DC657DC86B` FOREIGN KEY (`lastInventory`) REFERENCES `inventories` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `FK_6A2F2F956B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6A2F2F95F2ACAE13` FOREIGN KEY (`fiscalYear`) REFERENCES `fiscalyears` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notificationChannels`
--
ALTER TABLE `notificationChannels`
  ADD CONSTRAINT `FK_6B34EB2AA2F98E47` FOREIGN KEY (`channel`) REFERENCES `channels` (`id`),
  ADD CONSTRAINT `FK_6B34EB2ABF5476CA` FOREIGN KEY (`notification`) REFERENCES `notifications` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notificationSubscriptions`
--
ALTER TABLE `notificationSubscriptions`
  ADD CONSTRAINT `FK_EC5C49688D93D649` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paymentDetails`
--
ALTER TABLE `paymentDetails`
  ADD CONSTRAINT `FK_61904E03C7440455` FOREIGN KEY (`client`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `priceUpdates`
--
ALTER TABLE `priceUpdates`
  ADD CONSTRAINT `FK_3E5F657F92C4739C` FOREIGN KEY (`provider`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3E5F657FC922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `productCompositions`
--
ALTER TABLE `productCompositions`
  ADD CONSTRAINT `FK_1919936249FEA157` FOREIGN KEY (`component`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_19199362D34A04AD` FOREIGN KEY (`product`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `productReferenceFamilies`
--
ALTER TABLE `productReferenceFamilies`
  ADD CONSTRAINT `FK_1AFF3AFDE4435C52` FOREIGN KEY (`parentFamily`) REFERENCES `productreferencefamilies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `productReferences`
--
ALTER TABLE `productReferences`
  ADD CONSTRAINT `FK_F37F39DCA5E6215B` FOREIGN KEY (`family`) REFERENCES `productreferencefamilies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F37F39DCDCBB0C53` FOREIGN KEY (`unit`) REFERENCES `quantityunits` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5A5E9E89CB` FOREIGN KEY (`location`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B3BA5A5AC922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `productTimes`
--
ALTER TABLE `productTimes`
  ADD CONSTRAINT `FK_CDB933292C4739C` FOREIGN KEY (`provider`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDB9332C922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quoteLines`
--
ALTER TABLE `quoteLines`
  ADD CONSTRAINT `FK_41028C033D8E604F` FOREIGN KEY (`parent`) REFERENCES `quotelines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_41028C036B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_41028C03C922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `FK_A1B588C519402361` FOREIGN KEY (`sellerPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1B588C54E11BF79` FOREIGN KEY (`buyerPerson`) REFERENCES `naturalpersons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1B588C580566C4B` FOREIGN KEY (`referenceQuote`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1B588C584905FB3` FOREIGN KEY (`buyer`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1B588C58D36E38` FOREIGN KEY (`business`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1B588C58E5BD7B2` FOREIGN KEY (`currentVersion`) REFERENCES `quoteversions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_A1B588C5970AB45C` FOREIGN KEY (`businessManager`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_A1B588C5BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_A1B588C5FB1AD3FC` FOREIGN KEY (`seller`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quoteVersionLines`
--
ALTER TABLE `quoteVersionLines`
  ADD CONSTRAINT `FK_787A9BF53D8E604F` FOREIGN KEY (`parent`) REFERENCES `quoteversionlines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_787A9BF5B32AA39F` FOREIGN KEY (`quoteVersion`) REFERENCES `quoteversions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_787A9BF5C922E447` FOREIGN KEY (`productReference`) REFERENCES `productreferences` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `quoteVersions`
--
ALTER TABLE `quoteVersions`
  ADD CONSTRAINT `FK_7B0D6B7D6B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `RFModulesProjectDependencies`
--
ALTER TABLE `RFModulesProjectDependencies`
  ADD CONSTRAINT `FK_C30FC3BF81AFC5FD` FOREIGN KEY (`Project_dependency_id`) REFERENCES `rfmodules` (`id`),
  ADD CONSTRAINT `FK_C30FC3BFBF396750` FOREIGN KEY (`id`) REFERENCES `rfmodules` (`id`);

--
-- Contraintes pour la table `RFModulesRFDependencies`
--
ALTER TABLE `RFModulesRFDependencies`
  ADD CONSTRAINT `FK_63CEC33EBF396750` FOREIGN KEY (`id`) REFERENCES `rfmodules` (`id`),
  ADD CONSTRAINT `FK_63CEC33EF75E4717` FOREIGN KEY (`RF_dependency_id`) REFERENCES `rfmodules` (`id`);

--
-- Contraintes pour la table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `FK_BC00AA63329B6475` FOREIGN KEY (`contact2`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BC00AA63AB9235CF` FOREIGN KEY (`contact1`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BC00AA63C7440455` FOREIGN KEY (`client`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BC00AA63D7943D68` FOREIGN KEY (`area`) REFERENCES `areas` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stockStates`
--
ALTER TABLE `stockStates`
  ADD CONSTRAINT `FK_204682161190A649` FOREIGN KEY (`inventorist`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_20468216B12D4A36` FOREIGN KEY (`inventory`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_20468216D34A04AD` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_20468216EC949BDE` FOREIGN KEY (`controlUser`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stockUpdates`
--
ALTER TABLE `stockUpdates`
  ADD CONSTRAINT `FK_B9E01FB93E78D4B2` FOREIGN KEY (`priceUpdate`) REFERENCES `priceupdates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B9E01FB98D93D649` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B9E01FB9D34A04AD` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traceabilities`
--
ALTER TABLE `traceabilities`
  ADD CONSTRAINT `FK_EBEE0A3615CA51A5` FOREIGN KEY (`finalProduct`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EBEE0A36533BFF54` FOREIGN KEY (`sourceProduct`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `userChannels`
--
ALTER TABLE `userChannels`
  ADD CONSTRAINT `FK_26277F978D93D649` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_26277F97A2F98E47` FOREIGN KEY (`channel`) REFERENCES `channels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
