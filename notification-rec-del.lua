--[[
%% properties
%% weather
%% events
%% globals
--]]

local startSource = fibaro:getSourceTrigger();
test='Delete notification'
fibaro:debug(test)

local notification = api.get('/notificationCenter')
if __fibaroSceneId~=nil then id = __fibaroSceneId; prefix = 'S' else id = fibaro:getSelfId(); prefix = 'V' end
local dev = prefix..id
fibaro:startScene(76, {{sev = "debug"}, {src = dev}, {msg = test}})



for k,v in pairs (notification) do
-- notification for delete  
  if notification[k]["type"] == "SceneToManyInstancesNotification" and notification[k]["canBeDeleted"] == true then
	fibaro:debug(notification[k].id..' - '..notification[k].created..' - '..notification[k].data.sceneId..' - '..notification[k].type..'!')
    fibaro:startScene(76, {{sev = notification[k].priority}, {src = dev}, {msg = tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.sceneId..' - '..notification[k].type..'!'}})
	HomeCenter.NotificationService.remove(notification[k].id)
  

-- only for information without delete
  elseif notification[k]["type"] == "GenericDeviceNotification" and notification[k]["canBeDeleted"] == true then
	fibaro:debug(notification[k].id..' - '..notification[k].created..' - '..notification[k].data.deviceId..' - '..notification[k].type..'!')
    fibaro:startScene(76, {{sev = notification[k].priority}, {src = dev}, {msg = tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.deviceId..' - '..notification[k].type..'!'}})
-- HomeCenter.NotificationService.remove(notification[k].id)

-- if bloceced for delete
--[[  
  elseif notification[k]["type"] == "GenericSystemNotification" and notification[k]["canBeDeleted"] == false then
  fibaro:startScene(76, {{sev = notification[k].priority}, {src = dev}, {msg = tostring(os.date('%Y-%m-%d %H:%M:%S', notification[k].created))..' - '..notification[k].data.subType..' - '..notification[k].type..'!'}})
  HomeCenter.NotificationService.update(notification[k].id, {
    canBeDeleted = true,
    data =
    {
        subType = "DeviceNotConfigured",
      	name = "Start centralki",
        text =  "Ostatni start centralki "..data,
        url = "/configuration/notification-center.html",
        urlText = "Komunikaty"

    }
	})
	HomeCenter.NotificationService.remove(notification[k].id)
  --]]
  end


end






