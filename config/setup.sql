--INITIALIZE DB--
--drop table users cascade;
--drop table accessitems cascade;
--drop table accesslevel cascade;


CREATE TABLE users (
	id SERIAL,
	active int2 DEFAULT 0,
	username varchar(25) DEFAULT '' NOT NULL UNIQUE,
	firstname varchar(25) DEFAULT '' NOT NULL,
	surname varchar(25) DEFAULT '' NOT NULL,
	regdate timestamp DEFAULT '01-01-1999' NOT NULL,
	password varchar(32) DEFAULT '' NOT NULL,
	lastvisit timestamp DEFAULT '01-01-1999' NOT NULL,
	email varchar(255),
	avatar varchar(100),
	sessionid char(32) DEFAULT '0' NOT NULL,
	session_time timestamp DEFAULT '01-01-1999' NOT NULL,
	session_ip char(15) DEFAULT '0' NOT NULL,
	password_hash character varying(255) NOT NULL DEFAULT '',
	token_hash  character varying(255) NOT NULL DEFAULT '',
	rows integer DEFAULT 45,
	maxdescr integer DEFAULT 70,
	mobile text DEFAULT ''::text,
	owner_id integer DEFAULT 0,
	ga text DEFAULT '',
	CONSTRAINT users_pkey PRIMARY KEY (id)
);


CREATE TABLE groups (
   id SERIAL,
   name varchar(40) NOT NULL,
   type int2 DEFAULT '1' NOT NULL,
   descr varchar(255) NOT NULL,
   CONSTRAINT groups_pkey PRIMARY KEY (id)
);

CREATE TABLE user_group (
   groupid int DEFAULT '0' NOT NULL,
   userid int DEFAULT '0' NOT NULL
) WITH OIDS;
CREATE  INDEX group_id_user_group_index ON user_group (groupid);
CREATE  INDEX user_id_user_group_index ON user_group (userid);

CREATE TABLE accessitems (
  id SERIAL PRIMARY KEY,
  name text default 'main_' UNIQUE
);

CREATE TABLE accesslevel (
  groupid INTEGER,
  accessid INTEGER,
  access int2 DEFAULT '0' NOT NULL
) WITH OIDS;


alter table accesslevel add constraint fk_acclv_acci foreign key (accessid) references accessitems (id) on delete cascade on update cascade;
alter table user_group add constraint fk_ug_user foreign key (userid) references users (id) on delete cascade on update cascade;
alter table user_group add constraint fk_ug_group foreign key (groupid) references groups (id) on delete cascade on update cascade;


CREATE OR REPLACE FUNCTION process_addaccss() RETURNS TRIGGER AS $add_accss$
    DECLARE
    GRP RECORD;
    BEGIN
        FOR GRP IN SELECT * from groups LOOP
          INSERT INTO accesslevel (groupid, accessid, access) VALUES (GRP.id,NEW.id,'0');
        END LOOP;
        RETURN NEW;
    END;
$add_accss$ LANGUAGE plpgsql; 
   
CREATE TRIGGER add_accss
AFTER INSERT ON accessitems
    FOR EACH ROW EXECUTE PROCEDURE process_addaccss();
    
CREATE OR REPLACE FUNCTION process_addaccssg() RETURNS TRIGGER AS $add_gaccss$
    DECLARE
    ACC RECORD;
    BEGIN
        FOR ACC IN SELECT * from accessitems LOOP
          INSERT INTO accesslevel (groupid, accessid, access) VALUES (NEW.id,ACC.id,'0');
        END LOOP;
        RETURN NEW;
    END;
$add_gaccss$ LANGUAGE plpgsql; 
   
CREATE TRIGGER add_gaccss
AFTER INSERT ON groups
    FOR EACH ROW EXECUTE PROCEDURE process_addaccssg();
    
    
