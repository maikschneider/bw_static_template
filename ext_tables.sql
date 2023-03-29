create table tt_content (
	tx_bwstatictemplate_template_path varchar(255) default '' not null,
	tx_bwstatictemplate_from_file     tinyint(4) unsigned default 0 not null,
	tx_bwstatictemplate_file_path     varchar(255) default '' not null,
	tx_bwstatictemplate_be_template   varchar(255) default '' not null,
);
