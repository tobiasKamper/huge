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

    public static function updateIsRead($toUser, $fromUser){
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE chat SET isRead = 1  
        WHERE toUserId = :toUser AND fromUserId = :fromUser AND isRead = 0");
        return $query->execute(array(
                ':fromUser' => $fromUser,
                ':toUser' => $toUser
        ));
    }

    public static function getNumberOfNewMessages($toUser){
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT DISTINCT(fromUserId) FROM chat WHERE isRead = 0 AND toUserId = :toUser");
        $query->execute(array(
            ':toUser' => $toUser
    ));
    $allUserswithNewMessages = $query->fetchAll();
    $newArray = [];
    foreach($allUserswithNewMessages as $userId)
    {
        $query = $database->prepare("SELECT COUNT(*) AS count FROM chat WHERE isRead = 0 AND toUserId = :toUser AND fromUserId = :fromUser");
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
        toUserId = :fromUser) AND (fromUserId = :fromUser OR fromUserId = :toUser) ORDER BY timestamp asc;");
        $query->execute(
            array(
                ':fromUser' => $fromUser,
                ':toUser' => $toUser
        )
    );

    return $query->fetchAll();
    }
}