CREATE OR REPLACE FUNCTION process_addaccsstbl() RETURNS INTEGER
  AS 
   $$
    DECLARE
      REC RECORD;
      RES INTEGER;
    BEGIN
        FOR REC IN SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*' AND NOT relname ~ 'sql_.*' AND relkind = 'r' ORDER BY relname LOOP
          IF ((SELECT count(*) FROM accessitems WHERE name='view_'||REC.relname)=0) THEN
             INSERT INTO accessitems (name) VALUES ('view_'||REC.relname);
          END IF;
          IF ((SELECT count(*) FROM accessitems WHERE name='edit_'||REC.relname)=0) THEN
             INSERT INTO accessitems (name) VALUES ('edit_'||REC.relname);
          END IF;
        END LOOP;
      RES=1;
      RETURN RES;
    END;
    $$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION add_new_user(TEXT, TEXT, TEXT, TEXT, TEXT, DATE, TEXT,TEXT) RETURNS INTEGER
  AS 
   $$
    DECLARE
      REC RECORD;
      RES INTEGER;
    BEGIN
      INSERT INTO users (username, password,email, firstname,surname,regdate,session_ip,avatar) VALUES ($1,$2,$3,$4,$5,$6,$7,$8); 
      SELECT INTO RES MAX(id) as id FROM users;
      INSERT INTO user_group (groupid, userid) VALUES (3, RES);
      RETURN RES;
    END;
    $$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION add_new_list(bigint, text, text, text[]) RETURNS integer 
AS
$BODY$
DECLARE 
var_list_ID bigint;
var_item_ID bigint;
def boolean;
m   text;

BEGIN
var_list_ID := $1;
var_item_ID := var_list_ID * 100;
INSERT INTO lists VALUES (var_list_ID, $2,$3,'');
	DELETE FROM listitems WHERE list_id=var_list_ID;
	FOREACH m IN ARRAY $4
	LOOP
		def := (var_item_ID = var_list_ID * 100);
		INSERT INTO listitems (list_id, id, name, alias, default_value,text1) VALUES (var_list_ID, var_item_ID,m,'',def,'');
		var_item_ID := var_item_ID + 1;
	END LOOP;
	RETURN var_item_ID;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
COST 100;
ALTER FUNCTION add_new_list(bigint, text, text, text[])
OWNER TO postgres;

--SELECT "add_new_list" (2, 'Etity type','entity',array['Client','Pharmacy','Laboratory','Hospital','Insurance']);


-- CREATE OR REPLACE FUNCTION add_new_menu(text, text[],text[]) RETURNS integer 
-- AS
-- $BODY$
-- DECLARE 
-- var_menu_ID bigint;
-- var_item_ID bigint;
-- i bigint;
-- def boolean;
-- m   text;
-- 
-- BEGIN
-- SELECT INTO var_menu_ID MIN(id) as id FROM menuitems where name=$1 and ;
-- var_list_ID := $1;
-- var_item_ID := var_list_ID * 100;
-- INSERT INTO lists VALUES (var_list_ID, $2,$3,'');
-- 	DELETE FROM listitems WHERE list_id=var_list_ID;
-- 	FOREACH m IN ARRAY $2
-- 	LOOP
-- 		i := i + 1;
-- 		def := (var_item_ID = var_list_ID * 100);
-- 		INSERT INTO listitems (list_id, id, name, alias, default_value,text1) VALUES (var_list_ID, var_item_ID,m,'',def,'');
-- 		var_item_ID := var_item_ID + 1;
-- 	END LOOP;
-- 	RETURN var_item_ID;
-- END;
-- $BODY$
-- LANGUAGE plpgsql VOLATILE
-- COST 100;
-- ALTER FUNCTION add_new_list(bigint, text, text, text[])
-- OWNER TO postgres;

--SELECT "add_new_list" (2, 'Etity type','entity',array['Client','Pharmacy','Laboratory','Hospital','Insurance']);


CREATE VIEW vw_acceesslist AS
SELECT ai.id, ai.name, al.access, ug.userid  FROM accesslevel al, accessitems ai, user_group ug WHERE al.accessid=ai.id and al.groupid=ug.groupid;



--user
INSERT INTO users (id, username, password, email, active, firstname) VALUES ( -3, 'NoAuth', '', '',1, 'No Auth');
INSERT INTO users (id, username, password, email, active, firstname) VALUES ( -2, 'Offline', '', '',1, 'Offline');
INSERT INTO users (id, username, password, email, active, firstname) VALUES ( -1, 'Anonymous', '', '',1, 'Guest');
--Admin
INSERT INTO users (id, username, password, email, active) VALUES ( 2, 'admin',  '70682896e24287b0476eff2a14c148f0', 'admin@yourdomain.com',  1);
INSERT INTO users (id, username, password, email, active) VALUES ( 0, 'nobody',  '', 'nobody@nowhere.com',  0);
update users set 
	password='2b4c7c52a61c494ce05cbef3589eabca',
	password_hash='sha256:1000:7LmfqDi7Hdtc3tc6ETYU66CKJN+zkM3u:aGCemxu31vR1PMaR1nBKlI4Q3EHyZnvR',
	token_hash='47c4-742d7269' where id=2; --Pass1234
