<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetup extends Model{
  protected $fillable = ["store_id", "bank_id", "identity", "bank_number", "bank_account_name"];
}
