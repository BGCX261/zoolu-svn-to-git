SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `zoolu` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `zoolu`;

-- -----------------------------------------------------
-- Table `zoolu`.`actions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`actions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`categories` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idParentCategory` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' ,
  `idRootCategory` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `idCategoryTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `matchCode` VARCHAR(255) NULL DEFAULT NULL ,
  `lft` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `rgt` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `depth` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `lft` (`lft` ASC) ,
  INDEX `rgt` (`rgt` ASC) ,
  INDEX `idRootCategory` (`idRootCategory` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 67
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`categoryTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`categoryTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idCategories` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `idCategories` (`idCategories` ASC) ,
  CONSTRAINT `categoryTitles_ibfk_1`
    FOREIGN KEY (`idCategories` )
    REFERENCES `zoolu`.`categories` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 82
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`categoryTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`categoryTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`contacts` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Person, letzte Änderung' ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `idUnits` BIGINT(20) UNSIGNED NOT NULL ,
  `salutation` VARCHAR(255) NULL DEFAULT NULL ,
  `title` VARCHAR(255) NULL DEFAULT NULL ,
  `fname` VARCHAR(255) NOT NULL ,
  `sname` VARCHAR(255) NOT NULL ,
  `position` VARCHAR(255) NULL DEFAULT NULL ,
  `phone` VARCHAR(128) NULL DEFAULT NULL ,
  `mobile` VARCHAR(128) NULL DEFAULT NULL ,
  `fax` VARCHAR(128) NULL DEFAULT NULL ,
  `email` VARCHAR(128) NULL DEFAULT NULL ,
  `website` VARCHAR(128) NULL DEFAULT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`files`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`files` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fileId` VARCHAR(32) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `path` VARCHAR(500) NOT NULL ,
  `idParent` BIGINT(20) UNSIGNED NOT NULL ,
  `idParentTypes` INT(10) UNSIGNED NOT NULL ,
  `isS3Stored` TINYINT(4) NOT NULL DEFAULT '0' ,
  `isImage` TINYINT(4) NOT NULL DEFAULT '0' ,
  `filename` VARCHAR(500) NULL DEFAULT NULL ,
  `idFileTypes` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `size` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Filesize in KB' ,
  `extension` VARCHAR(10) NOT NULL ,
  `mimeType` VARCHAR(255) NOT NULL ,
  `version` INT(10) NOT NULL ,
  `archived` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 96
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idContacts` BIGINT(20) UNSIGNED NOT NULL ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idContacts` (`idContacts` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`idContacts` )
    REFERENCES `zoolu`.`contacts` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idContacts` BIGINT(20) UNSIGNED NOT NULL ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idContacts` (`idContacts` ASC) ,
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`idContacts` )
    REFERENCES `zoolu`.`contacts` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`contact-DEFAULT_CONTACT-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idContacts` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `idContacts` (`idContacts` ASC) ,
  CONSTRAINT `contact-DEFAULT_CONTACT-1-Instances_ibfk_1`
    FOREIGN KEY (`idContacts` )
    REFERENCES `zoolu`.`contacts` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`decorators`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`decorators` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`fieldPermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fieldPermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NOT NULL ,
  `idGroups` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fieldProperties`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fieldProperties` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  `idProperties` INT(10) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id`) ,
  INDEX `idFields` (`idFields` ASC) ,
  INDEX `idProperties` (`idProperties` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fieldTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fieldTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idFields` (`idFields` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 97
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fieldTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fieldTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idDecorator` BIGINT(20) UNSIGNED NOT NULL ,
  `sqlType` VARCHAR(30) NOT NULL ,
  `size` INT(10) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `defaultValue` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 26
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`fieldValidators`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fieldValidators` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  `idValidators` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFieldTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `idSearchFieldTypes` INT(10) NOT NULL DEFAULT '1' ,
  `idRelationPage` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `idCategory` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `sqlSelect` VARCHAR(2000) NULL DEFAULT NULL ,
  `columns` INT(10) UNSIGNED NOT NULL ,
  `height` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `isCoreField` TINYINT(1) NOT NULL DEFAULT '0' ,
  `isKeyField` TINYINT(1) NOT NULL DEFAULT '0' ,
  `isSaveField` TINYINT(1) NOT NULL DEFAULT '1' ,
  `isRegionTitle` TINYINT(1) NOT NULL DEFAULT '0' ,
  `isDependentOn` BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'must be an ID' ,
  `copyValue` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'decision if we addittionally write the value into the table (result: id and e.g. title in table)' ,
  PRIMARY KEY (`id`) ,
  INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 119
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fileAttributes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fileAttributes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `xDim` INT(10) NULL DEFAULT NULL ,
  `yDim` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `fileAttributes_ibfk_1`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 66
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`filePermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`filePermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NOT NULL ,
  `idGroups` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fileTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fileTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `fileTitles_ibfk_1`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 41
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`fileTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`fileTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `isImage` TINYINT(1) NULL DEFAULT NULL COMMENT 'If filetyp ecan be rendered to image' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`folders`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`folders` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) UNSIGNED NOT NULL ,
  `idFolderTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `idParentFolder` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `idRootLevels` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `lft` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `rgt` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `depth` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `idSortTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `sortOrder` VARCHAR(255) NULL DEFAULT NULL ,
  `sortPosition` BIGINT(20) UNSIGNED NOT NULL ,
  `sortTimestamp` TIMESTAMP NULL DEFAULT NULL ,
  `folderId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `publisher` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `published` TIMESTAMP NULL DEFAULT NULL ,
  `idStatus` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' ,
  `isUrlFolder` TINYINT(1) NOT NULL DEFAULT '0' ,
  `showInNavigation` TINYINT(1) NOT NULL DEFAULT '0' ,
  `isVirtualFolder` TINYINT(1) NOT NULL DEFAULT '0' ,
  `virtualFolderType` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `folderId` (`folderId` ASC) ,
  INDEX `idParentFolder` (`idParentFolder` ASC) ,
  INDEX `idRootLevels` (`idRootLevels` ASC) ,
  INDEX `lft` (`lft` ASC) ,
  INDEX `rgt` (`rgt` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`folder-DEFAULT_FOLDER-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`folder-DEFAULT_FOLDER-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `folderId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `description` TEXT NULL DEFAULT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `folderId` (`folderId` ASC) ,
  CONSTRAINT `folder-DEFAULT_FOLDER-1-Instances_ibfk_1`
    FOREIGN KEY (`folderId` )
    REFERENCES `zoolu`.`folders` (`folderId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`folderPermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`folderPermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idFolders` BIGINT(20) UNSIGNED NOT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NOT NULL ,
  `idGroups` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`folderTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`folderTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `folderId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` BIGINT(20) UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `folderId` (`folderId` ASC) ,
  CONSTRAINT `folderTitles_ibfk_1`
    FOREIGN KEY (`folderId` )
    REFERENCES `zoolu`.`folders` (`folderId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`folderTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`folderTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`genericFormTabs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`genericFormTabs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) NOT NULL ,
  `idTabs` BIGINT(20) NOT NULL ,
  `order` INT(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`genericForms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`genericForms` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `genericFormId` VARCHAR(32) NOT NULL ,
  `version` INT(10) NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `idGenericFormTypes` INT(10) UNSIGNED NOT NULL ,
  `mandatoryUpgrade` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`genericFormTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`genericFormTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(255) NULL DEFAULT NULL ,
  `idAction` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `genericFormTitles_ibfk_1` (`idGenericForms` ASC) ,
  CONSTRAINT `genericFormTitles_ibfk_1`
    FOREIGN KEY (`idGenericForms` )
    REFERENCES `zoolu`.`genericForms` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`genericFormTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`genericFormTypes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`groupPermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`groupPermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGroups` BIGINT(20) UNSIGNED NOT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`groupTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`groupTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COMMENT = 'Typen für Gruppen CMS,...';


-- -----------------------------------------------------
-- Table `zoolu`.`groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`groups` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `idGroupTypes` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`guiTexts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`guiTexts` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idLanguanges` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `guiId` VARCHAR(32) NOT NULL ,
  `description` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idLanguanges` (`idLanguanges` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Table for multilanguage GUI';


-- -----------------------------------------------------
-- Table `zoolu`.`languages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`languages` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `languageCode` VARCHAR(3) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`modules`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`modules` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pages` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) UNSIGNED NOT NULL ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idPageTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `isStartPage` TINYINT(1) NOT NULL DEFAULT '0' ,
  `showInNavigation` TINYINT(1) NOT NULL DEFAULT '0' ,
  `idParent` BIGINT(20) UNSIGNED NOT NULL ,
  `idParentTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `sortPosition` BIGINT(20) UNSIGNED NOT NULL ,
  `sortTimestamp` TIMESTAMP NULL DEFAULT NULL ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `publisher` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `published` TIMESTAMP NULL DEFAULT NULL ,
  `idStatus` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `articletitle` TEXT NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `pics_title` VARCHAR(255) NULL DEFAULT NULL ,
  `docs_title` VARCHAR(255) NULL DEFAULT '' ,
  `video_title` VARCHAR(255) NULL DEFAULT NULL ,
  `video_embed_code` TEXT NULL DEFAULT NULL ,
  `shortdescription` TEXT NOT NULL ,
  `event_duration` VARCHAR(255) NULL DEFAULT NULL ,
  `event_street` VARCHAR(255) NULL DEFAULT NULL ,
  `event_streetnr` VARCHAR(255) NULL DEFAULT NULL ,
  `event_plz` VARCHAR(255) NULL DEFAULT NULL ,
  `event_city` VARCHAR(255) NULL DEFAULT NULL ,
  `event_location` VARCHAR(255) NULL DEFAULT NULL ,
  `event_max_members` VARCHAR(255) NULL DEFAULT NULL ,
  `event_costs` VARCHAR(255) NULL DEFAULT NULL ,
  `event_status` VARCHAR(255) NULL DEFAULT NULL ,
  `contact` VARCHAR(255) NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT-1-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `shortdescription` TEXT NOT NULL ,
  `description` TEXT NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `articletitle` VARCHAR(255) NULL DEFAULT NULL ,
  `shortdescription` TEXT NULL DEFAULT NULL ,
  `description` TEXT NOT NULL ,
  `header_embed_code` TEXT NULL DEFAULT NULL ,
  `contact` VARCHAR(255) NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `block_title` VARCHAR(255) NULL DEFAULT NULL ,
  `block_description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Region14-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `entry_title` VARCHAR(255) NULL DEFAULT NULL ,
  `entry_category` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_label` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_viewtype` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_number` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_sorttype` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_sortorder` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_depth` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Region15-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 410
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `articletitle` TEXT NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `header_embed_code` TEXT NULL DEFAULT NULL ,
  `pics_title` VARCHAR(255) NULL DEFAULT NULL ,
  `docs_title` VARCHAR(255) NULL DEFAULT '' ,
  `video_title` VARCHAR(255) NULL DEFAULT NULL ,
  `video_embed_code` TEXT NULL DEFAULT NULL ,
  `shortdescription` TEXT NOT NULL ,
  `contact` VARCHAR(255) NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 25
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `block_title` VARCHAR(255) NULL DEFAULT NULL ,
  `block_description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 200
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRegionInstances` BIGINT(20) UNSIGNED NOT NULL ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idRegionInstances` (`idRegionInstances` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles_ibfk_1`
    FOREIGN KEY (`idRegionInstances` )
    REFERENCES `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 89
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRegionInstances` BIGINT(20) UNSIGNED NOT NULL ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idRegionInstances` (`idRegionInstances` ASC) ,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`idRegionInstances` )
    REFERENCES `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `short_description` TEXT NULL DEFAULT NULL ,
  `department` VARCHAR(255) NULL DEFAULT NULL ,
  `position` VARCHAR(255) NULL DEFAULT NULL ,
  `content_responsible` VARCHAR(255) NULL DEFAULT NULL ,
  `organizational_responsible` VARCHAR(255) NULL DEFAULT NULL ,
  `process_inputs` TEXT NULL DEFAULT NULL ,
  `process_output` TEXT NULL DEFAULT NULL ,
  `process_indicator` TEXT NULL DEFAULT NULL ,
  `process_instructions` TEXT NULL DEFAULT NULL ,
  `process_techniques` TEXT NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `instruction_title` VARCHAR(255) NULL DEFAULT NULL ,
  `instruction_description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 79
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `steps_title` VARCHAR(255) NULL DEFAULT NULL ,
  `steps_text` TEXT NULL DEFAULT NULL ,
  `steps_who` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 65
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `risk_description` TEXT NOT NULL ,
  `risk_measure` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 25
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `rule_title` VARCHAR(255) NULL DEFAULT NULL ,
  `rule_text` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `articletitle` VARCHAR(255) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `header_embed_code` TEXT NULL DEFAULT NULL ,
  `top_title` TEXT NULL DEFAULT NULL ,
  `top_category` TEXT NULL DEFAULT NULL ,
  `top_label` TEXT NULL DEFAULT NULL ,
  `top_number` TEXT NULL DEFAULT NULL ,
  `top_sorttype` TEXT NULL DEFAULT NULL ,
  `top_sortorder` TEXT NULL DEFAULT NULL ,
  `banner_title` TEXT NULL DEFAULT NULL ,
  `banner_description` TEXT NULL DEFAULT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRegionInstances` BIGINT(20) UNSIGNED NOT NULL ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idRegionInstances` (`idRegionInstances` ASC) ,
  INDEX `idFiles` (`idFiles` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles_ibfk_2`
    FOREIGN KEY (`idFiles` )
    REFERENCES `zoolu`.`files` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(255) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idRegionInstances` BIGINT(20) UNSIGNED NOT NULL ,
  `idRelation` BIGINT(20) UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `idRegionInstances` (`idRegionInstances` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `block_title` VARCHAR(255) NULL DEFAULT NULL ,
  `block_description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `sortPosition` INT(10) UNSIGNED NOT NULL ,
  `entry_title` VARCHAR(255) NULL DEFAULT NULL ,
  `entry_nav_point` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_category` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_label` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_number` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_sorttype` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `entry_sortorder` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region17-Instances_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`pageCategories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageCategories` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `category` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) ,
  CONSTRAINT `pageCategories_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 113
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageContacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageContacts` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `idContacts` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) ,
  INDEX `pageId_2` (`pageId` ASC, `version` ASC) ,
  CONSTRAINT `pageContacts_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageDatetimes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageDatetimes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `datetime` VARCHAR(255) NULL DEFAULT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) ,
  INDEX `pageId_2` (`pageId` ASC, `version` ASC) ,
  CONSTRAINT `pageDatetimes_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageExternals`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageExternals` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `external` VARCHAR(255) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `pageExternals_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageGmaps`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageGmaps` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `latitude` VARCHAR(255) NOT NULL ,
  `longitude` VARCHAR(255) NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageLabels`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageLabels` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `label` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) ,
  INDEX `pageId_2` (`pageId` ASC, `version` ASC) ,
  CONSTRAINT `pageLabels_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 60
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageLinks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageLinks` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idPages` BIGINT(20) UNSIGNED NOT NULL ,
  `pageId` VARCHAR(32) NOT NULL COMMENT 'linked page' ,
  PRIMARY KEY (`id`) ,
  INDEX `idPages` (`idPages` ASC) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `pageLinks_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pagePermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pagePermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idPages` BIGINT(20) UNSIGNED NOT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NOT NULL ,
  `idGroups` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageRegistrations`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageRegistrations` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idPage` BIGINT(20) UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `fname` VARCHAR(255) NOT NULL ,
  `sname` VARCHAR(255) NOT NULL ,
  `mail` VARCHAR(255) NOT NULL ,
  `address` VARCHAR(255) NULL DEFAULT NULL ,
  `phone` VARCHAR(255) NULL DEFAULT NULL ,
  `plz` VARCHAR(255) NULL DEFAULT NULL ,
  `city` VARCHAR(255) NULL DEFAULT NULL ,
  `club` VARCHAR(255) NULL DEFAULT NULL ,
  `infos` TEXT NULL DEFAULT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(255) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `pageTitles_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageTypeTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageTypeTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idPageTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `page` TINYINT(1) NOT NULL DEFAULT '0' ,
  `startpage` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `page` (`page` ASC) ,
  INDEX `startpage` (`startpage` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`pageUrls`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageUrls` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `url` VARCHAR(255) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  CONSTRAINT `pageUrls_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`pageVideos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`pageVideos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `userId` VARCHAR(32) NOT NULL ,
  `videoId` VARCHAR(64) NOT NULL ,
  `thumb` VARCHAR(255) NOT NULL ,
  `idVideoTypes` INT(10) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `pageId` (`pageId` ASC) ,
  INDEX `version` (`version` ASC) ,
  CONSTRAINT `pageVideos_ibfk_1`
    FOREIGN KEY (`pageId` )
    REFERENCES `zoolu`.`pages` (`pageId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`parentTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`parentTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`permissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`permissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`properties`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`properties` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`regionFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`regionFields` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `idRegions` BIGINT(20) NOT NULL ,
  `idFields` BIGINT(20) NOT NULL ,
  `order` INT(10) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idRegions` (`idRegions` ASC) ,
  INDEX `idFields` (`idFields` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 107
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`regions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`regions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRegionTypes` BIGINT(20) UNSIGNED NOT NULL ,
  `columns` INT(10) NOT NULL COMMENT 'size of region' ,
  `isTemplate` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'indicates, whether this region should be multiusable - needed later on for tempalte editor' ,
  `collapsable` TINYINT(1) NOT NULL DEFAULT '1' ,
  `isCollapsed` TINYINT(1) NOT NULL DEFAULT '1' ,
  `position` VARCHAR(255) NULL DEFAULT NULL ,
  `isMultiply` TINYINT(1) NOT NULL DEFAULT '0' ,
  `multiplyRegion` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 39
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`regionTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`regionTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRegions` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idRegions` (`idRegions` ASC) ,
  CONSTRAINT `regionTitles_ibfk_1`
    FOREIGN KEY (`idRegions` )
    REFERENCES `zoolu`.`regions` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 40
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`regionTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`regionTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`renderedFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`renderedFiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL ,
  `idFiles` BIGINT(20) UNSIGNED NOT NULL ,
  `folder` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`rootLevelPermissions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`rootLevelPermissions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRootLevels` BIGINT(20) UNSIGNED NOT NULL ,
  `idGroups` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `idPermissions` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`rootLevels`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`rootLevels` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRootLevelTypes` INT(10) UNSIGNED NOT NULL ,
  `idModules` BIGINT(20) UNSIGNED NOT NULL ,
  `idThemes` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`rootLevelTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`rootLevelTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRootLevels` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `rootLevelTitles_ibfk_1` (`idRootLevels` ASC) ,
  CONSTRAINT `rootLevelTitles_ibfk_1`
    FOREIGN KEY (`idRootLevels` )
    REFERENCES `zoolu`.`rootLevels` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`rootLevelTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`rootLevelTypes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`rootLevelUrls`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`rootLevelUrls` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRootLevels` BIGINT(20) UNSIGNED NOT NULL ,
  `url` VARCHAR(255) NOT NULL COMMENT 'without \"www\" in front' ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `idRootLevels` (`idRootLevels` ASC) ,
  CONSTRAINT `rootLevelUrls_ibfk_1`
    FOREIGN KEY (`idRootLevels` )
    REFERENCES `zoolu`.`rootLevels` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`searchFieldTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`searchFieldTypes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`sortTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`sortTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`status` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`statusTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`statusTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idStatus` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idStatus` (`idStatus` ASC) ,
  CONSTRAINT `statusTitles_ibfk_1`
    FOREIGN KEY (`idStatus` )
    REFERENCES `zoolu`.`status` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tabRegions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tabRegions` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `idTabs` BIGINT(20) NOT NULL ,
  `idRegions` BIGINT(20) NOT NULL ,
  `order` INT(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 92
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tabTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tabTitles` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `idTabs` BIGINT(20) NOT NULL ,
  `idLanguages` BIGINT(20) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tabs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tabs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `color` CHAR(7) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `zoolu`.`tagFiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tagFiles` (
  `idTags` BIGINT(20) UNSIGNED NOT NULL ,
  `fileId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`idTags`, `fileId`, `version`, `idLanguages`) ,
  INDEX `idTags` (`idTags` ASC) ,
  INDEX `idFiles` (`fileId` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tagFolders`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tagFolders` (
  `idTags` BIGINT(20) UNSIGNED NOT NULL ,
  `folderId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`idTags`, `folderId`, `version`, `idLanguages`) ,
  INDEX `idTags` (`idTags` ASC) ,
  INDEX `idFolders` (`folderId` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tagPages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tagPages` (
  `idTags` BIGINT(20) UNSIGNED NOT NULL ,
  `pageId` VARCHAR(32) NOT NULL ,
  `version` INT(10) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`idTags`, `pageId`, `version`, `idLanguages`) ,
  INDEX `idTags` (`idTags` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`templates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templates` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `genericFormId` VARCHAR(32) NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `thumbnail` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`templateExcludedFields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templateExcludedFields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idFields` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `templateExcludedFields_ibfk_1` (`idTemplates` ASC) ,
  CONSTRAINT `templateExcludedFields_ibfk_1`
    FOREIGN KEY (`idTemplates` )
    REFERENCES `zoolu`.`templates` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`templateExcludedRegions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templateExcludedRegions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idRegions` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `templateExcludedRegions_ibfk_1` (`idTemplates` ASC) ,
  CONSTRAINT `templateExcludedRegions_ibfk_1`
    FOREIGN KEY (`idTemplates` )
    REFERENCES `zoolu`.`templates` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`templateRegionProperties`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templateRegionProperties` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idRegions` BIGINT(20) UNSIGNED NOT NULL ,
  `order` INT(10) NULL DEFAULT NULL ,
  `collapsable` TINYINT(1) NULL DEFAULT NULL ,
  `isCollapsed` TINYINT(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `templateRegionProperties_ibfk_1` (`idTemplates` ASC) ,
  CONSTRAINT `templateRegionProperties_ibfk_1`
    FOREIGN KEY (`idTemplates` )
    REFERENCES `zoolu`.`templates` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`templateTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templateTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `templateTitles_ibfk_1` (`idTemplates` ASC) ,
  CONSTRAINT `templateTitles_ibfk_1`
    FOREIGN KEY (`idTemplates` )
    REFERENCES `zoolu`.`templates` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`templateTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`templateTypes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idTemplates` BIGINT(20) UNSIGNED NOT NULL ,
  `idTypes` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`themes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`themes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  `creator` BIGINT(20) UNSIGNED NOT NULL ,
  `created` TIMESTAMP NULL DEFAULT NULL ,
  `changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`types` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`units`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`units` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idGenericForms` BIGINT(20) UNSIGNED NOT NULL ,
  `idParentUnit` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' ,
  `idRootUnit` BIGINT(20) UNSIGNED NULL DEFAULT NULL ,
  `lft` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `rgt` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `depth` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `lft` (`lft` ASC) ,
  INDEX `rgt` (`rgt` ASC) ,
  INDEX `idRootCategory` (`idRootUnit` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`unitTitles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`unitTitles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idUnits` BIGINT(20) UNSIGNED NOT NULL ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `title` VARCHAR(500) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idUnits` (`idUnits` ASC) ,
  CONSTRAINT `unitTitles_ibfk_1`
    FOREIGN KEY (`idUnits` )
    REFERENCES `zoolu`.`units` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `zoolu`.`urlReplacers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`urlReplacers` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `from` VARCHAR(255) NOT NULL ,
  `to` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`userGroups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`userGroups` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idUsers` BIGINT(20) NOT NULL ,
  `idGroups` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`userProfiles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`userProfiles` (
  `id` BIGINT(20) UNSIGNED NOT NULL ,
  `idUsers` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idLanguages` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  `username` VARCHAR(64) NOT NULL ,
  `password` VARCHAR(64) NOT NULL ,
  `fname` VARCHAR(255) NOT NULL ,
  `sname` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `username` (`username` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`validators`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`validators` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`videoTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`videoTypes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zoolu`.`virtualFolderTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zoolu`.`virtualFolderTypes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
