<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<script type="module">
</script>

<div class="card m-0">
  <!-- <div class="card-header">
    <b>Chat</b>
  </div> -->
  <div class="card-body">

  <div class="row">
    <div class="card-body chat-widget p-0">

        <div class="row chat-widget-content">
            <div class="col-12 col-sm-12 col-md-4 chat-widget-tabs p-0">
                <!-- <div class="btn-toolbar mb-3" role="toolbar">
                <div class="input-group" style="width:100%;">
                    <input type="text" class="form-control" placeholder="Cari Nama">
                </div>
                </div> -->
                <div class="chat-widget-tab chat-widget-conversations-tab"></div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 chat-widget-tabs p-0" id="chat-body">
                <div class="chat-widget-tab chat-widget-conversation-tab"></div>
            </div>
        </div>  



    </div>
</div>

<div class="chat-widget-header" style="display:none;">
    <a href="#" class="previous-chat-tab-btn">&lsaquo;</a>
    <a href="#" class="close-chat-widget-btn">&times;</a>
</div>


<!-- add your HTML code here -->
<a href="#" class="open-chat-widget"><i class="fa-solid fa-comment-dots fa-lg"></i></a>



<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('chat.css'); ?>" rel="stylesheet" type="text/css">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://unpkg.com/picmo@latest/dist/umd/index.js"></script>
<script src="https://unpkg.com/@picmo/popup-picker@latest/dist/umd/index.js"></script>
<script>

// Place the JS code here
// Variables we will use in our app
let currentChatTab = 1;
let conversationId = null;
// let status = 'Idle';
let max = true;

// OnClick event handler for our open chat button
document.querySelector('.open-chat-widget').onclick = event => {
    event.preventDefault();
    // Execute the initialize chat function
    initChat();
};
// Intialize chat function - handle all aspects of the chat widget
const initChat = () => {
    // Add init code here
    // Show the chat widget
    document.querySelector('.chat-widget').style.display = 'flex';
    // Animate the chat widget
    document.querySelector('.chat-widget').getBoundingClientRect();
    document.querySelector('.chat-widget').classList.add('open');
    // Close button OnClick event handler
    document.querySelector('.close-chat-widget-btn').onclick = event => {
        event.preventDefault();
        // Close the chat
        document.querySelector('.chat-widget').classList.remove('open');
    };

    fetch('<?= base_url('chat/conversations'); ?>', { cache: 'no-store' }).then(response => response.text()).then(data => {
        // Update the status
        // status = 'Idle';
        // Update the conversations tab content
        document.querySelector('.chat-widget-conversations-tab').innerHTML = data;
        // Execute the conversation handler function
        conversationHandler();
        // Transition to the conversations tab
        selectChatTab(2);
    });

    // If the secret code cookie exists, attempt to automatically authenticate the user
    if (document.cookie.match(/^(.*;)?\s*chat_secret\s*=\s*[^;]+(.*)?$/)) {
        // Execute GET AJAX request to retireve the conversations
        fetch('<?= base_url('chat/conversations'); ?>', { cache: 'no-store' }).then(response => response.text()).then(data => {
            // If respone not equals error
            if (data != 'error') {
                // User is authenticated! Update the status and conversations tab content
                // status = 'Idle';
                document.querySelector('.chat-widget-conversations-tab').innerHTML = data;
                // Execute the conversation handler function
                conversationHandler();
                // Transition to the conversations tab
                selectChatTab(2);
            }
        });
    }

    // Previous tab button OnClick event handler
    document.querySelector('.previous-chat-tab-btn').onclick = event => {
        event.preventDefault();
        // Transition to the respective page
        selectChatTab(currentChatTab-1);
    };
};

