delimiter $$

CREATE FUNCTION `findAllChildEventType` (event_type_id INT)

RETURNS VARCHAR(4000)

BEGIN

DECLARE sTemp VARCHAR(4000);
DECLARE sTempChd VARCHAR(4000);

SET sTemp = '$';

SET sTempChd = cast(event_type_id as char);


WHILE sTempChd is not NULL DO

SET sTemp = CONCAT(sTemp,',',sTempChd);

SELECT group_concat(id) INTO sTempChd FROM event_types where FIND_IN_SET(parent_event_type_id,sTempChd)>0;

END WHILE;

return sTemp;

END$$
delimiter ;
