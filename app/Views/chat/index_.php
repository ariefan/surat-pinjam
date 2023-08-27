<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<style>
  #chat-body {
    margin: 0;
    padding-bottom: 3rem;
    background-color: #073c64;
    font-family: "Noto Sans", sans-serif;
    background-image: url(https://i.ibb.co/6DCsvBF/bg.png);
  }

  #toolbar {
    background: rgba(255, 255, 255, 0.25);
    padding: 0.25rem;
    position: fixed;
    z-index: 999;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    height: 4rem;
    box-sizing: border-box;
    backdrop-filter: blur(10px);
  }

  #form {
    background: rgba(0, 0, 0, 0.15);
    padding: 0.25rem;
    display: flex;
    width: 100%;
    height: 3rem;
    box-sizing: border-box;
    background-color: #073c64;
    backdrop-filter: blur(10px);
  }
  #message-input {
    border: none;
    padding: 3px 1rem;
    flex-grow: 1;
    border-radius: 2rem;
    margin: 0.25rem;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(10px);
    font-size: 1rem;
  }
  #message-input:focus {
    outline: none;
  }
  #form > button {
    background: #fff;
    border: none;
    padding: 0 0.5rem;
    margin: 0.25rem;
    border-radius: 50%;
    outline: none;
    color: #000;
    cursor: pointer;
  }

  #messages {
    margin: 4rem 0 1rem 0;
    padding: 0;
    overflow: auto;
    word-wrap: break-word;
  }
  #messages > li {
    padding: 0.5rem 1rem;
  }
  #messages > li:nth-child(odd) {
    background: #efefef;
  }

  /* CSS talk bubble */
  .talk-bubble {
    margin: 3px 5% 3px 5%;
    float: right;
    clear: both;
    position: relative;
    max-width: 60%;
    height: auto;
    background-color: #fdcb2c;
  }
  .round {
    border-radius: 10px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
  }

  /* Right triangle placed top left flush. */
  .tri-right.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -20px;
    right: auto;
    top: 0px;
    bottom: auto;
    border: 22px solid;
    border-color: #fdcb2c transparent transparent transparent;
  }

  /* Right triangle placed top right flush. */
  .tri-right.right-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -10px;
    top: 0px;
    bottom: auto;
    border: 10px solid;
    border-color: #fdcb2c transparent transparent transparent;
  }

  /* Right triangle placed top left flush. */
  .tri-left.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -20px;
    left: auto;
    top: 0px;
    bottom: auto;
    border: 22px solid;
    border-color: #fdcb2c transparent transparent transparent;
  }

  /* Right triangle placed top left flush. */
  .tri-left.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    left: -10px;
    top: 0px;
    bottom: auto;
    border: 10px solid;
    border-color: #fdcb2c transparent transparent transparent;
  }

  /* talk bubble contents */
  .talktext {
    padding: 0.5em;
    text-align: left;
    line-height: 1.5em;
  }
  .talktext p {
    /* remove webkit p margins */
    -webkit-margin-before: 0em;
    -webkit-margin-after: 0em;
  }
</style>
<style>
  /* width */
  ::-webkit-scrollbar {
    width: 10px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #aaa;
    border-radius: 20px;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #888;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- <iframe id="my-frame" src="<?= base_url('chat.html'); ?>" style="bottom: 0;"></iframe> -->

<div class="card">
  <div class="card-header">
    <h4>Chat</h4>
  </div>
  <div class="card-body p-0" style="background-color:#eee;">

    <div class="row">
      <div class="col-12 col-sm-12 col-md-4 mb-3">
        <div class="btn-toolbar mb-3" role="toolbar">
          <div class="input-group" style="width:100%;">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-search"></i></div>
            </div>
            <input type="text" class="form-control" placeholder="Cari Nama">
          </div>
        </div>
        <ul id="user-list" class="list-group"></ul>
      </div>
      <div class="col-12 col-sm-12 col-md-8" id="chat-body">
        <div class="row">
          <div class="col-12">
            <div class="message-holder">
                <div id="messages" class="row"></div>
            </div>
          </div>
        </div>
      </div>
    </div>  

    <div class="row">
      <div class="col-12 col-sm-12 col-md-4 mb-3"></div>
      <div class="col-12 col-sm-12 col-md-8 pl-0">
        <form id="form">
          <input type="text" id="message-input" class="form-control" name="" />
          <button id="send" class="btn float-right btn-primary"><i class="fas fa-paper-plane"></i></button>
        </form>
      </div>
    </div>

  </div>
</div>


<?= $this->endSection() ?>



<?= $this->section('css') ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    let selected_user_id = 0;

    $('#chat-body').css('height', ($(window).height() - 200) + 'px');
    $('#chat-body').css('width', '100%');

    $(function () {
        scrollMsgBottom()
    })
    
    function scrollMsgBottom(){
        var d = $('.message-holder');
        d.scrollTop(d.prop("scrollHeight"));
    }

    function getImages(){
        const imgs = {
        'Mary' : 'mary.jpg',
        'Jon' : 'jon.jpg',
        'Alex' : 'alex.jpg',
        }

        return imgs
    }

    $(function () {
        var conn = new WebSocket('ws://117.53.46.140:6969?access_token=<?= session()->get('id') ?>');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            console.log(e.data);
            
            var data = JSON.parse(e.data)
            
            if ('users' in data){
            updateUsers(data.users)
            } else if('message' in data){
            newMessage(data)
            }

        };

        $('#form').on("submit", function (e) {
          e.preventDefault()
          var msg = $('#message-input').val()
          if(msg.trim() == '')
              return false
          conn.send(msg);
          myMessage(msg)
          $('#message-input').val('')
        })
    })

    function newMessage(msg){
        const imgs = getImages()
        html = 
            `<div style="width:100%;">
              <div class="talk-bubble tri-left round left-top" style="float:left;">
                <div class="talktext">
                    <span class="author"><b>` + msg.author + `</b></span> <span class="time">` + msg.time + `</span><br>
                    <p>` + msg.message + `</p>
                </div>
              </div>
            </div>`
        $('#messages').append(html)
        scrollMsgBottom()
    
    }

    function myMessage(msg){
        var name = '<?= session('name') ?>'
        const imgs = getImages()
        var date = new Date;
        var minutes = date.getMinutes();
        var hour = date.getHours();
        var time = hour + ':' + minutes
        html = 
            `<div style="width:100%;">
              <div class="talk-bubble tri-right round right-top">
                <div class="talktext">
                    <span class="author"><b>Me</b></span> <span class="time">` + time + `</span><br>
                    <p>` + msg + `</p>
                </div>
              </div>
            </div>`
        $('#messages').append(html)
        scrollMsgBottom()
    }

    function updateUsers(users){ 
        var html = ''
        var myId = <?= session('id') ?>;
        
        for (let index = 0; index < users.length; index++) {
          if(myId != users[index].c_user_id){
            selected_user_id = selected_user_id == 0 ? users[index].c_user_id : selected_user_id
            html += '<a href="#" class="list-group-item list-group-item-action '+(selected_user_id == users[index].c_user_id ? 'active' : '')+'">'+ users[index].c_name +'</li>'
          }
        }

        if(html == ''){
          html = '<p>Daftar obrolan kosong</p>'
        }
        

        $('#user-list').html(html)
        

    }

</script>
<?= $this->endSection() ?>