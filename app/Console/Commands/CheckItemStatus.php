<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;

class CheckItemStatus extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:check-item-status';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    Item::updateStatus();
    return 1;
  }
}
