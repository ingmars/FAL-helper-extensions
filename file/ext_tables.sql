
# PART 1: Example Usage


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_file_images_overlayed int(11) DEFAULT '0' NOT NULL
	tx_file_images_list int(11) DEFAULT '0' NOT NULL
);




# PART 2: VFS Table Structure


#
# Table structure for table 'sys_file_mountpoints'
#
CREATE TABLE sys_file_mountpoints (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	name tinytext,
	description text,
	storage tinytext,
	storage_configuration text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'sys_file'
#
CREATE TABLE sys_file (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,

	mount int(11) DEFAULT '0' NOT NULL,
	identifier varchar(30) DEFAULT '' NOT NULL,
	name tinytext,
	sha1 tinytext,
	size int(11) DEFAULT '0' NOT NULL,
	usage_count int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid)
);



#
# Table structure for table 'sys_file_references'
#
CREATE TABLE sys_file_references (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	# Reference fields (basically same as MM table)
	# // uid_local would be the uid of the sys_file record
	uid_local int(11) DEFAULT '0' NOT NULL,
	# // uid_foreign would be e.g. the uid of a tt_content record
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	# // tablenames would e.g. contain "tt_content":
	tablenames varchar(255) DEFAULT '' NOT NULL,
	# // fieldname would e.g. contain "images":
	fieldname tinytext,
	# // sorting_foreign would be the sorting order inside of the tt_content.images field
	sorting_foreign int(11) DEFAULT '0' NOT NULL,
	# // table_local is not used yet, but would be either "sys_file" or "sys_file_collection"
	table_local varchar(255) DEFAULT '' NOT NULL,

	# Local usage overlay fields
	title tinytext,
	description text,
	downloadname tinytext,

	PRIMARY KEY (uid),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign),
	KEY parent (pid)
);



#
# Table structure for table 'sys_file_collection'
#
CREATE TABLE sys_file_collection (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,

	# Actual fields
	files int(11) DEFAULT '0' NOT NULL,
	name tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid)
);
