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

class CreateNoticeBoardsTable extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        LAModule::generate("Notice_boards", 'notice_boards', 'notice_title', 'fa-sticky-note-o', [
            [
                "colname" => "notice_title",
                "label" => "Notice Title",
                "field_type" => "TextField",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 250,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "notice_date",
                "label" => "Date",
                "field_type" => "Date",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "notice_details",
                "label" => "Notice Details",
                "field_type" => "HTML",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 10000,
                "required" => true,
                "listing_col" => false
            ], [
                "colname" => "pdf_notice",
                "label" => "PDF Notice",
                "field_type" => "File",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 250,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "custom_url",
                "label" => "Custom URL",
                "field_type" => "URL",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 250,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "news_flash",
                "label" => "News Flash",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => ["1","0"],
            ], [
                "colname" => "exam_notice",
                "label" => "Exam Notice",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => ["1","0"],
            ], [
                "colname" => "status",
                "label" => "Status",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "1",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => ["1","0"],
            ], [
                "colname" => "new_news",
                "label" => "New News",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "Yes",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => ["Yes","No"],
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
        if(Schema::hasTable('notice_boards')) {
            Schema::drop('notice_boards');
        }
    }
}
