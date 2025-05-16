<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EncryptionKey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class KeyController extends Controller
{
    //
    public function generate(Request $request)
    {
  
        $validator = Validator::make($request->all(), [
            'telegram_code' => 'required|string|max:10',
            'file_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Incorrect input data',
                'errors' => $validator->errors(),
            ], 400);
        }

        $telegram_code = $request->input('telegram_code');
        $file_name = $request->input('file_name');
        
        $key = Str::random(52);
        //echo strlen($key);

        try 
        {
            $record = EncryptionKey::create([
                'file_name' => $file_name,
                'key' => $key,
                'telegram_code' => $telegram_code,
            ]);

            return response()->json([
                'id' => $record->id,
                'key' => $record->key,
            ], 201);
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'code' => 500,
                'message' => 'Server error during key generation',
                'errors' => $validator->errors(),
            ], 500);
        }
    
    }
    
    public function get_key(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'telegram_code' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Incorrect input data',
            ], 400);
        }

        $telegram_code = $request->input('telegram_code');
        $id = $request->input('id');

        try {
            $record = EncryptionKey::where('id', $id)
                ->where('telegram_code', $telegram_code)
                ->first();

            if (!$record) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Key not found or access denied',
                ], 404);
            }
            
            $key = $record->key;

            $record->delete();

            return response()->json([
                'key' => $record->key,
            ]);
        } catch (\Exception $e) {  
            return response()->json([
                'code' => 500,
                'message' => 'Server error during key lookup',
            ], 500);
        }
    }
}


