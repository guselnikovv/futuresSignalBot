<?php

class Check extends Db
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkNewSignal()
    {
        $stmt = $this->db->query("SELECT * FROM signals WHERE status = 'NEW' ORDER BY id DESC");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE api_key != '' AND api_secret != '' AND budget > 40");
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;

    }

    public function checkUserSignal($user_id, $signal_id)
    {
        $stmt = $this->db->query("SELECT * FROM signals_users WHERE user_id = $user_id AND signal_id = $signal_id");
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }




}
