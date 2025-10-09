<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\Setting;
use App\Traits\CMSData;

class HomeController extends Controller
{
    use CMSData;
    public function index()
    {
        $data = [];

        $cmsData = CMSData::all()->makeHidden(['created_at', 'updated_at']);

        $data['home_example']       = $cmsData->where('page', PageEnum::HOME)->where('section', SectionEnum::EXAMPLE)->first();
        $data['home_examples']      = $cmsData->where('page', PageEnum::HOME)->where('section', SectionEnum::EXAMPLES)->values();
        $data['home_about']         = $cmsData->where('page', PageEnum::HOME)->where('section', SectionEnum::ABOUT)->first();
        $data['common']             = $cmsData->where('page', PageEnum::COMMON);
        
        $data['settings']           = Setting::first();

        return Helper::jsonResponse(true, 'Home Page', 200, $data);

    }
}
