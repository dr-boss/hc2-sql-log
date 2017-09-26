--[[
%% properties
%% weather
%% events
%% globals
--]]

local startSource = fibaro:getSourceTrigger();
test='Check notification'
fibaro:debug(test)
local sqlid = 76
local notification = api.get('/notificationCenter')
if __fibaroSceneId~=nil then id = __fibaroSceneId; prefix = 'S' else id = fibaro:getSelfId(); prefix = 'V' end
local dev = prefix..id
fibaro:startScene(sqlid, {{sev = "info"}, {src = dev}, {msg = test}})

function encode(str)
  if (str) then
    str = string.gsub (str, "([^%w])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
  end
  return str 
end


for k,v in pairs (notification) do
-- notification for delete  
  if notification[k]["type"] == "SceneToManyInstancesNotification" and notification[k]["canBeDeleted"] == true then
	fibaro:debug(notification[k].id..' - '..notification[k].created..' - '..notification[k].data.sceneId..' - '..notification[k].type..'!')
    fibaro:startScene(sqlid, {{sev = notification[k].priority}, {src = dev}, {msg = encode(tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.sceneId..' - '..notification[k].type..'!')}})
	if fibaro:getGlobalValue("sys_Log_err") ~= "Working" then
		print('Error in connection with SQL server, notification not removed')
    else
    	HomeCenter.NotificationService.remove(notification[k].id)
	end  

-- only for information without delete
  elseif notification[k]["type"] == "GenericDeviceNotification" and notification[k]["canBeDeleted"] == true then
	fibaro:debug(notification[k].id..' - '..notification[k].created..' - '..notification[k].data.deviceId..' - '..notification[k].type..'!')
    fibaro:startScene(sqlid, {{sev = notification[k].priority}, {src = dev}, {msg = encode(tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.deviceId..' - '..notification[k].type..'!')}})
	HomeCenter.NotificationService.remove(notification[k].id)

-- ex. if bloceced for delete
-- --[[  
  elseif notification[k]["type"] == "GenericSystemNotification" and notification[k]["canBeDeleted"] == false then
  fibaro:startScene(sqlid, {{sev = notification[k].priority}, {src = dev}, {msg = encode(tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.name..' - '..notification[k].type..'!')}})
  fibaro:debug(notification[k].id..' - '..notification[k].created..' - '..notification[k].data.name..' - '..notification[k].type..'!')
  HomeCenter.NotificationService.update(notification[k].id, {
    canBeDeleted = true,
    data =
    {
        subType = notification[k].data.subType,
      	name = notification[k].data.name,
        text =  notification[k].data.text,
        url = notification[k].data.url,
        urlText = notification[k].data.urlText

    }
	})
--    	if fibaro:getGlobalValue("sys_Log_err") ~= "Working" then
--		print('Error in connection with SQL server, notification not removed')
--	    else
--		HomeCenter.NotificationService.remove(notification[k].id)
--		end
--  --]]
  end


end


