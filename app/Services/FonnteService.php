<?php

namespace App\Services;

use App\Models\WhatsappApi;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $endpoint = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $wa = WhatsappApi::first();
        $this->token = $wa->access_token ?? '';
    }

    /**
     * Kirim pesan teks biasa.
     */
    public function sendText(string $target, string $message): array
    {
        return $this->send([
            'target' => $this->formatNumber($target),
            'message' => $message,
            'countryCode' => '62',
        ]);
    }

    /**
     * Kirim pesan + gambar (url gambar publik).
     */
    public function sendImage(string $target, string $message, string $imageUrl): array
    {
        return $this->send([
            'target' => $this->formatNumber($target),
            'message' => $message,
            'url' => $imageUrl,
            'countryCode' => '62',
        ]);
    }

    /**
     * Eksekusi cURL ke Fonnte.
     */
    private function send(array $payload): array
    {
        if (empty($this->token)) {
            Log::warning('[Fonnte] Token belum diatur di Pengaturan API Whatsapp.');
            return ['status' => false, 'message' => 'Token kosong'];
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('[Fonnte] cURL error: ' . $error);
            return ['status' => false, 'message' => $error];
        }

        $result = json_decode($response, true) ?? [];
        Log::info('[Fonnte] Response: ' . $response);
        return $result;
    }

    /**
     * Format nomor: hapus leading 0, tambah kode negara kalau perlu.
     */
    private function formatNumber(string $number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        return $number;
    }
}