-- Groups
INSERT INTO groups (id, name, descr) VALUES (0, 'Everyone', 'All');
INSERT INTO groups (id, name, descr) VALUES (1, 'Anonymous', 'Guests');
INSERT INTO groups (id, name, descr) VALUES (2, 'Admin', 'Power Users');
INSERT INTO groups (id, name, descr) VALUES (3, 'User', 'Normal Users');
INSERT INTO groups (id, name, descr) VALUES (4, 'Viewer', 'Report viewers');

-- User -> Group
INSERT INTO user_group (groupid, userid) VALUES (1, -1);
INSERT INTO user_group (groupid, userid) VALUES (2, 2);
--Access Level

--drop TABLE failed_logins;
CREATE TABLE failed_logins (
	id SERIAL,
	date_time timestamp,
	ip TEXT  DEFAULT '' NOT NULL,
	descr TEXT  DEFAULT '' NOT NULL,
CONSTRAINT flogins_pkey PRIMARY KEY (id)
);
--drop TABLE blacklist_ip;
CREATE TABLE blacklist_ip (
	id SERIAL,
	date_time timestamp,
	ip TEXT  DEFAULT '' NOT NULL,
	descr TEXT  DEFAULT '' NOT NULL,
CONSTRAINT bplacklist_ip_pkey PRIMARY KEY (id)
);

--===========FAVORITES============--
--drop table favorites;
create table favorites (
	id serial NOT NULL,
	userid int4 default 0,
	reference TEXT,
	refid int4 default 0,
	CONSTRAINT favor_pkey PRIMARY KEY (id)
)WITH OIDS;


----=========MENU NEW================----
create sequence menuitem_sec increment 1 start 1 minvalue 1 maxvalue 2147483647 cache 1;
create table menuitems (
	id int4 default nextval('menuitem_sec') NOT NULL,
	name  TEXT default '',
	link  TEXT default '#',
	hidden boolean DEFAULT false,
	descr  TEXT default ''
)WITH OIDS;

create sequence menu_sec increment 1 start 1 minvalue 1 maxvalue 2147483647 cache 1;
--drop table menus;
create table menus (
	id int4 default nextval('menu_sec') NOT NULL,
	parentid int4 default 0,
	groupid int4 default 0,
	menuid  int4 default 0,
	type int4 default 0,
	sort int4 default 100,
	level int4 default 0
)WITH OIDS;

CREATE TABLE menue2group (
	gid int4 default 0,
	menugid int4 default 0	
)WITH OIDS;


--drop TABLE fast_menu;
CREATE TABLE fastmenu (
	id SERIAL,
	name text DEFAULT '',
	date date,
	gid int4 default 0,
	menu text DEFAULT '',
CONSTRAINT fastmenu_pkey PRIMARY KEY (id)
);
CREATE TABLE logs(
  id SERIAL,
  userid integer,
  ip character varying(15),
  date timestamp without time zone,
  action text,
  CONSTRAINT logs_pkey PRIMARY KEY (id)
)WITH OIDS;
	
CREATE TABLE tableaccess (
		id SERIAL,
		date date,
		time text,
		userid int4 default 0,		
		refid int4,
		tablename text default 'none',
		ip text,
		descr text,
		addinfo text,
		CONSTRAINT tableaccess_pkey PRIMARY KEY (id)
)WITH OIDS;

CREATE TABLE dbchanges(
  id serial NOT NULL,
  date timestamp without time zone DEFAULT now(),
  tablename text DEFAULT ''::text,
  ref_id integer NOT NULL DEFAULT 0,
  user_id integer NOT NULL DEFAULT 0,
  before text DEFAULT ''::text,
  after text DEFAULT ''::text,
  action text DEFAULT ''::text,
  descr text DEFAULT ''::text,
  changes text DEFAULT ''::text,
  CONSTRAINT dbchanges_pkey PRIMARY KEY (id)
)WITH OIDS;


