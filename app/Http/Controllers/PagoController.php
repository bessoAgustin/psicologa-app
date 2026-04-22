<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PagoController extends Controller
{
    public function crear(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sesion = Sesion::findOrFail($request->sesion_id);

        $checkout = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Sesión de psicología - ' . $sesion->fecha . ' ' . $sesion->hora,
                    ],
                    'unit_amount' => $sesion->precio * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/confirmacion?sesion_id=' . $sesion->id),
            'cancel_url' => url('/agenda'),
        ]);

        return response()->json(['url' => $checkout->url]);
    }

    public function confirmar(Request $request)
    {
    $sesion = Sesion::findOrFail($request->sesion_id);
    $sesion->update(['estado' => 'confirmada']);
    
    return inertia('Confirmacion', [
        'sesion' => $sesion
    ]);
    }
}