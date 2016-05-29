CREATE TRIGGER check_user_own_this_type
BEFORE insert on user_events
FOR each ROW
	INSERT INTO  user_event_types (user_id,event_type_id,event_type_name,created_at,updated_at) 
	SELECT new.user_id, events.event_type_id, event_types.name, now(), now() FROM event_types,events
	WHERE
		new.event_id=events.id AND events.event_type_id=event_types.id 
		AND events.event_type_id NOT IN 
			(SELECT event_type_id FROM user_event_types WHERE user_id=new.user_id);
