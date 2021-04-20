CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



INSERT INTO mysql.user(host,user,password,Select_priv ,Insert_priv ,Update_priv ,Delete_priv ,Create_priv ,Drop_priv   ,Reload_priv ,Shutdown_priv,Process_priv,File_priv   ,Grant_priv  ,References_priv,Index_priv  ,Alter_priv) VALUES('localhost','test',PASSWORD('secret'),'Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y');\
INSERT INTO mysql.user(host,user,password,Select_priv ,Insert_priv ,Update_priv ,Delete_priv ,Create_priv ,Drop_priv   ,Reload_priv ,Shutdown_priv,Process_priv,File_priv   ,Grant_priv  ,References_priv,Index_priv  ,Alter_priv) VALUES('%','test',PASSWORD('secret'),'Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y');\


Grant all privileges on test.* to 'test'@'localhost' identified by 'secret' with grant option;
Grant all privileges on test.* to 'test'@'%' identified by 'secret' ;
grant RELOAD,PROCESS on test.* to 'test'@'localhost';
grant USAGE on test.* to 'test'@'localhost';