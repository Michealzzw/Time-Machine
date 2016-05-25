
CREATE TRIGGER send_request
AFTER insert on user_friends
FOR each ROW
	INSERT INTO  user_friend_requests (user_id,request_user_id,request_user_name, confirmed ,created_at,updated_at) 
	SELECT new.friend_user_id, new.user_id, users.name, false , now(), now() FROM users
	WHERE
		new.confirmed=false AND users.id=new.user_id;


CREATE TRIGGER request_refuse
AFTER DELETE on user_friend_requests
FOR each ROW
	DELETE FROM user_friends WHERE old.confirmed=false AND user_friends.user_id=old.request_user_id AND user_friends.friend_user_id = old.user_id;
