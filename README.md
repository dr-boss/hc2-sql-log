# hc2-sql-log
log notification to sql database v.2
this scrips I use for two remotly controled HC2. Scirpts send to my NAS notifications importand for my administation and delete not interested for users. 
If HC2 lost connection with SQL/WEB server it does not erase any notification until the connection is recovered and when notifications are copied successfully. 
Installation
    create scene notification-rec-del.lua           -- add to timer scene ex every 1 or 2h
    create scene sql-syslog.lua                     -- set runing instance for 5 or more (when many VD or scenes call in smae time to send message)
    edit scene and change IP or DNS of your WEB (when you copy PHP files
    efter test set debug to 'no'
    
if You can store another message remotly modify and use in Your scenes or VD this 2 line (76 is my id of sql-syslog.lua scene)
    if __fibaroSceneId~=nil then dev = 'S' .. __fibaroSceneId else dev = 'V' .. fibaro:getSelfId() end
    fibaro:startScene(76, {{sev = "info"}, {src = dev}, {msg = "test"}})
    fibaro:startScene(76, {{sev = "info"}, {src = dev}, {msg = urlencode("test")}})  --if you use national character in message

copy to web server directory:
  fibaro.php           -- this file is called by all HC2
  fibaro_log.php       -- for test, display last 20 message, or export all data as csv
  fibaro_csv.php       -- for fibaro_log.php only

In all php files edit user,password and if you change database name from default 'fibaro'
If You use this for monitor two or more HC2 for each fibaro.php will automatically create a table
If you connect HC2 to SQL/WEB server by internet do this by VPN or https (change parameter in scene sql-syslog.lua 
For see message use fibaro_log.php who display list of all connected HC2 (with option to show or download) or any sql browser or excel 

on Your sql server (ex. NAS):
    CREATE DATABASE fibaro      -- or another or use any existing (if You use another database name change it in fibaro*.php
Check if the user which you are use for access sql database has CREATE TABLE permission for auto create table for new HC2
edit all PHP files and add proper user and password

Structur of table (normally if user have good right created automatically)
    CREATE TABLE `NAME` (      -- ex. name of monitored HC2 without '-'
      `SQL_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `HC2_Time` datetime NOT NULL,
      `HC2_Level` varchar(10) NOT NULL,
      `HC2_Source` varchar(5) NOT NULL,
      `HC2_Message` varchar(200) CHARACTER SET utf8 NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin2;



IMPORTANT!!! when you use sql-syslog from your VDs or scenes
Because of bug in startscene with arguments, if you use national character in message, You need use:
msg=urlencode(msg) or second version of example in line 13
