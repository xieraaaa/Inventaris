<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder {
    private $statuses = [
        'Pending',
        'Approved',
        'Rejected',
        'Borrowed',
        'Returned'
    ];
    
    public function run()
    {
        foreach ($this->statuses as $status) {
            if (!is_null(Status::firstWhere(['status' => $status]))) {
                continue;
            }
            
            Status::create([
                'status' => $status
            ]);
        }
    }
}
