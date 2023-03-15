<?php

namespace App\Http\Controllers\Paquetes;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Armancodes\DownloadLink\Facades\DownloadLinkGenerator;
use Illuminate\Support\Facades\Storage;

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

    public function excel_export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function excel_import()
    {
        Excel::import(new UsersImport, 'users.xlsx');
        return response()->json(["excel" => "Importado con exito"]);
    }

    public function google_translate(Request $request)
    {
        $tr = new GoogleTranslate('en'); // Translates to 'en' from auto-detected language by default
        $tr->setSource(); // Detect language automatically
        return response()->json(['translate' => $tr->translate($request->text)]);
    }

    public function stripe_create_customer(Request $request)
    {
        $user = User::find(1);
        $stripeCustomer = $user->createAsStripeCustomer();
        return response()->json(['customer' => $stripeCustomer]);
    }

    public function stripe_payment_method_register()
    {
        $user = User::find(1);

        //Al consumir los servicios de stripe en la siguiente vista responde un json en el cual se debe tomar la llave "payment_method": "pm_1MlwFeHOmndNleswdYtL5iYF" para relacionarla con $user->addPaymentMethod('pm_1MlwFeHOmndNleswdYtL5iYF');

        return view('paquetes.stripe_payment_method_register', [
            'intent' => $user->createSetupIntent()
        ]);
    }

    public function stripe_payment_method_create()
    {
        $user = User::find(1);
        $user->addPaymentMethod('pm_1MlwFeHOmndNleswdYtL5iYF');
        return response()->json(['user' => $user]);
    }

    public function stripe_payment_method()
    {
        $user = User::find(1);
        $paymentMethods = $user->paymentMethods();
        return response()->json(['stripe_payment_method' => $paymentMethods]);
    }

    public function stripe_create_only_pay_form()
    {
        $user = User::find(1);
        return view('paquetes.stripe_create_only_pay_form');
    }

    //$paymentMethod se carga de la resupesta de la vista paquetes.stripe_create_only_pay_form

    public function stripe_create_only_pay()
    {
        $payment = null;
        $user = User::find(1);
        $paymentMethod = 'pm_1Mlx6xHOmndNleswL8XX82I4';
        try {
            $payment = $user->charge(100, $paymentMethod);
        } catch (Exception $e) {
            //
        }
        return response()->json(['payment' => $payment]);
    }

    public function stripe_create_suscription()
    {
        $user = User::find(1);
        $paymentMethod = $user->defaultPaymentMethod(); //Metodo de pago por defecto
        $suscription = $user->newSubscription('default', 'price_1MlxvuHOmndNleswaNesujMQ')->create($paymentMethod->id);


        return response()->json(['suscription' => $suscription]);
    }

    public function download_link()
    {
        //Storage::fake('local')->put('example.txt', 'Hola mundo');
        Storage::disk('local')->put('example.txt', 'Hola mundo');
        $link = DownloadLinkGenerator::disk('local')->filePath('example.txt')->generate();
        return response()->json(['file' => $link]);
    }


}
