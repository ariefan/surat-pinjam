<?php

namespace App\Controllers;

use Hashids\Hashids;
use App\Models\UserModel;

class Chat extends BaseController
{
    public $sk;

    function __construct()
    {
    }

    public function index()
    {
        $data = [];
        return view('chat/index', $data);
    }

    public function authenticate()
    {
        exit('success');
    }

    public function conversations()
    {
        if (!$this->is_loggedin()) {
            exit('error');
        }
        // (new UserModel())->update(session('id'), ['online_status' => 'Idle']);

        // Retrieve all the conversations associated with the user along with the most recent message
        $db = \Config\Database::connect();

        $new_conversations = $db->query("SELECT * FROM users WHERE id NOT IN
            (SELECT IF(" . session('id') . " = account_sender_id, account_receiver_id, account_sender_id) user_id 
            FROM conversations 
            WHERE account_sender_id = " . session('id') . " OR account_receiver_id = " . session('id') . ")
            ORDER BY nama")->getResultArray();

        foreach ($new_conversations as $n) {
            $db->table('conversations')->insert([
                'account_sender_id' => session('id'),
                'account_receiver_id' => $n['id'],
                'submit_date' => date('Y-m-d H:i:s'),
            ]);
        }

        $q = $db->escapeLikeString(htmlspecialchars($_GET['q'] ?? ''));
        // $conversations = $db->query('SELECT 
        //     c.*, 
        //     (SELECT msg FROM messages WHERE conversation_id = c.id ORDER BY submit_date DESC LIMIT 1) AS msg, 
        //     (SELECT COUNT(1) FROM messages WHERE `read` = 0 AND conversation_id = c.id AND account_id != ' . session('id') . ') AS msg_unread, 
        //     (SELECT submit_date FROM messages WHERE conversation_id = c.id ORDER BY submit_date DESC LIMIT 1) AS msg_date, 
        //     a.nama AS account_sender_full_name, 
        //     a2.nama AS account_receiver_full_name, 
        //     a.username AS account_sender_username, 
        //     a2.username AS account_receiver_username, 
        //     IF(TIMESTAMPDIFF(SECOND, a2.last_seen, NOW()) < 180 AND a2.online_status = "Online", "Online", a2.last_seen) status
        //     FROM 
        //     conversations c
        //     JOIN users a ON a.id = c.account_sender_id 
        //     JOIN users a2 ON a2.id = c.account_receiver_id 
        //     WHERE 
        //     (CONCAT(a.username, a2.username) LIKE "%' . $q . '%") AND
        //     (c.account_sender_id = ' . session('id') . ' OR c.account_receiver_id = ' . session('id') . ') GROUP BY c.id')->getResultArray();

        $user_id = session('id');
        $conversations = $db->query('SELECT
            c.*, s.id sender_id, r.id receiver_id,
            (SELECT msg FROM messages WHERE conversation_id = c.id ORDER BY submit_date DESC LIMIT 1) AS msg, 
            (SELECT COUNT(1) FROM messages WHERE `read` = 0 AND conversation_id = c.id AND account_id != ' . $user_id . ') AS msg_unread,
            (SELECT submit_date FROM messages WHERE conversation_id = c.id ORDER BY submit_date DESC LIMIT 1) AS msg_date, 
            s.nama AS sender_name, 
            r.nama AS receiver_name, 
            s.username AS sender_username, 
            r.username AS receiver_username, 
            IF(TIMESTAMPDIFF(SECOND, r.last_seen, NOW()) < 180 AND r.online_status = "Online", "Online", r.last_seen) status
            FROM
            conversations c
            JOIN users s ON s.id = c.account_sender_id
            JOIN users r ON r.id = c.account_receiver_id
            ' . (empty($q) ? 'LEFT JOIN messages m ON c.id = m.conversation_id' : '') . '
            WHERE ' . (empty($q) ? 'msg IS NOT NULL AND ' : '') . 'CONCAT(s.username, s.nama, r.username, r.nama) LIKE "%' . $q . '%"
            AND (c.account_sender_id = ' . $user_id . ' OR c.account_receiver_id = ' . $user_id . ')
            GROUP BY c.id
        ')->getResultArray();

        // Sort the conversations by the most recent message date
        usort($conversations, function ($a, $b) {
            $date_a = strtotime($a['msg_date'] ? $a['msg_date'] : $a['submit_date']);
            $date_b = strtotime($b['msg_date'] ? $b['msg_date'] : $b['submit_date']);
            return $date_b - $date_a;
        });

        $data = [
            'conversations' => $conversations,
        ];
        return view('chat/conversations', $data);
    }

    public function conversation()
    {
        if (!$this->is_loggedin()) {
            exit('error');
        }
        // Ensure the GET ID param exists
        if (!isset($_GET['id'])) {
            exit('error');
        }

        // Update the account status to Occupied
        // (new UserModel())->update(session('id'), ['online_status' => 'Occupied']);
        // Retrieve the conversation based on the GET ID param and account ID
        $db = \Config\Database::connect();
        $id = $db->escapeLikeString(htmlspecialchars($_GET['id'] ?? ''));
        $conversation = $db->query('SELECT 
            c.*, m.msg, 
            a.nama AS account_sender_full_name, 
            a2.nama AS account_receiver_full_name,   
            a.username AS account_sender_username, 
            a2.username AS account_receiver_username,            
            IF(TIMESTAMPDIFF(SECOND, a2.last_seen, NOW()) < 180 AND a2.online_status = "Online", "Online", a2.last_seen) status,
            a2.last_seen last_seen
            FROM 
            conversations c 
            JOIN users a ON a.id = c.account_sender_id 
            JOIN users a2 ON a2.id = c.account_receiver_id 
            LEFT JOIN messages m ON m.conversation_id = c.id 
            WHERE 
            c.id = ' . $id . ' AND (c.account_sender_id = ' . session('id') . ' OR c.account_receiver_id = ' . session('id') . ') LIMIT 1')->getResultArray();
        // If the conversation doesn't exist
        if (!$conversation) {
            exit('error');
        }
        $conversation = $conversation[0];
        // Retrieve all messages based on the conversation ID
        $results = $db->query('SELECT * FROM messages WHERE conversation_id = ' . $id . ' ORDER BY submit_date ASC')->getResultArray();
        // Group all messages by the submit date
        $messages = [];
        foreach ($results as $result) {
            $messages[date('y/m/d', strtotime($result['submit_date']))][] = $result;
        }

        $db->table('messages')->where('conversation_id = ' . $id . ' AND account_id != ' . session('id'))->update(['read' => 1]);

        $data = [
            'conversation' => $conversation,
            'messages' => $messages,
        ];
        return view('chat/conversation', $data);
    }

    function is_loggedin()
    {
        if (isset($_SESSION['logged_in'])) {
            (new UserModel())->update(session('id'), ['last_seen' => date('Y-m-d H:i:s')]);
            return true;
        }
        if (isset($_COOKIE['chat_secret']) && !empty($_COOKIE['chat_secret'])) {
            $account = (new UserModel())->where('chat_secret', $_COOKIE['chat_secret'])->first();
            $stmt = $pdo->prepare('SELECT * FROM accounts WHERE secret = ?');
            $stmt->execute([$_COOKIE['chat_secret']]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
            // Does the account exist?
            if ($account) {
                // Yes it does... Authenticate the user
                $_SESSION['account_loggedin'] = TRUE;
                $_SESSION['account_id'] = $account['id'];
                $_SESSION['account_role'] = $account['role'];
                return TRUE;
            }
        }
        // User isn't logged-in!
        return FALSE;
    }

    function post_message()
    {
        if (!$this->is_loggedin()) {
            exit('error');
        }
        // Ensure the GET ID and msg params exists
        if (!isset($_POST['id'], $_POST['msg'])) {
            exit('error');
        }
        // Make sure the user is associated with the conversation
        $db = \Config\Database::connect();
        $conversation = $db->query('SELECT id FROM conversations WHERE id = ' . $_POST['id'] . ' AND (account_sender_id = ' . session('id') . ' OR account_receiver_id = ' . session('id') . ')')->getResultArray();

        if (!$conversation) {
            exit('error');
        }

        $msg = $_POST['msg'];

        $file = $this->request->getFile('file_img');
        if (!empty($file) && !empty($file->getFileName())) {
            $file_name = date('YmdHis') . '.' . $file->guessExtension();
            $file->move('upload/attachment', $file_name);
            $msg = '<a target="__blank" href="' . base_url("upload/attachment/$file_name") . '"><img src="' . base_url("upload/attachment/$file_name") . '" width="200px" /></a>';
        }

        $file_doc = $this->request->getFile('file_doc');
        if (!empty($file_doc) && !empty($file_doc->getFileName())) {
            $file_name = date('YmdHis') . '.' . $file_doc->guessExtension();
            $file_doc->move('upload/attachment', $file_name);
            $msg = '<a href="' . base_url("upload/attachment/$file_name") . '" style="color:white;text-decoration:underline;" target="__blank">' . $file_name . '</a>';
        }

        // Insert the new message into the database
        $db->table('messages')->insert([
            'conversation_id' => $_POST['id'],
            'account_id' => session('id'),
            'msg' => $msg,
            'submit_date' => date('Y-m-d H:i:s'),
        ]);
        // Output success
        exit('success');
    }

    public function total_unread()
    {
        $db = \Config\Database::connect();
        $total_unread = $db->query('SELECT 
        SUM((SELECT COUNT(1) FROM messages WHERE `read` = 0 AND conversation_id = c.id AND account_id != ' . session('id') . ')) AS msg_unread
        FROM 
        conversations c
        WHERE 
        c.account_sender_id = ' . session('id') . ' OR c.account_receiver_id = ' . session('id'))->getResultArray()[0]['msg_unread'];

        exit($total_unread);
    }
}