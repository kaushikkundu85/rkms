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

use App\Models\StaticPage;

class StaticPagesController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Static_pages.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Static_pages');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return StaticPage::all();
            } else {
                return View('la.static_pages.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Static_pages'),
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
     * Show the form for creating a new static_page.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created static_page in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Static_pages", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Static_pages", $request);

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

            $insert_id = LAModule::insert("Static_pages", $request);

            $static_page = StaticPage::find($insert_id);

            // Add LALog
            LALog::make("Static_pages.STATIC_PAGE_CREATED", [
                'title' => "Static page Created",
                'module_id' => 'Static_pages',
                'context_id' => $static_page->id,
                'content' => $static_page,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $static_page,
                    'message' => 'StaticPage updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/static_pages')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.static_pages.index');
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
     * Display the specified static_page.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id static_page ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Static_pages", "view")) {

            $static_page = StaticPage::find($id);
            if(isset($static_page->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $static_page;
                } else {
                    $module = LAModule::get('Static_pages');
                    $module->row = $static_page;

                    return view('la.static_pages.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('static_page', $static_page);
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
                        'record_name' => ucfirst("static_page"),
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
     * Show the form for editing the specified static_page.
     *
     * @param int $id static_page ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Static_pages", "edit")) {
            $static_page = StaticPage::find($id);
            if(isset($static_page->id)) {
                $module = LAModule::get('Static_pages');

                $module->row = $static_page;

                return view('la.static_pages.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('static_page', $static_page);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("static_page"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified static_page in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id static_page ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Static_pages", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Static_pages", $request, true);

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

            $static_page_old = StaticPage::find($id);

            if(isset($static_page_old->id)) {

                // Update Data
                LAModule::updateRow("Static_pages", $request, $id);

                $static_page_new = StaticPage::find($id);

                // Add LALog
                LALog::make("Static_pages.STATIC_PAGE_UPDATED", [
                    'title' => "Static page Updated",
                    'module_id' => 'Static_pages',
                    'context_id' => $static_page_new->id,
                    'content' => [
                        'old' => $static_page_old,
                        'new' => $static_page_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $static_page_new,
                        'message' => 'StaticPage updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/static_pages')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.static_pages.index');
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
                        'record_name' => ucfirst("static_page"),
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
     * Remove the specified static_page from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id static_page ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Static_pages", "delete")) {

            $static_page = StaticPage::find($id);
            if(isset($static_page->id)) {
                $static_page->delete();

                // Add LALog
                LALog::make("Static_pages.STATIC_PAGE_DELETED", [
                    'title' => "Static page Deleted",
                    'module_id' => 'Static_pages',
                    'context_id' => $static_page->id,
                    'content' => $static_page,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/static_pages')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.static_pages.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.static_pages.index');
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
        $module = LAModule::get('Static_pages');
        $listing_cols = LAModule::getListingColumns('Static_pages');

        $values = DB::table('static_pages')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Static_pages');

        for($i = 0; $i < count($data->data); $i++) {

            $static_page = StaticPage::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/static_pages/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Static_pages", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/static_pages/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Static_pages", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.static_pages.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
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
