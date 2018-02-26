<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ImageRequest;
use App\Transformers\ImageTransformer;
use App\Http\Models\Image;
use App\Handlers\ImageUploadHandler;

class QSImagesController extends QSApiController
{
    //
    public function submit(ImageRequest $request, ImageUploadHandler $uploader)
    {
        $user = $this->user();

        $size = 362;
        $result = $uploader->save($request->image, str_plural('avatar'), $user->id, $size);

        $image = new Image();
        $image->avatar = $result['path'];
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image, new ImageTransformer());
    }
}
