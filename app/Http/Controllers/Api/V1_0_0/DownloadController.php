<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Download Attachment
     *
     * @param \App\Models\Media $media
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function attachment(Media $media)
    {
        return response()->download($media->getPath(), $media->file_name);
    }
}
