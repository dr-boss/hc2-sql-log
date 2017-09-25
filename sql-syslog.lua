--[[
%% properties
%% events
%% globals
--]]

--[[
For use it from a VD or Scene you need call them like this:
  fibaro:startScene(76, {{sev = "debug"}, {src= "Snnn or Vnnn - VD/Scene Name/id"}, {msg = "SysLog text message"}})
or paste:
  if __fibaroSceneId~=nil then dev = 'S' .. __fibaroSceneId else dev = 'V' .. fibaro:getSelfId() end
  fibaro:startScene(76, {{sev = "info"}, {src = dev}, {msg = "test"}})

  first parameter (76) is the number this Scene
  sev = the severity (textual) of the message, see the value bilow (not the number)
  src = the nome of the VD ou Scene who call this Scene to indicate the origine of the message
  msg = Your message
  sev - example take from syslog values 
   		  ["emergency"] = 0,
		  ["alert"]     = 1,
	  	  ["critical"]  = 2,
		  ["error"]     = 3,
		  ["warning"]   = 4,
		  ["notice"]    = 5,
		  ["info"]      = 6,
		  ["debug"]     = 7
--]]
local IP = "192.168.1.99" -- ip/dns of php/sql server
local name = "HC123456" -- name of HC2 system, called php file for this system and table
local sev = "info"
local src = nil
local msg = nil
local password = '' -- if php servere use basic authorisation, uncomments line  (as BASE64)
local params = fibaro:args()
if (params) then

  for k, v in ipairs(params) do
    if (v.sev) then sev = v.sev end
    if (v.src) then src = v.src end
    if (v.msg) then msg = v.msg end
  end
end

if sev and src and msg then
local http = net.HTTPClient()
data = os.date("%Y-%m-%d")..'%20'..os.date("%H:%M:%S")  
  
local GETClient = net.HTTPClient()

function urldecode(s)
  return string.gsub(s, '%%(%x%x)', 
    function (hex) return string.char(tonumber(hex,16)) end)
end
msg=urldecode(msg)
fibaro:debug('=============================')
fibaro:debug(data)
fibaro:debug(sev)
fibaro:debug(src)
fibaro:debug(msg)

  
function urlencode(str)
if (str) then
str = string.gsub (str, "\n", "\r\n")
str = string.gsub (str, "([^%w ])",
function (c) return string.format ("%%%02X", string.byte(c)) end)
str = string.gsub (str, " ", "+")
end
fibaro:debug(str)
return str 
end

local Diacritic = {["Ą"]="A",["Ć"]="C",["Ę"]="E",["Ł"]="L",["Ń"]="N",["Ś"]="S",["Ź"]="Z",["Ż"]="Z",["Ó"]="O",["ą"]="a",["ć"]="c",["ę"]="e",["ł"]="l",["ń"]="n",["ś"]="s",["ź"]="z",["ż"]="z",["ó"]="o"}
  function conv_char(text)
    for oldchar,newchar in pairs(Diacritic) do text=string.gsub(text, oldchar, newchar) end
    return text
  end
  
msg = urlencode(conv_char(msg))
data = urlencode(os.date("%Y-%m-%d %H:%M:%S"))
query = 'http://'..IP..'/'..name..'.php?hctime='.. data ..'&hcid=' .. src .. '&hclev=' .. sev .. '&hcmsg='..msg
--query = 'https://'..IP..'/fibaro.php?hctime='.. data ..'&hcid=' .. src .. '&hclev=' .. sev .. '&hcmsg='..msg  -- for ssl connection 
GETClient:request(query, {

      success = function(response)
				if tonumber(response.status) == 200 then
					fibaro:debug(response.data)
				else
					print("Error " .. response.status)						
                   end
                end,
	error = function(err)
				print('error = ' .. err)
            end,
	headers = {
				["content-type"] = 'application/x-www-form-urlencoded;',
--				["authorization"] = 'Basic '..password
				}				      	
	});
	

end

