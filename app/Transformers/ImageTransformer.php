<?php

namespace App\Transformers;

use App\Http\Models\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(Image $image)
    {
        return [
            'id' => $image->id,
            'user_id' => $image->user_id,
            'avatar' => $image->avatar,
            'created_at' => $image->created_at->toDateTimeString(),
            'updated_at' => $image->updated_at->toDateTimeString(),
        ];
    }
}