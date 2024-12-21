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

use App\Models\Banner;

class BannersController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Banners.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Banners');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Banner::all();
            } else {
                return View('la.banners.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Banners'),
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
     * Show the form for creating a new banner.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created banner in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Banners", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Banners", $request);

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

            $insert_id = LAModule::insert("Banners", $request);

            $banner = Banner::find($insert_id);

            // Add LALog
            LALog::make("Banners.BANNER_CREATED", [
                'title' => "Banner Created",
                'module_id' => 'Banners',
                'context_id' => $banner->id,
                'content' => $banner,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $banner,
                    'message' => 'Banner updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/banners')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.banners.index');
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
     * Display the specified banner.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id banner ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Banners", "view")) {

            $banner = Banner::find($id);
            if(isset($banner->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $banner;
                } else {
                    $module = LAModule::get('Banners');
                    $module->row = $banner;

                    return view('la.banners.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('banner', $banner);
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
                        'record_name' => ucfirst("banner"),
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
     * Show the form for editing the specified banner.
     *
     * @param int $id banner ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Banners", "edit")) {
            $banner = Banner::find($id);
            if(isset($banner->id)) {
                $module = LAModule::get('Banners');

                $module->row = $banner;

                return view('la.banners.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('banner', $banner);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("banner"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified banner in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id banner ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Banners", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Banners", $request, true);

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

            $banner_old = Banner::find($id);

            if(isset($banner_old->id)) {

                // Update Data
                LAModule::updateRow("Banners", $request, $id);

                $banner_new = Banner::find($id);

                // Add LALog
                LALog::make("Banners.BANNER_UPDATED", [
                    'title' => "Banner Updated",
                    'module_id' => 'Banners',
                    'context_id' => $banner_new->id,
                    'content' => [
                        'old' => $banner_old,
                        'new' => $banner_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $banner_new,
                        'message' => 'Banner updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/banners')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.banners.index');
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
                        'record_name' => ucfirst("banner"),
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
     * Remove the specified banner from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id banner ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Banners", "delete")) {

            $banner = Banner::find($id);
            if(isset($banner->id)) {
                $banner->delete();

                // Add LALog
                LALog::make("Banners.BANNER_DELETED", [
                    'title' => "Banner Deleted",
                    'module_id' => 'Banners',
                    'context_id' => $banner->id,
                    'content' => $banner,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/banners')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.banners.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.banners.index');
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
        $module = LAModule::get('Banners');
        $listing_cols = LAModule::getListingColumns('Banners');

        $values = DB::table('banners')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Banners');

        for($i = 0; $i < count($data->data); $i++) {

            $banner = Banner::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/banners/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Banners", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/banners/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Banners", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.banners.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
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
