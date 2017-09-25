# hc2-sql-log
log notification to sql database
this scrips I use for two remotly controled HC2. Scirpts send to my NAS notifications importand for my administation and delete not interested for users. 
Installation
    create scene notification-rec-del.lua           -- add to timer scene ex every 1 or 2h
    create scene sql-syslog.lua                     -- set runing instance for 5 or more (when many VD or scenes call in smae time to send message)
if You can store another message remotly modify and use in Your scenes or devices (76 is my id of sql-syslog.lua scene)
    if __fibaroSceneId~=nil then dev = 'S' .. __fibaroSceneId else dev = 'V' .. fibaro:getSelfId() end
    fibaro:startScene(76, {{sev = "info"}, {src = dev}, {msg = "test"}})

on Your sql server (NAS):
    CREATE DATABASE HC12345      -- or another or use any existing
    CREATE TABLE `debug` (      -- ex. name of monitored HC2
      `SQL_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `HC2_Time` datetime NOT NULL,
      `HC2_Level` varchar(10) NOT NULL,
      `HC2_Source` varchar(5) NOT NULL,
      `HC2_Message` varchar(200) CHARACTER SET utf8 NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin2;
copy to web directory:
    HC12345.php                  -- this file is called by HC2, change login dana for SQL server. Importand use the sane name in http string in sql-sys-log.lua
    HC12345_log.php              -- for test, display last 20 message.
edit user and password in php files, and if need database name
I use this for monitor 2 two HC2 by internet (one by VPN, second by https
