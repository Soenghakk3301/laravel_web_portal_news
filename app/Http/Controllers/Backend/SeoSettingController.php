<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function SeoSetting()
    {
        $seo = SeoSetting::find(1);
        return view('backend.seo.seo_data', compact('seo'));
    }

    public function updateSeoSetting(Request $request)
    {
        $seo_id = $request->id;

        SeoSetting::findOrFail($seo_id)->update([
            'meta_title' => $request->meta_title,
            'meta_author' => $request->meta_author,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,

        ]);


        $notification = array(
            'message' => 'Seo Setting Updated Successfully',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    }
}