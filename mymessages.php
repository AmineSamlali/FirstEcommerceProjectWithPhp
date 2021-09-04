<!-- active_chat -->
<style >
    .container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
</style>



<?php 
    session_start();
    session_regenerate_id();
    if(!isset($_SESSION['username'])){
        header('location:index.php');
        exit();
    }
    $pageName = 'Add New Product';
    include 'init.php';
    checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
    checkMaintenanceMode();

?>

<div class="container">
<h3 class=" text-center">Messaging</h3>
<div class="messaging">
    <div id="messageContainer" class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recent</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar" placeholder="Search">
                </div>
            </div>
          </div>
          <div  id="inbox_chat" class="inbox_chat">
            
            
            
            
            
            

            <?php
                $connection = $conn->prepare("SELECT 
                    messages.*,
                    products.*,
                    users.Image as userImage,
                    users.username as userUserName
                  FROM
                      messages
                  INNER JOIN products ON products.product_id = messages.added_to
                  INNER JOIN users ON users.user_id = messages.added_by
                  WHERE messages.added_by = ? or products.Added_by = ?  ORDER BY messages.message_id DESC");
                $connection->execute([$_SESSION['user_id'],$_SESSION['user_id']]);
                $messages = $connection->fetchAll();
                foreach($messages as $message){
                  $date = date("F,j", strtotime($message['message_date']));

                  if($message['Added_by'] === $_SESSION['user_id']){
                      echo '<div class="chat_list">
                      <div class="chat_people">
                        <div class="chat_img"> <img class="img-circle" src="data/users/'.$message['userImage'].'" alt="sunil"> </div>
                        <div class="chat_ib">
                          <h5><a onclick="message(this)" msgnumber="'.$message['message_id'].'" style="cursor: pointer;">'.$message['userUserName'].'</a> <span class="chat_date">'.$date.'</span></h5>
                          <p>About product: <a href="product.php?itemid='.$message['product_id'].'">'.$message['Name'].'</a>
                          <i style="float:right;cursor:pointer;" onclick="deleteConversation(this)" msgid="'.$message['message_id'].'" class="fa fa-trash" aria-hidden="true"></i>
                          </p>
                        </div>
                      </div>
                    </div>';
                  }else{
                    // <a href="product.php?itemid='.$message['product_id'].'">
                    echo '<div class="chat_list">
                    <div class="chat_people">
                      <div class="chat_img"> <img src="data/uploads/'.$message['Image'].'" alt="sunil"> </div>
                      <div class="chat_ib">
                        <h5> <a onclick="message(this)" msgnumber="'.$message['message_id'].'" style="cursor: pointer;">'.$message['Name'].'</a> <span class="chat_date">'.$date.'</span></h5>
                        <p>'.substr($message['Description'],0,40).'...</p>
                        <p style="float:right;">$'.$message['Price'].'
                        <i style="float:right;cursor:pointer;" onclick="deleteConversation(this)" msgid="'.$message['message_id'].'" class="fa fa-trash" aria-hidden="true"></i>

                        </p>
                      </div>
                    </div>
                  </div>';

                  }
                }
            ?>

            
          </div>
        </div>
        <div class="mesgs">
          <div id="msg_history" class="msg_history">

          </div>
          <div id="inputMessage" class="type_msg">
            <div class="input_msg_write">
              <input id="userMessage" type="text" class="write_msg" placeholder="Type a message">
              <button class="msg_send_btn" id="sendMsg" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>      
      
      
    </div></div>
<?php include $tpl.'footer.php'; ?>


<script>

  let curretMessage = '';
  let lastSmsFrom = '';
  function message(e){
    let allmessages = document.querySelectorAll('.chat_list');
    allmessages.forEach(msg => {
      if(msg.querySelector('.chat_ib h5 a').getAttribute('msgnumber') !== e.getAttribute('msgnumber')){
        msg.classList.remove('active_chat')
      }else{
        msg.classList.add('active_chat')

      }
    });
    curretMessage = e.getAttribute('msgnumber');
    // get all of Messages
    $.post("admin/ajax_messages.php",{
      formType:'getAllMessages',
      mainMessage:curretMessage
    },(data) =>{
      let jsonData = JSON.parse(data);
      document.getElementById('msg_history').innerHTML = '';
      jsonData.forEach(msg => {
        let msgdiv = document.createElement('div')
        let time = new Date(msg['sms_date']);
        var  months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let smsTime = time.getHours() +':'+time.getMinutes() + ((time.getHours() >= 12) ? ' PM' : ' AM') + ' | ' + time.getMonth() +' ' + months[time.getMonth()];

        if(msg['sms_from'] != <?php echo $_SESSION['user_id'] ?>){
          msgdiv.innerHTML = `
            <div class="incoming_msg">
                <div class="incoming_msg_img"> <img class="img-circle" src="data/users/${msg['image']}" alt="sunil"> </div>
                <div class="received_msg">
                  <div class="received_withd_msg">
                    <p>${ msg['sms_text'] } </p>
                    <span class="time_date"> ${smsTime}</span></div>
                </div>
              </div>
          `
        }else{
          msgdiv.innerHTML = `<div class="outgoing_msg">
              <div class="sent_msg">
                <p>${msg['sms_text']}</p>
                <span class="time_date">${smsTime}</span> </div>
            </div>`
        }
        document.getElementById('msg_history').appendChild(msgdiv)
        lastSmsFrom  = msg['sms_id'];
        var myDiv = document.getElementById("msg_history");
        myDiv.scrollTop = myDiv.scrollHeight;

      })

    })

  }


    // check if there is No Messages From Charts 
  if(!document.getElementById('inbox_chat').children.length > 0){
    document.getElementById('messageContainer').innerHTML = "<center><h1>Sorry You Don't Have Any Messages</h1></center>";
  }else{
    document.getElementById('inbox_chat').children[0].querySelector('.chat_people .chat_ib h5 a').click();
  }

  // check if there is no chats 


  setInterval((e) => {
    if(curretMessage && lastSmsFrom){
        $.post('admin/ajax_messages.php' , {
          formType:'getNewMessages',
          currentMsg:curretMessage,
          lastSms:lastSmsFrom
        },(res) => {
          if(res){
            let response = JSON.parse(res);
            if(response.length > 0){
              response = response[0];
              let msgdiv = document.createElement('div')
              let time = new Date(response['sms_date']);
              var  months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
              let smsTime = time.getHours() +':'+time.getMinutes() + ((time.getHours() >= 12) ? ' PM' : ' AM') + ' | ' + time.getMonth() +' ' + months[time.getMonth()];
        
                if(response['sms_from'] != <?php echo $_SESSION['user_id'] ?>){
                msgdiv.innerHTML = `
                  <div class="incoming_msg">
                      <div class="incoming_msg_img"> <img class="img-circle" src="data/users/${response['image']}" alt="sunil"> </div>
                      <div class="received_msg">
                        <div class="received_withd_msg">
                          <p>${response['sms_text']}</p>
                          <span class="time_date"> ${smsTime}</span></div>
                      </div>
                    </div>
                `
              }else{
                // <p class="text-muted"><em>This Message Was Deleted</em></p>
                msgdiv.innerHTML = `<div class="outgoing_msg">
                    <div class="sent_msg">
                      <p>${response['sms_text']} </p>
                      <span class="time_date"> ${smsTime}</span> </div>
                  </div>`
              }
              document.getElementById('msg_history').appendChild(msgdiv)
              lastSmsFrom  = response['sms_id'];
              var myDiv = document.getElementById("msg_history");
              myDiv.scrollTop = myDiv.scrollHeight;
            }
          }
          

        })

      }      
    }, 0500);

  document.getElementById('sendMsg').addEventListener('click' , (e) => {

    sendMessage()

  })

  // refreash for deleting messsages 

  function sendMessage(){
    if($('#userMessage').val().length > 0){
      $.post('admin/ajax_messages.php',{
        formType:'sendNewMessage',
        message:$('#userMessage').val(),
        currentMessage:curretMessage
      },(res) => {
        $('#userMessage').val('');
      })
    }

  }


  function deleteConversation(e){
    let okey = confirm('Are u sure ?');
    if(okey){
      let coverId = e.getAttribute('msgid');
      $.post('admin/ajax_messages.php',{
        formType:'deleteConv',
        coverId:coverId
      },(res) => {
        if(res){
          e.parentNode.parentNode.parentNode.parentNode.remove();
         location.reload();
        }
      })

    }
  }



</script>