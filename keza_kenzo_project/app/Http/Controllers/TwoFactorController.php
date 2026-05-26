<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function enableTwoFactor()
    {
        $user = Auth::user();
        
        if ($user->google2fa_enabled) {
            return back()->with('error', '2FA is already enabled');
        }

        $secret = $this->google2fa->generateSecretKey();
        $user->google2fa_secret = $secret;
        $user->save();

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($qrCodeUrl);

        return view('auth.2fa-enable', [
            'qrCode' => $qrCode,
            'secret' => $secret,
            'user' => $user
        ]);
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6'
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->google2fa_enabled = true;
            $user->save();
            
            return redirect()->route('profile.security')
                ->with('success', '2FA has been enabled successfully');
        }

        return back()->withErrors(['code' => 'Invalid verification code']);
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string|digits:6'
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->google2fa_enabled = false;
            $user->google2fa_secret = null;
            $user->save();
            
            return redirect()->route('profile.security')
                ->with('success', '2FA has been disabled');
        }

        return back()->withErrors(['code' => 'Invalid verification code']);
    }

    public function showVerification()
    {
        return view('auth.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6'
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended($this->intendedUrl());
        }

        return back()->withErrors(['code' => 'Invalid verification code']);
    }

    private function intendedUrl()
    {
        return session('url.intended', route('dashboard'));
    }
}
