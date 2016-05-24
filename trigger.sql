CREATE TRIGGER create_user_view
after insert on users
FOR each ROW
BEGIN
	CREATE VIEW "user_"+id+""
END