CREATE TABLE uploads(
  id serial NOT NULL,
  userid integer NOT NULL,
  refid integer NOT NULL,
  reftype integer,
  tablename character varying(100) NOT NULL DEFAULT ''::character varying,
  name character varying(255) NOT NULL DEFAULT ''::character varying,
  path text NOT NULL DEFAULT ''::text,
  link text NOT NULL DEFAULT ''::text,
  thumb text NOT NULL DEFAULT 'save.png'::text,
  filename character varying(255) NOT NULL DEFAULT ''::character varying,
  filetype character varying(251) NOT NULL DEFAULT ''::character varying,
  filesize integer NOT NULL DEFAULT 0,
  xsize integer NOT NULL DEFAULT 0,
  ysize integer NOT NULL DEFAULT 0,
  hasthumb smallint NOT NULL DEFAULT 0,
  public smallint NOT NULL DEFAULT 0,
  rating smallint NOT NULL DEFAULT 0,
  date timestamp without time zone NOT NULL DEFAULT '1999-01-01 00:00:00'::timestamp without time zone,
  lastchange timestamp without time zone NOT NULL DEFAULT '1999-01-01 00:00:00'::timestamp without time zone,
  descr text,
  tags text DEFAULT ''::text,
  active boolean DEFAULT true,
  CONSTRAINT uploads_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

CREATE TABLE useralerts(
  id serial NOT NULL,
  userid integer DEFAULT 0,
  fromuserid integer DEFAULT 0,
  date date,
  "time" text,
  refid integer,
  tablename text DEFAULT 'none'::text,
  wasread boolean DEFAULT false,
  readdate date,
  readtime text,
  descr text,
  addinfo text,
  confirm boolean DEFAULT false,
  CONSTRAINT useralerts_pkey PRIMARY KEY (id),
  CONSTRAINT fk_useralerts_fromuserid FOREIGN KEY (fromuserid)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_useralerts_userid FOREIGN KEY (userid)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)WITH (OIDS=TRUE);

CREATE TABLE lists(
  id integer NOT NULL DEFAULT 0,
  name text NOT NULL DEFAULT ''::text,
  alias text NOT NULL DEFAULT ''::text,
  descr text,
  addinfo text,
  CONSTRAINT lists_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

CREATE TABLE listitems
(
  id serial NOT NULL,
  name text NOT NULL DEFAULT ''::text,
  alias text NOT NULL DEFAULT ''::text,
  list_id integer NOT NULL DEFAULT 0,
  qty integer NOT NULL DEFAULT 0,
  default_value boolean DEFAULT false,
  values text,
  descr text,
  addinfo text,
  text1 text,
  text2 text,
  num1 double precision DEFAULT 0,
  num2 double precision DEFAULT 0,
  CONSTRAINT listitems_pkey PRIMARY KEY (id),
  CONSTRAINT fk_list_item FOREIGN KEY (list_id)
      REFERENCES lists (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)WITH (OIDS=TRUE);

CREATE TABLE posts(
  id serial NOT NULL,
  name text DEFAULT ''::text,
  user_id integer DEFAULT 0,
  ref_id integer DEFAULT 0,
  ref_table text DEFAULT ''::text,
  date timestamp without time zone NOT NULL DEFAULT '2030-01-01 00:00:00'::timestamp without time zone,
  text text DEFAULT ''::text,
  CONSTRAINT posts_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

CREATE TABLE comments(
  id serial NOT NULL,
  name text,
  userid integer,
  controller text,
  date date,
  refid integer,
  tablename text,
  type integer DEFAULT 0,
  active boolean DEFAULT true,
  descr text,
  CONSTRAINT comments_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

CREATE TABLE help(
  id serial NOT NULL,
  name text,
  descr text,
  CONSTRAINT help_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

CREATE TABLE warnings(
  id serial NOT NULL,
  name text,
  userid integer DEFAULT 0,
  date date,
  refid integer,
  tablename text DEFAULT 'none'::text,
  descr text,
  CONSTRAINT warnings_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);


CREATE TABLE schedules(
  id serial NOT NULL,
  name text,
  type integer DEFAULT 0,
  userid integer DEFAULT 0,
  usersinvolved text DEFAULT ''::text,
  date date,
  refid integer,
  tablename text DEFAULT 'none'::text,
  active boolean DEFAULT true,
  confidential boolean DEFAULT false,
  nextdate date,
  prevdate date,
  "interval" integer DEFAULT 0,
  descr text,
  addinfo text,
  confirm boolean DEFAULT false,
  qty integer DEFAULT 1000,
  makeintorders boolean DEFAULT false,
  makerequests boolean DEFAULT false,
  send_sms boolean DEFAULT false,
  send_mail boolean DEFAULT false,
  CONSTRAINT schedules_pkey PRIMARY KEY (id)
)WITH (OIDS=TRUE);

--DROP TABLE history_watch;
CREATE TABLE history_watch(
  id serial NOT NULL,
  table_name text DEFAULT '',
  field_name text DEFAULT '',
  active boolean default TRUE,
  CONSTRAINT historywatch_pkey PRIMARY KEY (id)
)WITH OIDS;

--DROP TABLE history;
CREATE TABLE history(
  id serial NOT NULL,
  date timestamp without time zone DEFAULT now(),
  table_name text DEFAULT '',
  record_id integer NOT NULL DEFAULT 0,
  sequence integer NOT NULL DEFAULT 0,
  field_name text DEFAULT '',
  field_value text DEFAULT '',
  user_id integer NOT NULL DEFAULT 0,
  active boolean default TRUE,
  CONSTRAINT history_pkey PRIMARY KEY (id)
)WITH OIDS;

--DROP TABLE clicks;
CREATE TABLE clicks(
  id serial NOT NULL,
  date timestamp without time zone DEFAULT now(),
  ip text DEFAULT '',
  uid integer NOT NULL DEFAULT 0,
  uname text DEFAULT '',
  act text DEFAULT '',
  what text DEFAULT '',
  ref_id integer NOT NULL DEFAULT 0,
  post text DEFAULT '',
  get text DEFAULT '',
  CONSTRAINT clicks_pkey PRIMARY KEY (id)
)WITH OIDS;

--DROP TABLE changes;
CREATE TABLE changes(
  id serial NOT NULL,
  reference text DEFAULT '',
  ref_id integer NOT NULL DEFAULT 0,
  changes_json text  DEFAULT '',
  CONSTRAINT changes_pkey PRIMARY KEY (id)
)WITH OIDS;

-- DROP TABLE signups;
CREATE TABLE signups(
  id serial NOT NULL,
  name text DEFAULT ''::text,
  surname text DEFAULT ''::text,
  email text DEFAULT ''::text UNIQUE,
  reg_date timestamp DEFAULT now(),
  rev_date timestamp DEFAULT null,
  exp_date date DEFAULT null,
  active boolean default true,
  verified boolean default false,
  verification_code text DEFAULT ''::text,
  password_hash text DEFAULT ''::text,
  user_id integer NOT NULL DEFAULT 0,
  entity_id integer NOT NULL DEFAULT 0,
  ip text DEFAULT ''::text,
  descr text DEFAULT ''::text,
  CONSTRAINT signups_pkey PRIMARY KEY (id)
)WITH OIDS;


-- DROP TABLE apis;
CREATE TABLE apis(
  id serial NOT NULL,
  key text DEFAULT ''::text UNIQUE,
  date timestamp DEFAULT now(),
  use_date timestamp DEFAULT null,
  exp_date date DEFAULT null,
  active boolean default true,
  functions text DEFAULT ''::text,
  user_id integer NOT NULL DEFAULT 0 UNIQUE,
  ip text DEFAULT ''::text,
  CONSTRAINT apis_pkey PRIMARY KEY (id)
)WITH OIDS;

alter table apis add constraint fk_apis_users foreign key (user_id) references users (id) on delete cascade on update cascade;


delete from menus;
ALTER SEQUENCE menu_sec RESTART WITH 1;
ALTER SEQUENCE menu_sec START 1;

--1st level
insert into menuitems (id,name) values (0,'Root');-- ID:0
insert into menuitems (name) values ('Home');-- ID:1
insert into menuitems (name) values ('Login');-- ID:2
insert into menuitems (name) values ('Register');-- ID:3
	
	--//Anonymous
	insert into menus (id,groupid, parentid,menuid,sort) values (0,0,0,0,0); --0 ROOT
	insert into menus (groupid, parentid,menuid,sort) values (0,0,2,1); --1 Login
	insert into menus (groupid, parentid,menuid,sort) values (0,0,3,2); --2 Register
	
insert into menuitems (name,link) values ('Main','?');-- ID:4
insert into menuitems (name) values ('Documents');-- ID:5
insert into menuitems (name) values ('Data');-- ID:6
insert into menuitems (name) values ('Reports');-- ID:7
insert into menuitems (name) values ('Tools');-- ID:8
insert into menuitems (name) values ('New');-- ID:9
insert into menuitems (name, link) values ('Favorites','?act=report&what=favorites');-- ID:10
insert into menuitems (name) values ('Help');-- ID:11
	
	--//Main menu
	insert into menus (groupid, parentid,menuid,sort) values (2,0,4,1); --3 Main
	insert into menus (groupid, parentid,menuid,sort) values (2,0,5,2);	--4 Documents
	insert into menus (groupid, parentid,menuid,sort) values (2,0,6,3); --5 Data
	insert into menus (groupid, parentid,menuid,sort) values (2,0,7,4); --6 Reports
	insert into menus (groupid, parentid,menuid,sort) values (2,0,8,5); --7 Tools
	insert into menus (groupid, parentid,menuid,sort) values (2,0,9,6); --8 New
	insert into menus (groupid, parentid,menuid,sort) values (2,0,10,7); --9 Favorites
	insert into menus (groupid, parentid,menuid,sort) values (2,0,11,8); --10 Help
		
--2nd level
insert into menuitems (name) values ('ADMIN');-- ID:12
insert into menuitems (name) values ('Update');-- ID:13
insert into menuitems (name) values ('Search');-- ID:14
insert into menuitems (name) values ('Import');-- ID:15
insert into menuitems (name) values ('Export');-- ID:16
--ADMIN
insert into menuitems (name) values ('Users');-- ID:17
insert into menuitems (name) values ('Data Base');-- ID:18
insert into menuitems (name) values ('System');-- ID:19
insert into menuitems (name) values ('UI');-- ID:20
--dividers
insert into menuitems (name) values ('Partners');-- ID:21
insert into menuitems (name) values ('Office');-- ID:22
insert into menuitems (name) values ('System');-- ID:23
insert into menuitems (name) values ('Partners');-- ID:24
--docs
insert into menuitems (name) values ('My');-- ID:25
insert into menuitems (name) values ('All');-- ID:26
insert into menuitems (name) values ('ORD');-- ID:27
insert into menuitems (name) values ('POAs');-- ID:28
insert into menuitems (name) values ('Invoices');-- ID:29

	--2nd level
	insert into menus (groupid, parentid,menuid,sort) values (2,7,12,101); --11 Tools>ADMIN
	insert into menus (groupid, parentid,menuid,sort) values (2,7,13,102); --12 Tools>Update
	insert into menus (groupid, parentid,menuid,sort) values (2,12,15,101);--13 Update>Import
	insert into menus (groupid, parentid,menuid,sort) values (2,12,16,102);--14 Update>Export
		
	insert into menus (groupid, parentid,menuid,sort) values (2,11,17,101); --15 Tools>ADMIN>Users
	insert into menus (groupid, parentid,menuid,sort) values (2,11,18,201); --16 Tools>ADMIN>Data
	insert into menus (groupid, parentid,menuid,sort) values (2,11,19,301); --17 Tools>ADMIN>System
	insert into menus (groupid, parentid,menuid,sort) values (2,11,20,401); --18 Tools>ADMIN>UI
		
		
		
--Admin->Users	
insert into menuitems (name,link, descr) values ('Users','?act=show&what=users','Show Users Table');-- ID:30
insert into menuitems (name,link, descr) values ('Groups','?act=show&what=groups','Show Groups Table');-- ID:31
insert into menuitems (name,link, descr) values ('Last Logins','?act=report&what=userslogins','');-- ID:32
insert into menuitems (name,link, descr) values ('Users Activity','?act=report&what=usersactivity','');-- ID:33
insert into menuitems (name,link, descr) values ('Access Items','?act=show&what=accessitems','');-- ID:34
insert into menuitems (name,link, descr) values ('Add access','?act=tools&what=addaccessitems&item=edit_lists','');-- ID:35
	
	insert into menus (groupid, parentid,menuid,sort) values (2,15,30,101); --19 Tools>ADMIN>Users>Users
	insert into menus (groupid, parentid,menuid,sort) values (2,15,31,201); --20 Tools>ADMIN>Users>Groups
	insert into menus (groupid, parentid,menuid,sort) values (2,15,32,301); --21 Tools>ADMIN>Users>Last Logins
	insert into menus (groupid, parentid,menuid,sort) values (2,15,33,401); --22 Tools>ADMIN>Users>Users Activity
	insert into menus (groupid, parentid,menuid,sort) values (2,15,34,501); --23 Tools>ADMIN>Users>Access Items
	insert into menus (groupid, parentid,menuid,sort) values (2,15,35,601); --24 Tools>ADMIN>Users>Add access
	

	
--Admin->Data

insert into menuitems (name,link) values ('Backup DB','?act=tools&what=backup');-- ID:36
insert into menuitems (name,link) values ('Restore DB','?act=tools&what=restore');-- ID:37
insert into menuitems (name,link) values ('Restore Record','?act=tools&what=infodeleted&table=documents&name=10-12-1348');-- ID:38
insert into menuitems (name,link) values ('Uploads','?act=show&what=uploads&opt=all');-- ID:39
insert into menuitems (name,link) values ('Tables','?act=report&what=tables');-- ID:40
insert into menuitems (name,link) values ('Alarms','?act=show&what=useralerts&showall=1&wasread=&unread=1&from=&to=&tablename=');-- ID:41
insert into menuitems (name,link) values ('Change Directors','?act=tools&what=changedirector&olddirid=2616&newdirid=2091&date=15.06.2011');-- ID:42
insert into menuitems (name,link) values ('Statdata','?act=show&what=statdata&nomaxid=1');-- ID:43
insert into menuitems (name,link) values ('SQL','?act=tools&what=sqlisha');-- ID:44
	
	insert into menus (groupid, parentid,menuid,sort) values (2,16,36,101); --25 Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,37,201); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,38,301); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,39,401); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,40,501); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,41,601); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,42,701); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,43,801); -- Tools>ADMIN>Data>Backup
	insert into menus (groupid, parentid,menuid,sort) values (2,16,44,901); --33 Tools>ADMIN>Data>SQL
		
	
