<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SampelInhouseParameter;
use App\Models\DataHomogenitas;
use App\Models\SampelInhouse;
use Illuminate\Support\Facades\DB;

class ResetHomogenitas extends Command
{
    protected $signature = 'reset:homogenitas';
    public function handle()
    {
        $params = SampelInhouseParameter::where('sampel_inhouse_id', 1)->get();
        foreach($params as $p) {
            DataHomogenitas::where('sampel_inhouse_parameter_id', $p->id)->delete();
            DB::table('sampel_inhouse_parameter')->where('id', $p->id)->update([
                'f_hitung' => null, 
                'f_tabel' => null, 
                'status_parameter' => 'draft', 
                'tanggal_homogenitas' => null
            ]);
        }
        DB::table('sampel_inhouse')->where('sampel_inhouse_id', 1)->update(['status' => 'uji_homogenitas']);
        $this->info('Reset Success');
    }
}
