<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;

class MahasiswaController extends Controller
{

    public function __construct(Request $request) //
    {
        $this->request = $request;
        //
       
    }

    protected function jwt(Mahasiswa $mahasiswa)
    {
        $payload = [
            'iss' => 'lumen-jwt', //issuer of the token
            'sub' => $mahasiswa->nim, //subject of the token
            'iat' => time(), //time when JWT was issued.
            'exp' => time() + 60 * 60 //time when JWT will expire
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }


    public function Register(Request $request)
    {
        $nim = $request->nim;
        $nama = $request->nama;
        $angkatan = $request->angkatan;
        $password = Hash::make($request->password);

        $mahasiswa = Mahasiswa::create([
            'nim' => $nim,
            'nama' => $nama,
            'angkatan' => $angkatan,
            'password' => $password
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'User Created Successfully',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function Login(Request $request)
    {
        $nim = $request->nim;
        $password = $request->password;

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User Not Exist',
            ], 404);
        }

        if (!Hash::check($password, $mahasiswa->password)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Wrong Password',
            ], 400);
        }

        $mahasiswa->token = $this->jwt($mahasiswa); //
        $mahasiswa->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Successfully Login',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function GetUsers()
    {
        $mahasiswa = Mahasiswa::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'Get All Users',
            'mahasiswa' => $mahasiswa,
        ], 200);
    }

    public function GetUserByToken(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Get User By Token',
            'mahasiswa' => $request->mahasiswa,
        ], 200);
    }

    public function AddMatkul(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;

        $matakuliah = Matakuliah::create([
            'id' => $id,
            'nama' => $nama,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Matkul Created Successfully',
            'data' => [
                'matakuliah' => $matakuliah,
            ]
        ], 200);
    }

    public function GetMatkul()
    {
        $matakuliah = Matakuliah::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'Get All Matkul',
            'matakuliah' => $matakuliah,
        ], 200);
    }

    public function GetMahasiswaByNim(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);

        return response()->json([
            'success' => true,
            'message' => 'Get Mahasiswa By NIM',
            'mahasiswa' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'angkatan' => $mahasiswa->angkatan,
                'password' => $mahasiswa->password,
                'matakuliah' => $mahasiswa->matakuliahs,
            ]
        ]);
    }

    public function AddMatkulMahasiswa($nim, $mkId)
    {
        $mahasiswa = Mahasiswa::find($nim);
        $mahasiswa->matakuliahs()->attach($mkId);
        return response()->json([
            'success' => true,
            'message' => 'Matkul Added For Mahasiswa',
        ]);
    }

    public function DeleteMatkulMahasiswa($nim, $mkId)
    {
        $mahasiswa = Mahasiswa::find($nim);
        $mahasiswa->matakuliahs()->detach($mkId);
        return response()->json([
            'success' => true,
            'message' => 'Matkul Deleted From Mahasiswa',
        ]);
    }
}