--Admin->System
insert into menuitems (name,link) values ('Long requests','?act=report&what=long_requests');-- ID:45
insert into menuitems (name,link) values ('Backup ALL','?act=tools&what=backupprog&ext=&opt=nowrap');-- ID:46
insert into menuitems (name,link) values ('Backup PHP','?act=tools&what=backupprog&ext=php&opt=nowrap');-- ID:47
insert into menuitems (name,link) values ('Save Last Changes','?act=tools&what=backupprog&ext=upd&opt=nowrap');-- ID:48
insert into menuitems (name,link) values ('Block','?act=tools&what=block');-- ID:49
insert into menuitems (name,link) values ('Reset','?act=tools&what=reset');-- ID:50
insert into menuitems (name,link) values ('Apply Updates','?act=tools&what=update');-- ID:51
insert into menuitems (name,link) values ('Vacuum','?act=tools&what=vacuum');-- ID:52
insert into menuitems (name,link) values ('Functions and system calls','?act=report&what=functions');-- ID:53
insert into menuitems (name,link) values ('Settings','?act=show&what=config');-- ID:54
	
	insert into menus (groupid, parentid,menuid,sort) values (2,17,45,101); --34 Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,46,201); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,47,301); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,48,401); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,49,501); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,50,601); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,51,701); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,52,801); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,53,901); -- Tools>ADMIN>System>requests
	insert into menus (groupid, parentid,menuid,sort) values (2,17,54,1001); --43 Tools>ADMIN>System>requests

	
--Admin->UI
insert into menuitems (name,link) values ('Menu Items','?act=show&what=menuitems&nopager=1&sortby=id');-- ID:55
insert into menuitems (name,link) values ('Menues','?act=show&what=menus&groupid=2');-- ID:56
insert into menuitems (name,link) values ('Generate code','?act=tools&what=genform&tablename=lists');-- ID:57
	
	insert into menus (groupid, parentid,menuid,sort) values (2,18,55,101); -- Tools>ADMIN>UI>	
	insert into menus (groupid, parentid,menuid,sort) values (2,18,56,201); -- Tools>ADMIN>UI>	
	insert into menus (groupid, parentid,menuid,sort) values (2,18,57,201); -- Tools>ADMIN>UI>	
			
insert into menuitems (name,link) values ('Debug Info','?act=details&what=groupaccess&type=view_debug');-- ID:58
	insert into menus (groupid, parentid,menuid,sort) values (2,11,58,501); --19 Tools>ADMIN>Debug

	
insert into menuitems (name,link) values ('About IS', '?act=report&what=info');-- ID:46
insert into menuitems (name,link) values ('Help Topics', '?act=show&what=help');-- ID:47
insert into menuitems (name,link) values ('Versions', '?act=show&what=versions');-- ID:48
	








