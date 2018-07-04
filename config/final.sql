-- Below Always LAST! ========================================
CREATE TABLE config (
   name varchar(255) NOT NULL,
   value varchar(255) NOT NULL,
   CONSTRAINT config_pkey PRIMARY KEY (name)
);
--Insert Data
INSERT INTO config (name, value) VALUES ('config_id','1');
INSERT INTO config (name, value) VALUES ('db_disable','0');
INSERT INTO config (name, value) VALUES ('sitename','bi6');
INSERT INTO config (name, value) VALUES ('site_desc','BI Information System');
INSERT INTO config (name, value) VALUES ('cookie_name','bi6cookie');
INSERT INTO config (name, value) VALUES ('cookie_path','/');
INSERT INTO config (name, value) VALUES ('cookie_domain','');
INSERT INTO config (name, value) VALUES ('cookie_secure','0');
INSERT INTO config (name, value) VALUES ('session_length','3600');
INSERT INTO config (name, value) VALUES ('max_autologin_time','0');
INSERT INTO config (name, value) VALUES ('require_activation','0');
INSERT INTO config (name, value) VALUES ('flood_interval','15');
INSERT INTO config (name, value) VALUES ('search_flood_interval','15');
INSERT INTO config (name, value) VALUES ('search_min_chars','3');
INSERT INTO config (name, value) VALUES ('max_login_attempts', '5');
INSERT INTO config (name, value) VALUES ('login_reset_time', '30');
INSERT INTO config (name, value) VALUES ('version', '6.0.0');
INSERT INTO config (name, value) VALUES ('docdir', 'docs');
INSERT INTO config (name, value) VALUES ('tmpdir', '/tmp');
INSERT INTO config (name, value) VALUES ('rmcmd', 'rm');
INSERT INTO config (name, value) VALUES ('lscmd', 'ls -c');
INSERT INTO config (name, value) VALUES ('limit', '45');
INSERT INTO config (name, value) VALUES ('timeout', '600');
INSERT INTO config (name, value) VALUES ('refreshtime', '10');
INSERT INTO config (name, value) VALUES ('auxserverip', '192.168.9.100');
INSERT INTO config (name, value) VALUES ('imgmaxx', '1000000');
INSERT INTO config (name, value) VALUES ('imgmaxy', '1000000');
INSERT INTO config (name, value) VALUES ('use_convert', '0');
INSERT INTO config (name, value) VALUES ('active', '1');
INSERT INTO config (name, value) VALUES ('shutdowntext', 'Sytem is unavailable. Try later.');
INSERT INTO config (name, value) VALUES ('sendmonthly', '01.01.2015');
INSERT INTO config (name, value) VALUES ('sendweekly', '01.01.2015');
INSERT INTO config (name, value) VALUES ('senddaily', '01.01.2015');


delete from accessitems;
ALTER SEQUENCE accessitems_id_seq RESTART WITH 1;
insert into accessitems (name) values ('main_access');
insert into accessitems (name) values ('main_admin');
insert into accessitems (name) values ('view_users');
insert into accessitems (name) values ('view_groupaccess');
insert into accessitems (name) values ('edit_profile');
insert into accessitems (name) values ('report_monitor');
insert into accessitems (name) values ('report_changes');
insert into accessitems (name) values ('report_logs');
insert into accessitems (name) values ('report_info');
insert into accessitems (name) values ('report_tables');
insert into accessitems (name) values ('report_events');
insert into accessitems (name) values ('report_mainvalues');
insert into accessitems (name) values ('report_mainreport');
insert into accessitems (name) values ('view_debug');
insert into accessitems (name) values ('report_home_page');
insert into accessitems (name) values ('report_long_requests');
insert into accessitems (name) values ('report_db_changes');
insert into accessitems (name) values ('report_posts');
	

select * from process_addaccsstbl();

update accesslevel set access=1 where groupid=2;
update accesslevel set access=1 where groupid=1 and accessid=1;

ALTER SEQUENCE users_id_seq RESTART WITH 3;
ALTER SEQUENCE groups_id_seq RESTART WITH 4;

--INDEXES
CREATE INDEX uploads_tablename ON uploads (tablename);
CREATE INDEX uploads_refid ON uploads (refid);

CREATE INDEX useralerts_tablename ON useralerts (tablename);
CREATE INDEX useralerts_refid ON useralerts (refid);

CREATE INDEX tableaccess_tablename ON tableaccess (tablename);
CREATE INDEX tableaccess_refid ON tableaccess (refid);

CREATE INDEX logs_date ON logs (date);

update users set 
	password='2b4c7c52a61c494ce05cbef3589eabca',
	password_hash='sha256:1000:7LmfqDi7Hdtc3tc6ETYU66CKJN+zkM3u:aGCemxu31vR1PMaR1nBKlI4Q3EHyZnvR',
	token_hash='47c4-742d7269' where id=2; --Pass1234