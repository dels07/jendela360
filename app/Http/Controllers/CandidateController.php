<?php

namespace App\Http\Controllers;

use App\Candidate;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\CandidateRejection;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CandidateFormRequest;

class CandidateController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function datatable()
    {
        $data = Candidate::oldest();

        return DataTables::of($data)
                ->editColumn('name', function($data) {
                    return '<a href="#" data-id="'.$data->id.'" class="link-show">'.$data->name.'</a>';
                })
                ->addColumn('cv_link', function($data) {
                    return '<a href="'.$data->file.'"><img src="http://wwwimages.adobe.com/content/dam/acom/en/legal/images/badges/Adobe_PDF_file_icon_32x32.png"></a>';
                })
                ->addColumn('actions', function ($data) {
                    return '<a href="#" data-id="'.$data->id.'" class="link-approve btn btn-success">Accept</a>&nbsp;&nbsp;<a href="#" data-id="'.$data->id.'" class="link-reject btn btn-danger">Reject</a>';
                })
                ->rawColumns(['name', 'cv_link', 'actions'])
                ->setRowId('id')
                ->make(true);
    }

    public function create()
    {
        return view('form');
    }

    public function store(CandidateFormRequest $request)
    {
        $candidate = new Candidate;
        $candidate->fill($request->except(['file']));

        $fileName = md5($request->file->getClientOriginalName()).'.pdf';
        $request->file->storeAs('pdf', $fileName, 'public');

        $candidate->file = $fileName;

        $candidate->save();

        return redirect(route('create'))
                ->with('type', 'success')
                ->with('message', 'Aplikasi berhasil dikirimkan');
    }

    public function show(Candidate $candidate)
    {
        return response()->json($candidate);
    }

    public function approve(Candidate $candidate)
    {
        // generate contract using dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Kontrak Kerja</h1><br>'.$candidate->name);
        $pdf->save('kontrak-'.Str::slug($candidate->name).'.pdf');

        // send contract to whatsapp candidate (https://chat-api.com/en/docs.html POST /sendFile)
        // $endpoint = "/sendFile";
        $endpoint = "/sendMessage"; // Test using /sendFile not working due to not having domain for file
        $baseUrl  = config('services.chatapi.url');
        $token    = config('services.chatapi.key');
        $data     = json_encode([
            'phone'    => '62'.ltrim($candidate->phone, '0'), // international standard number
            'body'     => asset('pdf/'.$candidate->file),
            // 'filename' => $candidate->file,
            // 'caption'  => 'Kontrak Kerja '.$candidate->name
        ]);
        $url      = $baseUrl.$endpoint.'?token='.$token;
        $whatsapp = new Client;

        $response = $whatsapp->post($url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $data
        ]);

        if ($response->getStatusCode() == 200) {
            $candidate->status = 'waiting for sign';
            $candidate->save();

            return response()->json(['type' => 'success', 'message' => "Candidate {$candidate->name} successsfully approved!"]);
        }
    }

    public function reject(Candidate $candidate)
    {
        $candidate->status = 'rejected';
        $candidate->save();

        // send email to candidate
        Mail::to($candidate->email)->send(new CandidateRejection($candidate));

        return response()->json(['type' => 'warning', 'message' => "Candidate {$candidate->name} successsfully rejected!"]);
    }

    public function webhook(Request $request)
    {
        $phone     = rtrim($request->author, '@c.us');
        $candidate = Candidate::where('phone', $phone)->first();

        if ($request->body == strtolower('confirm')) {
            $candidate->status = 'signed';
        } elseif ($request->body == strtolower('rejected')) {
            $candidate->status = 'rejected';
        }

        $candidate->save();
    }
}
