<?php
/***
 * Migration generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\LAModule;

class CreateStaticPagesTable extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        LAModule::generate("Static_pages", 'static_pages', 'content_title', 'fa-book', [
            [
                "colname" => "content_title",
                "label" => "Title",
                "field_type" => "Name",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 100,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "cms_content",
                "label" => "Content",
                "field_type" => "HTML",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 10000,
                "required" => true,
                "listing_col" => false
            ], [
                "colname" => "status",
                "label" => "Status",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => true,
                "popup_vals" => ["1","0"],
            ]
        ]);

        /*
        LAModule::generate("Module_Name", "Table_Name", "view_column_name" "Fields_Array");

        Field Format:
        [
            "colname" => "name",
            "label" => "Name",
            "field_type" => "Name",
            "unique" => false,
            "defaultvalue" => "John Doe",
            "minlength" => 5,
            "maxlength" => 100,
            "required" => true,
            "listing_col" => true,
            "popup_vals" => ["Employee", "Client"],
            "comment" => ""
        ]
        # Format Details: Check https://laraadmin.com/docs/migrations_cruds#schema-ui-types

        colname: Database column name. lowercase, words concatenated by underscore (_)
        label: Label of Column e.g. Name, Cost, Is Public
        field_type: It defines type of Column in more General way.
        unique: Whether the column has unique values. Value in true / false
        defaultvalue: Default value for column.
        minlength: Minimum Length of value in integer.
        maxlength: Maximum Length of value in integer.
        required: Is this mandatory field in Add / Edit forms. Value in true / false
        listing_col: Is allowed to show in index page datatable.
        popup_vals: These are values for MultiSelect, TagInput and Radio Columns. Either connecting @tables or to list []
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('static_pages')) {
            Schema::drop('static_pages');
        }
    }
}
