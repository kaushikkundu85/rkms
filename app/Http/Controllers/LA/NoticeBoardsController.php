<?php
/***
 * Controller generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Collective\Html\FormFacade as Form;
use App\Helpers\LAHelper;
use App\Models\LAModule;
use App\Models\LAModuleField;
use App\Models\LALog;

use App\Models\NoticeBoard;

class NoticeBoardsController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Notice_boards.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Notice_boards');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return NoticeBoard::all();
            } else {
                return View('la.notice_boards.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Notice_boards'),
                    'module' => $module
                ]);
            }
        } else {
            if($request->ajax() && !isset($request->_pjax)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Show the form for creating a new notice_board.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created notice_board in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Notice_boards", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Notice_boards", $request);

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                if($request->ajax() || isset($request->quick_add)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    , 400]);
                } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $insert_id = LAModule::insert("Notice_boards", $request);

            $notice_board = NoticeBoard::find($insert_id);

            // Add LALog
            LALog::make("Notice_boards.NOTICE_BOARD_CREATED", [
                'title' => "Notice board Created",
                'module_id' => 'Notice_boards',
                'context_id' => $notice_board->id,
                'content' => $notice_board,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $notice_board,
                    'message' => 'NoticeBoard updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/notice_boards')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.notice_boards.index');
            }
        } else {
            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Display the specified notice_board.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id notice_board ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Notice_boards", "view")) {

            $notice_board = NoticeBoard::find($id);
            if(isset($notice_board->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $notice_board;
                } else {
                    $module = LAModule::get('Notice_boards');
                    $module->row = $notice_board;

                    return view('la.notice_boards.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('notice_board', $notice_board);
                }
            } else {
                if($request->ajax() && !isset($request->_pjax)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return view('errors.404', [
                        'record_id' => $id,
                        'record_name' => ucfirst("notice_board"),
                    ]);
                }
            }
        } else {
            if($request->ajax() && !isset($request->_pjax)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Show the form for editing the specified notice_board.
     *
     * @param int $id notice_board ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Notice_boards", "edit")) {
            $notice_board = NoticeBoard::find($id);
            if(isset($notice_board->id)) {
                $module = LAModule::get('Notice_boards');

                $module->row = $notice_board;

                return view('la.notice_boards.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('notice_board', $notice_board);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("notice_board"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified notice_board in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id notice_board ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Notice_boards", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Notice_boards", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    ], 400);
                } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $notice_board_old = NoticeBoard::find($id);

            if(isset($notice_board_old->id)) {

                // Update Data
                LAModule::updateRow("Notice_boards", $request, $id);

                $notice_board_new = NoticeBoard::find($id);

                // Add LALog
                LALog::make("Notice_boards.NOTICE_BOARD_UPDATED", [
                    'title' => "Notice board Updated",
                    'module_id' => 'Notice_boards',
                    'context_id' => $notice_board_new->id,
                    'content' => [
                        'old' => $notice_board_old,
                        'new' => $notice_board_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $notice_board_new,
                        'message' => 'NoticeBoard updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/notice_boards')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.notice_boards.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return view('errors.404', [
                        'record_id' => $id,
                        'record_name' => ucfirst("notice_board"),
                    ]);
                }
            }

        } else {
            if($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Remove the specified notice_board from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id notice_board ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Notice_boards", "delete")) {

            $notice_board = NoticeBoard::find($id);
            if(isset($notice_board->id)) {
                $notice_board->delete();

                // Add LALog
                LALog::make("Notice_boards.NOTICE_BOARD_DELETED", [
                    'title' => "Notice board Deleted",
                    'module_id' => 'Notice_boards',
                    'context_id' => $notice_board->id,
                    'content' => $notice_board,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/notice_boards')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.notice_boards.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.notice_boards.index');
                }
            }
        } else {
            if($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = LAModule::get('Notice_boards');
        $listing_cols = LAModule::getListingColumns('Notice_boards');

        $values = DB::table('notice_boards')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Notice_boards');

        for($i = 0; $i < count($data->data); $i++) {

            $notice_board = NoticeBoard::find($data->data[$i]->id);

            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if(isset($fields_popup[$col]) && str_starts_with($fields_popup[$col]->popup_vals, "@")) {
                    if($col == $module->view_col) {
                        $data->data[$i]->$col = LAModuleField::getFieldValue($fields_popup[$col], $data->data[$i]->$col);
                    } else {
                        $data->data[$i]->$col = LAModuleField::getFieldLink($fields_popup[$col], $data->data[$i]->$col);
                    }
                }
                if($col == $module->view_col) {
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/notice_boards/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Notice_boards", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/notice_boards/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Notice_boards", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.notice_boards.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i]->dt_action = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}
