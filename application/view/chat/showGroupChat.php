
<?php
if(!$this->chatData){
    
    ?>
    <div style="justify-content: center; display: flex;">
    No history available...
    </div>
    <?php
}
else{
?>
<section class="discussion">
<?php
$maxindex = count($this->chatData)-1;
foreach($this->chatData as $index=>$chatthing)
{
    if($chatthing->fromUserId == Session::get('user_id'))
    {
        //FIRST INDEX
        if($index == 0)
        {
            if($index == $maxindex){
                ?>
                <div class="bubble recipient">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
            else{
            if($this->chatData[$index+1]->fromUserId == Session::get('user_id')){
                ?>
                <div class="bubble recipient first">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
            else{
                ?>
                <div class="bubble recipient">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }}
        }
        //LAST INDEX
        else if($index == $maxindex){
            if($this->chatData[$index-1]->fromUserId == Session::get('user_id')){
            ?>
                <div class="bubble recipient last">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
            else{
                ?>
                <div class="bubble recipient">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
        }
        //OTHER INDEXES first element
        else if($this->chatData[$index+1]->fromUserId == Session::get('user_id')&&$this->chatData[$index-1]->fromUserId != Session::get('user_id')){
            ?>
                <div class="bubble recipient first">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
        }
        //OTHER INDEXES last element
        else if($this->chatData[$index-1]->fromUserId == Session::get('user_id')&&$this->chatData[$index+1]->fromUserId != Session::get('user_id')){
            ?>
                <div class="bubble recipient last">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
        }
        else if($this->chatData[$index-1]->fromUserId == Session::get('user_id')&&$this->chatData[$index+1]->fromUserId == Session::get('user_id')){
            ?>
            <div class="bubble middle recipient">
                <?php echo $chatthing->text; ?>
        </div>
            <?php
        }
        else{
            ?>
            <div class="bubble recipient">
                <?php echo $chatthing->text; ?>
        </div>
            <?php
        }
    }
    else{
        //FIRST INDEX
        if($index == 0)
        {
                if($index == $maxindex){
                    ?>
                    <div class="bubble sender">
                        <?php echo $chatthing->text; ?>
                </div>
                    <?php
                }
            
            else{
            if($this->chatData[$index]->fromUserId == $this->chatData[$index+1]->fromUserId){
                
                echo"<span>". $chatthing->fromUserName ."</span>";
                
                ?>
                <div class="bubble sender first">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
            else{
                echo"<span>". $chatthing->fromUserName ."</span>";
                ?>
                <div class="bubble sender">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }}
        }
        //LAST INDEX
        else if($index == $maxindex){
            if($this->chatData[$index-1]->fromUserId == $this->chatData[$index]->fromUserId){
            ?>
                <div class="bubble sender last">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
            else{
                echo"<span>". $chatthing->fromUserName ."</span>";
                ?>
                <div class="bubble sender">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
            }
        }
        //OTHER INDEXES first element
        else if($this->chatData[$index+1]->fromUserId == $this->chatData[$index]->fromUserId&&$this->chatData[$index-1]->fromUserId !=$this->chatData[$index]->fromUserId){
            
            echo"<span>". $chatthing->fromUserName ."</span>";
            ?>
                <div class="bubble sender first">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
        }
        //OTHER INDEXES last element
        else if($this->chatData[$index-1]->fromUserId == $this->chatData[$index]->fromUserId&&$this->chatData[$index+1]->fromUserId != $this->chatData[$index]->fromUserId){
            ?>
                <div class="bubble sender last">
                    <?php echo $chatthing->text; ?>
            </div>
                <?php
        }
        else if($this->chatData[$index-1]->fromUserId == $this->chatData[$index]->fromUserId&&$this->chatData[$index+1]->fromUserId == $this->chatData[$index]->fromUserId){
            ?>
            <div class="bubble middle sender">
                <?php echo $chatthing->text; ?>
        </div>
            <?php
        }
        else{
            echo"<span>". $chatthing->fromUserName ."</span>";
            ?>
            <div class="bubble sender">
                <?php echo $chatthing->text; ?>
        </div>
            <?php
        }
    }
}}
?>
<br><br><br>
<div style="justify-content: center; display: flex;">
<form action="<?= config::get("URL"); ?>Chat/createNewGroupEntry" method="post">
    <input type="hidden" name="fromUser" value="<?php  echo Session::get('user_id')  ?>" >
    <input type="text" name="text" />
        <input type="submit" input="Chat"value="Chat" />
    </form>
    </div>
</section>