<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Phpsa\FilamentCms\Glide;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Models\CmsMedia;

class GlideController extends Controller
{
    public function __invoke(Request $request, string $media)
    {
        $record = CmsMedia::whereUuid($media)->firstOrFail();

        $options = $request->only([
            'p', // preset
            'w', // width
            'h', // height
            'blur', // blur
            'pixel', // pixelate
            'fit', // crop
            'crop', // manualCrop
            'or', // orientation
            'flip', // flip
            'fit', // fit
            'dpr', // devicePixelRatio
            'bri', // brightness
            'con', // contrast
            'gam', // gamma
            'sharp', // sharpen
            'filt', // filter
            'bg', // background
            'border', // border
            'q', // quality
            'fm', // format
            'mark', // watermark
            'markw', // watermarkWidth
            'markh', // watermarkHeight
            'markfit', // watermarkFit
            'markx', // watermarkPaddingX
            'marky', // watermarkPaddingY
            'markpos', // watermarkPosition
            'markalpha', // watermarkOpacity
        ]);

        Glide::output($record, $options);
    }
}
