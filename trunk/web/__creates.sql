CREATE TABLE IF NOT EXISTS `samusframework`.`sf_pais` (`id` INTEGER(11) auto_increment  ,
`nome` VARCHAR(60)  ,
`sigla` VARCHAR(10)  ,
 PRIMARY KEY  (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `samusframework`.`sf_model_sample_type` (`id` INTEGER(11) auto_increment  ,
`nome` VARCHAR(45)  ,
 PRIMARY KEY  (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `samusframework`.`sf_model_sample` (`id` INTEGER(11) auto_increment  ,
`name` VARCHAR(90)  ,
`number` INTEGER  ,
`date` DATETIME  ,
`boolean` BOOLEAN  ,
`modelSampleType` INTEGER  ,
 PRIMARY KEY  (`id`), CONSTRAINT `fk_ModelSample_modelSampleType`
					    FOREIGN KEY (`modelSampleType` )
					    REFERENCES `samusframework`.`sf_model_sample_type` (`id` )
					    ON DELETE SET NULL
					    ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=latin1;

