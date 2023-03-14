<?php

namespace App\Http\Controllers\Paquetes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PackageController extends Controller
{

    public function intervention_image(Request $request)
    {

        $img = Image::make('images/laravel.png')->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        });

        //Generar marca de agua
        $img->insert("images/watermark.png");
        $img->save("images/test.png");

        return response()->json([
            "image" => 'ok'
        ]);
    }


    public function qr_generate(Request $request)
    {
        QrCode::format('png')->size(400)->color(255,255,0)->generate('Make me into a QrCode!', public_path()."/QR/QR.png");

        return response()->json([
            "qr" => "QR generado con exito"
        ]);
    }
}
