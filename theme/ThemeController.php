<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;

class ThemeController extends Controller
{
    private array $defaults = [
        'primary'       => '#f97316',
        'primary_hover' => '#fb923c',
        'bg'            => '#151929',
        'bg2'           => '#1a1f30',
        'card'          => '#1e2438',
        'border'        => '#2a3150',
        'discord_link'  => '/',
        'billing_link'  => '/',
        'support_link'  => '/',
        'status_link'   => '/',
        'logo_path'     => '/assets/images/logo.png',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->defaults as $key => $default) {
            $envKey = 'THEME_' . strtoupper($key);
            $settings[$key] = env($envKey, $default);
        }
        return view('admin.theme.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'primary'       => 'required|string|max:7',
            'primary_hover' => 'required|string|max:7',
            'bg'            => 'required|string|max:7',
            'bg2'           => 'required|string|max:7',
            'card'          => 'required|string|max:7',
            'border'        => 'required|string|max:7',
            'discord_link'  => 'nullable|string|max:255',
            'billing_link'  => 'nullable|string|max:255',
            'support_link'  => 'nullable|string|max:255',
            'status_link'   => 'nullable|string|max:255',
            'logo_path'     => 'nullable|string|max:255',
        ]);

        $this->saveToEnv($data);
        $this->generateCSS($data);

        return redirect()->route('admin.theme')
            ->with('success', '✅ Theme updated! Run: cd /var/www/pterodactyl && yarn build:production to apply changes.');
    }

    private function generateCSS(array $d): void
    {
        $p  = $d['primary'];
        $ph = $d['primary_hover'];
        $css = <<<CSS
/* Premium Theme - Auto Generated - github.com/volexion95-tech/pterodactyl-premium-theme */
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap');
:root {
  --primary: {$p};
  --primary-hover: {$ph};
  --bg: {$d['bg']};
  --bg2: {$d['bg2']};
  --bg3: {$d['card']};
  --card: {$d['card']};
  --border: {$d['border']};
  --text: #e2e8f0; --text2: #94a3b8; --text3: #64748b;
  --success: #22c55e; --danger: #ef4444; --warning: #f59e0b;
}
body{background:var(--bg)!important;font-family:'Nunito',sans-serif!important;color:var(--text)!important}
nav,aside,[class*="sidebar"]{background:var(--bg2)!important;border-right:1px solid var(--border)!important}
[class*="card"],[class*="Card"],[class*="ServerRow"],[class*="Box"]{background:var(--card)!important;border:1px solid var(--border)!important;border-radius:8px!important;transition:all .2s!important}
[class*="card"]:hover,[class*="Card"]:hover,[class*="ServerRow"]:hover{border-color:{$p}66!important;transform:translateY(-2px)!important;box-shadow:0 8px 24px {$p}22!important}
button,[class*="btn"],[class*="Button"]{font-family:'Nunito',sans-serif!important;font-weight:700!important;border-radius:8px!important;cursor:pointer!important;border:none!important;transition:all .15s!important}
[class*="primary"],button[type="submit"]{background:{$p}!important;color:#fff!important;box-shadow:0 4px 12px {$p}44!important}
[class*="primary"]:hover,button[type="submit"]:hover{background:{$ph}!important;transform:translateY(-1px)!important}
[class*="success"],[class*="Start"]{background:rgba(34,197,94,.12)!important;color:#22c55e!important;border:1px solid rgba(34,197,94,.3)!important}
[class*="danger"],[class*="Stop"],[class*="Kill"]{background:rgba(239,68,68,.12)!important;color:#ef4444!important;border:1px solid rgba(239,68,68,.3)!important}
input,textarea,select{background:var(--bg3)!important;border:1px solid var(--border)!important;border-radius:8px!important;color:var(--text)!important;font-family:'Nunito',sans-serif!important;outline:none!important}
input:focus,textarea:focus{border-color:{$p}!important;box-shadow:0 0 0 3px {$p}22!important}
[class*="progress"],[class*="Progress"]{background:rgba(255,255,255,.06)!important;border-radius:999px!important;height:5px!important;border:none!important}
[class*="progress"]>div,[class*="Progress"]>div{background:linear-gradient(90deg,{$p},{$ph})!important;border-radius:999px!important}
[class*="running"],[class*="online"]{background:rgba(34,197,94,.12)!important;color:#22c55e!important;border:1px solid rgba(34,197,94,.3)!important;border-radius:999px!important}
[class*="offline"],[class*="stopped"]{background:rgba(239,68,68,.12)!important;color:#ef4444!important;border:1px solid rgba(239,68,68,.3)!important;border-radius:999px!important}
[class*="console"],[class*="terminal"]{background:#080b10!important;border:1px solid var(--border)!important;border-radius:8px!important}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-thumb{background:var(--border);border-radius:999px}
a{color:{$p}!important;text-decoration:none!important}
CSS;
        file_put_contents(resource_path('scripts/custom.css'), $css);
    }

    private function saveToEnv(array $data): void
    {
        $path = base_path('.env');
        $env = file_get_contents($path);
        foreach ($data as $key => $value) {
            $envKey = 'THEME_' . strtoupper($key);
            if (strpos($env, $envKey . '=') !== false) {
                $env = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$value}", $env);
            } else {
                $env .= "\n{$envKey}={$value}";
            }
        }
        file_put_contents($path, $env);
    }
}
