<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel
{
    public $user_id;
    public $amount;
    public $method;
    public $status;
    public $created_at;

    public function save()
    {
        global $db;
        $sql = "INSERT INTO payments (user_id, amount, method, status, created_at) VALUES (" .
            $this->user_id . ", " . $this->amount . ", '" . $this->method . "', '" . $this->status . "', '" . $this->created_at . "')";
        mysqli_query($db, $sql);
    }

}
