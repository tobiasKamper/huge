<div class="container">
    <h1>Chat/index</h1>

    <div class="box">
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>

        <div>
            This Chat shows CHAT
        </div>
        <div>
            <table class="overview-table" id="adminID">
            <script>
                $(document).ready( function () {
                    $('#adminID').DataTable();
                } );
            </script>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Send Message</th>
                    <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($this->users as $user) { 
                    if($user->user_id == Session::get('user_id'))continue;?>

                    <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                        <td><?= $user->user_id; ?></td>
                        <td class="avatar">
                            <?php if (isset($user->user_avatar_link)) { ?>
                                <img src="<?= $user->user_avatar_link; ?>"/>
                            <?php } ?>
                        </td>
                        
                        <td><?= $user->user_name; ?></td>
                        <form action="<?= config::get("URL"); ?>Chat/getMessages" method="post">
                    <input type="hidden" name="toUser" value="<?php  echo $user->user_id  ?>" >
                    <input type="hidden" name="fromUser" value="<?php  echo Session::get('user_id')  ?>" >
                        <td>
                        <input type="submit" input="Chat"value="Chat" />
                    </form>
                        </td>

                        <td>
                            <?php 
                                    if(isset($this->newMessages["u".$user->user_id])){
                                        echo $this->newMessages["u".$user->user_id]. " new messages";
                                    }
                                    else{
                                        echo "";
                                    }
                            ?>

                        </td>
                        
                    </tr>
                <?php } ?></tbody>
            </table>
        </div>
    </div>
</div>
