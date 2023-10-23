<?php

/**
 * Handles all data manipulation of chat
 */
class ChatModel
{

    public static function createNewChatEntry($fromUser, $toUser, $text)
    {
        if(!$text)return;
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("INSERT INTO chat (`fromUserId`, `toUserId`, `text`) 
        VALUES (:fromUser, :toUser, :input)");
        $query->execute(array(
                ':fromUser' => $fromUser,
                ':toUser' => $toUser,
                ':input' => $text,
        ));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_SUSPENSION_DELETION_STATUS'));
            return true;
        }
    }

    public static function updateIsReadGroup(){

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE chat SET isRead = 1  
        WHERE toUserId = :toUser AND `group` = :group AND isRead = 0");
        return $query->execute(array(
                ':group' => Session::get('user_account_type'),
                ':toUser' => Session::get('user_id')
        ));
    }
    public static function createNewGroupEntry($fromUser, $text)
    {
        if(!$text)return;

    $UserWithinGroup = ChatModel::getUsersFromChat();

    
    $database = DatabaseFactory::getFactory()->getConnection();

    $valueQuery ="";
    foreach($UserWithinGroup as $userId){
        if($userId->user_id == Session::get('user_id'))continue;
        $valueQuery = $valueQuery . "($fromUser, $userId->user_id, " ."'" . $text . "', " . Session::get('user_account_type') . "),";
    }
    $valueQuery = substr_replace($valueQuery, "", -1);
    $query = $database->prepare("INSERT INTO chat (`fromUserId`, `toUserId`, `text`, `group`) 
    VALUES $valueQuery");
    $query->execute();
    }


    public static function getGroupMessages(){
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT " . "text" . ", fromUserId, `Group`, timestamp  FROM chat WHERE (`Group` = :groupId AND (toUserId = :toUser OR fromUserId =:toUser)) ORDER BY timestamp asc;");
        $query->execute(
            array(
                ':groupId' => Session::get('user_account_type'),
                ':toUser' => Session::get('user_id'),
        )
    );

    $tempArray = $query->fetchAll();

    $returnArray = [];

    foreach($tempArray as $item){

        if($returnArray == [])
        {
            array_push($returnArray, $item);
        }
        else{
            $sameItem = false;
            foreach($returnArray as $rItem){
                if($item->timestamp == $rItem->timestamp&&$item->fromUserId == $rItem->fromUserId&&$item->text == $rItem->text){
                    $sameItem = true;
                }
            }
            if(!$sameItem){
                array_push($returnArray, $item);
            }
        }
    }
    


    $query = $database->prepare("SELECT user_id, user_name FROM users;");
    $query->execute();
        $userArray = $query->fetchAll();

        foreach($returnArray as $rI){
            foreach($userArray as $uI){
                if($rI->fromUserId == $uI->user_id){
                    $rI->fromUserName = $uI->user_name;
                }
            }
        }



    return $returnArray;
    }



    

    public static function updateIsRead($toUser, $fromUser){
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE chat SET isRead = 1  
        WHERE toUserId = :toUser AND fromUserId = :fromUser AND isRead = 0 AND `group` IS NULL");
        return $query->execute(array(
                ':fromUser' => $fromUser,
                ':toUser' => $toUser
        ));
    }

    public static function getNumberOfNewMessages($toUser){
        
    $newArray = [];
    $database = DatabaseFactory::getFactory()->getConnection();

    $query = $database->prepare("SELECT COUNT(*) AS count FROM chat WHERE isRead = 0 AND toUserId = :toUser AND `group` = :group");
    $query->execute(array(
            ':group' => Session::get('user_account_type'),
            ':toUser' => $toUser
    ));
    $tVariable = $query->fetch();

    if($tVariable){
        $newArray["group"] = $tVariable->count;
    }


        $query = $database->prepare("SELECT DISTINCT(fromUserId) FROM chat WHERE isRead = 0 AND toUserId = :toUser AND `group` IS NULL");
        $query->execute(array(
            ':toUser' => $toUser
    ));
    $allUserswithNewMessages = $query->fetchAll();
    foreach($allUserswithNewMessages as $userId)
    {
        $query = $database->prepare("SELECT COUNT(*) AS count FROM chat WHERE isRead = 0 AND toUserId = :toUser AND fromUserId = :fromUser AND `group` IS NULL");
        $query->execute(array(
                ':fromUser' => $userId->fromUserId,
                ':toUser' => $toUser
        ));
        $tempVariable = $query->fetch();

        $newArray["u".$userId->fromUserId] = $tempVariable->count;
        // array_push($newArray, ("u".$userId->fromUserId=> $tempVariable));
    }

    return $newArray;

    }

    public static function getMessages($fromUser, $toUser){
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT " . "text" . ", fromUserId, toUserId FROM chat WHERE (toUserId = :toUser OR
        toUserId = :fromUser) AND (fromUserId = :fromUser OR fromUserId = :toUser) AND `group` IS NULL ORDER BY timestamp asc;");
        $query->execute(
            array(
                ':fromUser' => $fromUser,
                ':toUser' => $toUser
        )
    );

    return $query->fetchAll();
    }


private static function getUsersFromChat(){
    $database = DatabaseFactory::getFactory()->getConnection();

    $query = $database->prepare("SELECT user_id FROM users WHERE user_account_type = :group");
    $query->execute(array(
        ':group' => Session::get('user_account_type')
));

return $query->fetchAll();
}
}
