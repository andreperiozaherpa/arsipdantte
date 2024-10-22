<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('get_data');
    }

    public function verify(Request $request)
    {
        $files = $request->file;
        $param = $request->param;
        if ($param == 'uploads') {
            $path = storage_path('/app/public/dokumen');
            $name = Str::random(5) . '.' .  $files->getClientOriginalExtension();
            $files->move($path, $name);

            $files_pdf = @file_get_contents($path . '/' . $name);
            $b64Pdf = base64_encode($files_pdf);

            $responses_verify = Http::withBody(
                '{
                    "file":"' .
                    $b64Pdf .
                    '"
                }',
                'application/json',
            )
                ->withBasicAuth('kominfo', '12345678')
                ->retry(10, 100, throw: false)
                ->post('http://10.90.160.6/api/v2/verify/pdf');

            return response()->json([
                'name' => $name,
                'data' => json_decode($responses_verify->body())
            ]);
        } else {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        $index = $request->index;
        $response = Http::get('aplikasi.tubaba.go.id/api/arsip_tte?index=' . $index);
        $url_dokumen = json_decode($response->body())->url_dokumen;

        $files_pdf = @file_get_contents($url_dokumen);
        $b64Pdf = base64_encode($files_pdf);

        $responses_verify = Http::withBody(
            '{
                "file":"' .
                $b64Pdf .
                '"
            }',
            'application/json',
        )
            ->withBasicAuth('kominfo', '12345678')
            ->retry(10, 100, throw: false)
            ->post('http://103.175.217.188/api/v2/verify/pdf');

        // dd(json_decode($responses_verify->body()));
        return response()->json([$response->json(), 'data_sig' => json_decode($responses_verify->body())]);
    }

    public function create()
    {
        return view('verify');
    }

    public function data()
    {
        return view('get_data_verify');
    }
}
