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
    CREATE DATABASE fibaro      -- or another or use any existing
    CREATE TABLE `hc123456` (      -- ex. name of monitored HC2
      `SQL_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `HC2_Time` datetime NOT NULL,
      `HC2_Level` varchar(10) NOT NULL,
      `HC2_Source` varchar(5) NOT NULL,
      `HC2_Message` varchar(200) CHARACTER SET utf8 NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin2;
copy to web directory:
  fibaro.php as  HC12345.php          -- this file is called by HC2, Importand use the sane name in http string in sql-sys-log.lua
  fibaro_log.php as  HC12345_log.php  -- for test, display last 20 message.
in two php files edit user,password and table - to name of HC2 and if you change database name from default 'fibaro'
I use this for monitor 2 two HC2 by internet (one by VPN, second by https
For see message use any sql browser or excel with mysql connect or by file ......_log.php for corect HC2



IMPORTANT!!!
Because of bug in startscene with arguments, if you use national character in message, You need before call use:

function encode(str)
  if (str) then
    str = string.gsub (str, "([^%w])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
  end
  return str 
end

msg=encode(msg)
