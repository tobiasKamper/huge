<?php

class ChatController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // special authentication check for the entire controller: Note the check-ADMIN-authentication!
        // All methods inside this controller are only accessible for admins (= users that have role type 7)
        Auth::checkAuthentication();
    }

    /**
     * This method controls what happens when you move to /admin or /admin/index in your app.
     */
    public function index()
    {
        var_dump(ChatModel::getNumberOfNewMessages(Session::get('user_id'))
        );
        $this->View->render('chat/index', array(
                'users' => UserModel::getPublicProfilesOfAllUsers(),
                'userRoles' => UserRoleModel::getRoleFromDatabase(),
                'newMessages' => ChatModel::getNumberOfNewMessages(Session::get('user_id')))
        );
    }

    public function createNewGroupEntry(){
        ChatModel::createNewGroupEntry(
            Request::post('fromUser'), Request::post('text')
        );
        $this->View->render('chat/showGroupChat', array(
            'chatData' => ChatModel::getGroupMessages(),
        ));
    }

    public function getGroupMessages()
    {
        ChatModel::updateIsReadGroup();

        $this->View->render('chat/showGroupChat', array(
            'chatData' => ChatModel::getGroupMessages(),
        ));
        // Redirect::to("chat");
    }

    public function getCountUnreadMessages()
    {
        ChatModel::getNumberOfNewMessages(
            Request::post('toUserId')
        );
        Redirect::to("chat");
    }

    public function getMessages()
    {
        ChatModel::updateIsRead( Request::post('fromUser'), Request::post('toUser'));
        $this->View->render('chat/showChat', array(
            'chatData' => ChatModel::getMessages(
            Request::post('fromUser'), Request::post('toUser')),
            'toUser' => Request::post('toUser')
        ));
        // Redirect::to("chat");
    }
    public function updateIsRead()
    {
         ChatModel::updateIsRead(
            Request::post('fromUserId'), Request::post('toUserId')
        );
    }

    public function createNewChatEntry(){
        ChatModel::createNewChatEntry(
            Request::post('fromUser'), Request::post('toUser'), Request::post('text')
        );
        $this->View->render('chat/showChat', array(
            'chatData' => ChatModel::getMessages(
            Request::post('fromUser'), Request::post('toUser')),
            'toUser' => Request::post('toUser')
        ));
    }
}