// Select chat tab - it will be used to smoothly transition between tabs
const selectChatTab = value => {
    // Update the current tab variable
    currentChatTab = value;
    // Select all tab elements and add the CSS3 property transform
    //document.querySelectorAll('.chat-widget-tab').forEach(element => element.style.transform = `translateX(-${(value-1)*100}%)`);
    // If the user is on the first tab, hide the prev tab button element
    document.querySelector('.previous-chat-tab-btn').style.display = value > 1 ? 'block' : 'none';
    // Update the conversation ID variable if the user is on the first or second tab
    if (value == 1 || value == 2) {
        conversationId = null;
    }
    // If the user is on the login form tab (tab 1), remove the secret code cookie (logout)
    if (value == 1) {
        document.cookie = 'chat_secret=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
};

// Conversation handler function - will add the event handlers to the conversations list and new chat button
const conversationHandler = () => {
    // New chat button OnClick event handler
    document.querySelector('.chat-widget-new-conversation').onclick = event => {
        event.preventDefault();
        // Update the status
        status = 'Waiting';
        // Notify the user
        document.querySelector('.chat-widget-conversation-tab').innerHTML = `
        <div class="chat-widget-messages">
            <div class="chat-widget-message">Please wait...</div>
        </div>
        `;
        // Transition to the conversation tab (tab 3)
        selectChatTab(3);                
    };
    // Iterate the conversations and add the OnClick event handler to each element
    document.querySelectorAll('.chat-widget-conversation').forEach(element => {
        element.onclick = event => {
            event.preventDefault();
            // Get the conversation
            getConversation(element.dataset.id);
        };
    });
};

// Get conversation function - execute an AJAX request that will retrieve the conversation based on the conversation ID column
const getConversation = id => {
    // Execute GET AJAX request
    fetch(`<?= base_url('chat/conversation'); ?>?id=${id}`, { cache: 'no-store' }).then(response => response.text()).then(data => {
        // Update conversation ID variable
        conversationId = id;
        // Update the status
        // status = 'Occupied';
        // Update the converstaion tab content
        document.querySelector('.chat-widget-conversation-tab').innerHTML = data;
        // Transition to the conversation tab (tab 3)
        selectChatTab(3);  
        // Retrieve the input message form element 
        let chatWidgetInputMsg = document.querySelector('.chat-widget-input-message');
        // If the element exists
        if (chatWidgetInputMsg) {
            // Scroll to the bottom of the messages container
            if (document.querySelector('.chat-widget-messages').lastElementChild) {
                document.querySelector('.chat-widget-messages').scrollTop = document.querySelector('.chat-widget-messages').lastElementChild.offsetTop;
            }
            // Message submit event handler
            chatWidgetInputMsg.onsubmit = event => {
                event.preventDefault();
                // Execute POST AJAX request that will send the captured message to the server and insert it into the database
                fetch(chatWidgetInputMsg.action, { 
                    cache: 'no-store',
                    method: 'POST',
                    body: new FormData(chatWidgetInputMsg)
                });
                // Create the new message element
                let chatWidgetMsg = document.createElement('div');
                chatWidgetMsg.classList.add('chat-widget-message');
                chatWidgetMsg.textContent = chatWidgetInputMsg.querySelector('input').value;
                // Add it to the messages container, right at the bottom
                document.querySelector('.chat-widget-messages').insertAdjacentElement('beforeend', chatWidgetMsg);
                // Reset the message element
                chatWidgetInputMsg.querySelector('input').value = '';
                // Scroll to the bottom of the messages container
                document.querySelector('.chat-widget-messages').scrollTop = chatWidgetMsg.offsetTop;
            };
        }
    });
};

// Update the conversations and messages in real-time
setInterval(() => {
    // Use AJAX to update the conversations list
    let q = document.querySelector('#chat-search').value
    fetch('<?= base_url('chat/conversations'); ?>?q='+q, { cache: 'no-store' }).then(response => response.text()).then(html => {
        let doc = (new DOMParser()).parseFromString(html, 'text/html');
        document.querySelector('.chat-widget-conversations').innerHTML = doc.querySelector('.chat-widget-conversations').innerHTML;
        conversationHandler();
    }); 
    // If the current tab is 2
    if (currentChatTab == 2) {
    // If the current tab is 3 and the conversation ID variable is not NUll               
    } else if (currentChatTab == 3 && conversationId != null) {
        // Use AJAX to update the conversation
        fetch('<?= base_url('chat/conversation'); ?>?id=' + conversationId, { cache: 'no-store' }).then(response => response.text()).then(html => {
            // The following variable will prevent the messages container from automatically scrolling to the bottom if the user previously scrolled up in the chat list
            let canScroll = true;
            if (document.querySelector('.chat-widget-messages').lastElementChild && document.querySelector('.chat-widget-messages').scrollHeight - document.querySelector('.chat-widget-messages').scrollTop != document.querySelector('.chat-widget-messages').clientHeight) {
                canScroll = false;
            }                    
            let doc = (new DOMParser()).parseFromString(html, 'text/html');
            // Update content
            document.querySelector('.chat-widget-messages').innerHTML = doc.querySelector('.chat-widget-messages').innerHTML;
            if (canScroll && document.querySelector('.chat-widget-messages').lastElementChild) {
                // Scroll to the bottom of the container
                document.querySelector('.chat-widget-messages').scrollTop = document.querySelector('.chat-widget-messages').lastElementChild.offsetTop;
            }                   
        });  
    // If the current tab is 3 and the status is Waiting           
    } else if (currentChatTab == 3 && status == 'Waiting') {
        // Attempt to find a new conversation between the user and operator (or vice-versa)
        fetch('<?= base_url('chat/find_conversation'); ?>', { cache: 'no-store' }).then(response => response.text()).then(data => {
            if (data != 'error') {
                // Success! Two users are now connected! Retrieve the new conversation
                getConversation(data);
            }
        });               
    }
}, 5000); // 5 seconds (5000ms) - the lower the number, the more demanding it is on your server.



if(max){
    document.querySelector('.open-chat-widget').style.display = 'none';
    document.querySelector('.close-chat-widget-btn').style.display = 'none';
    document.querySelector('.chat-widget').style.position = 'initial';
    document.querySelector('.chat-widget').style.width = '100%';
    document.querySelector('.chat-widget').style.height = (window.innerHeight - 160) + 'px';
    initChat();
}

</script>


<?= $this->endSection() ?>
