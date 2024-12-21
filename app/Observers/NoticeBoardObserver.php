<?php
/***
 * Model generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\LAModule;
use App\Models\LAModuleField;
use Illuminate\Support\Facades\DB;

use App\Models\NoticeBoard;

class NoticeBoardObserver
{
    /**
     * Listen to the Record deleting event.
     *
     * @param  NoticeBoard  $notice_board
     * @return void
     */
    public function deleting(NoticeBoard $notice_board)
    {
        return LAModule::clearMultiselects('Notice_boards', $notice_board->id);
    }
}
